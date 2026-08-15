<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

requirePermission('manage_users', true);

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $full_name = trim($data['full_name'] ?? '');
    $email = trim($data['email'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $password = $data['password'] ?? '';
    $role_id = $data['role_id'] ?? null;
    
    if (empty($full_name) || empty($email) || empty($password) || empty($role_id)) {
        echo json_encode(["success" => false, "message" => "Name, Email, Password, and Role are required"]);
        exit();
    }
    
    // Check if email already exists
    $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmtCheck->execute([$email]);
    if ($stmtCheck->fetch()) {
        echo json_encode(["success" => false, "message" => "Email is already registered"]);
        exit();
    }
    
    // Check if role exists
    $stmtRole = $pdo->prepare("SELECT id FROM roles WHERE id = ?");
    $stmtRole->execute([$role_id]);
    if (!$stmtRole->fetch()) {
        echo json_encode(["success" => false, "message" => "Invalid role selected"]);
        exit();
    }
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password_hash, role, role_id, status) VALUES (?, ?, ?, ?, 'admin', ?, 'active')");
    $stmt->execute([$full_name, $email, $phone, $password_hash, $role_id]);
    
    echo json_encode(["success" => true, "message" => "Admin user created successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
