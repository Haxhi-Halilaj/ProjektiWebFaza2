<?php

namespace Classes;

use Config\Database;

class Contact
{
    private $db;
    private $table = 'contacts';

    public $id;
    public $name;
    public $email;
    public $subject;
    public $message;
    public $created_at;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function create($name, $email, $subject, $message)
    {
        $query = "INSERT INTO {$this->table} (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return ['success' => false, 'message' => $this->db->error];
        }

        $stmt->bind_param('ssss', $name, $email, $subject, $message);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Message sent successfully'];
        }
        return ['success' => false, 'message' => $stmt->error];
    }

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function markAsRead($id)
    {
        $query = "UPDATE {$this->table} SET is_read = 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
