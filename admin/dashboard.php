<?php
require_once __DIR__.'db.php';
require_once __DIR__.'includes/auth_check.php';

$pdo = Database::getInstance();

// Statistiques
$stats = [
    'annonces' => $pdo->query("SELECT COUNT(*) FROM annonces")->fetchColumn(),
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'contacts' => $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn(),
    'en_attente' => $pdo->query("SELECT COUNT(*) FROM annonces WHERE statut = 'en attente'")->fetchColumn()
];

// Dernières activités
$logs = $pdo->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<div class="admin-dashboard">
    <!-- Cartes de stats -->
    <div class="stats-grid">
        <?php foreach ($stats as $key => $value): ?>
        <div class="stat-card <?= str_replace('_', '-', $key) ?>">
            <h3><?= ucfirst(str_replace('_', ' ', $key)) ?></h3>
            <p><?= $value ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Dernières activités -->
    <div class="recent-activity">
        <h3>Activité récente</h3>
        <ul>
            <?php foreach ($logs as $log): ?>
            <li>
                <span class="log-action"><?= $log['action'] ?></span>
                <span class="log-date"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>