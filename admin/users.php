<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\User;

$user = new User();
$users = $user->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <h1>Manage Users</h1>

            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $usr): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usr['username']); ?></td>
                        <td><?php echo htmlspecialchars($usr['email']); ?></td>
                        <td>
                            <span class="badge <?php echo $usr['role'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                                <?php echo ucfirst($usr['role']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($usr['created_at'])); ?></td>
                        <td>
                            <?php if ($usr['id'] !== $_SESSION['user_id']): ?>
                                <a href="change-role.php?id=<?php echo $usr['id']; ?>&role=<?php echo $usr['role'] === 'admin' ? 'user' : 'admin'; ?>" class="btn btn-small">
                                    Change Role
                                </a>
                                <a href="delete-user.php?id=<?php echo $usr['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                            <?php else: ?>
                                <span class="text-muted">Current User</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
