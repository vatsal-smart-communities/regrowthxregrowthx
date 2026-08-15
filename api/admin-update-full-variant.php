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
    $variant_key = trim($data['variant_key'] ?? '');
    $variant_name = trim($data['variant_name'] ?? '');
    $price_inr = $data['price_inr'] ?? '';
    $mrp_inr = $data['mrp_inr'] ?? '';
    $image_path = isset($data['image_path']) ? trim($data['image_path']) : null;
    
    if (!$variant_id || !$variant_key || !$variant_name || !$price_inr || !$mrp_inr) {
        echo json_encode(["success" => false, "message" => "Please fill in all required fields"]);
        exit();
    }
    
    if ($image_path !== null && $image_path !== '') {
        $stmt = $pdo->prepare("
            UPDATE product_variants 
            SET variant_key = ?, variant_name = ?, price_inr = ?, mrp_inr = ?, image_path = ?
            WHERE id = ?
        ");
        $stmt->execute([$variant_key, $variant_name, $price_inr, $mrp_inr, $image_path, $variant_id]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE product_variants 
            SET variant_key = ?, variant_name = ?, price_inr = ?, mrp_inr = ?
            WHERE id = ?
        ");
        $stmt->execute([$variant_key, $variant_name, $price_inr, $mrp_inr, $variant_id]);
    }
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
