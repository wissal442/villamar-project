<?php
include 'includes/header.php';
require 'includes/db.php';

// Vérifier les permissions admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
 //  header('Location: login.php');
 //  exit;
 //}

// Récupérer les stats
$usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$adsCount = $pdo->query("SELECT COUNT(*) FROM annonces")->fetchColumn();
$pendingAds = $pdo->query("SELECT COUNT(*) FROM annonces WHERE statut = 'pending'")->fetchColumn();
?>
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="annonces.php">
                            <i class="fas fa-home me-2"></i>Gestion des annonces
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="utilisateurs.php">
                            <i class="fas fa-users me-2"></i>Gestion des utilisateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Tableau de bord</h1>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Utilisateurs</h5>
                            <h2><?= $usersCount ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Annonces</h5>
                            <h2><?= $adsCount ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title">En attente</h5>
                            <h2><?= $pendingAds ?></h2>
                        </div>
                    </div>
                </div>
            </div>
<!-- Ajoutez cette section dans dashboard.php après les stats cards -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Maintenance</h5>
            </div>
            <div class="card-body">
                <form id="maintenanceForm">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="maintenanceSwitch" 
                               <?= isMaintenanceMode() ? 'checked' : '' ?>>
                        <label class="form-check-label" for="maintenanceSwitch">
                            Activer le mode maintenance
                        </label>
                    </div>
                    <small class="text-muted">
                        En mode maintenance, seuls les administrateurs peuvent accéder au site.
                    </small>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Gestion du mode maintenance
document.getElementById('maintenanceSwitch').addEventListener('change', function() {
    const isChecked = this.checked;
    
    fetch('api/toggle_maintenance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            enable: isChecked,
            csrf_token: document.querySelector('#maintenanceForm input[name="csrf_token"]').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(`Mode maintenance ${isChecked ? 'activé' : 'désactivé'}`, 'success');
        } else {
            showToast(data.error, 'error');
            document.getElementById('maintenanceSwitch').checked = !isChecked;
        }
    });
});
</script>
            <!-- Dernières annonces -->
            <div class="card">
                <div class="card-header">
                    <h5>Dernières annonces</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                     
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT a.*, u.username FROM annonces a JOIN users u ON a.user_id = u.id; ";
                                $ads = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
                                
                                foreach ($ads as $ad): ?>
                                <tr>
                                    <td><?= $ad['id'] ?></td>
                                    <td><?= htmlspecialchars($ad['titre']) ?></td>
                                    <td><?= number_format($ad['prix'], 0, ',', ' ') ?> DH</td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $ad['statut'] === 'approved' ? 'success' : 
                                            ($ad['statut'] === 'pending' ? 'warning' : 'danger') 
                                        ?>">
                                            <?= ucfirst($ad['statut']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit_ad.php?id=<?= $ad['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-ad" data-id="<?= $ad['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'includes/footer.php'; ?>