<?php
require_once __DIR__ . '/includes/header.php';
requirePermission('view_users');

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search & Filter
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? 'all';
$whereClauses = ["u.role_id IS NULL"];
$params = [];

if ($search !== '') {
    $whereClauses[] = "(u.full_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
}

if ($statusFilter !== 'all') {
    $whereClauses[] = "u.status = ?";
    $params[] = $statusFilter;
}

$whereSql = '';
if (!empty($whereClauses)) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

// Get Total Count
$countSql = "SELECT COUNT(*) FROM users u $whereSql";
$stmtCount = $pdo->prepare($countSql);
$stmtCount->execute($params);
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get Records with role name
$sql = "SELECT u.id, u.full_name, u.email, u.phone, u.role, u.role_id, u.status, u.created_at, r.name as role_name 
        FROM users u 
        LEFT JOIN roles r ON u.role_id = r.id
        $whereSql 
        ORDER BY u.created_at DESC 
        LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$canManageUsers = hasPermission('manage_users');

function buildUrl($newPage) {
    $params = $_GET;
    $params['page'] = $newPage;
    return 'users.php?' . http_build_query($params);
}
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h3 class="text-lg font-bold text-gray-900">User Management</h3>
        
        <form method="GET" action="users.php" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name, email, phone..." class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 min-w-[200px]">
            
            <select name="status" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5">
                <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive (Banned)</option>
            </select>

            <button type="submit" class="bg-gray-900 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors">Filter</button>
            <?php if ($search !== '' || $statusFilter !== 'all'): ?>
            <a href="users.php" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4 font-semibold">User Details</th>
                    <th class="px-6 py-4 font-semibold">Contact Info</th>
                    <th class="px-6 py-4 font-semibold">Registered</th>
                    <th class="px-6 py-4 font-semibold text-right">Status & Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No users found matching your criteria.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900"><?= htmlspecialchars($u['full_name'] ?: 'N/A') ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700 font-medium"><?= htmlspecialchars($u['email']) ?></p>
                            <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($u['phone'] ?: 'No Phone') ?></p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?= date('M j, Y', strtotime($u['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end gap-2">
                                <a href="view-user.php?id=<?= $u['id'] ?>" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">visibility</span> View
                                </a>
                                <?php if ($canManageUsers && $u['id'] != $_SESSION['user_id']): ?>
                                <select onchange="updateUserStatus(<?= $u['id'] ?>, this.value)" class="bg-white border border-gray-200 text-xs rounded-lg focus:ring-emerald-500 focus:border-emerald-500 inline-block p-1.5 <?= $u['status'] === 'active' ? 'text-emerald-700 font-semibold' : 'text-red-600 font-semibold' ?>">
                                    <option value="active" <?= $u['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $u['status'] === 'inactive' ? 'selected' : '' ?>>Inactive (Banned)</option>
                                </select>
                                <?php else: ?>
                                <span class="text-xs font-semibold <?= $u['status'] === 'active' ? 'text-emerald-600' : 'text-red-500' ?>"><?= ucfirst($u['status']) ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRecords) ?> of <?= $totalRecords ?> users</p>
        <div class="flex gap-2">
            <?php if ($page > 1): ?>
                <a href="<?= buildUrl($page - 1) ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">Previous</a>
            <?php endif; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="<?= buildUrl($page + 1) ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">Next</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
async function updateUserStatus(userId, newStatus) {
    if (newStatus === 'inactive' && !confirm('Are you sure you want to deactivate (ban) this user? They will not be able to log in.')) {
        window.location.reload();
        return;
    }
    
    try {
        const res = await fetch('../api/admin-update-user-status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, status: newStatus })
        });
        const data = await res.json();
        if (data.success) {
            showAdminToast('User status updated successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAdminToast(data.message || 'Failed to update user', 'error');
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
