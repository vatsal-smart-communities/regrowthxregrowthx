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

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            requirePermission('view_roles', true);
            
            // Get roles with user count
            $stmt = $pdo->query("
                SELECT r.id, r.name, r.slug, r.description, r.is_system,
                       (SELECT COUNT(*) FROM users WHERE role_id = r.id) as user_count
                FROM roles r
                ORDER BY r.name
            ");
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get permissions for each role
            $stmt = $pdo->query("
                SELECT rp.role_id, p.slug 
                FROM role_permissions rp
                JOIN permissions p ON rp.permission_id = p.id
            ");
            
            $role_perms = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $role_perms[$row['role_id']][] = $row['slug'];
            }
            
            foreach ($roles as &$role) {
                $role['permissions'] = $role_perms[$role['id']] ?? [];
                // Cast to integer/boolean where appropriate
                $role['id'] = (int)$role['id'];
                $role['is_system'] = (bool)$role['is_system'];
                $role['user_count'] = (int)$role['user_count'];
            }
            
            echo json_encode(['success' => true, 'roles' => $roles]);
            break;

        case 'list_permissions':
            requirePermission('view_roles', true);
            
            $stmt = $pdo->query("SELECT id, name, slug, description, group_name FROM permissions ORDER BY group_name, name");
            $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $grouped = [];
            foreach ($permissions as $p) {
                $group = $p['group_name'];
                if (!isset($grouped[$group])) {
                    $grouped[$group] = [];
                }
                $grouped[$group][] = [
                    'id' => (int)$p['id'],
                    'name' => $p['name'],
                    'slug' => $p['slug'],
                    'description' => $p['description']
                ];
            }
            
            echo json_encode(['success' => true, 'permissions' => $grouped]);
            break;

        case 'create':
            requirePermission('manage_roles', true);
            
            $name = trim($input['name'] ?? '');
            $slug = trim($input['slug'] ?? '');
            $description = trim($input['description'] ?? '');
            $permission_ids = $input['permission_ids'] ?? [];
            
            if (empty($name)) {
                throw new Exception("Role name is required");
            }
            
            if (empty($slug)) {
                $slug = strtolower($name);
                $slug = str_replace(' ', '_', $slug);
                $slug = preg_replace('/[^a-z0-9_]/', '', $slug);
            }
            
            // Check if slug exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE slug = ?");
            $stmt->execute([$slug]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("Role slug already exists");
            }
            
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("INSERT INTO roles (name, slug, description) VALUES (?, ?, ?)");
            $stmt->execute([$name, $slug, $description]);
            $role_id = $pdo->lastInsertId();
            
            if (is_array($permission_ids) && count($permission_ids) > 0) {
                $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
                foreach ($permission_ids as $pid) {
                    $stmt->execute([$role_id, $pid]);
                }
            }
            
            $pdo->commit();
            echo json_encode(['success' => true, 'role_id' => $role_id]);
            break;

        case 'update':
            requirePermission('manage_roles', true);
            
            $role_id = (int)($input['role_id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $description = trim($input['description'] ?? '');
            $permission_ids = $input['permission_ids'] ?? [];
            
            if (!$role_id) throw new Exception("Role ID is required");
            if (empty($name)) throw new Exception("Role name is required");
            
            $stmt = $pdo->prepare("SELECT is_system FROM roles WHERE id = ?");
            $stmt->execute([$role_id]);
            $is_system = $stmt->fetchColumn();
            
            $pdo->beginTransaction();
            
            if ($is_system) {
                // For system roles, update only description and permissions
                $stmt = $pdo->prepare("UPDATE roles SET description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$description, $role_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE roles SET name = ?, description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$name, $description, $role_id]);
            }
            
            // Update permissions
            $stmt = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = ?");
            $stmt->execute([$role_id]);
            
            if (is_array($permission_ids) && count($permission_ids) > 0) {
                $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
                foreach ($permission_ids as $pid) {
                    $stmt->execute([$role_id, $pid]);
                }
            }
            
            $pdo->commit();
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            requirePermission('manage_roles', true);
            
            $role_id = (int)($input['role_id'] ?? 0);
            if (!$role_id) throw new Exception("Role ID is required");
            
            $stmt = $pdo->prepare("SELECT is_system FROM roles WHERE id = ?");
            $stmt->execute([$role_id]);
            $is_system = $stmt->fetchColumn();
            
            if ($is_system) {
                throw new Exception("Cannot delete system roles");
            }
            
            $pdo->beginTransaction();
            
            // Unset role on users
            $stmt = $pdo->prepare("UPDATE users SET role_id = NULL, role = 'customer' WHERE role_id = ?");
            $stmt->execute([$role_id]);
            
            // Delete role permissions
            $stmt = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = ?");
            $stmt->execute([$role_id]);
            
            // Delete role
            $stmt = $pdo->prepare("DELETE FROM roles WHERE id = ?");
            $stmt->execute([$role_id]);
            
            $pdo->commit();
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception("Invalid action");
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
