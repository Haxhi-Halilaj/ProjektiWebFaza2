<?php
require_once '../init.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

use Classes\Order;

$order = new Order();
$orders = $order->getAll();

// Filter by status if provided
$filter_status = $_GET['status'] ?? 'all';
if ($filter_status !== 'all') {
    $orders = $order->getByStatus($filter_status);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Admin</title>
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
                <h1>Orders Management</h1>
                <div class="filter-buttons">
                    <a href="orders.php?status=all" class="btn btn-small <?php echo $filter_status === 'all' ? 'btn-primary' : 'btn-outline'; ?>">All</a>
                    <a href="orders.php?status=pending" class="btn btn-small <?php echo $filter_status === 'pending' ? 'btn-primary' : 'btn-outline'; ?>">Pending</a>
                    <a href="orders.php?status=processing" class="btn btn-small <?php echo $filter_status === 'processing' ? 'btn-primary' : 'btn-outline'; ?>">Processing</a>
                    <a href="orders.php?status=shipped" class="btn btn-small <?php echo $filter_status === 'shipped' ? 'btn-primary' : 'btn-outline'; ?>">Shipped</a>
                    <a href="orders.php?status=delivered" class="btn btn-small <?php echo $filter_status === 'delivered' ? 'btn-primary' : 'btn-outline'; ?>">Delivered</a>
                    <a href="orders.php?status=cancelled" class="btn btn-small <?php echo $filter_status === 'cancelled' ? 'btn-primary' : 'btn-outline'; ?>">Cancelled</a>
                </div>
            </div>

            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <p>No orders found.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Shipping Name</th>
                                <th>Email</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $ord): ?>
                                <tr>
                                    <td><strong>#<?php echo $ord['id']; ?></strong></td>
                                    <td>
                                        <?php if ($ord['username']): ?>
                                            <?php echo htmlspecialchars($ord['username']); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Guest</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($ord['shipping_name']); ?></td>
                                    <td><?php echo htmlspecialchars($ord['shipping_email']); ?></td>
                                    <td><strong>$<?php echo number_format($ord['total_amount'], 2); ?></strong></td>
                                    <td>
                                        <form method="POST" class="status-form" style="display: inline-block;" onsubmit="return updateOrderStatus(event, this, <?php echo $ord['id']; ?>)">
                                            <select name="status" class="status-select-inline" data-order-id="<?php echo $ord['id']; ?>" data-original-status="<?php echo $ord['status']; ?>" onchange="updateOrderStatusInline(this)">
                                                <option value="pending" <?php echo $ord['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="processing" <?php echo $ord['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                <option value="shipped" <?php echo $ord['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                <option value="delivered" <?php echo $ord['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                <option value="cancelled" <?php echo $ord['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                        </form>
                                    </td>
                                    <td><?php echo date('M d, Y H:i', strtotime($ord['created_at'])); ?></td>
                                    <td>
                                        <a href="view-order.php?id=<?php echo $ord['id']; ?>" class="btn btn-small">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function updateOrderStatusInline(select) {
            const orderId = select.dataset.orderId;
            const originalStatus = select.dataset.originalStatus;
            const newStatus = select.value;
            
            if (newStatus === originalStatus) {
                return;
            }
            
            if (!confirm('Change order #' + orderId + ' status from "' + originalStatus.charAt(0).toUpperCase() + originalStatus.slice(1) + '" to "' + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + '"?')) {
                select.value = originalStatus;
                return;
            }
            
            // Show loading state
            select.disabled = true;
            const originalValue = select.value;
            
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('status', newStatus);
            
            fetch('update-order-status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                select.disabled = false;
                
                if (data.success) {
                    // Update the original status
                    select.dataset.originalStatus = newStatus;
                    
                    // Show success message
                    showNotification('Order #' + orderId + ' status updated successfully!', 'success');
                    
                    // Optionally reload after a short delay to ensure consistency
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.message || 'Failed to update order status');
                    select.value = originalStatus;
                }
            })
            .catch(error => {
                select.disabled = false;
                select.value = originalStatus;
                console.error('Error:', error);
                alert('An error occurred while updating the order status');
            });
        }
        
        function updateOrderStatus(event, form, orderId) {
            event.preventDefault();
            return false;
        }
        
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = 'alert alert-' + (type === 'success' ? 'success' : 'danger');
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.padding = '1rem 1.5rem';
            notification.style.borderRadius = '5px';
            notification.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            notification.style.minWidth = '300px';
            notification.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
