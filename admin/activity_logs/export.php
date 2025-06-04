<?php
require_once __DIR__.'/../../../includes/db.php';
require_once __DIR__.'/../../../includes/auth_check.php';

$pdo = Database::getInstance();
$annonce_id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $annonce_id) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $image_name = uniqid().'_'.basename($_FILES['images']['name'][$key]);
            $target_file = __DIR__.'/../../../uploads/'.$image_name;
            
            if (move_uploaded_file($tmp_name, $target_file)) {
                $pdo->prepare("INSERT INTO ad_images (ad_id, image_path) VALUES (?, ?)")
                   ->execute([$annonce_id, $image_name]);
            }
        }
    }
    header("Location: edit.php?id=$annonce_id&success=1");
    exit;
}
?>

<form method="POST" enctype="multipart/form-data" class="upload-form">
    <h3>Ajouter des images</h3>
    <div class="form-group">
        <label>Sélectionnez plusieurs images (max 10)</label>
        <input type="file" name="images[]" multiple accept="image/*" max="10" required>
    </div>
    <button type="submit" class="btn-upload">Uploader</button>
</form>