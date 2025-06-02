<?php
require_once __DIR__.'db.php';
require_once __DIR__.'auth_check.php';

$pdo = Database::getInstance();
$status = $_GET['status'] ?? 'all';

$sql = "SELECT r.*, a.titre, u.username 
        FROM reservations r
        JOIN annonces a ON r.ad_id = a.id
        JOIN users u ON r.user_id = u.id";

if (in_array($status, ['pending', 'confirmed', 'cancelled'])) {
    $sql .= " WHERE r.status = '$status'";
}

$reservations = $pdo->query($sql)->fetchAll();
?>

<div class="reservations-management">
    <h2>Gestion des réservations</h2>
    
    <div class="filters">
        <a href="?status=all" class="<?= $status === 'all' ? 'active' : '' ?>">Toutes</a>
        <a href="?status=pending" class="<?= $status === 'pending' ? 'active' : '' ?>">En attente</a>
        <a href="?status=confirmed" class="<?= $status === 'confirmed' ? 'active' : '' ?>">Confirmées</a>
        <a href="?status=cancelled" class="<?= $status === 'cancelled' ? 'active' : '' ?>">Annulées</a>
    </div>

    <table class="reservations-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Annonce</th>
                <th>Client</th>
                <th>Dates</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $res): ?>
            <tr>
                <td><?= $res['id'] ?></td>
                <td><?= htmlspecialchars($res['titre']) ?></td>
                <td><?= htmlspecialchars($res['username']) ?></td>
                <td>
                    <?= date('d/m/Y', strtotime($res['start_date'])) ?> - 
                    <?= date('d/m/Y', strtotime($res['end_date'])) ?>
                </td>
                <td>
                    <span class="status-badge <?= $res['status'] ?>">
                        <?= $res['status'] ?>
                    </span>
                </td>
                <td>
                    <form action="update_status.php" method="POST" class="inline-form">
                        <input type="hidden" name="reservation_id" value="<?= $res['id'] ?>">
                        <select name="new_status" onchange="this.form.submit()">
                            <option value="pending" <?= $res['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                            <option value="confirmed" <?= $res['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmée</option>
                            <option value="cancelled" <?= $res['status'] === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>