<?php
require_once 'init.php';

use Classes\Cart;

$cart = new Cart();
$cart_items = $cart->getCartItems();
$cart_total = $cart->getCartTotal();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - TechCorp Solutions</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-header">
        <h1>Shopping Cart</h1>
        <p>Review your selected products</p>
    </div>

    <div class="container">
        <section class="section">
            <?php if (empty($cart_items)): ?>
                <div class="empty-state">
                    <i class="fas fa-shopping-cart" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                    <h2>Your cart is empty</h2>
                    <p>Start shopping to add products to your cart</p>
                    <a href="products.php" class="btn btn-primary">Browse Products</a>
                </div>
            <?php else: ?>
                <div class="cart-container">
                    <div class="cart-items">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr class="cart-item" data-product-id="<?php echo $item['id']; ?>">
                                        <td class="cart-item-info">
                                            <?php if ($item['image_path']): ?>
                                                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-image">
                                            <?php endif; ?>
                                            <div>
                                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                            </div>
                                        </td>
                                        <td class="cart-item-price">$<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="cart-item-quantity">
                                            <div class="quantity-controls">
                                                <button class="qty-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                                                <input type="number" class="qty-input" value="<?php echo $item['quantity']; ?>" min="1" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                                                <button class="qty-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                                            </div>
                                        </td>
                                        <td class="cart-item-subtotal">$<span class="subtotal-<?php echo $item['id']; ?>"><?php echo number_format($item['price'] * $item['quantity'], 2); ?></span></td>
                                        <td class="cart-item-action">
                                            <button class="btn btn-danger btn-small" onclick="removeFromCart(<?php echo $item['id']; ?>)">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-summary">
                        <h2>Order Summary</h2>
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="cart-subtotal">$<?php echo number_format($cart_total, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span>Calculated at checkout</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span id="cart-total">$<?php echo number_format($cart_total, 2); ?></span>
                        </div>
                        <div class="cart-actions">
                            <a href="products.php" class="btn btn-outline">Continue Shopping</a>
                            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        function updateQuantity(productId, quantity) {
            if (quantity < 1) {
                removeFromCart(productId);
                return;
            }

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            fetch('update-cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the cart');
            });
        }

        function removeFromCart(productId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            const formData = new FormData();
            formData.append('product_id', productId);

            fetch('remove-from-cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to remove item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the item');
            });
        }
    </script>
</body>
</html>
