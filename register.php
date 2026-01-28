<?php
require_once 'init.php';

use Classes\User;
use Classes\Validator;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = Validator::sanitizeInput($_POST['username'] ?? '');
    $email = Validator::sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if (!Validator::validateUsername($username)) {
        $error = 'Username must be 3-20 characters (alphanumeric and underscore only)';
    } elseif (!Validator::validateEmail($email)) {
        $error = 'Please enter a valid email address';
    } elseif (!Validator::validatePassword($password)) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $password_confirm) {
        $error = 'Passwords do not match';
    } else {
        $user = new User();
        $result = $user->register($username, $email, $password);

        if ($result['success']) {
            $success = 'Registration successful! You can now <a href="login.php">login</a>';
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
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-form">
            <h1>Register</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required 
                           pattern="[a-zA-Z0-9_]{3,20}" 
                           title="Username must be 3-20 characters (alphanumeric and underscore)"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="6">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>

            <p class="text-center mt-3">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
