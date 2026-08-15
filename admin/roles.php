<?php
require_once __DIR__ . '/includes/header.php';
requirePermission('view_roles');

// Fetch all roles with user counts
$rolesStmt = $pdo->query("
    SELECT r.*, 
           (SELECT COUNT(*) FROM users WHERE role_id = r.id) as user_count,
           (SELECT COUNT(*) FROM role_permissions WHERE role_id = r.id) as perm_count
    FROM roles r 
    ORDER BY r.is_system DESC, r.name ASC
");
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all permissions grouped
$permsStmt = $pdo->query("SELECT * FROM permissions ORDER BY group_name, name");
$allPermissions = $permsStmt->fetchAll(PDO::FETCH_ASSOC);
$permGroups = [];
foreach ($allPermissions as $p) {
    $permGroups[$p['group_name']][] = $p;
}

// Fetch role_permissions for all roles (for editing)
$rpStmt = $pdo->query("SELECT role_id, permission_id FROM role_permissions");
$rolePermsMap = [];
while ($rp = $rpStmt->fetch(PDO::FETCH_ASSOC)) {
    $rolePermsMap[$rp['role_id']][] = $rp['permission_id'];
}

$canManage = hasPermission('manage_roles');
$totalPerms = count($allPermissions);
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h3 class="text-2xl font-bold text-gray-900">Roles & Permissions</h3>
        <p class="text-sm text-gray-500 mt-1"><?= count($roles) ?> roles configured · <?= $totalPerms ?> permissions available</p>
    </div>
    <?php if ($canManage): ?>
    <button onclick="openCreateRoleModal()" class="px-5 py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-md flex items-center gap-2 shrink-0">
        <span class="material-symbols-outlined text-sm">add</span> Create New Role
    </button>
    <?php endif; ?>
</div>

<!-- Roles Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php foreach ($roles as $role): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group" id="role-card-<?= $role['id'] ?>">
        <div class="px-6 py-5 border-b border-gray-100 flex items-start justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-base font-bold text-gray-900"><?= htmlspecialchars($role['name']) ?></h4>
                    <?php if ($role['is_system']): ?>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">System</span>
                    <?php endif; ?>
                </div>
                <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($role['description'] ?? 'No description') ?></p>
            </div>
            <?php if ($canManage): ?>
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="openEditRoleModal(<?= $role['id'] ?>)" title="Edit Role" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                </button>
                <?php if (!$role['is_system']): ?>
                <button onclick="deleteRole(<?= $role['id'] ?>, '<?= htmlspecialchars($role['name']) ?>')" title="Delete Role" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[16px]">delete</span>
                </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="px-6 py-4 space-y-3">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">shield</span> Permissions
                </span>
                <span class="font-bold text-gray-900"><?= $role['perm_count'] ?> / <?= $totalPerms ?></span>
            </div>
            <!-- Permission progress bar -->
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: <?= $totalPerms > 0 ? round(($role['perm_count'] / $totalPerms) * 100) : 0 ?>%"></div>
            </div>
            <!-- Permission tags preview -->
            <div class="flex flex-wrap gap-1.5">
                <?php 
                $rolePermIds = $rolePermsMap[$role['id']] ?? [];
                $shownCount = 0;
                foreach ($allPermissions as $p): 
                    if (in_array($p['id'], $rolePermIds)):
                        $shownCount++;
                        if ($shownCount <= 4):
                ?>
                <span class="text-[10px] font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full"><?= htmlspecialchars($p['name']) ?></span>
                <?php 
                        endif;
                    endif;
                endforeach;
                if ($shownCount > 4): 
                ?>
                <span class="text-[10px] font-semibold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">+<?= $shownCount - 4 ?> more</span>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-50">
                <span class="text-gray-500 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">group</span> Users
                </span>
                <span class="font-bold text-gray-900"><?= $role['user_count'] ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if ($canManage): ?>
<!-- Create / Edit Role Modal -->
<div id="role-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeRoleModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between shrink-0">
                <h3 id="modal-title" class="text-lg font-bold text-gray-900">Create New Role</h3>
                <button onclick="closeRoleModal()" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-gray-500">close</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6">
                <input type="hidden" id="modal-role-id" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Role Name *</label>
                        <input type="text" id="modal-role-name" placeholder="e.g. Warehouse Staff" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Description</label>
                        <input type="text" id="modal-role-desc" placeholder="Brief role description" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">Permissions</label>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectAllPerms()" class="text-xs text-emerald-600 font-semibold hover:underline">Select All</button>
                            <span class="text-gray-300">|</span>
                            <button type="button" onclick="deselectAllPerms()" class="text-xs text-gray-500 font-semibold hover:underline">Deselect All</button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <?php foreach ($permGroups as $groupName => $perms): ?>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <?php
                                $groupIcons = ['Dashboard' => 'dashboard', 'Orders' => 'receipt_long', 'Products' => 'inventory_2', 'Customers' => 'group', 'Roles' => 'admin_panel_settings'];
                                $icon = $groupIcons[$groupName] ?? 'settings';
                                ?>
                                <span class="material-symbols-outlined text-sm text-emerald-600"><?= $icon ?></span>
                                <?= htmlspecialchars($groupName) ?>
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <?php foreach ($perms as $perm): ?>
                                <label class="flex items-start gap-3 p-2.5 rounded-lg hover:bg-white transition-colors cursor-pointer group/perm">
                                    <input type="checkbox" class="perm-checkbox mt-0.5 accent-emerald-600 w-4 h-4 rounded" value="<?= $perm['id'] ?>" data-slug="<?= $perm['slug'] ?>">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 group-hover/perm:text-emerald-700 transition-colors"><?= htmlspecialchars($perm['name']) ?></p>
                                        <p class="text-[11px] text-gray-500 leading-snug"><?= htmlspecialchars($perm['description']) ?></p>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 shrink-0">
                <button onclick="closeRoleModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">Cancel</button>
                <button onclick="saveRole()" id="modal-save-btn" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                    Create Role
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Role-Permissions data for JS -->
<script>
const rolePermissionsMap = <?= json_encode($rolePermsMap) ?>;

function openCreateRoleModal() {
    document.getElementById('modal-role-id').value = '';
    document.getElementById('modal-role-name').value = '';
    document.getElementById('modal-role-desc').value = '';
    document.getElementById('modal-title').textContent = 'Create New Role';
    document.getElementById('modal-save-btn').textContent = 'Create Role';
    deselectAllPerms();
    document.getElementById('role-modal').classList.remove('hidden');
}

function openEditRoleModal(roleId) {
    const card = document.getElementById('role-card-' + roleId);
    const name = card.querySelector('h4').textContent;
    const desc = card.querySelector('p.text-xs').textContent;
    
    document.getElementById('modal-role-id').value = roleId;
    document.getElementById('modal-role-name').value = name;
    document.getElementById('modal-role-desc').value = desc === 'No description' ? '' : desc;
    document.getElementById('modal-title').textContent = 'Edit Role: ' + name;
    document.getElementById('modal-save-btn').textContent = 'Save Changes';
    
    // Set permission checkboxes
    deselectAllPerms();
    const permIds = rolePermissionsMap[roleId] || [];
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.checked = permIds.includes(parseInt(cb.value));
    });
    
    document.getElementById('role-modal').classList.remove('hidden');
}

