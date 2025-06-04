<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();

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
    $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)")
       ->execute([$_SESSION['user_id'], "Modification des paramètres du site", $_SERVER['REMOTE_ADDR']]);
    
    header("Location: edit.php?success=1");
    exit;
}

$settings = $pdo->query("SELECT * FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<form method="POST" class="settings-form">
    <div class="form-section">
        <h3>Informations du site</h3>
        
        <div class="form-group">
            <label>Nom du site</label>
            <input type="text" name="settings[site_name]" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="settings[site_description]"><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
        </div>
    </div>
    
    <div class="form-section">
        <h3>Configuration</h3>
        
        <div class="form-group">
            <label>Email de contact</label>
            <input type="email" name="settings[contact_email]" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>Mode maintenance</label>
            <select name="settings[maintenance_mode]">
                <option value="0" <?= ($settings['maintenance_mode'] ?? 0) == 0 ? 'selected' : '' ?>>Désactivé</option>
                <option value="1" <?= ($settings['maintenance_mode'] ?? 0) == 1 ? 'selected' : '' ?>>Activé</option>
            </select>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn-save">Enregistrer</button>
    </div>
</form>