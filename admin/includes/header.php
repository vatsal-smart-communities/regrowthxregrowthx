<?php
// admin/includes/header.php
require_once __DIR__ . '/auth.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$userInitial = strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1));
$roleName = $_SESSION['role_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RegrowthX Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f3f4f6; }
        .text-brand-primary { color: #059669; }
        .bg-brand-primary { background-color: #059669; }
        .hover-bg-brand-primary:hover { background-color: #047857; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col h-full shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-gray-800">
            <img src="../img/logo.jpeg" alt="RegrowthX Admin" class="h-8 w-auto object-contain bg-white rounded p-1">
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <!-- Dashboard - always visible to any admin role -->
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'index.php' ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>

            <?php if (hasPermission('view_orders')): ?>
            <a href="orders.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'orders.php' ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">receipt_long</span> Orders
            </a>
            <?php endif; ?>

            <?php if (hasPermission('view_products')): ?>
            <a href="products.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= in_array($currentPage, ['products.php', 'add-product.php', 'edit-product.php']) ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">inventory_2</span> Products
            </a>
            <?php endif; ?>

            <?php if (hasPermission('view_users')): ?>
            <a href="users.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= in_array($currentPage, ['users.php', 'view-user.php']) ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">group</span> Customers
            </a>
            <?php endif; ?>

            <?php if (hasPermission('view_roles')): ?>
            <div class="pt-4 mt-4 border-t border-gray-800">
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">System</p>
                <a href="admin-users.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'admin-users.php' ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                    <span class="material-symbols-outlined">shield_person</span> Admin Users
                </a>
                <a href="roles.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'roles.php' ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                    <span class="material-symbols-outlined">admin_panel_settings</span> Roles & Permissions
                </a>
            </div>
            <?php endif; ?>
        </nav>
        
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold"><?= $userInitial ?></div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
                    <p class="text-[11px] text-emerald-400 font-medium truncate"><?= htmlspecialchars($roleName) ?></p>
                </div>
            </div>
            <button onclick="handleAdminLogout()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-400 bg-red-400/10 hover:bg-red-400/20 rounded-xl transition-colors">
                <span class="material-symbols-outlined text-base">logout</span> Logout
            </button>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        <!-- Top bar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shrink-0 shadow-sm z-10">
            <h2 class="text-lg font-semibold text-gray-800 capitalize"><?= str_replace(['.php', '-'], ['', ' '], $currentPage) ?></h2>
            <span class="text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1.5 rounded-full"><?= htmlspecialchars($roleName) ?></span>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-8">
            <!-- Toast Container -->
            <div id="admin-toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>