function closeRoleModal() {
    document.getElementById('role-modal').classList.add('hidden');
}

function selectAllPerms() {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = true);
}

function deselectAllPerms() {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
}

async function saveRole() {
    const roleId = document.getElementById('modal-role-id').value;
    const name = document.getElementById('modal-role-name').value.trim();
    const description = document.getElementById('modal-role-desc').value.trim();
    
    if (!name) {
        showAdminToast('Role name is required', 'error');
        return;
    }
    
    const permissionIds = [];
    document.querySelectorAll('.perm-checkbox:checked').forEach(cb => {
        permissionIds.push(parseInt(cb.value));
    });
    
    const action = roleId ? 'update' : 'create';
    const body = { action, name, description, permission_ids: permissionIds };
    if (roleId) body.role_id = parseInt(roleId);
    
    try {
        const res = await fetch('../api/admin-roles.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });
        const data = await res.json();
        if (data.success) {
            showAdminToast(roleId ? 'Role updated successfully' : 'Role created successfully', 'success');
            closeRoleModal();
            setTimeout(() => window.location.reload(), 800);
        } else {
            showAdminToast(data.message || 'Failed to save role', 'error');
        }
    } catch (err) {
        showAdminToast('Network error: ' + err.message, 'error');
    }
}

async function deleteRole(roleId, roleName) {
    if (!confirm(`Are you sure you want to delete the "${roleName}" role? Users with this role will lose their admin access.`)) return;
    
    try {
        const res = await fetch('../api/admin-roles.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'delete', role_id: roleId })
        });
        const data = await res.json();
        if (data.success) {
            showAdminToast('Role deleted', 'success');
            setTimeout(() => window.location.reload(), 800);
        } else {
            showAdminToast(data.message || 'Failed to delete role', 'error');
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
