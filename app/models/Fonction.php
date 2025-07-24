<?php

class Fonction {

    private $db;
    private $id_fonction;
    private $lib_fonction;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Getters
    public function getIdFonction() { return $this->id_fonction; }
    public function getLibFonction() { return $this->lib_fonction; }

    // Setters
    public function setIdFonction($id) { $this->id_fonction = $id; }
    public function setLibFonction($lib) { $this->lib_fonction = $lib; }

    public function ajouterFonction($lib_fonction)
    {
        $stmt = $this->db->prepare("INSERT INTO fonction (lib_fonction) VALUES (?)");
        return $stmt->execute([$lib_fonction]);
    }

    public function updateFonction($id_fonction, $lib_fonction)
    {
        $stmt = $this->db->prepare("UPDATE fonction SET lib_fonction = ? WHERE id_fonction = ?");
        return $stmt->execute([$lib_fonction, $id_fonction]);
    }

    public function deleteFonction($id_fonction)
    {
        $stmt = $this->db->prepare("DELETE FROM fonction WHERE id_fonction = ?");
        return $stmt->execute([$id_fonction]);
    }

    public function getFonctionById($id)
    {
        $query = "SELECT * FROM fonction WHERE id_fonction = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllFonctions()
    {
        $query = "SELECT * FROM fonction ORDER BY lib_fonction";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}