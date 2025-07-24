<?php

if (isset($_GET['page']) && $_GET['page'] === 'piste_audit') {
    require_once __DIR__ . '/../../app/config/database.php';
    require_once __DIR__ . '/../../app/controllers/AuditController.php';
    $controller = new AuditController();
    $controller->index();
}