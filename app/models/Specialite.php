<?php

class Specialite
{
    private $db;
    private $id_specialite;
    private $lib_specialite;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Getters
    public function getIdSpecialite()
    {
        return $this->id_specialite;
    }

    public function getLibSpecialite()
    {
        return $this->lib_specialite;
    }

    // Setters
    public function setIdSpecialite($id)
    {
        $this->id_specialite = $id;
    }

    public function setLibSpecialite($lib)
    {
        $this->lib_specialite = $lib;
    }

    // Méthodes CRUD
    public function getAllSpecialites()
    {
        $query = "SELECT * FROM specialite ORDER BY lib_specialite";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getSpecialiteById($id)
    {
        $query = "SELECT * FROM specialite WHERE id_specialite = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function ajouterSpecialite($lib)
    {
        $stmt = $this->db->prepare("INSERT INTO specialite (lib_specialite) VALUES (?)");
        return $stmt->execute([$lib]);
    }

    public function updateSpecialite($id, $lib)
    {
        $stmt = $this->db->prepare("UPDATE specialite SET lib_specialite = ? WHERE id_specialite = ?");
        return $stmt->execute([$lib, $id]);
    }

    public function deleteSpecialite($id)
    {
        $stmt = $this->db->prepare("DELETE FROM specialite WHERE id_specialite = ?");
        return $stmt->execute([$id]);
    }
}