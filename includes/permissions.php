<?php
/**
 * RegrowthX RBAC Permission Helper
 * 
 * Provides functions to load, check, and enforce permissions
 * based on the user's assigned role.
 */

/**
 * Load all permission slugs for a user into $_SESSION['permissions'].
 * Call this once when the user first accesses an admin page.
 */
function loadUserPermissions($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT p.slug 
        FROM permissions p
        INNER JOIN role_permissions rp ON rp.permission_id = p.id
        INNER JOIN users u ON u.role_id = rp.role_id
        WHERE u.id = ?
    ");
    $stmt->execute([$userId]);
    $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $_SESSION['permissions'] = $permissions;

    // Also load role info
    $stmtRole = $pdo->prepare("
        SELECT r.id, r.name, r.slug 
        FROM roles r 
        INNER JOIN users u ON u.role_id = r.id 
        WHERE u.id = ?
    ");
    $stmtRole->execute([$userId]);
    $role = $stmtRole->fetch(PDO::FETCH_ASSOC);
    if ($role) {
        $_SESSION['role_name'] = $role['name'];
        $_SESSION['role_slug'] = $role['slug'];
    }

    return $permissions;
}

/**
 * Check if the current session user has a specific permission.
 * Returns true/false.
 */
function hasPermission($slug) {
    if (!isset($_SESSION['permissions'])) return false;
    return in_array($slug, $_SESSION['permissions']);
}

/**
 * Require a specific permission for the current page/action.
 * If the user lacks it, either redirect (for pages) or return JSON 403 (for API).
 * 
 * @param string $slug  Permission slug to check
 * @param bool   $isApi If true, returns JSON error. If false, redirects to dashboard.
 */
function requirePermission($slug, $isApi = false) {
    if (!hasPermission($slug)) {
        if ($isApi) {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(["success" => false, "message" => "Access denied: You do not have the '{$slug}' permission."]);
            exit();
        } else {
            $_SESSION['admin_error'] = "Access Denied: You do not have permission to access this page.";
            header("Location: index.php");
            exit();
        }
    }
}

/**
 * Require that the current user has an admin role (any role_id set).
 * Used as the base gate for accessing the /admin/ panel at all.
 */
function requireAdminRole() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_name'])) {
        $_SESSION['admin_error'] = "Access Denied: You must be logged in as an Administrator.";
        header("Location: ../index.php");
        exit();
    }
}
?>
