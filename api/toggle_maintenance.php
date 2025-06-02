<?php
require 'includes/db.php';
require 'includes/functions.php';

header('Content-Type: application/json');

try {
    session_start();
    
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        throw new Exception('Accès non autorisé');
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!validateCsrfToken($data['csrf_token'] ?? '')) {
        throw new Exception('Token CSRF invalide');
    }
    
    setMaintenanceMode($data['enable']);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}