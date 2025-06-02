<?php include 'includes/header.php'; ?>

<style>
  :root {
    --primary: #6F826A;
    --secondary: #BF9264;
    --light: #F0F1C5;
    --accent: #BBD8A3;
    --dark: #3A3A3A;
  }
  
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--light);
  }

  .auth-container {
    position: relative;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
    background: url('assets/images/back.JPG') center/cover no-repeat;
  }

  .auth-container::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
    z-index: 0;
  }

  .auth-card {
    position: relative;
    z-index: 1;
    background: rgba(255, 255, 255, 0.85);
    border-radius: 18px;
    padding: 2rem;
    width: 500px;
    max-width: 95vw;
    box-shadow: 0 8px 32px rgba(255, 255, 255, 0.25);
    border: 2px solid var(--accent);
    transition: transform 0.3s;
  }

  .auth-card:hover {
    transform: translateY(-5px);
  }

  .auth-header {
    text-align: center;
    margin-bottom: 1rem;
    background: radial-gradient(circle at center, var(--secondary) 0%, var(--primary) 100%);
    padding: 1rem;
    border-radius: 14px;
    color: var(--light);
  }

  .logo-wrapper {
    width: 48px;
    height: 48px;
    margin: 0 auto 0.7rem;
    background: linear-gradient(45deg, var(--secondary), var(--primary));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .logo-icon {
    font-size: 1.6rem;
    color: var(--light);
  }

  .input-field {
    position: relative;
    margin-bottom: 1rem;
  }

  .input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    font-size: 1.1rem;
  }

  .form-control {
    width: 100%;
    padding: 10px 15px 10px 38px;
    border: none;
    border-bottom: 2px solid var(--accent);
    background: rgba(255, 255, 255, 0.7);
    font-size: 1rem;
    transition: all 0.3s;
  }

  .form-control:focus {
    outline: none;
    border-color: var(--secondary);
    background: rgba(255, 255, 255, 0.9);
  }

  .btn-auth {
    width: 100%;
    padding: 12px;
    background: linear-gradient(45deg, var(--secondary), var(--primary));
    color: white;
    border: none;
    border-radius: 30px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(111, 130, 106, 0.4);
  }

  .btn-auth:hover {
    background: linear-gradient(45deg, var(--primary), var(--secondary));
    transform: translateY(-2px);
  }

  .auth-links {
    text-align: center;
    margin-top: 1rem;
    font-size: 0.9rem;
    color: var(--primary);
  }

  .auth-link {
    color: var(--secondary);
    font-weight: 600;
    text-decoration: none;
  }

  .auth-link:hover {
    text-decoration: underline;
  }

  .invalid-feedback {
    color: #dc3545;
    font-size: 0.8rem;
    margin-top: 5px;
  }
</style>

<div class="auth-container">
  <div class="auth-card">
    <div class="auth-header">
      <div class="logo-wrapper">
        <i class="fas fa-home logo-icon"></i>
      </div>
      <h2>Créer un compte</h2>
      <p>Rejoignez Villamar Immobilier</p>
    </div>

    <form method="POST" action="includes/register_process.php" id="registerForm">
      <div class="input-field">
        <i class="fas fa-user input-icon"></i>
        <input type="text" class="form-control" name="username" placeholder="Nom complet" required>
        <div class="invalid-feedback" id="usernameError"></div>
      </div>

      <div class="input-field">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" class="form-control" name="email" placeholder="Email" required>
        <div class="invalid-feedback" id="emailError"></div>
      </div>

      <div class="input-field">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required minlength="8">
        <div class="invalid-feedback" id="passwordError"></div>
      </div>

      <div class="input-field">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmez le mot de passe" required>
        <div class="invalid-feedback" id="confirmError"></div>
      </div>

      <button type="submit" class="btn-auth">S'inscrire</button>
    </form>

    <div class="auth-links">
      Déjà inscrit ? <a href="login.php" class="auth-link">Connectez-vous</a>
    </div>
  </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
        document.getElementById('confirmError').textContent = "Les mots de passe ne correspondent pas";
        valid = false;
    }
    
    if (!valid) e.preventDefault();
});
</script>

<?php include 'includes/footer.php'; ?>