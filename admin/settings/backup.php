<?php
require_once __DIR__.'/../../../includes/db.php';
require_once __DIR__.'/../../../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $backup_file = 'backup_'.date('Y-m-d_H-i-s').'.sql';
    $command = "mysqldump --user=".DB_USER." --password=".DB_PASS." --host=".DB_HOST." ".DB_NAME." > ../../backups/$backup_file";
    
    system($command, $output);
    
    if ($output === 0) {
        header('Location: ../settings.php?backup_success=1');
    } else {
        header('Location: ../settings.php?backup_error=1');
    }
    exit;
}
?>

<div class="backup-confirmation">
    <h2>Sauvegarde de la base de données</h2>
    <p>Cette action va créer une sauvegarde complète de la base de données.</p>
    
    <form method="POST">
        <div class="form-actions">
            <button type="submit" class="btn-confirm">Lancer la sauvegarde</button>
            <a href="../settings.php" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>