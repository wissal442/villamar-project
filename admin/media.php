<?php
require_once __DIR__.'db.php';
require_once __DIR__.'includes/auth_check.php';

$pdo = Database::getInstance();

// Suppression d'image
if (isset($_POST['delete_image'])) {
    $imageId = $_POST['image_id'];
    $image = $pdo->prepare("SELECT * FROM ad_images WHERE id = ?")->execute([$imageId])->fetch();
    
    if ($image) {
        unlink(__DIR__.'/../../uploads/'.$image['image_path']);
        $pdo->prepare("DELETE FROM ad_images WHERE id = ?")->execute([$imageId]);
    }
}

// Liste des images
$images = $pdo->query("
    SELECT ai.*, a.titre 
    FROM ad_images ai
    LEFT JOIN annonces a ON ai.ad_id = a.id
    ORDER BY ai.id DESC
")->fetchAll();
?>

<div class="media-manager">
    <h2>Gestion des médias</h2>
    
    <div class="media-grid">
        <?php foreach ($images as $image): ?>
        <div class="media-item">
            <img src="../uploads/<?= $image['image_path'] ?>" alt="Image annonce">
            <div class="media-info">
                <?php if ($image['ad_id']): ?>
                <p>Annonce: <?= htmlspecialchars($image['titre']) ?></p>
                <?php endif; ?>
                <p>Date: <?= date('d/m/Y', strtotime($image['created_at'])) ?></p>
            </div>
            <form method="POST" onsubmit="return confirm('Supprimer cette image définitivement?')">
                <input type="hidden" name="image_id" value="<?= $image['id'] ?>">
                <button type="submit" name="delete_image" class="btn-delete">Supprimer</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>