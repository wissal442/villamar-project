<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();
$contact_id = $_GET['id'] ?? null;

if (!$contact_id) {
    header('Location: list.php');
    exit;
}

// Marquer comme lu
$pdo->prepare("UPDATE contacts SET is_read = 1 WHERE id = ?")->execute([$contact_id]);

$contact = $pdo->prepare("SELECT * FROM contacts WHERE id = ?")->execute([$contact_id])->fetch();
?>

<div class="contact-details">
    <h2>Message de <?= htmlspecialchars($contact['name']) ?></h2>
    
    <div class="contact-info">
        <div class="info-group">
            <label>Email:</label>
            <span><?= htmlspecialchars($contact['email']) ?></span>
        </div>
        
        <div class="info-group">
            <label>Date:</label>
            <span><?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></span>
        </div>
    </div>
    
    <div class="contact-message">
        <h3>Message</h3>
        <div class="message-content">
            <?= nl2br(htmlspecialchars($contact['message'])) ?>
        </div>
    </div>
    
    <div class="contact-actions">
        <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="btn-reply">Répondre</a>
        <a href="list.php" class="btn-back">Retour</a>
    </div>
</div>