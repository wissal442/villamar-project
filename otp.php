<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['otp_data'])) {
    header('Location: register_process.php');
    exit;
}

require_once __DIR__ . '/db.php';

$error = '';
$email = $_SESSION['otp_data']['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_otp = trim($_POST['otp'] ?? '');
    
    if (empty($user_otp)) {
        $error = "Veuillez entrer le code";
    } elseif ($user_otp !== $_SESSION['otp_data']['otp']) {
        $error = "Code incorrect";
    } elseif (time() > $_SESSION['otp_data']['expires']) {
        $error = "Code expiré";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET verified = 1 WHERE id = ?");
        $stmt->execute([$_SESSION['otp_data']['user_id']]);
        
        $_SESSION['user_id'] = $_SESSION['otp_data']['user_id'];
        unset($_SESSION['otp_data']);
        
        header('Location: ../client_dashboard.php');
        exit;
    }
}

echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vérification OTP - Villamar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #6F826A;
            --secondary: #BF9264;
            --light: #F0F1C5;
            --accent: #BBD8A3;
            --dark: #3A3A3A;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../assets/images/back.JPG') center/cover no-repeat;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .auth-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            padding: 2rem;
        }
        .auth-container::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border-radius: 18px;
            z-index: 0;
        }
        .auth-card {
            position: relative;
            z-index: 1;
            background: white;
            border-radius: 18px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(111, 130, 106, 0.2);
            border: 1px solid var(--accent);
            text-align: center;
        }
        .auth-title {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        .otp-input {
            font-size: 1.5rem;
            letter-spacing: 10px;
            text-align: center;
            padding: 10px;
            width: 100%;
            border: 1px solid var(--accent);
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        /* Style modifié pour le bouton */
        .btn-auth {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
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

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
            color: #dc3545;
        }
        .resend-link {
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
        .auth-link {
            color: var(--secondary);
            font-weight: 600;
            text-decoration: none;
        }

        /* Classe pour les messages d'erreur sous le champ */
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">Vérification OTP</h1>
            <p>Entrez le code envoyé à <strong>HTMLspecialchars($email)</strong></p>
HTML;

if ($error) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
}

echo <<<HTML
            <form method="POST" novalidate>
                <input type="text" name="otp" class="otp-input" maxlength="6" pattern="\d{6}" required autofocus />
                <button type="submit" class="btn-auth">Valider</button>
            </form>
            <div class="resend-link">
                <a href="resend_otp.php" class="auth-link">Renvoyer le code</a>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
