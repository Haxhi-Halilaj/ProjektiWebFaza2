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
                     VALUES ('admin', 'admin@example.com', '$admin_password', 'admin')";
    
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
                     VALUES ('home', 'Welcome to Our Company', 'Welcome to our amazing company. We provide the best services in the industry.');";
    $conn->query($home_content);

    $about_content = "INSERT INTO page_content (page_name, title, description) 
                      VALUES ('about', 'About Us', 'We are a dedicated team of professionals committed to delivering excellence.');";
    $conn->query($about_content);

    echo "Sample content inserted successfully<br>";
}

echo "Database setup completed successfully!<br>";
?>
