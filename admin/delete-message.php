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
if ($contact->delete($_GET['id'])) {
    header('Location: messages.php?success=Message deleted successfully');
} else {
    header('Location: messages.php?error=Failed to delete message');
}
exit;
?>
