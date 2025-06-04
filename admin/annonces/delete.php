<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT image_path FROM ad_images WHERE ad_id = ?");
$stmt->execute([$id]);
$images = $stmt->fetchAll();
    
    
    // Supprimer les fichiers images
    foreach ($images as $img) {
        $filePath = __DIR__.'/../../uploads/'.$img['image_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    // Supprimer de la base de données
    $pdo->prepare("DELETE FROM annonces WHERE id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM ad_images WHERE ad_id = ?")->execute([$id]);
    
    // Journalisation
    $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)")
       ->execute([$_SESSION['user_id'], "Suppression annonce #$id", $_SERVER['REMOTE_ADDR']]);
    
    header('Location: list.php?success=1');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ?");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    header('Location: list.php');
    exit;
}

?>

<div class="delete-confirmation">
    <h2>Confirmer la suppression</h2>
    <p>Êtes-vous sûr de vouloir supprimer l'annonce "<?= htmlspecialchars($annonce['titre']) ?>" ?</p>
    
    <form method="POST">
        <div class="form-actions">
            <button type="submit" class="btn-confirm-delete">Confirmer la suppression</button>
            <a href="list.php" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>