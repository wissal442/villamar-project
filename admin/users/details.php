<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth_check.php';

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($user_id <= 0) {
    header('Location: list.php');
    exit;
}

$pdo = Database::getInstance();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: list.php');
    exit;
}
?>



<div class="user-details">
    <h2>Détails de l'utilisateur</h2>
    
    <div class="user-info">
        <div class="info-group">
            <label>Nom d'utilisateur:</label>
            <span><?= htmlspecialchars($user['username']) ?></span>
        </div>
        <div class="info-group">
            <label>Email:</label>
            <span><?= htmlspecialchars($user['email']) ?></span>
        </div>
        <div class="info-group">
            <label>Rôle:</label>
            <span class="role-badge <?= $user['role'] ?>"><?= $user['role'] ?></span>
        </div>
        <div class="info-group">
            <label>Inscrit le:</label>
            <span><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></span>
        </div>
    </div>

    <div class="user-actions">
        <a href="edit.php?id=<?= $user['id'] ?>" class="btn-edit">Modifier</a>
        <a href="delete.php?id=<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer définitivement cet utilisateur?')">Supprimer</a>
    </div>

    <h3>Annonces de cet utilisateur</h3>
    <?php if (count($annonces) > 0): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($annonces as $annonce): ?>
            <tr>
                <td><?= htmlspecialchars($annonce['titre']) ?></td>
                <td><span class="status-badge <?= $annonce['statut'] ?>"><?= $annonce['statut'] ?></span></td>
                <td><?= date('d/m/Y', strtotime($annonce['created_at'])) ?></td>
                <td>
                    <a href="../annonces/edit.php?id=<?= $annonce['id'] ?>" class="btn-action">Voir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>Aucune annonce publiée par cet utilisateur.</p>
    <?php endif; ?>
</div>