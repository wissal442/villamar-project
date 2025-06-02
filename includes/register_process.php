<?php
session_start();

// Configuration des chemins
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Erreur : Exécutez 'composer install' pour installer les dépendances");
}

require_once $autoloadPath;
require_once __DIR__ . '/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtpEmail($to, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ouledkhouyiwissal@gmail.com';
        $mail->Password = 'fpfz tsal ltjw lujy';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('no-reply@villamar.ma', 'Villamar Immobilier');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Votre code de vérification';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #6F826A;'>Confirmation d'inscription</h2>
                <p>Votre code de vérification est :</p>
                <div style='background: #F0F1C5; padding: 15px; text-align: center; font-size: 24px; margin: 20px 0;'>
                    <strong>$otp</strong>
                </div>
                <p>Ce code est valable 10 minutes.</p>
            </div>
        ";

        return $mail->send();
    } catch (Exception $e) {
        error_log("Erreur mail: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    $errors = [];
    if (empty($username)) $errors['username'] = "Le nom est requis";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Email invalide";
    if (strlen($password) < 8) $errors['password'] = "8 caractères minimum";
    if ($password !== $confirm_password) $errors['confirm'] = "Les mots de passe ne correspondent pas";

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors['email'] = "Cet email est déjà utilisé";
    }

    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);
            
            // Modification importante ici: conversion en string
            $otp = strval(random_int(100000, 999999));
            
            $_SESSION['otp_data'] = [
                'user_id' => $pdo->lastInsertId(),
                'otp' => $otp, // Stocké comme string
                'email' => $email,
                'expires' => time() + 600
            ];

            if (sendOtpEmail($email, $otp)) {
                header('Location: otp.php');
                exit;
            } else {
                $errors[] = "Erreur lors de l'envoi du code OTP";
            }
        } catch (Exception $e) {
            $errors[] = "Erreur lors de l'inscription";
            error_log("Erreur inscription: " . $e->getMessage());
        }
    }

    $_SESSION['register_errors'] = $errors;
    $_SESSION['old_input'] = $_POST;
    header('Location: ../register.php');
    exit;
}

header('Location: /register.php');
exit;
?>