<?php

class TypeUtilisateur
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupérer tous les type utilisateur
    public function getAllTypeUtilisateur()
    {
        $stmt = $this->pdo->query("SELECT * FROM type_utilisateur ORDER BY lib_type_utilisateur");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Ajouter un nouveau type utilisateur
    public function ajouterTypeUtilisateur($lib_type_utilisateur)
    {
        $stmt = $this->pdo->prepare("INSERT INTO type_utilisateur (lib_type_utilisateur) VALUES (?)");
        return $stmt->execute([$lib_type_utilisateur]);
    }

    //Modifier un type utilisateur
    public function updateTypeUtilisateur($id_type_utilisateur, $lib_type_utilisateur)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE type_utilisateur SET lib_type_utilisateur = ? WHERE id_type_utilisateur = ?");
            return $stmt->execute([$lib_type_utilisateur, $id_type_utilisateur]);
        } catch (PDOException $e) {
            error_log("Erreur pendant la maj du groupe utilisateur");
            return false;
        }
    }

    // Supprimer un type utilisateur
    public function deleteTypeUtilisateur($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM type_utilisateur WHERE id_type_utilisateur = ?");
        return $stmt->execute([$id]);
    }

    public function getTypeUtilisateurById($id_type_utilisateur)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM type_utilisateur WHERE id_type_utilisateur = ?");
        $stmt->execute([$id_type_utilisateur]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getTypeUtilisateurByLibelle($lib_type_utilisateur)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM type_utilisateur WHERE lib_type_utilisateur = ?");
        $stmt->execute([$lib_type_utilisateur]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


}