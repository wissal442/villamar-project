<?php
header('Content-Type: application/json');
require '../../includes/db.php';

// Vérifier l'authentification
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare("
        INSERT INTO ads 
        (user_id, title, description, type, price, location, surface, rooms, main_image, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        htmlspecialchars($data['title']),
        htmlspecialchars($data['description']),
        $data['type'],
        $data['price'],
        htmlspecialchars($data['location']),
        $data['surface'],
        $data['rooms'],
        $data['main_image']
    ]);
    
    echo json_encode(['success' => true, 'ad_id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données: ' . $e->getMessage()]);
}