<?php
// actions/send_message.php
require 'includes/messaging_functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false];
    
    try {
        $adId = (int)$_POST['ad_id'];
        $recipientId = (int)$_POST['recipient_id'];
        $message = $_POST['message'];
        
        if (isset($_SESSION['user_id'])) {
            $senderId = $_SESSION['user_id'];
            sendMessage($senderId, $recipientId, $adId, $message);
        } else {
            // Envoyer par email pour les non-connectés
            $guestEmail = filter_var($_POST['guest_email'], FILTER_SANITIZE_EMAIL);
            // Ici vous implémenteriez l'envoi par email
        }
        
        $response['success'] = true;
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

header('Location: index.php');