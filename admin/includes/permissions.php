<?php
class PermissionManager {
    const PERMISSIONS = [
        'admin' => [
            'view_dashboard',
            'manage_annonces',
            'manage_users',
            'manage_reservations',
            'manage_settings'
        ],
        'moderator' => [
            'view_dashboard',
            'manage_annonces',
            'manage_reservations'
        ]
    ];
    
    public static function check($permission) {
        if (!isset($_SESSION['user_role'])) {
            header('Location: /login.php');
            exit;
        }
        
        if (!in_array($permission, self::PERMISSIONS[$_SESSION['user_role']])) {
            die('Accès non autorisé');
        }
    }
}

// Utilisation :
// PermissionManager::check('manage_users');
?>