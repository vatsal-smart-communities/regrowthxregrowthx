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
require_once __DIR__ . '/../config/db.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $variant_id = $data['variant_id'] ?? null;
    $quantity = (int)($data['quantity'] ?? 1);
    
    if (!$variant_id || $quantity <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid variant_id or quantity"]);
        exit();
    }
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $stmt = $pdo->prepare("SELECT v.*, p.title FROM product_variants v JOIN products p ON v.product_id = p.id WHERE v.id = ?");
    $stmt->execute([$variant_id]);
    $variant = $stmt->fetch();
    
    if (!$variant) {
        echo json_encode(["success" => false, "message" => "Variant not found"]);
        exit();
    }
    
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['variant_id'] == $variant_id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    unset($item); // Fix reference bug
    
    if (!$found) {
        $_SESSION['cart'][] = [
            "variant_id" => $variant_id,
            "quantity" => $quantity,
            "title" => $variant['title'],
            "variant_name" => $variant['variant_name'],
            "price_inr" => $variant['price_inr'],
            "mrp_inr" => $variant['mrp_inr'],
            "image_path" => $variant['image_path']
        ];
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
    echo json_encode(["success" => false, "message" => "Error adding to cart: " . $e->getMessage()]);
}
?>
