<?php
require_once 'init.php';

use Classes\User;
use Classes\Validator;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = Validator::sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $user = new User();
        $result = $user->login($username, $password);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['role'] = $result['user']['role'];
            $_SESSION['email'] = $result['user']['email'];

            // Redirect based on role
            if ($result['user']['role'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
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
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-form">
            <h1>Login</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>

            <p class="text-center mt-3">
                Don't have an account? <a href="register.php">Register here</a>
            </p>

            <hr>

            <p class="text-center text-muted" style="font-size: 0.9em;">
                Demo credentials:<br>
                Username: <strong>admin</strong><br>
                Password: <strong>admin123</strong>
            </p>
        </div>
    </div>
</body>
</html>
