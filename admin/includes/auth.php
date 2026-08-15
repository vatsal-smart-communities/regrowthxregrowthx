<?php
// admin/includes/auth.php — RBAC-aware admin gate
session_start();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/permissions.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['admin_error'] = "Access Denied: You must be logged in as an Administrator.";
    header("Location: ../index.php");
    exit();
}

// Check if user has an admin role_id assigned
$stmtRole = $pdo->prepare("SELECT u.role_id, r.name as role_name, r.slug as role_slug FROM users u LEFT JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
$stmtRole->execute([$_SESSION['user_id']]);
$userRole = $stmtRole->fetch(PDO::FETCH_ASSOC);

if (!$userRole || !$userRole['role_id']) {
    $_SESSION['admin_error'] = "Access Denied: You do not have an admin role assigned.";
    header("Location: ../index.php");
    exit();
}

// Always load permissions to ensure real-time access control
loadUserPermissions($pdo, $_SESSION['user_id']);
$_SESSION['permissions_loaded_at'] = time();

// Store role info in session
$_SESSION['role_name'] = $userRole['role_name'];
$_SESSION['role_slug'] = $userRole['role_slug'];
$_SESSION['user_role'] = 'admin'; // Keep backward compatibility
?>
