<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth_check.php';
PermissionManager::check('view_audit_log');

$pdo = Database::getInstance();

// Filtres
$filters = [
    'user_id' => $_GET['user_id'] ?? null,
    'action_type' => $_GET['action_type'] ?? null,
    'date_from' => $_GET['date_from'] ?? null,
    'date_to' => $_GET['date_to'] ?? null
];

// Construction de la requête
$sql = "SELECT a.*, u.username FROM audit_log a LEFT JOIN users u ON a.user_id = u.id WHERE 1=1";
$params = [];

foreach ($filters as $key => $value) {
    if (!empty($value)) {
        switch ($key) {
            case 'user_id':
                $sql .= " AND a.user_id = ?";
                $params[] = $value;
                break;
            case 'action_type':
                $sql .= " AND a.action_type = ?";
                $params[] = $value;
                break;
            case 'date_from':
                $sql .= " AND a.created_at >= ?";
                $params[] = $value;
                break;
            case 'date_to':
                $sql .= " AND a.created_at <= ?";
                $params[] = $value;
                break;
        }
    }
}

$logs = $pdo->prepare($sql." ORDER BY a.created_at DESC LIMIT 100")
           ->execute($params)
           ->fetchAll();
?>

<!-- Interface de filtrage -->
<form method="GET" class="audit-filters">
    <!-- Champs de filtrage ici -->
</form>

<!-- Tableau des résultats -->
<table class="audit-table">
    <!-- Affichage des logs -->
</table>