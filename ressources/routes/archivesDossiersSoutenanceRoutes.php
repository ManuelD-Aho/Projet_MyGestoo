<?php

require_once __DIR__ . '/../../app/controllers/ArchivesDossiersSoutenanceController.php';

/**
 * Routes pour les archives des dossiers de soutenance
 */

if ($_GET['page'] === 'archives_dossiers_soutenance') {
    $controller = new ArchivesDossiersSoutenanceController();

    // Route pour exporter les archives
    if (isset($_GET['export'])) {
        $filtres = [
            'statut' => $_GET['statut'] ?? '',
            'annee' => $_GET['annee'] ?? '',
            'enseignant' => $_GET['enseignant'] ?? '',
            'etudiant' => $_GET['etudiant'] ?? '',
            'date_debut' => $_GET['date_debut'] ?? '',
            'date_fin' => $_GET['date_fin'] ?? ''
        ];
        
        $archivesData = $controller->getArchivesData($filtres);
        
        // Logique d'export (CSV, Excel, etc.)
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="archives_rapports_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // En-têtes CSV
        fputcsv($output, [
            'ID Rapport',
            'Statut',
            'Nom Rapport',
            'Thème',
            'Étudiant',
            'Enseignant',
            'Date Dépôt',
            'Date Validation',
            'Temps Traitement',
            'Commentaire'
        ]);
        
        // Données
        foreach ($archivesData['rapports_archives'] as $rapport) {
            fputcsv($output, [
                $rapport['id_rapport'],
                $rapport['decision_validation'],
                $rapport['nom_rapport'] ?? '',
                $rapport['theme_rapport'] ?? '',
                ($rapport['prenom_etu'] ?? '') . ' ' . ($rapport['nom_etu'] ?? ''),
                ($rapport['prenom_enseignant'] ?? '') . ' ' . ($rapport['nom_enseignant'] ?? ''),
                $rapport['date_rapport'] ?? '',
                $rapport['date_validation'] ?? '',
                $rapport['temps_traitement'] ?? 0,
                $rapport['commentaire_validation'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }

    // Gestion des actions
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'details_rapport':
                if (isset($_GET['id'])) {
                    $rapportDetails = $controller->getRapportDetails($_GET['id']);
                    $evaluations = $controller->getEvaluationsRapport($_GET['id']);
                    
                    if ($rapportDetails) {
                        echo "<script>alert('Détails du rapport #" . $_GET['id'] . "');</script>";
                    } else {
                        echo "<script>alert('Rapport non trouvé');</script>";
                    }
                }
                break;
                
            case 'download_rapport':
                if (isset($_GET['id'])) {
                    $rapportDetails = $controller->getRapportDetails($_GET['id']);
                    
                    if ($rapportDetails) {
                        echo "<script>alert('Téléchargement du rapport #" . $_GET['id'] . "');</script>";
                    } else {
                        echo "<script>alert('Rapport non trouvé');</script>";
                    }
                }
                break;
                
            default:
                // Action non reconnue, afficher la page principale
                break;
        }
    }

    // Route principale pour afficher les archives
    $controller->index();
    $contentFile = __DIR__ . '/../views/archives_dossiers_soutenance_content.php';
} 