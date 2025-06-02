<?php
require_once __DIR__.'db.php';
require_once __DIR__.'includes/auth_check.php';

$pdo = Database::getInstance();
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement du formulaire
    $stmt = $pdo->prepare("
        INSERT INTO annonces (
            user_id, titre, description, type, prix, 
            ville, adresse, surface, rooms, main_image
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Upload de l'image
    $imageName = '';
    if (isset($_FILES['main_image'])) {
        $uploadDir = __DIR__.'/../../uploads/';
        $imageName = uniqid().'_'.$_FILES['main_image']['name'];
        move_uploaded_file($_FILES['main_image']['tmp_name'], $uploadDir.$imageName);
    }

    $stmt->execute([
        $userId,
        $_POST['titre'],
        $_POST['description'],
        $_POST['type'],
        $_POST['prix'],
        $_POST['ville'],
        $_POST['adresse'],
        $_POST['surface'],
        $_POST['rooms'],
        $imageName
    ]);

    header("Location: /user/annonces/?success=1");
    exit;
}
?>

<form method="POST" enctype="multipart/form-data" class="annonce-form">
    <div class="form-group">
        <label>Titre *</label>
        <input type="text" name="titre" required>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Type *</label>
            <select name="type" required>
                <option value="appartement">Appartement</option>
                <option value="villa">Villa</option>
                <option value="terrain">Terrain</option>
                <option value="immeuble">Immeuble</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Prix (MAD) *</label>
            <input type="number" name="prix" step="1000" required>
        </div>
    </div>

    <div class="form-group">
        <label>Description détaillée *</label>
        <textarea name="description" rows="5" required></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Ville *</label>
            <input type="text" name="ville" required>
        </div>
        
        <div class="form-group">
            <label>Adresse</label>
            <input type="text" name="adresse">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Surface (m²)</label>
            <input type="number" name="surface">
        </div>
        
        <div class="form-group">
            <label>Pièces</label>
            <input type="number" name="rooms">
        </div>
    </div>

    <div class="form-group">
        <label>Image principale *</label>
        <input type="file" name="main_image" accept="image/*" required>
    </div>

    <button type="submit" class="btn-submit">Publier l'annonce</button>
</form>