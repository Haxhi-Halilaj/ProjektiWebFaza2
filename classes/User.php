<?php

namespace Classes;

use Config\Database;

class User
{
    private $db;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;
    public $role; // 'admin' or 'user'
    public $created_at;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function register($username, $email, $password, $role = 'user')
    {
        if ($this->userExists($username, $email)) {
            return ['success' => false, 'message' => 'Username or email already exists'];
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO {$this->table} (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return ['success' => false, 'message' => 'Query preparation failed: ' . $this->db->error];
        }

        $stmt->bind_param('ssss', $username, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User registered successfully'];
        } else {
            return ['success' => false, 'message' => 'Registration failed: ' . $stmt->error];
        }
    }

    public function login($username, $password)
    {
        $query = "SELECT id, username, email, password, role FROM {$this->table} WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return ['success' => false, 'message' => 'Query preparation failed'];
        }

        $stmt->bind_param('ss', $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'User not found'];
        }

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            return [
                'success' => true,
                'message' => 'Login successful',
                'user' => $user
            ];
        } else {
            return ['success' => false, 'message' => 'Invalid password'];
        }
    }

    public function userExists($username, $email)
    {
        $query = "SELECT id FROM {$this->table} WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function getUserById($id)
    {
        $query = "SELECT id, username, email, role FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllUsers()
    {
        $query = "SELECT id, username, email, role, created_at FROM {$this->table} ORDER BY created_at DESC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function updateRole($id, $role)
    {
        $query = "UPDATE {$this->table} SET role = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $role, $id);
        return $stmt->execute();
    }
}
