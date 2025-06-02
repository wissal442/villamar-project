<?php
include 'includes/header.php';
require 'includes/db.php';
require_once 'includes/functions.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Définir le rôle par défaut
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'user';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $uploadDir = 'uploads/';
        $file = $_FILES['main_image'];

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if ($file['error'] === 0) {
            $allowedTypes = ['image/jpeg', 'image/png'];
            $maxSize = 2 * 1024 * 1024;

            if (!in_array($file['type'], $allowedTypes)) {
                $error = "Format d'image non autorisé. Utilisez JPG ou PNG.";
            } elseif ($file['size'] > $maxSize) {
                $error = "Image trop lourde. Maximum 2MB.";
            } else {
                $filename = uniqid() . '_' . basename($file['name']);
                $targetPath = $uploadDir . $filename;

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $query = "INSERT INTO annonces 
                        (user_id, titre, description, type, prix, ville, surface, rooms, main_image, statut, country_code)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $statut = ($_SESSION['role'] === 'admin') ? 'acceptée' : 'en attente';

                    $stmt = $pdo->prepare($query);
                    $stmt->execute([
                        $_SESSION['user_id'],
                        htmlspecialchars($_POST['title']),
                        htmlspecialchars($_POST['description']),
                        $_POST['type'],
                        (float)$_POST['price'],
                        htmlspecialchars($_POST['location']),
                        (int)$_POST['surface'],
                        (int)$_POST['rooms'],
                        $targetPath,
                        $statut,
                        $_POST['country_code'] ?? 'MA'
                    ]);

                    $_SESSION['message'] = "Annonce créée avec succès" . ($statut === 'en attente' ? " (en attente de validation)" : "");
                    header('Location: annonces.php');
                    exit;
                } else {
                    $error = "Erreur lors de l'envoi de l'image.";
                }
            }
        } else {
            $error = "Veuillez sélectionner une image valide.";
        }
    } catch (PDOException $e) {
        $error = "Erreur base de données : " . $e->getMessage();
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Créer une nouvelle annonce</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" novalidate>
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Type *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="appartement">Appartement</option>
                                    <option value="villa">Villa</option>
                                    <option value="terrain">Terrain</option>
                                    <option value="immeuble">Immeuble</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Prix (DH) *</label>
                                <input type="number" class="form-control" id="price" name="price" required step="0.01" min="0">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Ville *</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="surface" class="form-label">Surface (m²) *</label>
                                <input type="number" class="form-control" id="surface" name="surface" required min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="rooms" class="form-label">Pièces *</label>
                                <input type="number" class="form-control" id="rooms" name="rooms" required min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Pays *</label>
                            <select class="form-select" id="country" name="country_code" required>
                                <?php foreach (getCountries() as $code => $country): ?>
                                    <option value="<?= $code ?>" <?= ($code === ($_POST['country_code'] ?? 'MA')) ? 'selected' : '' ?>>
                                        <?= $country ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="mainImage" class="form-label">Image principale *</label>
                            <input type="file" class="form-control" id="mainImage" name="main_image" accept="image/jpeg,image/png" required>
                            <small class="text-muted">Formats acceptés: JPG, PNG. Max 2MB.</small>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Publier l'annonce
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('mainImage').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">`;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
