<?php



class Message
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function ajouterMessage($contenu_message, $lib_message, $type_message)
    {
        $stmt = $this->db->prepare("INSERT INTO messages (contenu_message, lib_message, type_message) VALUES (?, ?, ?)");
        return $stmt->execute([$contenu_message, $lib_message, $type_message]);
    }
    

    public function updateMessage($id_message, $contenu_message, $lib_message, $type_message)
    {
        $stmt = $this->db->prepare("UPDATE messages SET contenu_message = ?, lib_message = ?, type_message= ? WHERE id_message = ?");
        return $stmt->execute([$contenu_message,$lib_message,$type_message, $id_message]);
    }

    public function deleteMessage($id_message)
    {
        $stmt = $this->db->prepare("DELETE FROM messages WHERE id_message = ?");
        return $stmt->execute([$id_message]);
    }

    public function getMessageById($id_message)
    {
        $stmt = $this->db->prepare("SELECT * FROM messages WHERE id_message = ?");
        $stmt->execute([$id_message]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllMessages()
    {
        $stmt = $this->db->prepare("SELECT * FROM messages ORDER BY contenu_message");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}