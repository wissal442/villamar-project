<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// Pour les pages admin
if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false && $_SESSION['user_role'] !== 'admin') {
    die('Accès non autorisé');
}
?>