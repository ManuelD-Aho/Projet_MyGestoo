<?php


require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/controllers/DashboardController.php';
require_once __DIR__ . '/../../app/controllers/DashboardCommissionController.php';

$controller = new DashboardController();
$commissionController = new DashboardCommissionController();

if (isset($_GET['page'])) {
    if ($_GET['page'] === 'dashboard') {
        $controller->index(); 
    } elseif ($_GET['page'] === 'dashboard_commission') {
        $commissionController->index();
    }
}