<?php
require 'includes/db.php';
require 'includes/functions.php';

if (isMaintenanceMode() && !(isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin')) {
    header('HTTP/1.1 503 Service Unavailable');
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Maintenance en cours</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { 
                background-color: #f8f9fa; 
                height: 100vh;
                display: flex;
                align-items: center;
            }
            .maintenance-card {
                max-width: 600px;
                margin: 0 auto;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="card maintenance-card">
                <div class="card-body p-5">
                    <h1 class="text-danger"><i class="fas fa-tools fa-3x mb-4"></i></h1>
                    <h2 class="card-title">Maintenance en cours</h2>
                    <p class="card-text">
                        Notre site est actuellement en maintenance. Nous revenons au plus vite.
                    </p>
                    <p class="text-muted">
                        <small>Veuillez nous excuser pour la gêne occasionnée.</small>
                    </p>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}