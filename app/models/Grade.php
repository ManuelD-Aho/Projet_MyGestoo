<?php

class Grade
{
    private $db;
    private $id_grade;
    private $lib_grade;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Getters
    public function getIdGrade()
    {
        return $this->id_grade;
    }

    public function getLibGrade()
    {
        return $this->lib_grade;
    }

    // Setters
    public function setIdGrade($id)
    {
        $this->id_grade = $id;
    }

    public function setLibGrade($lib)
    {
        $this->lib_grade = $lib;
    }

    // Méthodes CRUD
    public function getAllGrades()
    {
        $query = "SELECT * FROM grade ORDER BY lib_grade";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getGradeById($id)
    {
        $query = "SELECT * FROM grade WHERE id_grade = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Ajouter un nouveau grade
    public function ajouterGrade($lib_grade)
    {
        $stmt = $this->db->prepare("INSERT INTO grade (lib_grade) VALUES (?)");
        return $stmt->execute([$lib_grade]);
    }

    //Modifier un grade
    public function updateGrade($id_grade, $lib_grade)
    {
        try {
            $stmt = $this->db->prepare("UPDATE grade SET lib_grade = ? WHERE id_grade = ?");
            return $stmt->execute([$lib_grade, $id_grade]);
        } catch (PDOException $e) {
            error_log("Erreur pendant la maj du grade" . $e->getMessage());
            return false;
        }
    }

    // Supprimer un grade
    public function deleteGrade($id)
    {
        $stmt = $this->db->prepare("DELETE FROM grade WHERE id_grade = ?");
        return $stmt->execute([$id]);
    }
}