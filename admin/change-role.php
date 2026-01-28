<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\User;

if (!isset($_GET['id']) || !isset($_GET['role'])) {
    header('Location: users.php');
    exit;
}

$user = new User();
$role = ($_GET['role'] === 'admin') ? 'user' : 'admin';

if ($user->updateRole($_GET['id'], $role)) {
    header('Location: users.php?success=User role updated');
} else {
    header('Location: users.php?error=Failed to update role');
}
exit;
?>
