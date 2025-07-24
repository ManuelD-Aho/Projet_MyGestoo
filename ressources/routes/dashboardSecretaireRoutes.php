<?php

require_once __DIR__ . '/../../app/controllers/DashboardSecretaireController.php';
require_once __DIR__ . '/../../app/config/database.php';

$pdo = Database::getConnection();
$dashboardController = new DashboardSecretaireController($pdo);

if (isset($_GET['page']) && $_GET['page'] === 'dashboard_secretaire') {
    $data = $dashboardController->index();
    
    // Extraire les données pour la vue
    $stats = $data['stats'];
    $activites = $data['activites'];
    $evolutionEffectifs = $data['evolutionEffectifs'];
    
   
} 