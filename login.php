<?php
session_start();
require_once 'includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            // Journalisation
            $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)")
               ->execute([$user['id'], "Connexion réussie", $_SERVER['REMOTE_ADDR']]);

            header('Location: ' . ($user['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php'));
            exit;
        } else {
            $error = "Identifiants incorrects";
        }
    } else {
        $error = "Veuillez remplir tous les champs";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <!-- En-tête -->
                    <div class="auth-header">
                        <div class="logo-wrapper">
                            <i class="fas fa-building-shield logo-icon"></i>
                        </div>
                        <h2><i class="fas fa-fingerprint me-2"></i>Connexion Sécurisée</h2>
                        <p class="mb-0">Accédez à votre tableau de bord</p>
                    </div>
                    
                    <!-- Corps du formulaire -->
                    <div class="auth-body">
                        <!-- Message d'erreur -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger text-center">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" class="needs-validation" novalidate>
                            <!-- Email -->
                            <div class="mb-4 input-field">
                                <div class="input-group">
                                    <i class="fas fa-at input-icon"></i>
                                    <input type="email" class="form-control" name="email" placeholder="Adresse email" required
                                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                                    <div class="invalid-feedback">Veuillez saisir un email valide</div>
                                </div>
                                <div class="input-underline"></div>
                            </div>

                            <!-- Mot de passe -->
                            <div class="mb-4 input-field">
                                <div class="input-group">
                                    <i class="fas fa-key input-icon"></i>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                                    <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                                    <div class="invalid-feedback">Le mot de passe est requis</div>
                                </div>
                                <div class="input-underline"></div>
                            </div>

                            <!-- Options -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember">
                                    <label class="form-check-label" for="remember">Maintenir la connexion</label>
                                </div>
                                <a href="forgot_password.php" class="forgot-password">Mot de passe oublié ?</a>
                            </div>

                            <!-- Bouton -->
                            <button type="submit" class="btn btn-auth w-100 mb-3">
                                <span class="btn-circle"></span> 
                                <span class="btn-text">Se connecter</span>
                                <i class="fas fa-arrow-right btn-icon"></i>
                            </button>

                            <!-- Lien inscription -->
                            <div class="text-center auth-links">
                                <p class="register-text">Première visite ? <a href="register.php" class="register-link">Créer un compte</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<style>
/* Fond image immobilière floutée + dégradé vert doux */
.auth-container {
  position: relative;
  min-height: 100vh;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
  overflow: hidden;
  box-sizing: border-box;
  background: url('assets/images/back.JPG') center center/cover no-repeat;
}

/* Calque flou blanc */
.auth-container::before {
  content: "";
  position: absolute;
  inset: 0;
  background-color: rgba(255, 255, 255, 0.6); /* Voile blanc semi-transparent */
  backdrop-filter: blur(10px); /* Flou appliqué derrière ce voile blanc */
  -webkit-backdrop-filter: blur(10px); /* Compatibilité Safari */
  z-index: 0;
  pointer-events: none; /* Pour que le calque ne gêne pas les interactions */
}

/* Contenu au-dessus */
.auth-card {
  position: relative;
  z-index: 1;
  background: rgba(255, 255, 255, 0.85);
  border-radius: 18px;
  padding: 2rem;
  width: 500px;
  max-width: 95vw;
  box-shadow: 0 8px 32px rgba(255, 255, 255, 0.25);
  border: 2px solid #BBD8A3; /* bordure vert doux */
}

.auth-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 36px 0 rgba(111,130,106,0.13), 0 2px 8px 0 rgba(255,255,255,0.28);
}

/* En-tête */
.auth-header {
  background: linear-gradient(135deg, #BBD8A3 0%, #F0F1C5 100%);
  color: #6F826A;
  padding: 0.8rem 0.4rem 0.7rem 0.4rem;
  text-align: center;
  border-radius: 16px 16px 0 0;
  box-shadow: 0 2px 8px rgba(255,255,255,0.25);
  position: relative;
  overflow: hidden;
}
.logo-wrapper {
  width: 58px;
  height: 58px;
  background: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 10px;
  box-shadow: 0 2px 8px rgba(187,216,163,0.18);
}
.logo-icon {
  font-size: 2.1rem;
  background: linear-gradient(135deg, #6F826A 0%, #BBD8A3 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}
.auth-header h2 {
  font-family: 'Montserrat', sans-serif;
  font-weight: 700;
  margin: 0;
  font-size: 1.3rem;
  position: relative;
  display: inline-block;
}
.auth-header h2::after {
  content: "";
  position: absolute;
  bottom: -6px;
  left: 50%;
  transform: translateX(-50%);
  width: 30px;
  height: 2px;
  background: #BBD8A3;
  border-radius: 3px;
}
.auth-header p {
  font-size: 0.98rem;
  opacity: 0.85;
  margin-bottom: 0;
  margin-top: 3px;
}

/* Formulaire */
.auth-body {
  padding: 1.3rem 1rem 1rem 1rem;
}
.input-field {
  margin-bottom: 0.8rem;
}
.input-group {
  position: relative;
}
.input-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #6F826A;
  font-size: 1.1rem;
  z-index: 2;
}
.password-toggle {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #6F826A;
  cursor: pointer;
  z-index: 2;
}
.form-control {
  width: 100%;
  padding: 10px 12px 10px 38px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  background-color: rgba(240, 241, 197, 0.09);
  transition: all 0.3s;
  font-size: 0.98rem;
  box-shadow: 0 1px 3px rgba(255,255,255,0.08) inset;
  height: auto;
}
.form-control:focus {
  border-color: #BBD8A3;
  box-shadow: 0 0 0 2px rgba(187,216,163,0.15);
  background-color: #fff;
}
.input-underline {
  position: absolute;
  bottom: -5px;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, #BBD8A3, #F0F1C5);
  transition: width 0.3s ease;
}
.form-control:focus ~ .input-underline {
  width: 100%;
}

/* Bouton */
.btn-auth {
  background: linear-gradient(135deg, #BBD8A3 0%, #6F826A 100%);
  color: #fff;
  border: none;
  padding: 12px;
  width: 100%;
  border-radius: 8px;
  font-weight: 600;
  letter-spacing: 0.5px;
  cursor: pointer;
  transition: all 0.3s;
  margin-top: 6px;
  box-shadow: 0 4px 15px rgba(187,216,163,0.18);
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}
.btn-circle {
  position: absolute;
  width: 20px;
  height: 20px;
  background: rgba(240,241,197,0.18);
  border-radius: 50%;
  left: 20px;
  transition: all 0.3s;
}
.btn-text {
  position: relative;
  z-index: 1;
  transition: all 0.3s;
}
.btn-icon {
  position: absolute;
  right: 20px;
  opacity: 0;
  transition: all 0.3s;
  z-index: 1;
}
.btn-auth:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(187,216,163,0.22);
  background: linear-gradient(135deg, #6F826A 0%, #BBD8A3 100%);
}
.btn-auth:hover .btn-circle {
  transform: scale(20);
}
.btn-auth:hover .btn-text {
  color: #6F826A;
}
.btn-auth:hover .btn-icon {
  opacity: 1;
  right: 15px;
  color: #6F826A;
}

/* Lien inscription */
.auth-links {
  font-size: 0.98rem;
  color: #6F826A;
  text-align: center;
}
.auth-links a.register-link {
  color: #BF9264;
  font-weight: 600;
  text-decoration: none;
}
.auth-links a.register-link:hover {
  text-decoration: underline;
}
.invalid-feedback {
  font-size: 0.85rem;
  color: #b03020;
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.password-toggle');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
// Validation formulaire
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>

