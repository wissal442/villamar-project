// includes/favorite_functions.php
<?php
function toggleFavorite($userId, $adId) {
    global $pdo;
    
    try {
        // Vérifier si déjà en favori
        $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND ad_id = ?");
        $stmt->execute([$userId, $adId]);
        
        if ($stmt->fetch()) {
            // Supprimer des favoris
            $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND ad_id = ?");
            $stmt->execute([$userId, $adId]);
            return ['statut' => 'removed'];
        } else {
            // Ajouter aux favoris
            $stmt = $pdo->prepare("INSERT INTO favorites (user_id, ad_id) VALUES (?, ?)");
            $stmt->execute([$userId, $adId]);
            return ['statut' => 'added'];
        }
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    }
}

function getUserFavorites($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT a.* FROM annonces a
        JOIN favorites f ON a.id = f.ad_id
        WHERE f.user_id = ?
        ORDER BY f.created_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}