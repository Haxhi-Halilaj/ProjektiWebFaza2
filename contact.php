<?php
require_once 'init.php';

use Classes\Contact;
use Classes\Validator;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = Validator::sanitizeInput($_POST['name'] ?? '');
    $email = Validator::sanitizeInput($_POST['email'] ?? '');
    $subject = Validator::sanitizeInput($_POST['subject'] ?? '');
    $message = Validator::sanitizeInput($_POST['message'] ?? '');

    // Validate
    $errors = Validator::validateContactForm($name, $email, $subject, $message);

    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        $contact = new Contact();
        $result = $contact->create($name, $email, $subject, $message);

        if ($result['success']) {
            $success = 'Thank you for your message! We will get back to you soon.';
            // Clear form
            $_POST = [];
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-header">
        <h1>Contact Us</h1>
        <p>Get in touch with our team</p>
    </div>

    <div class="container">
        <section class="section">
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Contact Information</h2>
                    <div class="info-item">
                        <h3>Address</h3>
                        <p>123 Business Street<br>City, State 12345</p>
                    </div>
                    <div class="info-item">
                        <h3>Phone</h3>
                        <p>+1 (555) 123-4567</p>
                    </div>
                    <div class="info-item">
                        <h3>Email</h3>
                        <p>info@company.com</p>
                    </div>
                    <div class="info-item">
                        <h3>Hours</h3>
                        <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                           Saturday: 10:00 AM - 4:00 PM<br>
                           Sunday: Closed</p>
                    </div>
                </div>

                <div class="contact-form-wrapper">
                    <h2>Send us a Message</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" class="contact-form">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" required 
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required 
                                   value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="6" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
