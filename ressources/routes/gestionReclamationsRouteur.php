<?php
// Routeur pour la gestion des réclamations
// Chemin adapté à votre structure existante

require_once __DIR__ . '/../../app/controllers/GestionReclamationsController.php';

// Créer une instance du contrôleur
$controller = new GestionReclamationsController();

// Gérer les actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'soumettre_reclamation':
            $controller->soumettreReclamations();
            break;
        case 'suivi_historique_reclamation':
            $controller->suiviHistoriqueReclamations();
            break;
        case 'traiter':
            $controller->traiterReclamation();
            break;
        case 'exporter_reclamations':
            $controller->exporterReclamations();
            break;
        case 'get_reclamation_details':
            $controller->getReclamationDetailsAjax();
            break;
    }
} else {
    // Aucune action spécifiée, afficher le dashboard
    $controller->index();
}