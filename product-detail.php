<?php
require_once 'init.php';

use Classes\Product;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product = new Product();
$prod = $product->getById($_GET['id']);

if (!$prod) {
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($prod['name']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="breadcrumb">
            <a href="products.php">Products</a> / <span><?php echo htmlspecialchars($prod['name']); ?></span>
        </div>

        <section class="section">
            <div class="product-detail">
                <div class="product-detail-image">
                    <?php if ($prod['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($prod['image_path']); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                    <?php endif; ?>
                </div>
                <div class="product-detail-info">
                    <h1><?php echo htmlspecialchars($prod['name']); ?></h1>
                    <p class="product-meta">By <?php echo htmlspecialchars($prod['username'] ?? 'Admin'); ?></p>
                    
                    <div class="price-section">
                        <span class="price-large">$<?php echo number_format($prod['price'], 2); ?></span>
                    </div>

                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($prod['description'])); ?></p>

                    <div class="product-actions">
                        <button class="btn btn-primary" onclick="alert('Add to cart feature coming soon')">Add to Cart</button>
                        <button class="btn btn-outline" onclick="alert('Buy now feature coming soon')">Buy Now</button>
                    </div>

                    <div class="product-meta-info">
                        <p><strong>Posted:</strong> <?php echo date('F d, Y', strtotime($prod['created_at'])); ?></p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
