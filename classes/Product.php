<?php

namespace Classes;

use Config\Database;

class Product
{
    private $db;
    private $table = 'products';

    public $id;
    public $name;
    public $description;
    public $price;
    public $image_path;
    public $created_by;
    public $created_at;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function create($name, $description, $price, $image_path, $created_by)
    {
        $query = "INSERT INTO {$this->table} (name, description, price, image_path, created_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return ['success' => false, 'message' => $this->db->error];
        }

        $stmt->bind_param('ssdsi', $name, $description, $price, $image_path, $created_by);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Product created successfully', 'id' => $stmt->insert_id];
        }
        return ['success' => false, 'message' => $stmt->error];
    }

    public function getAll()
    {
        $query = "SELECT p.*, u.username FROM {$this->table} p 
                  LEFT JOIN users u ON p.created_by = u.id 
                  ORDER BY p.created_at DESC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT p.*, u.username FROM {$this->table} p 
                  LEFT JOIN users u ON p.created_by = u.id 
                  WHERE p.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $name, $description, $price, $image_path = null)
    {
        if ($image_path) {
            $query = "UPDATE {$this->table} SET name = ?, description = ?, price = ?, image_path = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ssdsi', $name, $description, $price, $image_path, $id);
        } else {
            $query = "UPDATE {$this->table} SET name = ?, description = ?, price = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ssdi', $name, $description, $price, $id);
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
