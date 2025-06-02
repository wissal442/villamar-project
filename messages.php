<?php
// messages.php
require 'includes/messaging_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$messages = getUserMessages($_SESSION['user_id']);
$unreadCount = getUnreadCount($_SESSION['user_id']);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="messages.php" class="list-group-item list-group-item-action active">
                    Boîte de réception <span class="badge bg-danger"><?= $unreadCount ?></span>
                </a>
                <a href="sent_messages.php" class="list-group-item list-group-item-action">
                    Messages envoyés
                </a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Messages reçus</h5>
                    <span class="badge bg-primary"><?= count($messages) ?> messages</span>
                </div>
                
                <div class="list-group list-group-flush">
                    <?php foreach ($messages as $msg): ?>
                    <a href="view_message.php?id=<?= $msg['id'] ?>" 
                       class="list-group-item list-group-item-action <?= !$msg['is_read'] ? 'fw-bold' : '' ?>">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?= htmlspecialchars($msg['ad_title']) ?></h6>
                            <small><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></small>
                        </div>
                        <p class="mb-1"><?= substr(htmlspecialchars($msg['message']), 0, 100) ?>...</p>
                        <small>De: <?= htmlspecialchars($msg['sender_name']) ?></small>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>