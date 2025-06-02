<?php
session_start();
require 'includes/db.php';
require_once 'includes/functions.php';

// Vérifie que l'utilisateur est admin
checkAdmin();

// Fonction pour afficher un message flash
function displayFlashMessage() {
    if (!empty($_SESSION['message'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['message']) . '</div>';
        unset($_SESSION['message']);
    }
    if (!empty($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']);
    }
}

// Récupérer les paramètres existants
$settings = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $_SESSION['error'] = "Token CSRF invalide.";
        header('Location: admin_settings.php');
        exit;
    }

    // Préparer et sécuriser les données reçues
    $site_title = htmlspecialchars(trim($_POST['site_title']));
    $admin_email = filter_var($_POST['admin_email'], FILTER_SANITIZE_EMAIL);
    $items_per_page = (int)$_POST['items_per_page'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE setting_value = :value
        ");

        $params = [
            ['site_title', $site_title],
            ['admin_email', $admin_email],
            ['items_per_page', $items_per_page]
        ];

        foreach ($params as $param) {
            $stmt->execute([':key' => $param[0], ':value' => $param[1]]);
        }

        $pdo->commit();

        logActivity($_SESSION['user_id'], "Mise à jour des paramètres système");

        $_SESSION['message'] = "Paramètres mis à jour avec succès.";
        header('Location: admin_settings.php');
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur lors de la mise à jour : " . $e->getMessage();
        header('Location: admin_settings.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Paramètres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f8f9fa;">

<div class="container py-5">
    <h1 class="mb-4">Paramètres d'administration</h1>

    <?php displayFlashMessage(); ?>

    <form method="POST" class="bg-white p-4 shadow rounded">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="mb-3">
            <label for="site_title" class="form-label">Titre du site</label>
            <input type="text" class="form-control" id="site_title" name="site_title" 
                   value="<?= htmlspecialchars($settings['site_title'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="admin_email" class="form-label">Email administrateur</label>
            <input type="email" class="form-control" id="admin_email" name="admin_email" 
                   value="<?= htmlspecialchars($settings['admin_email'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="items_per_page" class="form-label">Éléments par page</label>
            <select class="form-select" id="items_per_page" name="items_per_page" required>
                <?php
                $options = [5, 10, 20, 50];
                $current = (int)($settings['items_per_page'] ?? 10);
                foreach ($options as $opt) {
                    $selected = ($opt === $current) ? 'selected' : '';
                    echo "<option value=\"$opt\" $selected>$opt</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i> Enregistrer
        </button>
    </form>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
