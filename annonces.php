<?php
include 'includes/header.php';
require 'includes/db.php';

// Vérifier les permissions admin
//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//    header('Location:  login.php');
//    exit;
//}

// Gérer les actions
if (isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    
    switch ($_GET['action']) {
        case 'approve':
            $pdo->prepare("UPDATE annonces SET statut = 'approved' WHERE id = ?")->execute([$id]);
            $_SESSION['message'] = "Annonce approuvée avec succès";
            break;
            
        case 'reject':
            $pdo->prepare("UPDATE annonces SET status = 'rejected' WHERE id = ?")->execute([$id]);
            $_SESSION['message'] = "Annonce rejetée avec succès";
            break;
            
        case 'delete':
            $pdo->prepare("DELETE FROM annonces WHERE id = ?")->execute([$id]);
            $_SESSION['message'] = "Annonce supprimée avec succès";
            break;
    }
    
    header('Location: annonces.php');
    exit;
}

// Récupérer toutes les annonces
$query = "SELECT a.*, u.username FROM annonces a JOIN users u ON a.user_id = u.id";
$annonces = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid py-4">
    <div class="row">
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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des annonces</h1>
                <a href="create_ad.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Créer une annonce
                </a>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="adsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Utilisateur</th>
                                    <th>Prix</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($annonces as $ad): ?>
                                <tr>
                                    <td><?= $ad['id'] ?></td>
                                    <td><?= htmlspecialchars($ad['titre']) ?></td>
                                    <td><?= htmlspecialchars($ad['username']) ?></td>
                                    <td><?= number_format($ad['prix'], 0, ',', ' ') ?> DH</td>
                                    <td><?= date('d/m/Y', strtotime($ad['created_at'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $ad['statut'] === 'approved' ? 'success' : 
                                            ($ad['statut'] === 'pending' ? 'warning' : 'danger') 
                                        ?>">
                                            <?= ucfirst($ad['statut']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="edit_ad.php?id=<?= $ad['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($ad['statut'] === 'pending'): ?>
                                                <a href="annonces.php?action=approve&id=<?= $ad['id'] ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="annonces.php?action=reject&id=<?= $ad['id'] ?>" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="annonces.php?action=delete&id=<?= $ad['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
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

<script>
$(document).ready(function() {
    $('#adsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json'
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>