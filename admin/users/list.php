<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<div class="user-management">
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <form action="update_role.php" method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <select name="role" onchange="this.form.submit()">
                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                        </select>
                    </form>
                </td>
                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                <td>
                    <a href="details.php?id=<?= $user['id'] ?>" class="btn-view">👁️</a>
                    <a href="delete.php?id=<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer cet utilisateur?')">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>