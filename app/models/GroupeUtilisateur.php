<?php

class GroupeUtilisateur
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupérer tous les groupe utilisateurs
    public function getAllGroupeUtilisateur()
    {
        $stmt = $this->pdo->query("SELECT * FROM groupe_utilisateur ORDER BY lib_GU");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Ajouter un nouveau groupe utilisateur
    public function ajouterGroupeUtilisateur($lib_GU)
    {
        $stmt = $this->pdo->prepare("INSERT INTO groupe_utilisateur (lib_GU) VALUES (?)");
        return $stmt->execute([$lib_GU]);
    }

    //Modifier un groupe utilisateur
    public function updateGroupeUtilisateur($id_GU, $lib_GU)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE groupe_utilisateur SET lib_GU = ? WHERE id_GU = ?");
            return $stmt->execute([$lib_GU, $id_GU]);
        } catch (PDOException $e) {
            error_log("Erreur pendant la maj du groupe utilisateur");
            return false;
        }
    }

    // Supprimer un groupe utilisateur
    public function deleteGroupeUtilisateur($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM groupe_utilisateur WHERE id_GU = ?");
        return $stmt->execute([$id]);
    }

    public function getGroupeUtilisateurById($id_GU)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM groupe_utilisateur WHERE id_GU = ?");
        $stmt->execute([$id_GU]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}