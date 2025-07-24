<?php
if (isset($_GET['page']) && $_GET['page'] === 'candidature_soutenance') {
    require_once __DIR__ . '/../../app/controllers/CandidatureSoutenanceController.php';
    $controller = new CandidatureSoutenanceController();

   $controller->index();
}