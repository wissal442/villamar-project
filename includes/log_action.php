<?php
function logAction($action, $adId = null) {
    require_once __DIR__.'/../config/db.php';
    $pdo = Database::getInstance();
    
    $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, ip_address, ad_id)
        VALUES (?, ?, ?, ?)
    ")->execute([
        $_SESSION['user_id'],
        $action,
        $_SERVER['REMOTE_ADDR'],
        $adId
    ]);
}

// Exemple d'utilisation :
// logAction("Modification de l'annonce", $annonceId);