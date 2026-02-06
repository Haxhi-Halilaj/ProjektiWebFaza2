<?php
require_once 'init.php';

use Classes\News;

$news = new News();
$articles = $news->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Updates - TechCorp Solutions</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-header">
        <h1>Latest News</h1>
        <p>Stay updated with our latest news and announcements</p>
    </div>

    <div class="container">
        <section class="section">
            <?php if (empty($articles)): ?>
                <div class="empty-state">
                    <p>Stay tuned for the latest product updates, industry insights, and company announcements.</p>
                </div>
            <?php else: ?>
                <div class="news-list">
                    <?php foreach ($articles as $article): ?>
                        <article class="news-item">
                            <div class="news-item-header">
                                <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                                <div class="news-meta">
                                    <span class="author">By <?php echo htmlspecialchars($article['username'] ?? 'Admin'); ?></span>
                                    <span class="date"><?php echo date('F d, Y', strtotime($article['created_at'])); ?></span>
                                </div>
                            </div>
                            <?php if ($article['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="news-item-image">
                            <?php endif; ?>
                            <div class="news-item-content">
                                <p><?php echo htmlspecialchars(substr($article['content'], 0, 150)) . '...'; ?></p>
                                <a href="news-detail.php?id=<?php echo $article['id']; ?>" class="btn btn-small">Read Full Article</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
