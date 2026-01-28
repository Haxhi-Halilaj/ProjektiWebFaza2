<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\News;
use Classes\Validator;

$error = '';
$success = '';
$news = new News();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = Validator::sanitizeInput($_POST['title'] ?? '');
    $content = Validator::sanitizeInput($_POST['content'] ?? '');
    $image_path = $_POST['image_path'] ?? '';

    $errors = Validator::validateNewsForm($title, $content);

    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        $result = $news->create($title, $content, $image_path, $_SESSION['user_id']);

        if ($result['success']) {
            $success = 'News article created successfully!';
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
    <title>Add News</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <h1>Add New News Article</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="admin-form">
                <div class="form-group">
                    <label for="title">Article Title *</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="image_path">Image URL</label>
                    <input type="url" id="image_path" name="image_path" 
                           value="<?php echo htmlspecialchars($_POST['image_path'] ?? ''); ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add News</button>
                    <a href="news.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
