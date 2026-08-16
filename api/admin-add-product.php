<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

requirePermission('manage_products', true);

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $title = trim($data['title'] ?? '');
    $description = trim($data['description'] ?? '');
    $base_price_inr = $data['base_price_inr'] ?? 0;
    $base_mrp_inr = $data['base_mrp_inr'] ?? 0;
    $base_stock_qty = $data['base_stock_qty'] ?? 0;
    $image_1 = $data['image_1'] ?? null;
    $image_2 = $data['image_2'] ?? null;
    $image_3 = $data['image_3'] ?? null;
    $image_4 = $data['image_4'] ?? null;
    $image_5 = $data['image_5'] ?? null;
    
    $variant_keys = $data['variant_key'] ?? [];
    $variant_names = $data['variant_name'] ?? [];
    $price_inrs = $data['price_inr'] ?? [];
    $mrp_inrs = $data['mrp_inr'] ?? [];
    $stock_qtys = $data['stock_qty'] ?? [];
    $image_paths = $data['image_path'] ?? [];
    
    if (!$title) {
        echo json_encode(["success" => false, "message" => "Please fill in all required fields"]);
        exit();
    }
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $slug = trim($slug, '-');
    $slug .= '-' . rand(100, 999); 
    
    $pdo->beginTransaction();
    
    // Insert Product
    $stmt = $pdo->prepare("INSERT INTO products (title, slug, description, active, base_price_inr, base_mrp_inr, base_stock_qty, image_1, image_2, image_3, image_4, image_5) VALUES (?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $slug, $description, $base_price_inr, $base_mrp_inr, $base_stock_qty, $image_1, $image_2, $image_3, $image_4, $image_5]);
    $product_id = $pdo->lastInsertId();
    
    // Insert Variants
    $stmtVariant = $pdo->prepare("
        INSERT INTO product_variants 
        (product_id, variant_key, variant_name, price_inr, mrp_inr, stock_qty, image_path) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    for ($i = 0; $i < count($variant_keys); $i++) {
        $vk = trim($variant_keys[$i]);
        $vn = trim($variant_names[$i]);
        $p = $price_inrs[$i];
        $m = $mrp_inrs[$i];
        $s = $stock_qtys[$i] ?? 0;
        $img = !empty($image_paths[$i]) ? trim($image_paths[$i]) : 'img/product-box-bottle.jpg';
        
        if ($vk && $vn) {
            $stmtVariant->execute([
                $product_id, 
                $vk . '-' . rand(100, 999), 
                $vn, 
                $p, 
                $m, 
                $s,
                $img
            ]);
        }
    }
    
    $pdo->commit();
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
