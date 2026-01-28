<?php

namespace Classes;

use Config\Database;

class Content
{
    private $db;
    private $table = 'page_content';

    public $id;
    public $page_name;
    public $title;
    public $description;
    public $image_path;
    public $updated_by;
    public $updated_at;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getByPageName($page_name)
    {
        $query = "SELECT * FROM {$this->table} WHERE page_name = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $page_name);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($page_name, $title, $description, $image_path = null, $updated_by = null)
    {
        $existing = $this->getByPageName($page_name);

        if ($existing) {
            if ($image_path) {
                $query = "UPDATE {$this->table} SET title = ?, description = ?, image_path = ?, updated_by = ? WHERE page_name = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param('sssss', $title, $description, $image_path, $updated_by, $page_name);
            } else {
                $query = "UPDATE {$this->table} SET title = ?, description = ?, updated_by = ? WHERE page_name = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param('ssss', $title, $description, $updated_by, $page_name);
            }
            return $stmt->execute();
        } else {
            $query = "INSERT INTO {$this->table} (page_name, title, description, image_path, updated_by) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('sssss', $page_name, $title, $description, $image_path, $updated_by);
            return $stmt->execute();
        }
    }
}
