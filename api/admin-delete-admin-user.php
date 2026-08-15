<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

requirePermission('manage_users', true);

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $user_id = $data['user_id'] ?? null;
    
    if (empty($user_id)) {
        echo json_encode(["success" => false, "message" => "User ID is required"]);
        exit();
    }
    
    if ($user_id == $_SESSION['user_id']) {
        echo json_encode(["success" => false, "message" => "You cannot delete your own account"]);
        exit();
    }
    
    // Optional: Protect Super Admin from being deleted
    $stmtCheck = $pdo->prepare("SELECT r.slug FROM users u LEFT JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
    $stmtCheck->execute([$user_id]);
    $userCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    
    if ($userCheck && $userCheck['slug'] === 'super_admin') {
        // Prevent deleting a super admin (maybe only allow another super admin to do it, but for safety block it)
        $stmtMe = $pdo->prepare("SELECT r.slug FROM users u LEFT JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
        $stmtMe->execute([$_SESSION['user_id']]);
        $myCheck = $stmtMe->fetch(PDO::FETCH_ASSOC);
        
        if (!$myCheck || $myCheck['slug'] !== 'super_admin') {
            echo json_encode(["success" => false, "message" => "Only a Super Admin can delete another Super Admin."]);
            exit();
        }
    }
    
    // Instead of full delete, you could also just remove their role_id, but the request was "delete options"
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    echo json_encode(["success" => true, "message" => "Admin user deleted successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
