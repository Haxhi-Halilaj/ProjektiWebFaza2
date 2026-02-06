<?php
require_once 'init.php';

use Classes\Product;

$product = new Product();
$products = $product->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - TechCorp Solutions</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-header">
        <h1>Our Products</h1>
        <p>Explore our range of high-quality products</p>
    </div>

    <div class="container">
        <section class="section">
            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <p>Our product catalog is being updated. Check back soon for our latest business solutions and premium products.</p>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $prod): ?>
                        <div class="product-card">
                            <?php if ($prod['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($prod['image_path']); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="product-image">
                            <?php endif; ?>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($prod['name']); ?></h3>
                                <p class="product-meta">By <?php echo htmlspecialchars($prod['username'] ?? 'Admin'); ?></p>
                                <p class="product-description">
                                    <?php echo htmlspecialchars(substr($prod['description'], 0, 80)) . '...'; ?>
                                </p>
                                <div class="product-footer">
                                    <span class="price">$<?php echo number_format($prod['price'], 2); ?></span>
                                    <a href="product-detail.php?id=<?php echo $prod['id']; ?>" class="btn btn-small">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
