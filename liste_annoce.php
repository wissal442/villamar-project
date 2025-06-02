<?php
session_start();
require_once 'db.php'; // connexion PDO

try {
    $stmt = $pdo->prepare("SELECT * FROM annonces WHERE statut = 'acceptée' ORDER BY date_creation DESC");
    $stmt->execute();
    $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Liste des annonces immobilières</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Style personnalisé pour harmoniser avec vos autres pages */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        header, footer {
            background-color: #343a40;
            color: white;
            padding: 1rem 0;
        }
        header h1, footer p {
            margin: 0;
            text-align: center;
        }
        .card {
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.15);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgb(0 0 0 / 0.25);
        }
        .card-title {
            color: #007bff;
            font-weight: 600;
        }
        .card-footer {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<header>
    <div class="container">
        <h1>Mon Site Immobilier</h1>
    </div>
</header>

<main class="container my-5">
    <h2 class="mb-4 text-center">Annonces immobilières disponibles</h2>

    <?php if (empty($annonces)) : ?>
        <div class="alert alert-info text-center">Aucune annonce acceptée pour le moment.</div>
    <?php else : ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($annonces as $annonce) : ?>
                <div class="col">
                    <div class="card h-100">
                        <!-- Image placeholder, à remplacer par une vraie image si vous en avez -->
                        <img src="https://via.placeholder.com/400x200?text=Image+Bien" class="card-img-top" alt="Image <?= htmlspecialchars($annonce['titre']) ?>" />
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($annonce['titre']) ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars(substr($annonce['description'], 0, 150))) ?>...</p>
                            <p><strong>Type :</strong> <?= htmlspecialchars($annonce['type']) ?></p>
                            <p><strong>Prix :</strong> <?= number_format($annonce['prix'], 2, ',', ' ') ?> MAD</p>
                            <p><strong>Ville :</strong> <?= htmlspecialchars($annonce['ville']) ?></p>
                        </div>
                        <div class="card-footer text-center">
                            Publiée le <?= date('d/m/Y', strtotime($annonce['date_creation'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <div class="container">
        <p>© <?= date('Y') ?> Mon Site Immobilier - Tous droits réservés</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
