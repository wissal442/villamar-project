<?php
require_once __DIR__.'db.php';
require_once __DIR__.'includes/auth_check.php';

$pdo = Database::getInstance();
$userId = $_SESSION['user_id'];

// Récupération des données utilisateur
$user = $pdo->prepare("SELECT * FROM users WHERE id = ?")->execute([$userId])->fetch();
$annonces = $pdo->prepare("SELECT * FROM annonces WHERE user_id = ? ORDER BY created_at DESC LIMIT 5")->execute([$userId])->fetchAll();
$favorites = $pdo->prepare("
    SELECT a.* FROM favorites f
    JOIN annonces a ON f.ad_id = a.id
    WHERE f.user_id = ?
    LIMIT 5
")->execute([$userId])->fetchAll();
?>

<div class="user-dashboard">
    <div class="welcome-banner">
        <h2>Bonjour, <?= htmlspecialchars($user['username']) ?>!</h2>
        <p>Bienvenue sur votre espace personnel</p>
    </div>

    <div class="quick-actions">
        <a href="annonces/add.php" class="btn-primary">+ Nouvelle annonce</a>
        <a href="favorites.php" class="btn-secondary">Mes favoris</a>
    </div>

    <div class="user-sections">
        <!-- Mes annonces -->
        <section class="annonces-section">
            <h3>Mes dernières annonces</h3>
            <?php if (count($annonces) > 0): ?>
            <ul class="annonce-list">
                <?php foreach ($annonces as $annonce): ?>
                <li>
                    <h4><?= htmlspecialchars($annonce['titre']) ?></h4>
                    <p>Statut: <span class="status-<?= $annonce['statut'] ?>"><?= $annonce['statut'] ?></span></p>
                    <a href="annonces/edit.php?id=<?= $annonce['id'] ?>" class="btn-small">Modifier</a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p>Vous n'avez pas encore créé d'annonce</p>
            <?php endif; ?>
            <a href="annonces/" class="see-all">Voir toutes mes annonces →</a>
        </section>

        <!-- Favoris -->
        <section class="favorites-section">
            <h3>Mes favoris</h3>
            <?php if (count($favorites) > 0): ?>
            <ul class="favorite-list">
                <?php foreach ($favorites as $fav): ?>
                <li>
                    <h4><?= htmlspecialchars($fav['titre']) ?></h4>
                    <p><?= number_format($fav['prix'], 2, ',', ' ') ?> MAD</p>
                    <a href="/annonce.php?id=<?= $fav['id'] ?>" class="btn-small">Voir</a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p>Vous n'avez pas encore d'annonces favorites</p>
            <?php endif; ?>
            <a href="favorites.php" class="see-all">Voir tous mes favoris →</a>
        </section>
    </div>
</div>