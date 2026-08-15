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
    
    $variant_id = $data['variant_id'] ?? null;
    $price_inr = $data['price_inr'] ?? null;
    $stock_qty = $data['stock_qty'] ?? null;
    
    if (!$variant_id || !isset($price_inr) || !isset($stock_qty)) {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        exit();
    }
    
    $stmt = $pdo->prepare("UPDATE product_variants SET price_inr = ?, stock_qty = ? WHERE id = ?");
    $stmt->execute([$price_inr, $stock_qty, $variant_id]);
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
