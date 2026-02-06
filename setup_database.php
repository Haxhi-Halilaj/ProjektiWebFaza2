<?php

require_once 'init.php';

use Config\Database;

$database = new Database();
$conn = $database->getConnection();

// Create tables
$tables_sql = [
    "CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) UNIQUE NOT NULL,
        `email` VARCHAR(100) UNIQUE NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `role` ENUM('admin', 'user') DEFAULT 'user',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    "CREATE TABLE IF NOT EXISTS `products` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `description` LONGTEXT NOT NULL,
        `price` DECIMAL(10, 2) NOT NULL,
        `image_path` VARCHAR(255),
        `created_by` INT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    "CREATE TABLE IF NOT EXISTS `news` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `content` LONGTEXT NOT NULL,
        `image_path` VARCHAR(255),
        `created_by` INT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    "CREATE TABLE IF NOT EXISTS `contacts` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `subject` VARCHAR(255) NOT NULL,
        `message` LONGTEXT NOT NULL,
        `is_read` TINYINT(1) DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    "CREATE TABLE IF NOT EXISTS `page_content` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `page_name` VARCHAR(100) UNIQUE NOT NULL,
        `title` VARCHAR(255),
        `description` LONGTEXT,
        `image_path` VARCHAR(255),
        `updated_by` INT,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    "CREATE TABLE IF NOT EXISTS `orders` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT,
        `total_amount` DECIMAL(10, 2) NOT NULL,
        `status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
        `shipping_name` VARCHAR(100) NOT NULL,
        `shipping_email` VARCHAR(100) NOT NULL,
        `shipping_address` TEXT NOT NULL,
        `shipping_phone` VARCHAR(20),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    "CREATE TABLE IF NOT EXISTS `order_items` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `order_id` INT NOT NULL,
        `product_id` INT,
        `product_name` VARCHAR(255) NOT NULL,
        `price` DECIMAL(10, 2) NOT NULL,
        `quantity` INT NOT NULL,
        FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
];

foreach ($tables_sql as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Check if admin user exists
$check_admin = "SELECT * FROM users WHERE username = 'admin' LIMIT 1";
$result = $conn->query($check_admin);

if ($result->num_rows == 0) {
    // Create default admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_admin = "INSERT INTO users (username, email, password, role) 
                     VALUES ('admin', 'admin@techcorp.com', '$admin_password', 'admin')";
    
    if ($conn->query($insert_admin) === TRUE) {
        echo "Default admin user created successfully<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Error creating admin user: " . $conn->error . "<br>";
    }
}

// Insert sample content
$check_content = "SELECT * FROM page_content WHERE page_name = 'home' LIMIT 1";
$result = $conn->query($check_content);

if ($result->num_rows == 0) {
    $home_content = "INSERT INTO page_content (page_name, title, description) 
                     VALUES ('home', 'Premium Products for Modern Businesses', 'Discover our curated selection of innovative products designed to elevate your business operations. From cutting-edge technology solutions to essential business tools, we deliver quality products that drive success.');";
    $conn->query($home_content);

    $about_content = "INSERT INTO page_content (page_name, title, description) 
                      VALUES ('about', 'About TechCorp Solutions', 'TechCorp Solutions is a leading provider of premium business products and technology solutions. Since 2014, we have been helping businesses worldwide transform their operations with innovative products backed by exceptional customer service and industry expertise.');";
    $conn->query($about_content);

    echo "Sample content inserted successfully<br>";
}

echo "Database setup completed successfully!<br>";
?>
