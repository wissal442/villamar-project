<?php
include 'includes/header.php';
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    
    // Vérifier le mot de passe actuel
    $user = $pdo->prepare("SELECT password FROM users WHERE id = ?")->execute([$_SESSION['user_id']])->fetch();
    
    if (password_verify($current, $user['password'])) {
        // Mettre à jour le mot de passe
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hashed, $_SESSION['user_id']]);
        
        $_SESSION['message'] = "Mot de passe mis à jour avec succès";
        header('Location: profile.php');
        exit;
    } else {
        $error = "Mot de passe actuel incorrect";
    }
}

// Redirection si accès direct
header('Location: profile.php');
exit;