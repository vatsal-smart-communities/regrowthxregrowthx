<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

requirePermission('manage_products', true);

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $product_id = $data['product_id'] ?? null;
    $title = trim($data['title'] ?? '');
    $description = trim($data['description'] ?? '');
    $active = isset($data['active']) ? (int)$data['active'] : 1;
    
    $base_price_inr = $data['base_price_inr'] ?? 0;
    $base_mrp_inr = $data['base_mrp_inr'] ?? 0;
    $base_stock_qty = $data['base_stock_qty'] ?? 0;
    $image_1 = $data['image_1'] ?? null;
    $image_2 = $data['image_2'] ?? null;
    $image_3 = $data['image_3'] ?? null;
    $image_4 = $data['image_4'] ?? null;
    $image_5 = $data['image_5'] ?? null;
    
    if (!$product_id || !$title) {
        echo json_encode(["success" => false, "message" => "Product ID and Title are required"]);
        exit();
    }
    
    $stmt = $pdo->prepare("UPDATE products SET title = ?, description = ?, active = ?, base_price_inr = ?, base_mrp_inr = ?, base_stock_qty = ?, image_1 = ?, image_2 = ?, image_3 = ?, image_4 = ?, image_5 = ? WHERE id = ?");
    $stmt->execute([$title, $description, $active, $base_price_inr, $base_mrp_inr, $base_stock_qty, $image_1, $image_2, $image_3, $image_4, $image_5, $product_id]);
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
