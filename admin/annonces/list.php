<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();
$status = $_GET['status'] ?? 'all';

// Requête dynamique
$sql = "SELECT a.*, u.username FROM annonces a LEFT JOIN users u ON a.user_id = u.id";
if (in_array($status, ['en attente', 'acceptée', 'refusée'])) {
    $sql .= " WHERE a.statut = '$status'";
}
$annonces = $pdo->query($sql)->fetchAll();
?>

<div class="annonce-management">
    <!-- Filtres -->
    <div class="filters">
        <a href="?status=all" class="<?= $status === 'all' ? 'active' : '' ?>">Toutes</a>
        <a href="?status=en attente" class="<?= $status === 'en attente' ? 'active' : '' ?>">En attente</a>
        <a href="?status=acceptée" class="<?= $status === 'acceptée' ? 'active' : '' ?>">Acceptées</a>
        <a href="?status=refusée" class="<?= $status === 'refusée' ? 'active' : '' ?>">Refusées</a>
    </div>

    <!-- Liste -->
    <table class="annonce-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($annonces as $annonce): ?>
            <tr>
                <td><?= $annonce['id'] ?></td>
                <td><?= htmlspecialchars($annonce['titre']) ?></td>
                <td><?= number_format($annonce['prix'], 2, ',', ' ') ?> MAD</td>
                <td>
                    <span class="status-badge <?= $annonce['statut'] ?>">
                        <?= $annonce['statut'] ?>
                    </span>
                </td>
                <td class="actions">
                    <a href="edit.php?id=<?= $annonce['id'] ?>" class="btn-edit">✏️</a>
                    <a href="delete.php?id=<?= $annonce['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer cette annonce?')">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>