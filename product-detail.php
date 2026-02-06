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

                    <div class="product-quantity">
                        <label for="quantity">Quantity:</label>
                        <div class="quantity-controls">
                            <button class="qty-btn" onclick="changeQuantity(-1)">-</button>
                            <input type="number" id="quantity" class="qty-input" value="1" min="1" onchange="validateQuantity()">
                            <button class="qty-btn" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>

                    <div class="product-actions">
                        <button class="btn btn-primary" onclick="addToCart(<?php echo $prod['id']; ?>)">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="btn btn-outline" onclick="buyNow(<?php echo $prod['id']; ?>)">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    </div>
                    
                    <div id="cart-message" style="margin-top: 15px;"></div>

                    <div class="product-meta-info">
                        <p><strong>Posted:</strong> <?php echo date('F d, Y', strtotime($prod['created_at'])); ?></p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        function changeQuantity(change) {
            const qtyInput = document.getElementById('quantity');
            let currentQty = parseInt(qtyInput.value) || 1;
            currentQty += change;
            if (currentQty < 1) currentQty = 1;
            qtyInput.value = currentQty;
        }

        function validateQuantity() {
            const qtyInput = document.getElementById('quantity');
            let qty = parseInt(qtyInput.value) || 1;
            if (qty < 1) qty = 1;
            qtyInput.value = qty;
        }

        function addToCart(productId) {
            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            const messageDiv = document.getElementById('cart-message');
            
            messageDiv.innerHTML = '<div class="alert alert-info">Adding to cart...</div>';

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            fetch('add-to-cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Product added to cart! <a href="cart.php">View Cart</a></div>';
                    updateCartCount();
                    setTimeout(() => {
                        messageDiv.innerHTML = '';
                    }, 3000);
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Failed to add product to cart') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
            });
        }

        function buyNow(productId) {
            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            
            // Add to cart first
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            fetch('add-to-cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to checkout
                    window.location.href = 'checkout.php';
                } else {
                    alert(data.message || 'Failed to add product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        function updateCartCount() {
            fetch('get-cart-count.php')
                .then(response => response.json())
                .then(data => {
                    const cartBadge = document.getElementById('cart-count');
                    if (cartBadge) {
                        cartBadge.textContent = data.count;
                        cartBadge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }
                })
                .catch(error => console.error('Error updating cart count:', error));
        }
    </script>
</body>
</html>
