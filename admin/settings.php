<?php
require_once __DIR__.'db.php';
require_once __DIR__.'auth_check.php';

$pdo = Database::getInstance();

// Récupération des paramètres existants
$settings = $pdo->query("SELECT * FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['settings'] as $key => $value) {
        $stmt = $pdo->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE setting_value = ?
        ");
        $stmt->execute([$key, $value, $value]);
    }
    
    // Journalisation
    $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, ip_address)
        VALUES (?, ?, ?)
    ")->execute([
        $_SESSION['user_id'],
        "Modification des paramètres du site",
        $_SERVER['REMOTE_ADDR']
    ]);
    
    header("Location: settings.php?success=1");
    exit;
}
?>

<div class="settings-management">
    <h2>Paramètres du site</h2>
    
    <form method="POST">
        <div class="setting-item">
            <label>Titre du site</label>
            <input type="text" name="settings[site_title]" value="<?= htmlspecialchars($settings['site_title'] ?? '') ?>">
        </div>
        
        <div class="setting-item">
            <label>Description</label>
            <textarea name="settings[site_description]"><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
        </div>
        
        <div class="setting-item">
            <label>Email de contact</label>
            <input type="email" name="settings[contact_email]" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>">
        </div>
        
        <div class="setting-item">
            <label>Maintenance mode</label>
            <select name="settings[maintenance_mode]">
                <option value="0" <?= ($settings['maintenance_mode'] ?? 0) == 0 ? 'selected' : '' ?>>Non</option>
                <option value="1" <?= ($settings['maintenance_mode'] ?? 0) == 1 ? 'selected' : '' ?>>Oui</option>
            </select>
        </div>
        
        <button type="submit" class="btn-save">Enregistrer</button>
    </form>
</div>