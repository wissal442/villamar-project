<?php
require 'includes/db.php';
session_start();

// 1. Vérification du token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    header('Location: forgot_password.php?error=invalid_token');
    exit();
}

$token = $_GET['token'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: forgot_password.php?error=expired_token');
    exit();
}

// 2. Traitement du formulaire
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération sécurisée
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($password)) {
        $errors['password'] = "Le mot de passe est requis";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères";
    }

    if (empty($confirm_password)) {
        $errors['confirm_password'] = "Veuillez confirmer le mot de passe";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Les mots de passe ne correspondent pas";
    }

    // Mise à jour si tout est OK
    if (empty($errors)) {
        $newPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $stmt->execute([$newPassword, $user['id']]);
            $pdo->commit();
            $success = true;

            // Redirection après 3 secondes
            header("Refresh:3; url=login.php");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors['database'] = "Erreur technique : " . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-key"></i> Réinitialisation du mot de passe</h4>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Mot de passe mis à jour avec succès ! Redirection en cours...
                        </div>
                    <?php else: ?>
                        <?php if (!empty($errors['database'])): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($errors['database']) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       id="password" name="password" required>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['password']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmation</label>
                                <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                       id="confirm_password" name="confirm_password" required>
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['confirm_password']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
