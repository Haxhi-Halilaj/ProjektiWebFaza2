<?php
// Ensure init.php is loaded (safe to call multiple times due to require_once)
require_once __DIR__ . '/../init.php';

use Classes\Cart;

$current_page = basename($_SERVER['PHP_SELF']);

// Initialize cart
if (!isset($GLOBALS['cart'])) {
    $GLOBALS['cart'] = new Cart();
}
$cart = $GLOBALS['cart'];
$cart_count = $cart->getCartCount();
?>
<header class="header">
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="index.php">TechCorp Solutions</a>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="index.php" class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="about.php" class="nav-link <?php echo $current_page === 'about.php' ? 'active' : ''; ?>">About</a></li>
                    <li><a href="products.php" class="nav-link <?php echo $current_page === 'products.php' ? 'active' : ''; ?>">Products</a></li>
                    <li><a href="news.php" class="nav-link <?php echo $current_page === 'news.php' ? 'active' : ''; ?>">News</a></li>
                    <li><a href="contact.php" class="nav-link <?php echo $current_page === 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-text">Cart</span>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge" id="cart-count"><?php echo $cart_count; ?></span>
                    <?php else: ?>
                        <span class="cart-badge" id="cart-count" style="display: none;">0</span>
                    <?php endif; ?>
                </a>
                <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin/dashboard.php" class="btn btn-small">Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-outline btn-small">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline btn-small">Login</a>
                    <a href="register.php" class="btn btn-primary btn-small">Register</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>
