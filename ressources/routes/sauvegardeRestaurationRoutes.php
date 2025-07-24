<?php
require_once __DIR__ . '/../../app/controllers/SauvegardeRestaurationController.php';

$controller = new SauvegardeRestaurationController();

// Vérifier si on est sur la page de sauvegarde/restauration
if (isset($_GET['page']) && $_GET['page'] === 'sauvegarde_restauration') {
    
    // Gestion des actions
    switch (isset($_GET['action']) ? $_GET['action'] : null) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->createBackup();
                exit;
            }
            break;
            
        case 'restore':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->restoreBackup();
                exit;
            }
            break;
        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->deleteBackup();
                exit;
            }
            break;
            
        case 'download':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller->downloadBackup();
                exit;
            }
            break;
            
        default:
            // Afficher la page principale avec la liste des sauvegardes
            $backups = $controller->index();
            break;
    }
} 