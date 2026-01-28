<aside class="admin-sidebar">
    <nav class="admin-nav">
        <ul>
            <li><a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-dashboard"></i> Dashboard
            </a></li>
            <li class="nav-section">Content Management</li>
            <li><a href="products.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'products.php' || basename($_SERVER['PHP_SELF']) === 'add-product.php' || basename($_SERVER['PHP_SELF']) === 'edit-product.php' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Products
            </a></li>
            <li><a href="news.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'news.php' || basename($_SERVER['PHP_SELF']) === 'add-news.php' || basename($_SERVER['PHP_SELF']) === 'edit-news.php' ? 'active' : ''; ?>">
                <i class="fas fa-newspaper"></i> News
            </a></li>
            <li><a href="messages.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'messages.php' || basename($_SERVER['PHP_SELF']) === 'view-message.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> Messages
            </a></li>
            <li class="nav-section">User Management</li>
            <li><a href="users.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Users
            </a></li>
            <li class="nav-section">Website</li>
            <li><a href="../index.php" class="nav-item">
                <i class="fas fa-globe"></i> View Website
            </a></li>
        </ul>
    </nav>
</aside>
