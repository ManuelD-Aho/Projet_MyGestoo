<?php

class NiveauAccesDonnees
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllNiveauxAccesDonnees()
    {
        $stmt = $this->pdo->query("SELECT * FROM niveau_acces_donnees ORDER BY lib_niveau_acces_donnees");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ajouterNiveauAccesDonnees($lib)
    {
        $stmt = $this->pdo->prepare("INSERT INTO niveau_acces_donnees (lib_niveau_acces_donnees) VALUES (?)");
        return $stmt->execute([$lib]);
    }

    public function updateNiveauAccesDonnees($id, $lib)
    {
        $stmt = $this->pdo->prepare("UPDATE niveau_acces_donnees SET lib_niveau_acces_donnees = ? WHERE id_niveau_acces_donnees = ?");
        return $stmt->execute([$lib, $id]);
    }

    public function deleteNiveauAccesDonnees($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM niveau_acces_donnees WHERE id_niveau_acces_donnees = ?");
        return $stmt->execute([$id]);
    }

    public function getNiveauAccesDonneesById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM niveau_acces_donnees WHERE id_niveau_acces_donnees = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getLastNiveauAccesDonnees()
    {
        $stmt = $this->pdo->query("SELECT * FROM niveau_acces_donnees ORDER BY id_niveau_acces_donnees DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}