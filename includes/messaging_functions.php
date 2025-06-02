// includes/messaging_functions.php
<?php
function sendMessage($senderId, $recipientId, $adId, $message) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        INSERT INTO messages (sender_id, recipient_id, ad_id, message, is_read)
        VALUES (?, ?, ?, ?, 0)
    ");
    return $stmt->execute([$senderId, $recipientId, $adId, htmlspecialchars($message)]);
}

function getUnreadCount($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE recipient_id = ? AND is_read = 0");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

function getUserMessages($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT m.*, a.title as ad_title, u.username as sender_name
        FROM messages m
        JOIN ads a ON m.ad_id = a.id
        JOIN users u ON m.sender_id = u.id
        WHERE m.recipient_id = ?
        ORDER BY m.created_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}