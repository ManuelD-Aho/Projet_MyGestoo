<?php

class Ue
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllUes()
    {
      $stmt = $this->pdo->query("SELECT ue.*, n.lib_niv_etude, s.lib_semestre, 
                          CONCAT(YEAR(a.date_deb), ' - ', YEAR(a.date_fin)) AS annee,
                          CONCAT(e.nom_enseignant, ' ', e.prenom_enseignant) AS nom_professeur
                          FROM ue 
                          JOIN niveau_etude n ON ue.id_niveau_etude = n.id_niv_etude
                          JOIN semestre s ON ue.id_semestre = s.id_semestre
                          JOIN annee_academique a ON ue.id_annee_academique = a.id_annee_acad
                          LEFT JOIN enseignants e ON ue.id_enseignant = e.id_enseignant
                          ORDER BY lib_ue");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ajouterUe($lib_ue, $id_niveau_etude, $id_semestre, $id_annee_academique, $credit, $id_enseignant = null)
    {
        $stmt = $this->pdo->prepare("INSERT INTO ue (lib_ue, id_niveau_etude, id_semestre, id_annee_academique, credit, id_enseignant) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$lib_ue, $id_niveau_etude, $id_semestre, $id_annee_academique, $credit, $id_enseignant]);
    }

    public function updateUe($id_ue, $lib_ue, $id_niveau_etude, $id_semestre, $id_annee_academique, $credit, $id_enseignant = null)
    {
        $stmt = $this->pdo->prepare("UPDATE ue SET lib_ue = ?, id_niveau_etude = ?, id_semestre = ?, id_annee_academique = ?, credit = ?, id_enseignant = ? WHERE id_ue = ?");
        return $stmt->execute([$lib_ue, $id_niveau_etude, $id_semestre, $id_annee_academique, $credit, $id_enseignant, $id_ue]);
    }

    public function deleteUe($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM ue WHERE id_ue = ?");
        return $stmt->execute([$id]);
    }

    public function getUeById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM ue WHERE id_ue = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getUesByNiveau(int $niveauId): array
    {
        $sql = "SELECT DISTINCT u.*, s.lib_semestre 
                FROM ue u 
                JOIN semestre s ON u.id_semestre = s.id_semestre 
                WHERE s.id_niv_etude = :niveau_id";
        
        $params = [':niveau_id' => $niveauId];
        
        $sql .= " ORDER BY s.lib_semestre, u.lib_ue";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getUesByEnseignant($enseignantId)
    {
        $stmt = $this->pdo->prepare("SELECT ue.*, n.lib_niv_etude, s.lib_semestre, CONCAT(YEAR(a.date_deb), ' - ', YEAR(a.date_fin)) AS annee
            FROM ue 
            JOIN niveau_etude n ON ue.id_niveau_etude = n.id_niv_etude
            JOIN semestre s ON ue.id_semestre = s.id_semestre
            JOIN annee_academique a ON ue.id_annee_academique = a.id_annee_acad
            WHERE ue.id_enseignant = ?
            ORDER BY lib_ue");
        $stmt->execute([$enseignantId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}