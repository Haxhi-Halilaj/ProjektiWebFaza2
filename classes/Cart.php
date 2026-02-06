<?php

namespace Classes;

use Config\Database;

class Cart
{
    private $db;
    private $table = 'cart';

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Initialize session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Create session cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Add product to cart (session-based)
     */
    public function addToCart($product_id, $quantity = 1)
    {
        $product = new \Classes\Product();
        $product_data = $product->getById($product_id);
        
        if (!$product_data) {
            return ['success' => false, 'message' => 'Product not found'];
        }

        // Check if product already in cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product_data['name'],
                'price' => $product_data['price'],
                'image_path' => $product_data['image_path'],
                'quantity' => $quantity
            ];
        }

        return ['success' => true, 'message' => 'Product added to cart', 'cart_count' => $this->getCartCount()];
    }

    /**
     * Remove product from cart
     */
    public function removeFromCart($product_id)
    {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            return ['success' => true, 'message' => 'Product removed from cart'];
        }
        return ['success' => false, 'message' => 'Product not in cart'];
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity($product_id, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($product_id);
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            return ['success' => true, 'message' => 'Cart updated'];
        }
        return ['success' => false, 'message' => 'Product not in cart'];
    }

    /**
     * Get all cart items
     */
    public function getCartItems()
    {
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Get cart count
     */
    public function getCartCount()
    {
        $count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += $item['quantity'];
            }
        }
        return $count;
    }

    /**
     * Get cart total
     */
    public function getCartTotal()
    {
        $total = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        return $total;
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        $_SESSION['cart'] = [];
        return ['success' => true, 'message' => 'Cart cleared'];
    }

    /**
     * Create order from cart
     */
    public function createOrder($user_id = null, $shipping_info = [])
    {
        $cart_items = $this->getCartItems();
        
        if (empty($cart_items)) {
            return ['success' => false, 'message' => 'Cart is empty'];
        }

        $total = $this->getCartTotal();
        $order_date = date('Y-m-d H:i:s');
        
        // Create order record
        $order_query = "INSERT INTO orders (user_id, total_amount, status, shipping_name, shipping_email, shipping_address, shipping_phone, created_at) 
                        VALUES (?, ?, 'pending', ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($order_query);
        
        $shipping_name = $shipping_info['name'] ?? '';
        $shipping_email = $shipping_info['email'] ?? '';
        $shipping_address = $shipping_info['address'] ?? '';
        $shipping_phone = $shipping_info['phone'] ?? '';
        
        $stmt->bind_param('idsssss', $user_id, $total, $shipping_name, $shipping_email, $shipping_address, $shipping_phone, $order_date);
        
        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Failed to create order: ' . $stmt->error];
        }
        
        $order_id = $stmt->insert_id;
        
        // Create order items
        foreach ($cart_items as $item) {
            $item_query = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity) 
                          VALUES (?, ?, ?, ?, ?)";
            $item_stmt = $this->db->prepare($item_query);
            $item_stmt->bind_param('iisdi', $order_id, $item['id'], $item['name'], $item['price'], $item['quantity']);
            $item_stmt->execute();
        }
        
        // Clear cart after order creation
        $this->clearCart();
        
        return ['success' => true, 'message' => 'Order created successfully', 'order_id' => $order_id];
    }
}
