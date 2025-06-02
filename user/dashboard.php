<?php
require_once __DIR__.'db.php';
require_once __DIR__.'auth_check.php';

$userId = $_SESSION['user_id'];
$pdo = Database::getInstance();

// Statistiques utilisateur
$stats = [
    'annonces' => $pdo->prepare("SELECT COUNT(*) FROM annonces WHERE user_id = ?")->execute([$userId])->fetchColumn(),
    'reservations' => $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ?")->execute([$userId])->fetchColumn(),
    'favoris' => $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ?")->execute([$userId])->fetchColumn(),
    'messages' => $pdo->prepare("SELECT COUNT(*) FROM contacts WHERE email = (SELECT email FROM users WHERE id = ?)")->execute([$userId])->fetchColumn()
];

// Dernières activités
$activities = $pdo->prepare("
    (SELECT 'annonce' as type, id, titre, created_at FROM annonces WHERE user_id = ? ORDER BY created_at DESC LIMIT 3)
    UNION
    (SELECT 'reservation' as type, id, CONCAT('Réservation #', id) as titre, created_at FROM reservations WHERE user_id = ? ORDER BY created_at DESC LIMIT 3)
    ORDER BY created_at DESC LIMIT 5
")->execute([$userId, $userId])->fetchAll();
?>

<div class="user-dashboard">
    <div class="welcome-section">
        <h2>Bonjour <?= htmlspecialchars($_SESSION['username']) ?> !</h2>
        <p>Bienvenue sur votre espace personnel</p>
    </div>

    <div class="stats-grid">
        <?php foreach ($stats as $key => $value): ?>
        <div class="stat-card">
            <h3><?= ucfirst($key) ?></h3>
            <p><?= $value ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="recent-activity">
        <h3>Vos dernières activités</h3>
        <ul>
            <?php foreach ($activities as $activity): ?>
            <li>
                <?php if ($activity['type'] === 'annonce'): ?>
                <span>Annonce: </span>
                <a href="annonces/edit.php?id=<?= $activity['id'] ?>"><?= htmlspecialchars($activity['titre']) ?></a>
                <?php else: ?>
                <span>Réservation: </span>
                <a href="reservations/details.php?id=<?= $activity['id'] ?>"><?= $activity['titre'] ?></a>
                <?php endif; ?>
                <span class="date"><?= date('d/m/Y', strtotime($activity['created_at'])) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="quick-actions">
        <a href="annonces/add.php" class="btn-primary">+ Nouvelle annonce</a>
        <a href="reservations/" class="btn-secondary">Mes réservations</a>
        <a href="profile/edit.php" class="btn-tertiary">Modifier mon profil</a>
    </div>
</div>