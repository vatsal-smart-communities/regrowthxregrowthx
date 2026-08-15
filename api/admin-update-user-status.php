<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

requirePermission('manage_users', true);

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $user_id = $data['user_id'] ?? null;
    $status = $data['status'] ?? null;
    
    if (!$user_id || !in_array($status, ['active', 'inactive'])) {
        echo json_encode(["success" => false, "message" => "Invalid parameters"]);
        exit();
    }
    
    // Prevent admin from deactivating themselves
    if ($user_id == $_SESSION['user_id']) {
        echo json_encode(["success" => false, "message" => "You cannot deactivate your own account"]);
        exit();
    }
    
    // Prevent demoting or deactivating another admin unless intended (For safety, we allow it for now, but usually restricted)
    $stmtCheck = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmtCheck->execute([$user_id]);
    $targetUser = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    
    if (!$targetUser) {
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit();
    }
    
    if ($targetUser['role'] === 'admin' && $status === 'inactive') {
        echo json_encode(["success" => false, "message" => "You cannot ban another Admin directly."]);
        exit();
    }
    
    $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->execute([$status, $user_id]);
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
