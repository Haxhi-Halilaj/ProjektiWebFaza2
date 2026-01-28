<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\User;

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$user = new User();
if ($user->deleteUser($_GET['id'])) {
    header('Location: users.php?success=User deleted');
} else {
    header('Location: users.php?error=Failed to delete user');
}
exit;
?>
