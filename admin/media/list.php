<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();

// Suppression d'image
if (isset($_POST['delete_media'])) {
    $media_id = $_POST['media_id'];
    $media = $pdo->prepare("SELECT * FROM ad_images WHERE id = ?")->execute([$media_id])->fetch();
    
    if ($media) {
        unlink(__DIR__."/../../uploads/".$media['image_path']);
        $pdo->prepare("DELETE FROM ad_images WHERE id = ?")->execute([$media_id]);
        
        // Journalisation
        $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)")
           ->execute([$_SESSION['user_id'], "Suppression média #$media_id", $_SERVER['REMOTE_ADDR']]);
    }
}

$media_items = $pdo->query("
    SELECT ai.*, a.titre 
    FROM ad_images ai
    LEFT JOIN annonces a ON ai.ad_id = a.id
    ORDER BY ai.created_at DESC
")->fetchAll();
?>

<div class="media-management">
    <h2>Gestion des Médias</h2>
    
    <div class="media-grid">
        <?php foreach ($media_items as $media): ?>
        <div class="media-item">
            <img src="../uploads/<?= $media['image_path'] ?>" alt="Media <?= $media['id'] ?>">
            <div class="media-info">
                <?php if ($media['ad_id']): ?>
                <p><strong>Annonce:</strong> <?= htmlspecialchars($media['titre']) ?></p>
                <?php endif; ?>
                <p><strong>Uploadé le:</strong> <?= date('d/m/Y H:i', strtotime($media['created_at'])) ?></p>
            </div>
            <form method="POST" onsubmit="return confirm('Supprimer définitivement ce média?')">
                <input type="hidden" name="media_id" value="<?= $media['id'] ?>">
                <button type="submit" name="delete_media" class="btn-delete">Supprimer</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>