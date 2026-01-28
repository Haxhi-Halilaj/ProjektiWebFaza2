<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\News;

if (!isset($_GET['id'])) {
    header('Location: news.php');
    exit;
}

$news = new News();
if ($news->delete($_GET['id'])) {
    header('Location: news.php?success=Article deleted successfully');
} else {
    header('Location: news.php?error=Failed to delete article');
}
exit;
?>
