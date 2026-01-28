<?php
require_once 'init.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Destroy session
session_destroy();
header('Location: index.php');
exit;
?>
