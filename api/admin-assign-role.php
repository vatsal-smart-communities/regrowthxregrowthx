<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

requirePermission('manage_users', true);

$input = json_decode(file_get_contents('php://input'), true);
$target_user_id = (int)($input['user_id'] ?? 0);
$role_id = $input['role_id'] ?? null;

if (!$target_user_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

if ($target_user_id === (int)$_SESSION['user_id']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cannot change your own role']);
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Check if target user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$target_user_id]);
    if (!$stmt->fetch()) {
        throw new Exception("User not found");
    }

    $new_role_name = 'Customer';
    
    if ($role_id === null || $role_id === 0 || $role_id === '0') {
        // Remove role
        $stmt = $pdo->prepare("UPDATE users SET role_id = NULL, role = 'customer' WHERE id = ?");
        $stmt->execute([$target_user_id]);
    } else {
        $role_id = (int)$role_id;
        // Verify role exists
        $stmt = $pdo->prepare("SELECT name FROM roles WHERE id = ?");
        $stmt->execute([$role_id]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$role) {
            throw new Exception("Role not found");
        }
        $new_role_name = $role['name'];
        
        // Assign role
        $stmt = $pdo->prepare("UPDATE users SET role_id = ?, role = 'admin' WHERE id = ?");
        $stmt->execute([$role_id, $target_user_id]);
    }
    
    $pdo->commit();
    echo json_encode([
        'success' => true, 
        'message' => 'Role updated successfully',
        'new_role_name' => $new_role_name
    ]);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
