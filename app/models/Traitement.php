<?php

class Traitement
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupère tous les traitements
     * @return array Liste des traitements
     */
    public function getAllTraitements() {
        $sql = "SELECT * FROM traitement ORDER BY ordre_traitement ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Récupère les traitements attribués à un groupe d'utilisateurs
     * @param int $id_GU ID du groupe d'utilisateurs
     * @return array Liste des traitements
     */
    public function getTraitementByGU($id_GU) {
        $sql = "SELECT t.* FROM traitement t 
                INNER JOIN rattacher r ON t.id_traitement = r.id_traitement 
                WHERE r.id_GU = :id_GU 
                ORDER BY t.ordre_traitement ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_GU' => $id_GU]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    

    public function getTraitementById($id_traitement) {
        $stmt = $this->db->prepare("SELECT * FROM traitement WHERE id_traitement = ?");
        $stmt->execute([$id_traitement]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getTraitementByLib($lib_traitement) {
        $stmt = $this->db->prepare("SELECT * FROM traitement WHERE lib_traitement = ?");
        $stmt->execute([$lib_traitement]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addTraitement($lib_traitement, $label_traitement, $icone_traitement, $ordre_traitement) {
        $stmt = $this->db->prepare("INSERT INTO traitement (lib_traitement, label_traitement, icone_traitement, ordre_traitement) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$lib_traitement,$label_traitement,$icone_traitement,$ordre_traitement]);
    }

    

    public function updateTraitement($id_traitement, $lib_traitement, $label_traitement, $icone_traitement, $ordre_traitement) {
        $stmt = $this->db->prepare("UPDATE traitement SET lib_traitement = ?, label_traitement = ?, icone_traitement = ?, ordre_traitement = ? WHERE id_traitement = ?");
        return $stmt->execute([$lib_traitement,$label_traitement,$icone_traitement,$ordre_traitement,$id_traitement]);
    }

    public function deleteTraitement($id_traitement) {
        $stmt = $this->db->prepare("DELETE FROM traitement WHERE id_traitement = ?");
        return $stmt->execute([$id_traitement]);
    }
}

    
    