<?php
require_once '../init.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\Order;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

$order = new Order();
$order_data = $order->getById($_GET['id']);

if (!$order_data) {
    header('Location: orders.php');
    exit;
}

$order_items = $order->getOrderItems($order_data['id']);

// Handle status update
$status_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $old_status = $order_data['status'];
    
    if ($new_status !== $old_status) {
        $result = $order->updateStatus($order_data['id'], $new_status);
        if ($result['success']) {
            $status_message = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Order status updated from "' . ucfirst($old_status) . '" to "' . ucfirst($new_status) . '" successfully!</div>';
            // Refresh order data
            $order_data = $order->getById($_GET['id']);
            $order_items = $order->getOrderItems($order_data['id']);
        } else {
            $status_message = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> ' . $result['message'] . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo $order_data['id']; ?> - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>

    <div class="admin-container">
        <?php include '../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <div class="page-header-admin">
                <div>
                    <a href="orders.php" class="btn btn-outline btn-small">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                    <h1>Order #<?php echo $order_data['id']; ?></h1>
                </div>
                <div>
                    <span class="status-badge status-<?php echo $order_data['status']; ?>">
                        <?php echo ucfirst($order_data['status']); ?>
                    </span>
                </div>
            </div>

            <?php echo $status_message; ?>

            <div class="order-details-grid">
                <!-- Order Information -->
                <section class="order-section">
                    <h2><i class="fas fa-info-circle"></i> Order Information</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Order ID:</label>
                            <span><strong>#<?php echo $order_data['id']; ?></strong></span>
                        </div>
                        <div class="info-item">
                            <label>Order Date:</label>
                            <span><?php echo date('F d, Y H:i:s', strtotime($order_data['created_at'])); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Total Amount:</label>
                            <span class="price-large">$<?php echo number_format($order_data['total_amount'], 2); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Order Status:</label>
                            <span>
                                <form method="POST" id="status-form" style="display: inline-block;">
                                    <select name="status" id="status-select" class="status-select" onchange="updateStatus(this)">
                                        <option value="pending" <?php echo $order_data['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order_data['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order_data['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order_data['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order_data['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Customer Information -->
                <section class="order-section">
                    <h2><i class="fas fa-user"></i> Customer Information</h2>
                    <div class="info-grid">
                        <?php if ($order_data['username']): ?>
                            <div class="info-item">
                                <label>Registered User:</label>
                                <span><?php echo htmlspecialchars($order_data['username']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>User Email:</label>
                                <span><?php echo htmlspecialchars($order_data['user_email']); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="info-item">
                                <label>Customer Type:</label>
                                <span class="text-muted">Guest Customer</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Shipping Information -->
                <section class="order-section">
                    <h2><i class="fas fa-truck"></i> Shipping Information</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Full Name:</label>
                            <span><?php echo htmlspecialchars($order_data['shipping_name']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Email:</label>
                            <span><?php echo htmlspecialchars($order_data['shipping_email']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Phone:</label>
                            <span><?php echo htmlspecialchars($order_data['shipping_phone'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="info-item full-width">
                            <label>Shipping Address:</label>
                            <span><?php echo nl2br(htmlspecialchars($order_data['shipping_address'])); ?></span>
                        </div>
                    </div>
                </section>

                <!-- Order Items -->
                <section class="order-section full-width">
                    <h2><i class="fas fa-shopping-cart"></i> Order Items</h2>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if ($item['image_path']): ?>
                                                <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                                     class="order-item-thumb">
                                            <?php else: ?>
                                                <div class="order-item-thumb-placeholder">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                            <?php if ($item['product_id']): ?>
                                                <br><small class="text-muted">Product ID: <?php echo $item['product_id']; ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><strong>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                    <td><strong class="price-large">$<?php echo number_format($order_data['total_amount'], 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        function updateStatus(select) {
            const form = select.closest('form');
            const originalValue = select.dataset.originalValue || select.value;
            
            if (!select.dataset.originalValue) {
                select.dataset.originalValue = originalValue;
            }
            
            if (select.value === originalValue) {
                return;
            }
            
            if (confirm('Are you sure you want to change the order status to "' + select.options[select.selectedIndex].text + '"?')) {
                select.disabled = true;
                form.submit();
            } else {
                select.value = originalValue;
            }
        }
        
        // Store original value on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status-select');
            if (statusSelect) {
                statusSelect.dataset.originalValue = statusSelect.value;
            }
        });
    </script>
</body>
</html>
