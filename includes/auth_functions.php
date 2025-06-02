<?php
session_start();
require 'db.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        
        header('Location: ../index.php');
        exit();
    } else {

        header('Location: ../login.php?error=Email+ou+mot+de+passe+incorrect');
        exit();
    }
}
?>
