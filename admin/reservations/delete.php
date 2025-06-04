<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();
$reservation_id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("DELETE FROM reservations WHERE id = ?")->execute([$reservation_id]);
    
    // Journalisation
    $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)")
       ->execute([$_SESSION['user_id'], "Suppression réservation #$reservation_id", $_SERVER['REMOTE_ADDR']]);
    
    header('Location: list.php?success=1');
    exit;
}

$reservation = $pdo->prepare("
    SELECT r.id, a.titre, u.username 
    FROM reservations r
    JOIN annonces a ON r.ad_id = a.id
    JOIN users u ON r.user_id = u.id
    WHERE r.id = ?
")->execute([$reservation_id])->fetch();
?>

<div class="delete-confirmation">
    <h2>Confirmer la suppression</h2>
    <p>Êtes-vous sûr de vouloir supprimer la réservation pour l'annonce "<?= htmlspecialchars($reservation['titre']) ?>" ?</p>
    <p>Client: <?= htmlspecialchars($reservation['username']) ?></p>
    
    <form method="POST">
        <div class="form-actions">
            <button type="submit" class="btn-confirm">Confirmer</button>
            <a href="list.php" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>