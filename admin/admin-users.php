<?php
require_once __DIR__ . '/includes/header.php';
requirePermission('manage_users'); // Only managers/super admins should manage admin users

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search & Filter
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? 'all';
$roleFilter = $_GET['role'] ?? 'all';

$whereClauses = ["u.role_id IS NOT NULL"]; // ONLY Admin Users
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

if ($roleFilter !== 'all') {
    $whereClauses[] = "u.role_id = ?";
    $params[] = $roleFilter;
}

$whereSql = "WHERE " . implode(" AND ", $whereClauses);

// Get Total Count
$countSql = "SELECT COUNT(*) FROM users u $whereSql";
$stmtCount = $pdo->prepare($countSql);
$stmtCount->execute($params);
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get Records with role name
$sql = "SELECT u.id, u.full_name, u.email, u.phone, u.role, u.role_id, u.status, u.created_at, r.name as role_name, r.is_system 
        FROM users u 
        LEFT JOIN roles r ON u.role_id = r.id
        $whereSql 
        ORDER BY u.created_at DESC 
        LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all roles for dropdowns
$rolesStmt = $pdo->query("SELECT id, name, slug FROM roles ORDER BY name");
$allRoles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);

function buildUrl($newPage) {
    $params = $_GET;
    $params['page'] = $newPage;
    return 'admin-users.php?' . http_build_query($params);
}
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h3 class="text-2xl font-bold text-gray-900">Admin Users</h3>
        <p class="text-sm text-gray-500 mt-1">Manage staff, managers, and admin accounts.</p>
    </div>
    <button onclick="openCreateAdminModal()" class="px-5 py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-md flex items-center gap-2 shrink-0">
        <span class="material-symbols-outlined text-sm">add</span> Add Admin User
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <form method="GET" action="admin-users.php" class="flex flex-wrap items-center gap-3 w-full">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name, email, phone..." class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 flex-1 min-w-[200px]">
            
            <select name="role" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5">
                <option value="all" <?= $roleFilter === 'all' ? 'selected' : '' ?>>All Roles</option>
                <?php foreach ($allRoles as $r): ?>
                <option value="<?= $r['id'] ?>" <?= $roleFilter == $r['id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="status" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5">
                <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive (Banned)</option>
            </select>
            
            <button type="submit" class="bg-gray-900 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors">Filter</button>
            <?php if ($search !== '' || $statusFilter !== 'all' || $roleFilter !== 'all'): ?>
            <a href="admin-users.php" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4 font-semibold">User Details</th>
                    <th class="px-6 py-4 font-semibold">Contact Info</th>
                    <th class="px-6 py-4 font-semibold">Role</th>
                    <th class="px-6 py-4 font-semibold">Registered</th>
                    <th class="px-6 py-4 font-semibold text-right">Status & Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No admin users found matching your criteria.</td>
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
                        <td class="px-6 py-4">
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <select onchange="assignRole(<?= $u['id'] ?>, this.value)" class="bg-white border border-gray-200 text-xs rounded-lg focus:ring-emerald-500 focus:border-emerald-500 p-1.5 min-w-[140px] <?= $u['role_id'] ? 'text-emerald-700 font-semibold' : 'text-gray-500' ?>">
                                <option value="0">Remove Admin Access</option>
                                <?php foreach ($allRoles as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= $u['role_id'] == $r['id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                <?= htmlspecialchars($u['role_name'] ?? 'Admin') ?>
                                <span class="ml-1 text-[10px] text-gray-400">(You)</span>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?= date('M j, Y', strtotime($u['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center gap-2">
                                    <button onclick="openEditAdminModal(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['full_name'])) ?>', '<?= htmlspecialchars(addslashes($u['email'])) ?>', '<?= htmlspecialchars(addslashes($u['phone'])) ?>', <?= $u['role_id'] ?>)" title="Edit User" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>
                                    
                                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                    <button onclick="deleteAdminUser(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['full_name'])) ?>')" title="Delete User" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                    <?php endif; ?>
                                </div>

                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
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

<!-- Create Admin Modal -->
<div id="create-admin-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreateAdminModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between shrink-0">
                <h3 class="text-lg font-bold text-gray-900">Add New Admin User</h3>
                <button onclick="closeCreateAdminModal()" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-gray-500">close</span>
                </button>
            </div>
            
            <form id="create-admin-form" class="px-6 py-6 space-y-4" onsubmit="handleCreateAdmin(event)">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Full Name *</label>
                    <input type="text" id="admin_full_name" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email Address *</label>
                    <input type="email" id="admin_email" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Phone Number</label>
                    <input type="text" id="admin_phone" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Password *</label>
                        <input type="password" id="admin_password" required minlength="6" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Role *</label>
                        <select id="admin_role_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                            <option value="">Select Role...</option>
                            <?php foreach ($allRoles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 mt-6 border-t border-gray-100">
                    <button type="button" onclick="closeCreateAdminModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">Cancel</button>
                    <button type="submit" id="create-btn" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                        Create Admin User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Admin Modal -->
<div id="edit-admin-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditAdminModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between shrink-0">
                <h3 class="text-lg font-bold text-gray-900">Edit Admin User</h3>
                <button onclick="closeEditAdminModal()" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-gray-500">close</span>
                </button>
            </div>
            
            <form id="edit-admin-form" class="px-6 py-6 space-y-4" onsubmit="handleEditAdmin(event)">
                <input type="hidden" id="edit_user_id">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Full Name *</label>
                    <input type="text" id="edit_full_name" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email Address *</label>
                    <input type="email" id="edit_email" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Phone Number</label>
                    <input type="text" id="edit_phone" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">New Password</label>
                        <input type="password" id="edit_password" minlength="6" placeholder="Leave blank to keep current" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Role *</label>
                        <select id="edit_role_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                            <option value="">Select Role...</option>
                            <?php foreach ($allRoles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 mt-6 border-t border-gray-100">
                    <button type="button" onclick="closeEditAdminModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">Cancel</button>
                    <button type="submit" id="edit-btn" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateAdminModal() {
    document.getElementById('create-admin-form').reset();
    document.getElementById('create-admin-modal').classList.remove('hidden');
}

function closeCreateAdminModal() {
    document.getElementById('create-admin-modal').classList.add('hidden');
}

async function handleCreateAdmin(e) {
    e.preventDefault();
    const btn = document.getElementById('create-btn');
    btn.disabled = true;
    btn.textContent = 'Creating...';
    
    const body = {
        full_name: document.getElementById('admin_full_name').value.trim(),
        email: document.getElementById('admin_email').value.trim(),
        phone: document.getElementById('admin_phone').value.trim(),
        password: document.getElementById('admin_password').value,
        role_id: parseInt(document.getElementById('admin_role_id').value)
    };
    
    try {
        const res = await fetch('../api/admin-create-admin-user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });
        const data = await res.json();
        
        if (data.success) {
            showAdminToast(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAdminToast(data.message || 'Failed to create user', 'error');
            btn.disabled = false;
            btn.textContent = 'Create Admin User';
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
        btn.disabled = false;
    }
}

function openEditAdminModal(id, name, email, phone, roleId) {
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_full_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_role_id').value = roleId;
    document.getElementById('edit_password').value = '';
    
    document.getElementById('edit-admin-modal').classList.remove('hidden');
}

function closeEditAdminModal() {
    document.getElementById('edit-admin-modal').classList.add('hidden');
}

async function handleEditAdmin(e) {
    e.preventDefault();
    const btn = document.getElementById('edit-btn');
    btn.disabled = true;
    btn.textContent = 'Saving...';
    
    const body = {
        user_id: parseInt(document.getElementById('edit_user_id').value),
        full_name: document.getElementById('edit_full_name').value.trim(),
        email: document.getElementById('edit_email').value.trim(),
        phone: document.getElementById('edit_phone').value.trim(),
        password: document.getElementById('edit_password').value,
        role_id: parseInt(document.getElementById('edit_role_id').value)
    };
    
    try {
        const res = await fetch('../api/admin-update-admin-user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });
        const data = await res.json();
        
        if (data.success) {
            showAdminToast(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAdminToast(data.message || 'Failed to update user', 'error');
            btn.disabled = false;
            btn.textContent = 'Save Changes';
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
        btn.disabled = false;
        btn.textContent = 'Save Changes';
    }
}

async function deleteAdminUser(userId, name) {
    if (!confirm(`Are you sure you want to permanently delete the admin user "${name}"? This action cannot be undone.`)) {
        return;
    }
    
    try {
        const res = await fetch('../api/admin-delete-admin-user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId })
        });
        const data = await res.json();
        if (data.success) {
            showAdminToast('User deleted successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAdminToast(data.message || 'Failed to delete user', 'error');
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
    }
}

async function updateUserStatus(userId, newStatus) {
    if (newStatus === 'inactive' && !confirm('Are you sure you want to deactivate (ban) this admin? They will not be able to log in.')) {
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
            showAdminToast('Admin status updated successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAdminToast(data.message || 'Failed to update status', 'error');
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
    }
}

async function assignRole(userId, roleId) {
    const roleName = roleId == 0 ? 'Customer' : event.target.options[event.target.selectedIndex].text;
    
    if (!confirm(`Change role to "${roleName}"? ${roleId == 0 ? '(This will remove admin access entirely)' : ''}`)) {
        window.location.reload();
        return;
    }
    
    try {
        const res = await fetch('../api/admin-assign-role.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, role_id: parseInt(roleId) || null })
        });
        const data = await res.json();
        if (data.success) {
            showAdminToast('Role updated successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAdminToast(data.message || 'Failed to update role', 'error');
            window.location.reload();
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
