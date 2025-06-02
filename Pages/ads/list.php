<?php 
include '../includes/header.php';
require '../includes/db.php';

// Récupérer les annonces
$query = "SELECT * FROM ads WHERE status = 'approved' ORDER BY created_at DESC";
$ads = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold text-primary">
            <i class="fas fa-home me-2"></i>Nos Annonces Immobilières
        </h1>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Déposer une annonce
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($ads as $ad): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 overflow-hidden">
                <div class="position-relative">
                    <img src="<?= htmlspecialchars($ad['main_image']) ?>" 
                         class="card-img-top object-fit-cover" 
                         alt="<?= htmlspecialchars($ad['title']) ?>"
                         style="height: 200px; width: 100%;">
                    <span class="badge bg-success position-absolute top-0 end-0 m-2">
                        <?= ucfirst($ad['type']) ?>
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-truncate"><?= htmlspecialchars($ad['title']) ?></h5>
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                        <?= htmlspecialchars($ad['location']) ?>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary fs-5">
                            <?= number_format($ad['price'], 0, ',', ' ') ?> DH
                        </span>
                        <a href="detail.php?id=<?= $ad['id'] ?>" class="btn btn-sm btn-outline-primary">
                            Voir plus
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <small class="text-muted">
                        <i class="far fa-clock me-1"></i>
                        <?= date('d/m/Y', strtotime($ad['created_at'])) ?>
                    </small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>