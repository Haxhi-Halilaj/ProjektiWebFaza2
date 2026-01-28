<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="header">
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="index.php">CompanyName</a>
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
</header>
