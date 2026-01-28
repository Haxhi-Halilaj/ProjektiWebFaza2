<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\Contact;

if (!isset($_GET['id'])) {
    header('Location: messages.php');
    exit;
}

$contact = new Contact();
$message = $contact->getById($_GET['id']);

if (!$message) {
    header('Location: messages.php');
    exit;
}

// Mark as read
$contact->markAsRead($_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <h1>Message Details</h1>

            <div class="message-view">
                <div class="message-header">
                    <h2><?php echo htmlspecialchars($message['subject']); ?></h2>
                    <p class="message-meta">
                        From: <strong><?php echo htmlspecialchars($message['name']); ?></strong><br>
                        Email: <strong><?php echo htmlspecialchars($message['email']); ?></strong><br>
                        Date: <?php echo date('F d, Y H:i', strtotime($message['created_at'])); ?>
                    </p>
                </div>

                <div class="message-content">
                    <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                </div>

                <div class="message-actions">
                    <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="btn btn-primary">Reply via Email</a>
                    <a href="delete-message.php?id=<?php echo $message['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
                    <a href="messages.php" class="btn btn-outline">Back to Messages</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
