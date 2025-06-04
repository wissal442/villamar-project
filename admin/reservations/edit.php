<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();
$reservation_id = $_GET['id'] ?? null;

if (!$reservation_id) {
    header('Location: list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE reservations SET
            start_date = ?,
            end_date = ?,
            status = ?
        WHERE id = ?
    ");
    
    $stmt->execute([
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['status'],
        $reservation_id
    ]);

    // Journalisation
    $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)")
       ->execute([$_SESSION['user_id'], "Modification réservation #$reservation_id", $_SERVER['REMOTE_ADDR']]);

    header('Location: list.php?success=1');
    exit;
}

$reservation = $pdo->prepare("
    SELECT r.*, a.titre, u.username 
    FROM reservations r
    JOIN annonces a ON r.ad_id = a.id
    JOIN users u ON r.user_id = u.id
    WHERE r.id = ?
")->execute([$reservation_id])->fetch();

if (!$reservation) {
    header('Location: list.php');
    exit;
}
?>

<form method="POST" class="reservation-form">
    <div class="form-group">
        <label>Annonce</label>
        <input type="text" value="<?= htmlspecialchars($reservation['titre']) ?>" readonly>
    </div>
    
    <div class="form-group">
        <label>Client</label>
        <input type="text" value="<?= htmlspecialchars($reservation['username']) ?>" readonly>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label>Date début</label>
            <input type="date" name="start_date" value="<?= $reservation['start_date'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Date fin</label>
            <input type="date" name="end_date" value="<?= $reservation['end_date'] ?>" required>
        </div>
    </div>
    
    <div class="form-group">
        <label>Statut</label>
        <select name="status" required>
            <option value="pending" <?= $reservation['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
            <option value="confirmed" <?= $reservation['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmée</option>
            <option value="cancelled" <?= $reservation['status'] === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
        </select>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn-save">Enregistrer</button>
        <a href="list.php" class="btn-cancel">Annuler</a>
    </div>
</form>