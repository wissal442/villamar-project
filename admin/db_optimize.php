<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth_check.php';
PermissionManager::check('manage_settings');

$pdo = Database::getInstance();
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($tables as $table) {
        $pdo->exec("OPTIMIZE TABLE `$table`");
    }
    
    // Journalisation
    $pdo->prepare("INSERT INTO audit_log (user_id, action_type, details) VALUES (?, ?, ?)")
       ->execute([$_SESSION['user_id'], 'system', 'Optimisation de la base de données']);
    
    header('Location: db_optimize.php?success=1');
    exit;
}
?>

<div class="optimize-container">
    <h2>Optimisation de la Base de Données</h2>
    <p>Cette action va défragmenter et optimiser toutes les tables de la base de données.</p>
    
    <ul class="table-list">
        <?php foreach ($tables as $table): ?>
        <li><?= htmlspecialchars($table) ?></li>
        <?php endforeach; ?>
    </ul>
    
    <form method="POST">
        <button type="submit" class="btn-optimize">Optimiser maintenant</button>
    </form>
</div>