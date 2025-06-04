<?php
header('Content-Type: application/json');
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();

$stats = [
    'annonces' => $pdo->query("SELECT COUNT(*) FROM annonces")->fetchColumn(),
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'reservations' => $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'")->fetchColumn(),
    'contacts' => $pdo->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn()
];

echo json_encode([
    'success' => true,
    'data' => $stats,
    'last_update' => date('Y-m-d H:i:s')
]);