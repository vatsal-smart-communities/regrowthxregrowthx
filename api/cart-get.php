<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$item_count = 0;
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $item_count += $item['quantity'];
    $cart_total += ($item['price_inr'] * $item['quantity']);
}

echo json_encode([
    "success" => true,
    "cart" => array_values($_SESSION['cart']),
    "item_count" => $item_count,
    "cart_total" => $cart_total
]);
?>
