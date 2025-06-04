<?php
class DBHelper {
    public static function getDashboardStats(PDO $pdo): array {
        $sql = "SELECT 
            (SELECT COUNT(*) FROM annonces) as annonces,
            (SELECT COUNT(*) FROM users) as users,
            (SELECT COUNT(*) FROM contacts) as contacts,
            (SELECT COUNT(*) FROM annonces WHERE statut = 'en attente') as en_attente,
            (SELECT COUNT(*) FROM reservations) as reservations,
            (SELECT COUNT(*) FROM ad_images) as media";
        
        return $pdo->query($sql)->fetch();
    }

    public static function getLatestData(PDO $pdo): array {
        $sql = "
            (SELECT 'annonce' as type, id, titre, statut, created_at FROM annonces ORDER BY created_at DESC LIMIT 5)
            UNION ALL
            (SELECT 'user' as type, id, username, role, created_at FROM users ORDER BY created_at DESC LIMIT 5)
            UNION ALL
            (SELECT 'contact' as type, id, name as titre, email as statut, created_at FROM contacts ORDER BY created_at DESC LIMIT 5)
            UNION ALL
            (SELECT 'reservation' as type, r.id, a.titre, r.status as statut, r.created_at 
             FROM reservations r JOIN annonces a ON r.ad_id = a.id ORDER BY r.created_at DESC LIMIT 5)
            ORDER BY created_at DESC";
        
        return $pdo->query($sql)->fetchAll();
    }
}