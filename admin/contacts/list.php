<?php
require_once __DIR__.'db.php';
require_once __DIR__.'includes/auth_check.php';

$pdo = Database::getInstance();
$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>

<div class="contacts-management">
    <h2>Messages reçus</h2>
    
    <div class="search-filter">
        <input type="text" id="contactSearch" placeholder="Rechercher un message...">
    </div>

    <div class="contacts-list">
        <?php foreach ($contacts as $contact): ?>
        <div class="contact-card <?= $contact['is_read'] ? '' : 'unread' ?>">
            <div class="contact-header">
                <h3><?= htmlspecialchars($contact['name']) ?></h3>
                <span class="contact-date"><?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></span>
            </div>
            <div class="contact-body">
                <p class="contact-email"><?= htmlspecialchars($contact['email']) ?></p>
                <p class="contact-message"><?= nl2br(htmlspecialchars($contact['message'])) ?></p>
            </div>
            <div class="contact-actions">
                <a href="mailto:<?= $contact['email'] ?>" class="btn-reply">✉ Répondre</a>
                <form action="mark_read.php" method="POST" class="inline-form">
                    <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
                    <button type="submit" class="btn-mark-read">
                        <?= $contact['is_read'] ? 'Marquer non lu' : 'Marquer comme lu' ?>
                    </button>
                </form>
                <form action="delete.php" method="POST" class="inline-form">
                    <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
                    <button type="submit" class="btn-delete">Supprimer</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
// Recherche en temps réel
document.getElementById('contactSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.contact-card').forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? 'block' : 'none';
    });
});
</script>