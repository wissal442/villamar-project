<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth_check.php';

$pdo = Database::getInstance();
$payments = $pdo->query("
    SELECT p.*, a.titre, u.username 
    FROM payments p
    JOIN annonces a ON p.ad_id = a.id
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
")->fetchAll();
?>

<div class="payments-management">
    <h2>Gestion des paiements</h2>
    
    <table class="payments-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Annonce</th>
                <th>Client</th>
                <th>Montant</th>
                <th>Méthode</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $pay): ?>
            <tr>
                <td><?= $pay['id'] ?></td>
                <td><?= htmlspecialchars($pay['titre']) ?></td>
                <td><?= htmlspecialchars($pay['username']) ?></td>
                <td><?= number_format($pay['amount'], 2, ',', ' ') ?> MAD</td>
                <td><?= $pay['payment_method'] ?></td>
                <td>
                    <span class="status-badge <?= $pay['status'] ?>">
                        <?= $pay['status'] ?>
                    </span>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($pay['created_at'])) ?></td>
                <td>
                    <a href="details.php?id=<?= $pay['id'] ?>" class="btn-view">Détails</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>