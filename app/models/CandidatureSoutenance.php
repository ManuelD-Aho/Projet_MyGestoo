<?php
require_once __DIR__ . '/../config/database.php';

class CandidatureSoutenance {
    public static function getStatutByEtudiant($num_etu) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT statut_candidature FROM candidature_soutenance WHERE num_etu = ? ORDER BY date_candidature DESC LIMIT 1");
        $stmt->execute([$num_etu]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['statut_candidature'] : null;
    }
} 