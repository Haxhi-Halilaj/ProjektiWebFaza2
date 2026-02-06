<?php
require_once 'init.php';

use Classes\Content;

$contentClass = new Content();
$about_content = $contentClass->getByPageName('about');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TechCorp Solutions</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-header">
        <h1>About Us</h1>
        <p>Learn more about our company and mission</p>
    </div>

    <div class="container">
        <section class="section">
            <div class="about-content">
                <h2><?php echo htmlspecialchars($about_content['title'] ?? 'About Our Company'); ?></h2>
                <p><?php echo htmlspecialchars($about_content['description'] ?? 'We are a dedicated team of professionals committed to delivering excellence.'); ?></p>

                <h3>Our Mission</h3>
                <p>To empower businesses worldwide by providing access to premium products and innovative solutions that drive growth, efficiency, and success. We are committed to being your trusted partner in building a better workplace.</p>

                <h3>What We Offer</h3>
                <p>TechCorp Solutions specializes in sourcing and delivering high-quality business products including office technology, ergonomic furniture, productivity tools, and professional equipment. Our curated product catalog features only the best-in-class items from trusted manufacturers.</p>

                <h3>Our Values</h3>
                <ul class="values-list">
                    <li><strong>Quality First:</strong> We carefully vet every product to ensure it meets our high standards</li>
                    <li><strong>Customer Success:</strong> Your success is our success - we're here to help you find the right solutions</li>
                    <li><strong>Innovation:</strong> We stay ahead of trends to bring you the latest and most effective products</li>
                    <li><strong>Transparency:</strong> Honest pricing, clear product information, and straightforward policies</li>
                    <li><strong>Reliability:</strong> Consistent service and dependable delivery you can count on</li>
                </ul>

                <h3>Our Team</h3>
                <p>Our team of product specialists, customer service professionals, and logistics experts work together to ensure you receive exceptional service from product selection to delivery. With over a decade of combined industry experience, we understand what businesses need to thrive.</p>
            </div>
        </section>

        <section class="section bg-light">
            <div class="section-header">
                <h2>Our Achievements</h2>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">2,500+</div>
                    <div class="stat-label">Products Sold</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">850+</div>
                    <div class="stat-label">Satisfied Customers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">12+</div>
                    <div class="stat-label">Years in Business</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Product Categories</div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
