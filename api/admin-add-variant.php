<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

require_once __DIR__ . '/../config/db.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $product_id = $data['product_id'] ?? null;
    $variant_key = trim($data['variant_key'] ?? '');
    $variant_name = trim($data['variant_name'] ?? '');
    $price_inr = $data['price_inr'] ?? '';
    $mrp_inr = $data['mrp_inr'] ?? '';
    $stock_qty = $data['stock_qty'] ?? 0;
    $image_path = trim($data['image_path'] ?? '');
    if (!$image_path) {
        $image_path = 'img/product-box-bottle.jpg';
    }
    
    if (!$product_id || !$variant_key || !$variant_name || !$price_inr || !$mrp_inr) {
        echo json_encode(["success" => false, "message" => "Please fill in all required fields"]);
        exit();
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO product_variants 
        (product_id, variant_key, variant_name, price_inr, mrp_inr, stock_qty, image_path) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Append rand to ensure variant key uniqueness if they try to reuse one
    $stmt->execute([
        $product_id, 
        $variant_key . '-' . rand(100, 999), 
        $variant_name, 
        $price_inr, 
        $mrp_inr, 
        $stock_qty,
        $image_path
    ]);
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
