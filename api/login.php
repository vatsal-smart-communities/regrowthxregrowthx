<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/db.php';
session_start();

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required"]);
        exit();
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(["success" => false, "message" => "Invalid email or password"]);
        exit();
    }
    
    // Check if password_hash is null (they registered with OTP previously)
    if (empty($user['password_hash'])) {
        echo json_encode(["success" => false, "message" => "Please use the 'Forgot Password' link to set a password for this account."]);
        exit();
    }
    
    if (!password_verify($password, $user['password_hash'])) {
        echo json_encode(["success" => false, "message" => "Invalid email or password"]);
        exit();
    }
    
    if ($user['status'] !== 'active') {
        echo json_encode(["success" => false, "message" => "This account is inactive"]);
        exit();
    }
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['user_role'] = $user['role'];
    
    // Load RBAC permissions if user has an admin role
    if (!empty($user['role_id'])) {
        require_once __DIR__ . '/../includes/permissions.php';
        loadUserPermissions($pdo, $user['id']);
        $_SESSION['permissions_loaded_at'] = time();
    }
    
    echo json_encode([
        "success" => true, 
        "message" => "Login successful",
        "user" => [
            "id" => $user['id'],
            "name" => $user['full_name'],
            "email" => $user['email'],
            "role" => $user['role']
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error during login: " . $e->getMessage()]);
}
?>
