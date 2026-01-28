<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\Product;

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product = new Product();
if ($product->delete($_GET['id'])) {
    header('Location: products.php?success=Product deleted successfully');
} else {
    header('Location: products.php?error=Failed to delete product');
}
exit;
?>
