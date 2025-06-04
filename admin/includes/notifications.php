<?php
require_once __DIR__.'/../db.php';
require_once __DIR__.'/../auth_check.php';

class NotificationSystem {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance();
    }
    
    public function getUnreadCount($user_id): int {
        return $this->pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0")
                       ->execute([$user_id])
                       ->fetchColumn();
    }
    
    public function getRecentNotifications($user_id, $limit = 5): array {
        return $this->pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?")
                        ->execute([$user_id, $limit])
                        ->fetchAll();
    }
    
    public function markAsRead($notification_id): bool {
        return $this->pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?")
                        ->execute([$notification_id]);
    }
    
    public function create($user_id, $message, $link = null): bool {
        return $this->pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)")
                        ->execute([$user_id, $message, $link]);
    }
}
?>

<!-- Affichage dans le header -->
<div class="notification-bell">
    <span class="badge"><?= (new NotificationSystem())->getUnreadCount($_SESSION['user_id']) ?></span>
    <div class="notification-dropdown">
        <?php foreach ((new NotificationSystem())->getRecentNotifications($_SESSION['user_id']) as $notif): ?>
        <a href="<?= $notif['link'] ?: '#' ?>" class="notification-item <?= $notif['is_read'] ? '' : 'unread' ?>">
            <?= htmlspecialchars($notif['message']) ?>
            <small><?= date('H:i', strtotime($notif['created_at'])) ?></small>
        </a>
        <?php endforeach; ?>
    </div>
</div>