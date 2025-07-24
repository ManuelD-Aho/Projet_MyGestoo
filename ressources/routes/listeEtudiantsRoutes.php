<?php 

if (isset($_GET['page']) && $_GET['page'] === 'liste_etudiants_resp_filiere') {
    require_once __DIR__ . '/../../app/config/database.php';
    require_once __DIR__ . '/../../app/controllers/GestionEtudiantController.php';

    $controller = new GestionEtudiantController();
    $controller->index();
} 

if (isset($_GET['page']) && $_GET['page'] === 'liste_etudiants_resp_niveau') {
    require_once __DIR__ . '/../../app/config/database.php';
    require_once __DIR__ . '/../../app/controllers/GestionEtudiantController.php';

    $controller = new GestionEtudiantController();
    $controller->index();
} 