<?php

class NiveauApprobation {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function ajouterNiveauApprobation($lib_approb) {
        $stmt = $this->db->prepare("INSERT INTO niveau_approbation (lib_approb) VALUES (?)");
        return $stmt->execute([$lib_approb]);
    }

    public function updateNiveauApprobation($id_approb, $lib_approb) {
        $stmt = $this->db->prepare("UPDATE niveau_approbation SET lib_approb = ? WHERE id_approb = ?");
        return $stmt->execute([$lib_approb, $id_approb]);
    }

    public function deleteNiveauApprobation($id_approb) {
        $stmt = $this->db->prepare("DELETE FROM niveau_approbation WHERE id_approb = ?");
        return $stmt->execute([$id_approb]);
    }

    public function getNiveauApprobationById($id_approb) {
        $stmt = $this->db->prepare("SELECT * FROM niveau_approbation WHERE id_approb = ?");
        $stmt->execute([$id_approb]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllNiveauxApprobation() {
        $stmt = $this->db->prepare("SELECT * FROM niveau_approbation ORDER BY lib_approb");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}