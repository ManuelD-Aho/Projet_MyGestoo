<?php
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/controllers/DossierAcademiqueController.php';
$pdo = Database::getConnection();
$controller = new DossierAcademiqueController($pdo);

if (isset($_GET['page']) && $_GET['page'] === 'dossiers_academiques') {

   $controller->index();
}