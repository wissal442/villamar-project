<?php
// favorites.php
require 'includes/favorite_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$favorites = getUserFavorites($_SESSION['user_id']);
?>

<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-heart text-danger me-2"></i>Mes favoris</h2>
    
    <?php if (empty($favorites)): ?>
        <div class="alert alert-info">
            Vous n'avez aucun bien en favoris pour le moment.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($favorites as $ad): ?>
                <!-- Utilisez le même format de carte que list.php -->
                <?php include 'components/property_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>