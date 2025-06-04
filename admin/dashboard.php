<?php
require_once __DIR__.'/../includes/auth_check.php';
require_once __DIR__.'/../includes/db.php';
$pdo = Database::getInstance();

// Statistiques principales
$stats = [
    'annonces' => ['count' => $pdo->query("SELECT COUNT(*) FROM annonces")->fetchColumn(), 'icon' => '📋', 'color' => 'rose'],
    'users' => ['count' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(), 'icon' => '👤', 'color' => 'menthe'],
    'contacts' => ['count' => $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn(), 'icon' => '✉️', 'color' => 'aqua'],
    'en_attente' => ['count' => $pdo->query("SELECT COUNT(*) FROM annonces WHERE statut = 'en attente'")->fetchColumn(), 'icon' => '⏳', 'color' => 'ciel'],
    'reservations' => ['count' => $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn(), 'icon' => '📅', 'color' => 'bleu'],
    'media' => ['count' => $pdo->query("SELECT COUNT(*) FROM ad_images")->fetchColumn(), 'icon' => '🖼️', 'color' => 'lavande']
];

// Données récentes
$latest = [
    'annonces' => $pdo->query("SELECT id, titre, statut FROM annonces ORDER BY created_at DESC LIMIT 5")->fetchAll(),
    'users' => $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll(),
    'contacts' => $pdo->query("SELECT id, name, email, created_at FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll(),
    'reservations' => $pdo->query("SELECT r.id, a.titre, u.username, r.status FROM reservations r JOIN annonces a ON r.ad_id = a.id JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC LIMIT 5")->fetchAll(),
    'logs' => $pdo->query("SELECT id, action, created_at FROM activity_logs ORDER BY created_at DESC LIMIT 10")->fetchAll()
];

// Paramètres du site
$settings = $pdo->query("SELECT setting_key, setting_value FROM settings LIMIT 3")->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../admin/dashboard.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-cogs"></i> Administration</h2>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li><a href="annonces/list.php"><i class="fas fa-newspaper"></i> Annonces</a></li>
                <li><a href="users/list.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
                <li><a href="reservations/list.php"><i class="fas fa-calendar-check"></i> Réservations</a></li>
                <li><a href="contacts/list.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="media/list.php"><i class="fas fa-images"></i> Médias</a></li>
                <li><a href="settings/edit.php"><i class="fas fa-cog"></i> Paramètres</a></li>
                <li><a href="activity_logs/list.php"><i class="fas fa-history"></i> Journal</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="../logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="admin-dashboard">
                <div class="content-header">
                    <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord</h1>
                    <div class="user-info">
                        <span>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></span>
                        <div class="user-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Cartes de statistiques -->
                <div class="stats-grid">
                    <?php foreach ($stats as $key => $data): ?>
                    <div class="stat-card <?= $data['color'] ?>">
                        <div class="stat-icon"><?= $data['icon'] ?></div>
                        <div class="stat-info">
                            <span class="stat-value"><?= $data['count'] ?></span>
                            <span class="stat-label"><?= ucfirst(str_replace('_', ' ', $key)) ?></span>
                        </div>
                        <a href="<?= $key ?>/list.php" class="stat-link"></a>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="dashboard-main">
                    <!-- Colonne de gauche -->
                    <div class="dashboard-col">
                        <!-- Dernières annonces -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-newspaper"></i> Dernières Annonces</h3>
                                <a href="annonces/list.php" class="view-all">Tout voir <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <table class="data-table">
                                <?php foreach ($latest['annonces'] as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['titre']) ?></td>
                                    <td><span class="badge <?= $item['statut'] ?>"><?= $item['statut'] ?></span></td>
                                    <td class="actions">
                                        <a href="annonces/edit.php?id=<?= $item['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                        <a href="annonces/delete.php?id=<?= $item['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer cette annonce?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>

                        <!-- Derniers contacts -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-envelope"></i> Messages Réçus</h3>
                                <a href="contacts/list.php" class="view-all">Tout voir <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <table class="data-table">
                                <?php foreach ($latest['contacts'] as $contact): ?>
                                <tr>
                                    <td><?= htmlspecialchars($contact['name']) ?></td>
                                    <td><?= htmlspecialchars($contact['email']) ?></td>
                                    <td class="actions">
                                        <a href="contacts/view.php?id=<?= $contact['id'] ?>" class="btn-view"><i class="fas fa-eye"></i></a>
                                        <a href="contacts/delete.php?id=<?= $contact['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ce message?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>

                    <!-- Colonne centrale -->
                    <div class="dashboard-col">
                        <!-- Derniers utilisateurs -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-users"></i> Nouveaux Utilisateurs</h3>
                                <a href="users/list.php" class="view-all">Tout voir <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <table class="data-table">
                                <?php foreach ($latest['users'] as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><span class="role <?= $user['role'] ?>"><?= $user['role'] ?></span></td>
                                    <td class="actions">
                                        <a href="users/edit.php?id=<?= $user['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                        <a href="users/delete.php?id=<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer cet utilisateur?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>

                        <!-- Dernières réservations -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-calendar-check"></i> Réservations Récentes</h3>
                                <a href="reservations/list.php" class="view-all">Tout voir <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <table class="data-table compact">
                                <?php foreach ($latest['reservations'] as $res): ?>
                                <tr>
                                    <td><?= htmlspecialchars($res['titre']) ?></td>
                                    <td><?= htmlspecialchars($res['username']) ?></td>
                                    <td><span class="status <?= $res['status'] ?>"><?= $res['status'] ?></span></td>
                                    <td class="actions">
                                        <a href="reservations/edit.php?id=<?= $res['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>

                    <!-- Colonne de droite -->
                    <div class="dashboard-col">
                        <!-- Paramètres rapides -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-cog"></i> Paramètres du Site</h3>
                                <a href="settings/edit.php" class="view-all">Modifier <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <ul class="settings-list">
                                <?php foreach ($settings as $key => $value): ?>
                                <li>
                                    <span class="setting-key"><?= ucfirst(str_replace('_', ' ', $key)) ?>:</span>
                                    <span class="setting-value"><?= htmlspecialchars($value) ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Activités récentes -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3><i class="fas fa-history"></i> Journal d'Activité</h3>
                                <a href="activity_logs/list.php" class="view-all">Voir tout <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <ul class="activity-list">
                                <?php foreach ($latest['logs'] as $log): ?>
                                <li>
                                    <span class="log-action"><?= $log['action'] ?></span>
                                    <span class="log-time"><?= date('H:i', strtotime($log['created_at'])) ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Confirmation pour les suppressions
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Confirmer la suppression ?')) {
                e.preventDefault();
            }
        });
    });

    // Menu responsive
    document.querySelector('.menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
        document.querySelector('.main-content').classList.toggle('active');
    });
    </script>
</body>
</html>