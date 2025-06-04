<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();
$userId = $_GET['id'] ?? null;

if (!$userId) {
    header('Location: list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'role' => $_POST['role'],
        'is_verified' => isset($_POST['is_verified']) ? 1 : 0,
        'id' => $userId
    ];

    // Mise à jour avec ou sans mot de passe
    $sql = "UPDATE users SET 
            username = :username,
            email = :email,
            phone = :phone,
            role = :role,
            is_verified = :is_verified
            WHERE id = :id";

    if (!empty($_POST['password'])) {
        $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = str_replace('WHERE', ', password = :password WHERE', $sql);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    // Journalisation
    $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)")
       ->execute([$_SESSION['user_id'], "Modification utilisateur #$userId", $_SERVER['REMOTE_ADDR']]);

    header('Location: list.php?success=1');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    header('Location: list.php');
    exit;
}
?>

<form method="post" class="user-form">
    <div class="form-group">
        <label>Nom d'utilisateur</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="form-group">
        <label>Téléphone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Rôle</label>
            <select name="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Vérifié</label>
            <input type="checkbox" name="is_verified" <?= $user['is_verified'] ? 'checked' : '' ?>>
        </div>
    </div>

    <div class="form-group">
        <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
        <input type="password" name="password">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-save">Enregistrer</button>
        <a href="list.php" class="btn-cancel">Annuler</a>
    </div>
</form>