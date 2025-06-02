<?php
 session_start();
include 'includes/header.php';
require 'includes/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupération de l'annonce si un ID est fourni
$ad = null;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ?");
    $stmt->execute([$id]);
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification des droits : propriétaire ou admin
    if (!$ad || ($ad['user_id'] !== $_SESSION['user_id'] && $_SESSION['role'] !== 'admin')) {
        header('Location: dashboard.php');
        exit;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titre' => htmlspecialchars($_POST['titre']),
        'description' => htmlspecialchars($_POST['description']),
        'type' => $_POST['type'],
        'prix' => (float)$_POST['prix'],
        'ville' => htmlspecialchars($_POST['ville']),
        'surface' => (int)$_POST['surface'],
        'rooms' => (int)$_POST['rooms'],
        'id' => (int)$_POST['id']
    ];

    $query = "UPDATE annonces SET 
                titre = :titre,
                description = :description,
                type = :type,
                prix = :prix,
                ville = :ville,
                surface = :surface,
                rooms = :rooms
              WHERE id = :id";

    $stmt = $pdo->prepare($query);
    if ($stmt->execute($data)) {
        $_SESSION['message'] = "Annonce mise à jour avec succès";
        header('Location: annonces.php');
        exit;
    } else {
        $error = "Erreur lors de la mise à jour de l'annonce";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        <?= isset($ad['id']) ? 'Modifier l\'annonce' : 'Créer une annonce' ?>
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $ad['id'] ?? '' ?>">

                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" value="<?= $ad['titre'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required><?= $ad['description'] ?? '' ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="appartement" <?= isset($ad['type']) && $ad['type'] === 'appartement' ? 'selected' : '' ?>>Appartement</option>
                                    <option value="villa" <?= isset($ad['type']) && $ad['type'] === 'villa' ? 'selected' : '' ?>>Villa</option>
                                    <option value="terrain" <?= isset($ad['type']) && $ad['type'] === 'terrain' ? 'selected' : '' ?>>Terrain</option>
                                    <option value="immeuble" <?= isset($ad['type']) && $ad['type'] === 'immeuble' ? 'selected' : '' ?>>Immeuble</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prix" class="form-label">Prix (DH)</label>
                                <input type="number" class="form-control" id="prix" name="prix" value="<?= $ad['prix'] ?? '' ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" value="<?= $ad['ville'] ?? '' ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="surface" class="form-label">Surface (m²)</label>
                                <input type="number" class="form-control" id="surface" name="surface" value="<?= $ad['surface'] ?? '' ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="rooms" class="form-label">Nombre de pièces</label>
                                <input type="number" class="form-control" id="rooms" name="rooms" value="<?= $ad['rooms'] ?? '' ?>" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
