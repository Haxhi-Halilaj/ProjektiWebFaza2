<?php
require_once 'init.php';

use Classes\News;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: news.php');
    exit;
}

$news = new News();
$article = $news->getById($_GET['id']);

if (!$article) {
    header('Location: news.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="breadcrumb">
            <a href="news.php">News</a> / <span><?php echo htmlspecialchars($article['title']); ?></span>
        </div>

        <section class="section">
            <article class="article-full">
                <h1><?php echo htmlspecialchars($article['title']); ?></h1>
                <div class="article-meta">
                    <span class="author">By <?php echo htmlspecialchars($article['username'] ?? 'Admin'); ?></span>
                    <span class="date"><?php echo date('F d, Y', strtotime($article['created_at'])); ?></span>
                </div>

                <?php if ($article['image_path']): ?>
                    <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image">
                <?php endif; ?>

                <div class="article-content">
                    <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                </div>

                <div class="article-footer">
                    <a href="news.php" class="btn btn-outline">Back to News</a>
                </div>
            </article>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
