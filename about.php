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
    <title>About Us</title>
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
                <p>To provide innovative solutions that transform businesses and improve lives while maintaining the highest standards of quality and integrity.</p>

                <h3>Our Values</h3>
                <ul class="values-list">
                    <li><strong>Integrity:</strong> We conduct business with honesty and transparency</li>
                    <li><strong>Excellence:</strong> We strive for the highest quality in everything we do</li>
                    <li><strong>Innovation:</strong> We embrace change and foster creativity</li>
                    <li><strong>Customer Focus:</strong> We prioritize customer satisfaction above all</li>
                    <li><strong>Teamwork:</strong> We believe in the power of collaboration</li>
                </ul>

                <h3>Our Team</h3>
                <p>Our experienced team consists of industry experts with years of combined experience in delivering exceptional results.</p>
            </div>
        </section>

        <section class="section bg-light">
            <div class="section-header">
                <h2>Our Achievements</h2>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Happy Clients</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Projects Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Years Experience</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support Available</div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
