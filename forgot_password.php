<?php include 'includes/header.php'; ?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <!-- En-tête -->
                    <div class="auth-header">
                        <div class="logo-wrapper">
                            <i class="fas fa-key logo-icon"></i>
                        </div>
                        <h2><i class="fas fa-lock me-2"></i>Réinitialisation du Mot de Passe</h2>
                        <p class="mb-0">Entrez votre adresse email pour recevoir un lien de réinitialisation</p>
                    </div>
                    
                    <!-- Corps du formulaire -->
                    <div class="auth-body">
                        <!-- Message d'erreur -->
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger text-center">
                                <?= htmlspecialchars($_GET['error']) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="includes/auth_functions.php" class="needs-validation" novalidate>
                            <!-- Email -->
                            <div class="mb-4 input-field">
                                <div class="input-group">
                                    <i class="fas fa-at input-icon"></i>
                                    <input type="email" class="form-control" name="email" placeholder="Adresse email" required>
                                    <div class="invalid-feedback">Veuillez saisir un email valide</div>
                                </div>
                                <div class="input-underline"></div>
                            </div>

                            <!-- Bouton -->
                            <button type="submit" class="btn btn-auth w-100 mb-3">
                                <span class="btn-circle"></span> 
                                <span class="btn-text">Envoyer le lien</span>
                                <i class="fas fa-paper-plane btn-icon"></i>
                            </button>

                            <!-- Lien retour -->
                            <div class="text-center auth-links">
                                <p class="register-text">Retour à la <a href="login.php" class="register-link">connexion</a></p>
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
  background-color: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  z-index: 0;
  pointer-events: none;
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
  border: 2px solid #BBD8A3;
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

/* Lien retour */
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
