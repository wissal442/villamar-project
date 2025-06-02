<?php
session_start();


require_once __DIR__ . '/vendor/autoload.php';  // vendor à la racine
require_once __DIR__ . '/db.php';                   // db.php dans includes/

// Ton code...


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

// Récupération de l'OTP soumis
$submittedOtp = trim($_POST['otp'] ?? '');

// Vérification que l'OTP attendu est bien en session
if (!isset($_SESSION['expected_otp']) || !isset($_SESSION['otp_email'])) {
    die("Session expirée. Veuillez recommencer l'inscription.");
}

// Comparaison sécurisée de l'OTP
if ($submittedOtp === $_SESSION['expected_otp']) {
    // ✅ OTP valide
    // Ici, tu peux marquer l'utilisateur comme "vérifié" dans la base de données

    // Exemple de mise à jour dans la base (si tu as un champ "verified")
    $email = $_SESSION['otp_email'];
    
    $stmt = $pdo->prepare("UPDATE users SET verified = 1 WHERE email = ?");
    $stmt->execute([$email]);

    // Nettoyage des variables de session
    unset($_SESSION['expected_otp']);
    unset($_SESSION['otp_email']);

    // Redirection vers un tableau de bord ou page de succès
    header('Location: ../dashboard.php'); // à adapter selon ton projet
    exit;
} else {
    // ❌ OTP invalide
    echo "<p style='color:red; text-align:center;'>Code incorrect. <a href='../otp.php'>Réessayer</a></p>";
}
