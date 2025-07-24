<?php

class Action
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function ajouterAction($lib_action)
    {
        $stmt = $this->db->prepare("INSERT INTO action (lib_action) VALUES (?)");
        return $stmt->execute([$lib_action]);
    }

    public function updateAction($id_action, $lib_action)
    {
        $stmt = $this->db->prepare("UPDATE action SET lib_action = ? WHERE id_action = ?");
        return $stmt->execute([$lib_action, $id_action]);
    }

    public function deleteAction($id_action)
    {
        $stmt = $this->db->prepare("DELETE FROM action WHERE id_action = ?");
        return $stmt->execute([$id_action]);
    }

    public function getActionById($id_action)
    {
        $stmt = $this->db->prepare("SELECT * FROM action WHERE id_action = ?");
        $stmt->execute([$id_action]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllAction()
    {
        $stmt = $this->db->prepare("SELECT * FROM action ORDER BY lib_action");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}