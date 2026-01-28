<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\Product;

$product = new Product();
$products = $product->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <div class="section-header">
                <h1>Manage Products</h1>
                <a href="add-product.php" class="btn btn-primary">Add New Product</a>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $prod): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(substr($prod['name'], 0, 40)); ?></td>
                        <td>$<?php echo number_format($prod['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($prod['username'] ?? 'Unknown'); ?></td>
                        <td><?php echo date('M d, Y', strtotime($prod['created_at'])); ?></td>
                        <td>
                            <a href="edit-product.php?id=<?php echo $prod['id']; ?>" class="btn btn-small">Edit</a>
                            <a href="delete-product.php?id=<?php echo $prod['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
