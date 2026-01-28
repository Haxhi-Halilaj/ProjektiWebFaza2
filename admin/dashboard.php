<?php
require_once '../init.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\User;
use Classes\Product;
use Classes\News;
use Classes\Contact;

$user = new User();
$product = new Product();
$news = new News();
$contact = new Contact();

$total_users = count($user->getAllUsers());
$all_products = $product->getAll();
$all_news = $news->getAll();
$all_contacts = $contact->getAll();

$total_products = count($all_products);
$total_news = count($all_news);
$total_contacts = count($all_contacts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <h1>Dashboard</h1>

            <div class="stats-grid">
                <div class="stat-box">
                    <i class="fas fa-users"></i>
                    <div class="stat-info">
                        <div class="stat-number"><?php echo $total_users; ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-box"></i>
                    <div class="stat-info">
                        <div class="stat-number"><?php echo $total_products; ?></div>
                        <div class="stat-label">Products</div>
                    </div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-newspaper"></i>
                    <div class="stat-info">
                        <div class="stat-number"><?php echo $total_news; ?></div>
                        <div class="stat-label">News Articles</div>
                    </div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-envelope"></i>
                    <div class="stat-info">
                        <div class="stat-number"><?php echo $total_contacts; ?></div>
                        <div class="stat-label">Contact Messages</div>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <section class="dashboard-section">
                    <h2>Recent Products</h2>
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
                            <?php 
                            $recent_products = array_slice($all_products, 0, 5);
                            foreach ($recent_products as $prod): 
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($prod['name'], 0, 30)); ?></td>
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
                    <a href="products.php" class="btn btn-outline">View All Products</a>
                </section>

                <section class="dashboard-section">
                    <h2>Recent News</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $recent_news = array_slice($all_news, 0, 5);
                            foreach ($recent_news as $article): 
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($article['title'], 0, 30)); ?></td>
                                <td><?php echo htmlspecialchars($article['username'] ?? 'Unknown'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($article['created_at'])); ?></td>
                                <td>
                                    <a href="edit-news.php?id=<?php echo $article['id']; ?>" class="btn btn-small">Edit</a>
                                    <a href="delete-news.php?id=<?php echo $article['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this news?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="news.php" class="btn btn-outline">View All News</a>
                </section>

                <section class="dashboard-section">
                    <h2>Recent Messages</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $recent_contacts = array_slice($all_contacts, 0, 5);
                            foreach ($recent_contacts as $msg): 
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars(substr($msg['subject'], 0, 25)); ?></td>
                                <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
                                <td>
                                    <a href="view-message.php?id=<?php echo $msg['id']; ?>" class="btn btn-small">View</a>
                                    <a href="delete-message.php?id=<?php echo $msg['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="messages.php" class="btn btn-outline">View All Messages</a>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
