<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();

// Filtres
$user_id = $_GET['user_id'] ?? '';
$action = $_GET['action'] ?? '';

// Requête avec filtres
$sql = "SELECT al.*, u.username 
        FROM activity_logs al
        LEFT JOIN users u ON al.user_id = u.id
        WHERE 1=1";
$params = [];

if (!empty($user_id)) {
    $sql .= " AND al.user_id = ?";
    $params[] = $user_id;
}

if (!empty($action)) {
    $sql .= " AND al.action LIKE ?";
    $params[] = "%$action%";
}

$sql .= " ORDER BY al.created_at DESC";
$logs = $pdo->prepare($sql)->execute($params)->fetchAll();

// Liste des utilisateurs pour le filtre
$users = $pdo->query("SELECT id, username FROM users ORDER BY username")->fetchAll();
?>

<div class="activity-logs">
    <h2>Journal d'Activité</h2>
    
    <div class="filters">
        <form method="GET" class="filter-form">
            <div class="form-group">
                <label>Utilisateur:</label>
                <select name="user_id">
                    <option value="">Tous</option>
                    <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= $user['id'] == $user_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user['username']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Action:</label>
                <input type="text" name="action" value="<?= htmlspecialchars($action) ?>" placeholder="Rechercher une action...">
            </div>
            
            <button type="submit" class="btn-filter">Filtrer</button>
            <a href="list.php" class="btn-reset">Réinitialiser</a>
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
                <td><?= $log['username'] ?? 'Système' ?></td>
                <td><?= htmlspecialchars($log['action']) ?></td>
                <td><?= $log['ip_address'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>