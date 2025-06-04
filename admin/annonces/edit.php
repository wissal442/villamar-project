<?php
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/auth_check.php';

$pdo = Database::getInstance();
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titre' => $_POST['titre'],
        'description' => $_POST['description'],
        'type' => $_POST['type'],
        'prix' => $_POST['prix'],
        'ville' => $_POST['ville'],
        'adresse' => $_POST['adresse'],
        'surface' => $_POST['surface'],
        'rooms' => $_POST['rooms'],
        'statut' => $_POST['statut'],
        'id' => $id
    ];

    $sql = "UPDATE annonces SET 
            titre = :titre, 
            description = :description,
            type = :type,
            prix = :prix,
            ville = :ville,
            adresse = :adresse,
            surface = :surface,
            rooms = :rooms,
            statut = :statut
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    // Journalisation
    $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)")
       ->execute([$_SESSION['user_id'], "Modification annonce #$id", $_SERVER['REMOTE_ADDR']]);

    header('Location: list.php?success=1');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ?");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    header('Location: list.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Annonce</title>
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166088;
            --accent-color: #4fc3f7;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
            padding: 20px;
        }

        .annonce-form {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .annonce-form h1 {
            color: var(--secondary-color);
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary-color);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.2);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-save,
        .btn-cancel {
            padding: 12px 25px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-save {
            background-color: var(--success-color);
            color: white;
        }

        .btn-save:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background-color: var(--light-color);
            color: var(--dark-color);
            border: 1px solid #ddd;
        }

        .btn-cancel:hover {
            background-color: #e2e6ea;
            transform: translateY(-2px);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .annonce-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <form method="post" class="annonce-form">
        <h1>Modifier l'annonce</h1>
        
        <div class="form-group">
            <label>Titre</label>
            <input type="text" name="titre" value="<?= htmlspecialchars($annonce['titre']) ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Type</label>
                <select name="type" required>
                    <option value="appartement" <?= $annonce['type'] === 'appartement' ? 'selected' : '' ?>>Appartement</option>
                    <option value="villa" <?= $annonce['type'] === 'villa' ? 'selected' : '' ?>>Villa</option>
                    <option value="terrain" <?= $annonce['type'] === 'terrain' ? 'selected' : '' ?>>Terrain</option>
                    <option value="immeuble" <?= $annonce['type'] === 'immeuble' ? 'selected' : '' ?>>Immeuble</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Statut</label>
                <select name="statut" required>
                    <option value="en attente" <?= $annonce['statut'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
                    <option value="acceptée" <?= $annonce['statut'] === 'acceptée' ? 'selected' : '' ?>>Acceptée</option>
                    <option value="refusée" <?= $annonce['statut'] === 'refusée' ? 'selected' : '' ?>>Refusée</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5" required><?= htmlspecialchars($annonce['description']) ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Prix (MAD)</label>
                <input type="number" name="prix" step="1000" value="<?= $annonce['prix'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Surface (m²)</label>
                <input type="number" name="surface" value="<?= $annonce['surface'] ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Ville</label>
                <input type="text" name="ville" value="<?= htmlspecialchars($annonce['ville']) ?>">
            </div>
            
            <div class="form-group">
                <label>Adresse</label>
                <input type="text" name="adresse" value="<?= htmlspecialchars($annonce['adresse']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Nombre de pièces</label>
            <input type="number" name="rooms" value="<?= $annonce['rooms'] ?>">
        </div>

        <div class="form-actions">
            <a href="list.php" class="btn-cancel">Annuler</a>
            <button type="submit" class="btn-save">Enregistrer</button>
        </div>
    </form>
</body>
</html>