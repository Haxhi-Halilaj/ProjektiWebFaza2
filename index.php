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
    <title>Home - Our Company</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Slider -->
    <div class="hero-slider">
        <div class="slider-container">
            <div class="slide active" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="slide-content">
                    <h1><?php echo htmlspecialchars($home_content['title'] ?? 'Welcome to Our Company'); ?></h1>
                    <p><?php echo htmlspecialchars($home_content['description'] ?? 'Your trusted partner'); ?></p>
                    <a href="products.php" class="btn btn-primary">Explore Products</a>
                </div>
            </div>
            <div class="slide" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="slide-content">
                    <h1>Quality & Excellence</h1>
                    <p>We deliver outstanding products and services</p>
                    <a href="about.php" class="btn btn-primary">Learn More</a>
                </div>
            </div>
            <div class="slide" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="slide-content">
                    <h1>Innovation & Technology</h1>
                    <p>Leading the industry with cutting-edge solutions</p>
                    <a href="contact.php" class="btn btn-primary">Get In Touch</a>
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
                    <p>No news available at the moment.</p>
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
                    <p>All our products meet the highest standards</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-headset"></i>
                    <h3>24/7 Support</h3>
                    <p>Our team is always ready to help you</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-shipping-fast"></i>
                    <h3>Fast Delivery</h3>
                    <p>Quick and reliable shipping worldwide</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-lock"></i>
                    <h3>Secure</h3>
                    <p>Safe transactions and data protection</p>
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
