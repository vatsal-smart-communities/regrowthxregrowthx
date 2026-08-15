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

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $variant_id = $data['variant_id'] ?? null;
    $quantity = (int)($data['quantity'] ?? 0);
    
    if (!$variant_id) {
        echo json_encode(["success" => false, "message" => "Invalid variant_id"]);
        exit();
    }
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if ($quantity <= 0) {
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($variant_id) {
            return $item['variant_id'] != $variant_id;
        });
    } else {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['variant_id'] == $variant_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
        unset($item);
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
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error updating cart: " . $e->getMessage()]);
}
?>
