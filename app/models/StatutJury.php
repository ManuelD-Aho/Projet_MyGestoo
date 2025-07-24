<?php
class StatutJury
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllStatutsJury()
    {
        $stmt = $this->pdo->query("SELECT * FROM statut_jury ORDER BY lib_jury");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ajouterStatutJury($lib)
    {
        $stmt = $this->pdo->prepare("INSERT INTO statut_jury (lib_jury) VALUES (?)");
        return $stmt->execute([$lib]);
    }

    public function updateStatutJury($id, $lib)
    {
        $stmt = $this->pdo->prepare("UPDATE statut_jury SET lib_jury = ? WHERE id_jury = ?");
        return $stmt->execute([$lib, $id]);
    }

    public function deleteStatutJury($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM statut_jury WHERE id_jury = ?");
        return $stmt->execute([$id]);
    }

    public function getStatutJuryById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM statut_jury WHERE id_jury = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}