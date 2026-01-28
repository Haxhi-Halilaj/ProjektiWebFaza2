<?php

namespace Classes;

use Config\Database;

class News
{
    private $db;
    private $table = 'news';

    public $id;
    public $title;
    public $content;
    public $image_path;
    public $created_by;
    public $created_at;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function create($title, $content, $image_path, $created_by)
    {
        $query = "INSERT INTO {$this->table} (title, content, image_path, created_by) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return ['success' => false, 'message' => $this->db->error];
        }

        $stmt->bind_param('sssi', $title, $content, $image_path, $created_by);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'News created successfully', 'id' => $stmt->insert_id];
        }
        return ['success' => false, 'message' => $stmt->error];
    }

    public function getAll()
    {
        $query = "SELECT n.*, u.username FROM {$this->table} n 
                  LEFT JOIN users u ON n.created_by = u.id 
                  ORDER BY n.created_at DESC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT n.*, u.username FROM {$this->table} n 
                  LEFT JOIN users u ON n.created_by = u.id 
                  WHERE n.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getLatest($limit = 3)
    {
        $query = "SELECT n.*, u.username FROM {$this->table} n 
                  LEFT JOIN users u ON n.created_by = u.id 
                  ORDER BY n.created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $title, $content, $image_path = null)
    {
        if ($image_path) {
            $query = "UPDATE {$this->table} SET title = ?, content = ?, image_path = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('sssi', $title, $content, $image_path, $id);
        } else {
            $query = "UPDATE {$this->table} SET title = ?, content = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ssi', $title, $content, $id);
        }

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
