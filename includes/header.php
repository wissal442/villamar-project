<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pays = [
    'MA' => ['name' => 'Maroc', 'currency' => 'DH'],
    'FR' => ['name' => 'France', 'currency' => '€'],
    'BE' => ['name' => 'Belgique', 'currency' => '€'],
    'CA' => ['name' => 'Canada', 'currency' => '$'],
    'US' => ['name' => 'États-Unis', 'currency' => '$'],
    'TN' => ['name' => 'Tunisie', 'currency' => 'TND'],
    'EG' => ['name' => 'Égypte', 'currency' => 'EGP'],
    'SA' => ['name' => 'Arabie Saoudite', 'currency' => 'SAR'],
    'AE' => ['name' => 'Émirats Arabes Unis', 'currency' => 'AED'],
    'QA' => ['name' => 'Qatar', 'currency' => 'QAR'],
    'KW' => ['name' => 'Koweït', 'currency' => 'KWD'],
    'OM' => ['name' => 'Oman', 'currency' => 'OMR'],
    'BH' => ['name' => 'Bahreïn', 'currency' => 'BHD'],
    'JO' => ['name' => 'Jordanie', 'currency' => 'JOD'],
    'LB' => ['name' => 'Liban', 'currency' => 'LBP'],
    'IQ' => ['name' => 'Irak', 'currency' => 'IQD'],
];

// Fonction pour drapeau 🇲🇦
function getFlagEmoji($countryCode) {
    $code = strtoupper($countryCode);
    return mb_chr(0x1F1E6 + ord($code[0]) - 65, 'UTF-8') .
           mb_chr(0x1F1E6 + ord($code[1]) - 65, 'UTF-8');
}

// Détection du pays sélectionné
if (isset($_GET['country']) && array_key_exists($_GET['country'], $pays)) {
    $_SESSION['country'] = $_GET['country'];
}
$selectedCountry = $_SESSION['country'] ?? 'MA';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villamar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/filters.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        :root {
            --primary: #6F826A;
            --secondary: #BF9264;
            --light: #F0F1C5;
            --accent: #BBD8A3;
            --text: #3A3A3A;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--light) !important;
            font-size: 1.8rem;
        }
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 8px;
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: var(--light) !important;
            transform: translateY(-2px);
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body style="background-color: var(--light); color: var(--text);">
<script src="/assets/js/main.js"></script>
<script src="/assets/js/auth.js"></script>
<script src="/assets/js/password.js"></script>

<nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-leaf me-2"></i>Villamar Immobilier
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="annonces.php"><i class="fas fa-home me-1"></i> Biens</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php"><i class="fas fa-envelope me-1"></i> Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <!-- Dropdown pays -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="countryDropdown" role="button" data-bs-toggle="dropdown">
                        🌍 <?= $pays[$selectedCountry]['name'] ?> (<?= $pays[$selectedCountry]['currency'] ?>)
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php foreach ($pays as $code => $info): ?>
                            <li>
                                <a class="dropdown-item" href="?country=<?= $code ?>">
                                    <?= getFlagEmoji($code) ?> <?= $info['name'] ?> (<?= $info['currency'] ?>)
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> Mon Espace
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="mon_profil.php"><i class="fas fa-user text-secondary me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="mes_annonces.php"><i class="fas fa-home text-secondary me-2"></i>Mes biens</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt text-secondary me-2"></i>Déconnexion</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light me-2" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-accent" href="register.php"><i class="fas fa-user-plus me-1"></i> Inscription</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
