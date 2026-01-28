<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="admin-header">
    <div class="admin-header-content">
        <div class="logo">
            <a href="dashboard.php">Admin Panel</a>
        </div>
        <div class="admin-user-menu">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="../logout.php" class="btn btn-small btn-outline">Logout</a>
        </div>
    </div>
</header>
