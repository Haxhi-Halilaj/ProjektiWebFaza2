<?php
require_once 'init.php';

use Classes\News;
use Classes\Content;

$news = new News();
$contentClass = new Content();

// Get homepage content
$home_content = $contentClass->getByPageName('home');
$latest_news = $news->getLatest(3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - TechCorp Solutions</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Slider -->
    <div class="hero-slider">
        <div class="slider-container">
            <div class="slide active" style="background-image: url('public/office.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="slide-content">
                    <h1><?php echo htmlspecialchars($home_content['title'] ?? 'Welcome to Our Company'); ?></h1>
                    <p><?php echo htmlspecialchars($home_content['description'] ?? 'Your trusted partner'); ?></p>
                    <a href="products.php" class="btn btn-primary">Explore Products</a>
                </div>
            </div>
            <div class="slide" style="background-image: url('public/office1.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="slide-content">
                    <h1>Premium Business Solutions</h1>
                    <p>Transform your workspace with our expertly curated product catalog</p>
                    <a href="products.php" class="btn btn-primary">Browse Products</a>
                </div>
            </div>
            <div class="slide" style="background-image: url('public/office2.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="slide-content">
                    <h1>Stay Informed</h1>
                    <p>Get the latest product updates, industry insights, and company news</p>
                    <a href="news.php" class="btn btn-primary">Read News</a>
                </div>
            </div>
        </div>
        <button class="slider-btn prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="slider-btn next" onclick="changeSlide(1)">&#10095;</button>
    </div>

    <div class="container">
        <!-- Latest News Section -->
        <section class="section">
            <div class="section-header">
                <h2>Latest News</h2>
                <a href="news.php" class="btn btn-outline">View All News</a>
            </div>

            <div class="news-grid">
                <?php if ($latest_news): ?>
                    <?php foreach ($latest_news as $article): ?>
                        <div class="news-card">
                            <?php if ($article['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                            <?php endif; ?>
                            <div class="news-card-content">
                                <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                                <p class="news-meta">
                                    By <?php echo htmlspecialchars($article['username'] ?? 'Admin'); ?> • 
                                    <?php echo date('M d, Y', strtotime($article['created_at'])); ?>
                                </p>
                                <p><?php echo htmlspecialchars(substr($article['content'], 0, 100)) . '...'; ?></p>
                                <a href="news-detail.php?id=<?php echo $article['id']; ?>" class="btn btn-small">Read More</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Check back soon for the latest product updates and company news.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Features Section -->
        <section class="section bg-light">
            <div class="section-header">
                <h2>Why Choose Us?</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>Quality Assured</h3>
                    <p>Every product is carefully selected and tested to meet our rigorous quality standards</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-headset"></i>
                    <h3>Expert Support</h3>
                    <p>Our product specialists are available to help you find the perfect solution</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-shipping-fast"></i>
                    <h3>Fast Shipping</h3>
                    <p>Quick and reliable delivery to get your products to you when you need them</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-lock"></i>
                    <h3>Secure Shopping</h3>
                    <p>Your transactions are protected with industry-leading security measures</p>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');

        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            if (n >= slides.length) currentSlide = 0;
            if (n < 0) currentSlide = slides.length - 1;
            slides[currentSlide].classList.add('active');
        }

        function changeSlide(n) {
            currentSlide += n;
            showSlide(currentSlide);
        }

        // Auto-rotate slides
        setInterval(() => {
            changeSlide(1);
        }, 5000);
    </script>
</body>
</html>
