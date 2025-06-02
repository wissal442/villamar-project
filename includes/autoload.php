<?php
require 'includes/autoload.php';
require 'includes/db.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: client_dashboard.php');
    exit;
}

$error = '';
$success = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Étape 1: Vérification email/mot de passe
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Générer un OTP
            $otp = rand(100000, 999999);
            $otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));
            
            // Stocker en session et en base de données
            $_SESSION['otp_data'] = [
                'user_id' => $user['id'],
                'otp' => $otp,
                'expires' => $otp_expiry
            ];
            
            $stmt = $pdo->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE id = ?");
            $stmt->execute([$otp, $otp_expiry, $user['id']]);
            
            // Envoyer l'OTP par email
            if (sendOTP($email, $otp)) {
                $_SESSION['otp_sent'] = true;
                $success = "Un code OTP a été envoyé à votre adresse email.";
            } else {
                $error = "Erreur lors de l'envoi de l'OTP. Veuillez réessayer.";
            }
        } else {
            $error = "Email ou mot de passe incorrect";
        }
    } elseif (isset($_POST['verify_otp'])) {
        // Étape 2: Vérification de l'OTP
        $otp = $_POST['otp'];
        $user_id = $_SESSION['otp_data']['user_id'] ?? null;
        
        if ($user_id) {
            $stmt = $pdo->prepare("SELECT otp, otp_expiry FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            if ($user && $user['otp'] == $otp && strtotime($user['otp_expiry']) > time()) {
                // Connexion réussie
                $_SESSION['user_id'] = $user_id;
                
                // Réinitialiser l'OTP
                $stmt = $pdo->prepare("UPDATE users SET otp = NULL, otp_expiry = NULL WHERE id = ?");
                $stmt->execute([$user_id]);
                
                unset($_SESSION['otp_data']);
                unset($_SESSION['otp_sent']);
                
                header('Location: client_dashboard.php');
                exit;
            } else {
                $error = "Code OTP incorrect ou expiré";
            }
        } else {
            $error = "Session invalide. Veuillez recommencer le processus de connexion.";
        }
    }
}

/**
 * Envoie un OTP par email
 */
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuration SMTP (à adapter)
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Remplacez par votre hôte SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'votre@email.com'; // Remplacez par votre email
        $mail->Password = 'votre-mot-de-passe'; // Remplacez par votre mot de passe
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Expéditeur et destinataire
        $mail->setFrom('no-reply@villamar.ma', 'Villamar Immobilier');
        $mail->addAddress($email);
        
        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Votre code de vérification Villamar';
        $mail->Body = "
            <h2 style='color: #6F826A;'>Votre code de sécurité</h2>
            <p>Bonjour,</p>
            <p>Voici votre code de vérification pour accéder à votre compte Villamar Immobilier :</p>
            <div style='background: #F0F1C5; padding: 15px; text-align: center; font-size: 24px; letter-spacing: 3px; margin: 20px 0; border-radius: 5px;'>
                <strong>$otp</strong>
            </div>
            <p>Ce code est valable pendant 5 minutes.</p>
            <p>Si vous n'avez pas demandé ce code, veuillez ignorer cet email.</p>
            <hr>
            <p style='font-size: 12px; color: #777;'>Villamar Immobilier - 34 avenue Oqbah, appt. 2, Agdal Rabat - Maroc</p>
        ";
        
        $mail->AltBody = "Votre code de vérification Villamar est: $otp (valable 5 minutes)";
        
        return $mail->send();
    } catch (Exception $e) {
        error_log("Erreur d'envoi d'email: " . $e->getMessage());
        return false;
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3><i class="fas fa-lock me-2"></i>Connexion sécurisée</h3>
                </div>
                
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['otp_sent']) && $_SESSION['otp_sent']): ?>
                        <!-- Formulaire de vérification OTP -->
                        <form action="login.php" method="post">
                            <div class="text-center mb-4">
                                <p>Entrez le code à 6 chiffres envoyé à votre adresse email</p>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-center">
                                    <input type="text" name="otp" id="otp" class="form-control form-control-lg text-center" 
                                           style="width: 200px; letter-spacing: 5px; font-size: 24px;" 
                                           maxlength="6" pattern="\d{6}" required autofocus>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" name="verify_otp" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check-circle me-2"></i> Vérifier
                                </button>
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="login.php?resend=1" class="text-muted">Renvoyer le code</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <!-- Formulaire de connexion standard -->
                        <form action="login.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                                </div>
                                <a href="forgot_password.php" class="text-primary">Mot de passe oublié ?</a>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" name="login" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p>Vous n'avez pas de compte ? <a href="register.php" class="text-primary">S'inscrire</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
