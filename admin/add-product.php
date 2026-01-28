<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\Product;
use Classes\Validator;

$error = '';
$success = '';
$product = new Product();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = Validator::sanitizeInput($_POST['name'] ?? '');
    $description = Validator::sanitizeInput($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $image_path = $_POST['image_path'] ?? '';

    $errors = Validator::validateProductForm($name, $description, $price);

    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        $result = $product->create($name, $description, $price, $image_path, $_SESSION['user_id']);

        if ($result['success']) {
            $success = 'Product created successfully!';
            $_POST = [];
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <h1>Add New Product</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="admin-form">
                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price ($) *</label>
                        <input type="number" id="price" name="price" step="0.01" required 
                               value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="image_path">Image URL</label>
                        <input type="url" id="image_path" name="image_path" 
                               value="<?php echo htmlspecialchars($_POST['image_path'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                    <a href="products.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
