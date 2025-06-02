<?php 
include 'includes/header.php';
require 'includes/db.php';
require_once 'includes/functions.php';

// Charger la liste des pays
$countries = getCountries();

// Construire la requête avec filtres dynamiques
$conditions = ["statut = 'acceptée'"];
$params = [];

// Filtre de recherche par ville ou adresse
if (!empty($_GET['q'])) {
    $conditions[] = "(ville LIKE :q OR adresse LIKE :q)";
    $params[':q'] = '%' . $_GET['q'] . '%';
}

// Filtre par type
if (!empty($_GET['type'])) {
    $conditions[] = "type = :type";
    $params[':type'] = $_GET['type'];
}

// Filtre par pays
if (!empty($_GET['country'])) {
    $conditions[] = "country_code = :country";
    $params[':country'] = $_GET['country'];
}

// Construire la requête SQL finale
$query = "SELECT * FROM annonces WHERE " . implode(" AND ", $conditions) . " ORDER BY id DESC LIMIT 6";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Section HÉRO avec formulaire de recherche avancée -->
<div class="hero-section" style="background: linear-gradient(rgba(107, 130, 106, 0.8), rgba(107, 130, 106, 0.8)), url('assets/images/hero-bg.jpg'); background-size: cover; background-position: center;">
    <div class="container">
        <div class="hero-content text-center text-white py-5">
            <h1>Trouvez votre propriété idéale</h1>
            <p>Découvrez des biens exceptionnels dans les meilleurs quartiers</p>

            <div class="search-box mt-4">
                <form action="" method="GET">
                    <div class="row g-2 justify-content-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="q" class="form-control" placeholder="Rechercher par ville, quartier..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="country">
                                <option value="">Tous les pays</option>
                                <?php foreach ($countries as $code => $country): ?>
                                    <option value="<?= $code ?>" <?= (isset($_GET['country']) && $_GET['country'] === $code) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($country) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="type">
                                <option value="">Tous types</option>
                                <option value="appartement" <?= (isset($_GET['type']) && $_GET['type'] === 'appartement') ? 'selected' : '' ?>>Appartement</option>
                                <option value="villa" <?= (isset($_GET['type']) && $_GET['type'] === 'villa') ? 'selected' : '' ?>>Villa</option>
                                <option value="terrain" <?= (isset($_GET['type']) && $_GET['type'] === 'terrain') ? 'selected' : '' ?>>Terrain</option>
                                <option value="immeuble" <?= (isset($_GET['type']) && $_GET['type'] === 'immeuble') ? 'selected' : '' ?>>Immeuble</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-light w-100">
                                Rechercher <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Affichage des dernières annonces -->
<div class="container mt-5">
    <h2 class="section-title">Nos dernières offres</h2>

    <div class="properties-grid d-flex flex-wrap gap-4 justify-content-center">
        <?php if(!empty($annonces)): ?>
            <?php foreach($annonces as $annonce): ?>
                <div class="property-card border rounded shadow-sm" style="width: 300px;">
                    <div class="property-badge bg-success text-white px-2 py-1 position-absolute">Nouveau</div>
                    <img src="assets/uploads/<?php echo htmlspecialchars($annonce['main_image'] ?: 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($annonce['titre']); ?>" class="img-fluid rounded-top" style="height: 200px; width: 100%; object-fit: cover;">
                    <div class="property-info p-3">
                        <h3><?php echo htmlspecialchars($annonce['titre']); ?></h3>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($annonce['adresse'] . ', ' . $annonce['ville']); ?></p>
                        <div class="property-price fw-bold text-primary fs-5">
                            <?= number_format($annonce['prix'], 0, ',', ' '); ?> DH
                        </div>
                        <a href="property.php?id=<?= $annonce['id']; ?>" class="btn btn-outline-primary mt-2 w-100">Voir détails</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune annonce disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
