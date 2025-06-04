<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth_check.php';
$pdo = Database::getInstance();

// Filtres
$userFilter = $_GET['user_id'] ?? '';
$actionFilter = $_GET['action'] ?? '';

// Requête avec filtres
$sql = "SELECT al.*, u.username 
        FROM activity_logs al
        LEFT JOIN users u ON al.user_id = u.id
        WHERE 1=1";

$params = [];
if ($userFilter) {
    $sql .= " AND al.user_id = ?";
    $params[] = $userFilter;
}
if ($actionFilter) {
    $sql .= " AND al.action LIKE ?";
    $params[] = "%$actionFilter%";
}

$sql .= " ORDER BY al.created_at DESC";
$logs = $pdo->prepare($sql)->execute($params)->fetchAll();

// Liste des utilisateurs pour le filtre
$users = $pdo->query("SELECT id, username FROM users ORDER BY username")->fetchAll();
?>

<div class="activity-logs">
    <h2>Journal d'activité</h2>
    
    <div class="filters">
        <form method="GET" class="filter-form">
            <select name="user_id">
                <option value="">Tous les utilisateurs</option>
                <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>" <?= $user['id'] == $userFilter ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['username']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            
            <input type="text" name="action" placeholder="Filtrer par action..." value="<?= htmlspecialchars($actionFilter) ?>">
            
            <button type="submit">Filtrer</button>
            <a href="activity_logs.php" class="btn-reset">Réinitialiser</a>
        </form>
    </div>
    
    <table class="logs-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Action</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                <td><?= $log['username'] ? htmlspecialchars($log['username']) : 'Système' ?></td>
                <td><?= htmlspecialchars($log['action']) ?></td>
                <td><?= $log['ip_address'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>