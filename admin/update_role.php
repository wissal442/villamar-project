<?php
require_once __DIR__.'db.php';
require_once __DIR__.'includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$_POST['role'], $_POST['user_id']]);
    
    // Journalisation
    $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, ip_address)
        VALUES (?, ?, ?)
    ")->execute([
        $_SESSION['user_id'],
        "Changement rôle utilisateur #".$_POST['user_id']." à ".$_POST['role'],
        $_SERVER['REMOTE_ADDR']
    ]);
    
    header("Location: ".$_SERVER['HTTP_REFERER']."?success=1");
    exit;
}
?>