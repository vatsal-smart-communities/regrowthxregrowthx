<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

requirePermission('manage_users', true);

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $user_id = $data['user_id'] ?? null;
    $full_name = trim($data['full_name'] ?? '');
    $email = trim($data['email'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $password = $data['password'] ?? ''; // Optional
    $role_id = $data['role_id'] ?? null;
    
    if (empty($user_id) || empty($full_name) || empty($email) || empty($role_id)) {
        echo json_encode(["success" => false, "message" => "User ID, Name, Email, and Role are required"]);
        exit();
    }
    
    // Check if email is used by another user
    $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmtCheck->execute([$email, $user_id]);
    if ($stmtCheck->fetch()) {
        echo json_encode(["success" => false, "message" => "Email is already in use by another account"]);
        exit();
    }
    
    // Check if role exists
    $stmtRole = $pdo->prepare("SELECT id FROM roles WHERE id = ?");
    $stmtRole->execute([$role_id]);
    if (!$stmtRole->fetch()) {
        echo json_encode(["success" => false, "message" => "Invalid role selected"]);
        exit();
    }
    
    // Prevent removing your own admin role
    if ($user_id == $_SESSION['user_id'] && $role_id == 0) {
        echo json_encode(["success" => false, "message" => "You cannot remove your own admin access"]);
        exit();
    }
    
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, role_id = ?, password_hash = ? WHERE id = ? AND role_id IS NOT NULL");
        $stmt->execute([$full_name, $email, $phone, $role_id, $password_hash, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, role_id = ? WHERE id = ? AND role_id IS NOT NULL");
        $stmt->execute([$full_name, $email, $phone, $role_id, $user_id]);
    }
    
    echo json_encode(["success" => true, "message" => "Admin user updated successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
