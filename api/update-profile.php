<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $action = $data['action'] ?? '';
    $userId = $_SESSION['user_id'];

    if ($action === 'update_info') {
        $fullName = trim($data['full_name'] ?? '');
        $phone = trim($data['phone'] ?? '');

        if (empty($fullName)) {
            echo json_encode(["success" => false, "message" => "Name is required"]);
            exit();
        }

        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$fullName, $phone, $userId]);

        // Update session name
        $_SESSION['user_name'] = $fullName;

        echo json_encode(["success" => true, "message" => "Profile updated"]);
        exit();

    } elseif ($action === 'update_password') {
        $currentPassword = $data['current_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            echo json_encode(["success" => false, "message" => "Passwords are required"]);
            exit();
        }

        // Validate pattern backend
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($passwordPattern, $newPassword)) {
            echo json_encode(["success" => false, "message" => "Password does not meet strict requirements"]);
            exit();
        }

        // Fetch current password hash
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            echo json_encode(["success" => false, "message" => "Incorrect current password"]);
            exit();
        }

        // Hash new password
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$newHash, $userId]);

        echo json_encode(["success" => true, "message" => "Password updated"]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid action"]);
        exit();
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
