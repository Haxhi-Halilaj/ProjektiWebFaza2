<?php
require_once 'init.php';

use Classes\Cart;

header('Content-Type: application/json');

$cart = new Cart();
$count = $cart->getCartCount();

echo json_encode(['count' => $count]);
?>
