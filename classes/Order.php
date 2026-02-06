<?php

namespace Classes;

use Config\Database;

class Order
{
    private $db;
    private $table = 'orders';

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Get all orders
     */
    public function getAll()
    {
        $query = "SELECT o.*, u.username, u.email as user_email 
                  FROM {$this->table} o 
                  LEFT JOIN users u ON o.user_id = u.id 
                  ORDER BY o.created_at DESC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get order by ID
     */
    public function getById($id)
    {
        $query = "SELECT o.*, u.username, u.email as user_email 
                  FROM {$this->table} o 
                  LEFT JOIN users u ON o.user_id = u.id 
                  WHERE o.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Get order items
     */
    public function getOrderItems($order_id)
    {
        $query = "SELECT oi.*, p.image_path 
                  FROM order_items oi 
                  LEFT JOIN products p ON oi.product_id = p.id 
                  WHERE oi.order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Update order status
     */
    public function updateStatus($order_id, $status)
    {
        $valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $valid_statuses)) {
            return ['success' => false, 'message' => 'Invalid status'];
        }

        $query = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $status, $order_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Order status updated successfully'];
        }
        return ['success' => false, 'message' => 'Failed to update order status'];
    }

    /**
     * Get orders by status
     */
    public function getByStatus($status)
    {
        $query = "SELECT o.*, u.username, u.email as user_email 
                  FROM {$this->table} o 
                  LEFT JOIN users u ON o.user_id = u.id 
                  WHERE o.status = ? 
                  ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $status);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get order statistics
     */
    public function getStatistics()
    {
        $stats = [];
        
        // Total orders
        $result = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $stats['total'] = $result->fetch_assoc()['total'];
        
        // Total revenue
        $result = $this->db->query("SELECT SUM(total_amount) as revenue FROM {$this->table} WHERE status != 'cancelled'");
        $stats['revenue'] = $result->fetch_assoc()['revenue'] ?? 0;
        
        // Orders by status
        $result = $this->db->query("SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status");
        $stats['by_status'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['by_status'][$row['status']] = $row['count'];
        }
        
        return $stats;
    }
}
