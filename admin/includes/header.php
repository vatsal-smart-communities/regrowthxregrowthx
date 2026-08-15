<?php
// admin/includes/header.php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../config/db.php';

$currentPage = basename($_SERVER['PHP_SELF']);
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
            <h1 class="text-xl font-bold tracking-tight text-white">Regrowth<span class="text-emerald-500">X</span> Admin</h1>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'index.php' ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <a href="orders.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'orders.php' ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">receipt_long</span> Orders
            </a>
            <a href="products.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'products.php' ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">inventory_2</span> Products
            </a>
            <a href="users.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'users.php' ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">group</span> Customers
            </a>
        </nav>
        
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold">A</div>
                <div>
                    <p class="text-sm font-semibold">Admin User</p>
                    <p class="text-xs text-gray-400 truncate"><?= htmlspecialchars($_SESSION['user_email']) ?></p>
                </div>
            </div>
            <button onclick="handleAdminLogout()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-400 bg-red-400/10 hover:bg-red-400/20 rounded-xl transition-colors">
                <span class="material-symbols-outlined text-base">logout</span> Logout
            </button>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        <!-- Top bar (mobile menu trigger if needed, not implemented for simplicity) -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center px-8 shrink-0 shadow-sm z-10">
            <h2 class="text-lg font-semibold text-gray-800 capitalize"><?= str_replace('.php', '', $currentPage) ?></h2>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-8">
            <!-- Toast Container -->
            <div id="admin-toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>
