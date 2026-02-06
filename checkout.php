<?php
require_once 'init.php';

use Classes\Cart;
use Classes\Validator;

$cart = new Cart();
$cart_items = $cart->getCartItems();
$cart_total = $cart->getCartTotal();

if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = Validator::sanitizeInput($_POST['name'] ?? '');
    $email = Validator::sanitizeInput($_POST['email'] ?? '');
    $phone = Validator::sanitizeInput($_POST['phone'] ?? '');
    $address = Validator::sanitizeInput($_POST['address'] ?? '');
    $city = Validator::sanitizeInput($_POST['city'] ?? '');
    $state = Validator::sanitizeInput($_POST['state'] ?? '');
    $zip = Validator::sanitizeInput($_POST['zip'] ?? '');
    
    $full_address = trim("$address, $city, $state $zip");

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($zip)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        $shipping_info = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $full_address
        ];
        
        $result = $cart->createOrder($user_id, $shipping_info);
        
        if ($result['success']) {
            $success = 'Order placed successfully! Your order ID is #' . $result['order_id'];
            // Redirect after 3 seconds
            header("Refresh: 3; url=index.php");
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
    <title>Checkout - TechCorp Solutions</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-header">
        <h1>Checkout</h1>
        <p>Complete your purchase</p>
    </div>

    <div class="container">
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <p>You will be redirected to the homepage shortly...</p>
            </div>
        <?php else: ?>
            <div class="checkout-container">
                <div class="checkout-form-wrapper">
                    <h2>Shipping Information</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" class="checkout-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" required 
                                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required 
                                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="address">Street Address *</label>
                            <input type="text" id="address" name="address" required 
                                   value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" name="city" required 
                                       value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="state">State *</label>
                                <input type="text" id="state" name="state" required 
                                       value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="zip">ZIP Code *</label>
                                <input type="text" id="zip" name="zip" required 
                                       value="<?php echo htmlspecialchars($_POST['zip'] ?? ''); ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Place Order</button>
                    </form>
                </div>

                <div class="checkout-summary">
                    <h2>Order Summary</h2>
                    <div class="order-items">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="order-item">
                                <div class="order-item-info">
                                    <?php if ($item['image_path']): ?>
                                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="order-item-image">
                                    <?php endif; ?>
                                    <div>
                                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                                    </div>
                                </div>
                                <div class="order-item-price">
                                    $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-total">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($cart_total, 2); ?></span>
                        </div>
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>Calculated at checkout</span>
                        </div>
                        <div class="total-row final">
                            <span>Total:</span>
                            <span>$<?php echo number_format($cart_total, 2); ?></span>
                        </div>
                    </div>
                    <a href="cart.php" class="btn btn-outline btn-block">Back to Cart</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
