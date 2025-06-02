<?php
// Protège contre les redéfinitions si le fichier est inclus plusieurs fois
if (defined('FUNCTIONS_LOADED')) return;
define('FUNCTIONS_LOADED', true);

/**
 * Vérifie si l'utilisateur est connecté
 */
if (!function_exists('checkLoggedIn')) {
    function checkLoggedIn() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }
}

/**
 * Vérifie les permissions admin
 */
if (!function_exists('checkAdmin')) {
    function checkAdmin() {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'admin') {
            header('Location: index.php');
            exit;
        }
    }
}

/**
 * Vérifie le rôle (équivalent de checkAuth)
 */
if (!function_exists('checkAuth')) {
    function checkAuth($role = null) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        if ($role && ($_SESSION['role'] ?? null) !== $role) {
            header('Location: index.php');
            exit;
        }
    }
}

/**
 * Filtre les données
 */
if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        return htmlspecialchars(trim($data));
    }
}

/**
 * Redirige avec un message
 */
if (!function_exists('redirect')) {
    function redirect($url, $message = null, $type = 'success') {
        if ($message) {
            $_SESSION['flash_message'] = $message;
            $_SESSION['flash_type'] = $type;
        }
        header("Location: $url");
        exit;
    }
}

/**
 * Affiche les messages flash
 */
if (!function_exists('displayFlash')) {
    function displayFlash() {
        if (isset($_SESSION['flash_message'])) {
            $type = $_SESSION['flash_type'] ?? 'success';
            echo '<div class="alert alert-' . $type . '">' . $_SESSION['flash_message'] . '</div>';
            unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        }
    }
}

/**
 * Journalise une activité
 */
if (!function_exists('logActivity')) {
    function logActivity($userId, $action) {
        global $pdo;

        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $action, $ip]);

            // Écriture dans un fichier log
            $logMessage = date('[Y-m-d H:i:s]') . "|" . ($userId ?? 'system') . "|$action|$ip" . PHP_EOL;
            file_put_contents(__DIR__ . '/../logs/app.log', $logMessage, FILE_APPEND);
        } catch (Exception $e) {
            // Silence en cas d'erreur de logging
        }
    }
}

/**
 * Active ou désactive le mode maintenance
 */
if (!function_exists('setMaintenanceMode')) {
    function setMaintenanceMode($enabled) {
        global $pdo;

        $value = $enabled ? '1' : '0';
        $stmt = $pdo->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES ('maintenance_mode', ?) 
            ON DUPLICATE KEY UPDATE setting_value = ?
        ");
        $stmt->execute([$value, $value]);

        logActivity($_SESSION['user_id'] ?? null, 
            $enabled ? "Activation mode maintenance" : "Désactivation mode maintenance");
    }
}

/**
 * Vérifie si le mode maintenance est activé
 */
if (!function_exists('isMaintenanceMode')) {
    function isMaintenanceMode() {
        global $pdo;

        try {
            $stmt = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'maintenance_mode'");
            return $stmt->fetchColumn() === '1';
        } catch (Exception $e) {
            return false;
        }
    }
}
// Dans includes/functions.php
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (!function_exists('getCountries')) {
    function getCountries() {
        return [
            'MA' => 'Maroc',
            'BE' => 'Belgique',
            'CA' => 'Canada',
            'US' => 'États-Unis',
        'TN' => 'Tunisie',
        'EG' => 'Égypte',
        'SA' => 'Arabie Saoudite',
        'AE' => 'Émirats Arabes Unis',
        'QA' => 'Qatar',
        'KW' => 'Koweït',
        'OM' => 'Oman',
        'BH' => 'Bahreïn',
        'JO' => 'Jordanie',
        'FR' => 'France',
        'ES' => 'Espagne',
        'IT' => 'Italie',
        'US' => 'États-Unis',
            
        ];
    }
}
