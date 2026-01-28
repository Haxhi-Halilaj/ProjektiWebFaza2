<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\News;

$news = new News();
$articles = $news->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <div class="section-header">
                <h1>Manage News</h1>
                <a href="add-news.php" class="btn btn-primary">Add New Article</a>
            </div>

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
                    <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(substr($article['title'], 0, 40)); ?></td>
                        <td><?php echo htmlspecialchars($article['username'] ?? 'Unknown'); ?></td>
                        <td><?php echo date('M d, Y', strtotime($article['created_at'])); ?></td>
                        <td>
                            <a href="edit-news.php?id=<?php echo $article['id']; ?>" class="btn btn-small">Edit</a>
                            <a href="delete-news.php?id=<?php echo $article['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this article?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
