<?php
// Activer la mise en tampon de sortie
ob_start();

require 'includes/db.php';
session_start(); // Important si non déjà lancé

// Vérifier les permissions admin (décommente si besoin)
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit;
// }

// Gérer les actions (avant tout affichage)
if (isset($_GET['action'])) {
    $id = (int)$_GET['id'];

    switch ($_GET['action']) {
        case 'promote':
            $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?")->execute([$id]);
            $_SESSION['message'] = "Utilisateur promu administrateur";
            break;

        case 'demote':
            $pdo->prepare("UPDATE users SET role = 'user' WHERE id = ?")->execute([$id]);
            $_SESSION['message'] = "Administrateur rétrogradé";
            break;

        case 'delete':
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
            $_SESSION['message'] = "Utilisateur supprimé";
            break;
    }

    header('Location: utilisateurs.php');
    exit;
}

// Récupérer tous les utilisateurs
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
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

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des utilisateurs</h1>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Inscrit le</th>
                                    <th>Rôle</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($user['role'] === 'user'): ?>
                                                <a href="utilisateurs.php?action=promote&id=<?= $user['id'] ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-user-shield"></i> Promouvoir
                                                </a>
                                            <?php else: ?>
                                                <a href="utilisateurs.php?action=demote&id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-user"></i> Rétrograder
                                                </a>
                                            <?php endif; ?>
                                            <a href="utilisateurs.php?action=delete&id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                                <i class="fas fa-trash"></i> Supprimer
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
    $('#usersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json'
        }
    });
});
</script>

<?php 
include 'includes/footer.php'; 
// Vider le tampon à la fin du script
ob_end_flush();
?>
