<?php


require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/controllers/NotesController.php';

$controller = new NotesController();

if (isset($_GET['page']) && ( $_GET['page'] === 'gestion_notes_evaluations')) {

    $controller->index(); 
}