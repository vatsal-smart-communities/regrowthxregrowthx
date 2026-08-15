<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

requirePermission('manage_products', true);

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $product_id = $data['product_id'] ?? null;
    
    if (!$product_id) {
        echo json_encode(["success" => false, "message" => "Product ID is required"]);
        exit();
    }
    
    // ON DELETE CASCADE in the database schema will automatically delete the variants
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
