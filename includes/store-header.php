<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($pageTitle ?? 'RegrowthX - Extra Strength 5% Minoxidil Hair Regrowth Serum') ?></title>
  
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          },
          colors: {
            brand: {
              dark: '#0f2415',
              primary: '#3a6332',
              light: '#72a048',
              bg: '#f8f7f2',
            }
          }
        }
      }
    };
  </script>

  <style>
    html { scroll-behavior: smooth; }
    body { background-color: #f8f7f2; overflow-x: hidden; }
    .hero-gradient { background: radial-gradient(circle at right center, #1b3a20 0%, #0d1a10 100%); }
    .glass-pill { background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.15); }
    .cart-drawer { transform: translateX(100%); transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
    .cart-drawer.open { transform: translateX(0); }
    .cart-overlay { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
    .cart-overlay.open { opacity: 1; pointer-events: auto; }
    .auth-modal-bg { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
    .auth-modal-bg.open { opacity: 1; pointer-events: auto; }
    .auth-modal-content { transform: translateY(30px) scale(0.95); opacity: 0; transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
    .auth-modal-bg.open .auth-modal-content { transform: translateY(0) scale(1); opacity: 1; }
    .toast-container { position: fixed; top: 90px; right: 20px; z-index: 9999; }
    .toast { transform: translateX(120%); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    .toast.show { transform: translateX(0); }
  </style>
</head>

<body class="font-sans text-gray-800 antialiased" id="home">

<!-- HEADER -->
<header class="fixed w-full top-0 z-50 bg-brand-bg/90 backdrop-blur-md border-b border-gray-200 transition-all duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
    <!-- Logo -->
    <a class="flex items-center gap-2 group transition-transform duration-300 hover:scale-105" href="index.php">
      <div class="text-2xl font-bold tracking-tight text-gray-900">
        <img src="img/logo.jpeg" alt="RegrowthX" class="h-8 sm:h-10 w-auto object-contain">
      </div>
      <div class="text-[10px] uppercase font-bold tracking-widest text-brand-light leading-none mt-1 hidden sm:block">
        Hair Growth<br/>Serum
      </div>
    </a>
    
    <!-- Desktop Navigation -->
    <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-700">
      <a class="hover:text-brand-primary transition-colors duration-200 relative py-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-brand-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform" href="index.php">Home</a>
      <a class="hover:text-brand-primary transition-colors duration-200 relative py-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-brand-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform" href="products.php">Products</a>
      <a class="hover:text-brand-primary transition-colors duration-200 relative py-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-brand-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform" href="index.php#about">About</a>
      <a class="hover:text-brand-primary transition-colors duration-200 relative py-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-brand-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform" href="contact.php">Contact Us</a>
      
      <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer'): ?>
      <a class="hover:text-brand-primary transition-colors duration-200 relative py-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-brand-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform" href="profile.php">My Profile</a>
      <?php endif; ?>
    </nav>
    
    <!-- Actions -->
    <div class="flex items-center gap-3">
      <!-- Cart Button -->
      <button onclick="toggleCartDrawer()" class="relative p-2 text-gray-600 hover:text-brand-primary transition-all duration-300 hover:scale-110 active:scale-95 cursor-pointer" aria-label="Cart" id="nav-cart-btn">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <span id="cart-badge-count" class="absolute -top-0.5 -right-0.5 bg-emerald-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">0</span>
      </button>

      <!-- Auth Button (dynamic) -->
      <div id="auth-nav-container">
        <button onclick="openAuthModal()" id="nav-login-btn" class="hidden sm:inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-primary hover:bg-brand-primary/90 rounded-full transition-all duration-300 hover:scale-105 hover:shadow-md active:scale-95 cursor-pointer">
          <span class="material-symbols-outlined text-base">person</span>
          Login / Sign Up
        </button>
        <!-- Logged in state -->
        <div id="nav-user-info" class="hidden items-center gap-2">
          <button onclick="toggleUserMenu()" class="flex items-center gap-2 px-3 py-2 rounded-full bg-emerald-50 hover:bg-emerald-100 transition-all text-sm font-semibold text-gray-800 cursor-pointer">
            <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold" id="nav-user-avatar">U</div>
            <span id="nav-user-name" class="hidden sm:inline max-w-[100px] truncate">User</span>
            <span class="material-symbols-outlined text-sm text-gray-500">expand_more</span>
          </button>
          <!-- User Dropdown -->
          <div id="user-dropdown" class="hidden absolute right-4 top-16 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50">
            <div class="px-4 py-2 border-b border-gray-100">
              <p class="text-xs text-gray-500">Signed in as</p>
              <p id="dropdown-email" class="text-sm font-semibold text-gray-900 truncate">user@email.com</p>
            </div>
            <button onclick="window.location.href='profile.php'" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors flex items-center gap-2 cursor-pointer">
              <span class="material-symbols-outlined text-base">person</span> My Profile
            </button>
            <button onclick="window.location.href='orders.php'" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors flex items-center gap-2 border-b border-gray-100 cursor-pointer">
              <span class="material-symbols-outlined text-base">shopping_bag</span> My Orders
            </button>
            <button onclick="handleLogout()" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2 cursor-pointer">
              <span class="material-symbols-outlined text-base">logout</span> Logout
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Button -->
      <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-gray-600 hover:text-brand-primary cursor-pointer" id="mobile-menu-btn">
        <span class="material-symbols-outlined text-2xl">menu</span>
      </button>
    </div>
  </div>

  <!-- Mobile Navigation Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg">
    <div class="px-4 py-4 space-y-1">
      <a href="index.php" class="block px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">Home</a>
      <a href="products.php" class="block px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">Products</a>
      <a href="index.php#about" class="block px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">About</a>
      <a href="contact.php" class="block px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">Contact Us</a>
      <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer'): ?>
      <a href="profile.php" class="block px-4 py-3 text-sm font-semibold text-emerald-600 hover:bg-emerald-50 rounded-xl transition-colors">My Profile</a>
      <?php endif; ?>
      <div class="border-t border-gray-100 pt-2 mt-2">
        <button onclick="openAuthModal(); toggleMobileMenu();" id="mobile-login-btn" class="w-full px-4 py-3 text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors flex items-center gap-2 cursor-pointer">
          <span class="material-symbols-outlined text-base">person</span> Login / Sign Up
        </button>
      </div>
    </div>
  </div>
</header>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toast-container"></div>

<!-- AUTH MODAL -->
<div id="auth-modal" class="auth-modal-bg fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="auth-modal-content w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white">
      <div>
        <h3 class="text-lg font-bold text-gray-900" id="auth-modal-title">Welcome Back</h3>
        <p class="text-xs text-gray-500" id="auth-modal-subtitle">Login to your account</p>
      </div>
      <button onclick="closeAuthModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-sm transition-all active:scale-90 cursor-pointer">✕</button>
    </div>

    <!-- Login View -->
    <div id="auth-step-login" class="p-6 space-y-4">
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
        <input type="email" id="login-email" placeholder="your@email.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
      </div>
      <div>
        <div class="flex justify-between items-center mb-1.5">
            <label class="block text-sm font-semibold text-gray-700">Password</label>
            <button onclick="switchAuthView('forgot')" class="text-xs text-emerald-600 font-semibold hover:text-emerald-700">Forgot Password?</button>
        </div>
        <input type="password" id="login-password" placeholder="••••••••" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
      </div>
      <p id="login-error" class="text-xs text-red-500 hidden text-center"></p>
      <button onclick="handleLogin()" id="login-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
        Login
      </button>
      <p class="text-sm text-center text-gray-500 mt-4">
        Don't have an account? <button onclick="switchAuthView('signup')" class="text-emerald-600 font-bold hover:underline">Sign up</button>
      </p>
    </div>

    <!-- Signup View -->
    <div id="auth-step-signup" class="p-6 space-y-4 hidden h-[70vh] overflow-y-auto">
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
        <input type="text" id="signup-name" placeholder="John Doe" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
      </div>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
        <input type="email" id="signup-email" placeholder="your@email.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
      </div>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
        <input type="tel" id="signup-phone" placeholder="10-digit mobile number" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
      </div>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
        <input type="password" id="signup-password" placeholder="At least 8 chars, 1 uppercase, 1 number, 1 special" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
        <p class="text-[10px] text-gray-400 mt-1">Must contain uppercase, lowercase, number, and special character.</p>
      </div>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
        <input type="password" id="signup-confirm-password" placeholder="Re-enter password" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
      </div>
      <p id="signup-error" class="text-xs text-red-500 hidden text-center"></p>
      <button onclick="handleSignup()" id="signup-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
        Create Account
      </button>
      <p class="text-sm text-center text-gray-500 mt-4 pb-4">
        Already have an account? <button onclick="switchAuthView('login')" class="text-emerald-600 font-bold hover:underline">Login</button>
      </p>
    </div>

    <!-- Signup OTP Verify View -->
    <div id="auth-step-signup-verify" class="p-6 space-y-4 hidden">
      <p class="text-sm text-gray-600 text-center mb-2">We've sent a 6-digit code to your email. Enter it below to verify your account.</p>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Verification Code</label>
        <input type="text" id="signup-otp" placeholder="123456" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm text-center tracking-[0.3em] font-bold focus:border-emerald-500 outline-none transition-all">
      </div>
      <p id="signup-verify-error" class="text-xs text-red-500 hidden text-center"></p>
      <button onclick="handleSignupVerify()" id="signup-verify-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
        Verify & Create Account
      </button>
      <div class="text-center mt-4">
        <button onclick="switchAuthView('signup')" class="text-sm text-gray-500 hover:text-gray-800 font-semibold flex items-center justify-center gap-1 mx-auto cursor-pointer">
          <span class="material-symbols-outlined text-sm">arrow_back</span> Back
        </button>
      </div>
    </div>

    <!-- Forgot Password View -->
    <div id="auth-step-forgot" class="p-6 space-y-4 hidden">
      <p class="text-sm text-gray-600 text-center mb-2">Enter your email address and we'll send you a reset code.</p>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
        <input type="email" id="forgot-email" placeholder="your@email.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
      </div>
      <p id="forgot-error" class="text-xs text-red-500 hidden text-center"></p>
      <button onclick="handleForgotPassword()" id="forgot-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
        Send Reset Code
      </button>
      <div class="text-center mt-4">
        <button onclick="switchAuthView('login')" class="text-sm text-gray-500 hover:text-gray-800 font-semibold flex items-center justify-center gap-1 mx-auto cursor-pointer">
          <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Login
        </button>
      </div>
    </div>

    <!-- Reset Password View -->
    <div id="auth-step-reset" class="p-6 space-y-4 hidden">
      <p class="text-sm text-gray-600 text-center mb-2">Enter the reset code sent to your email.</p>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Reset Code</label>
        <input type="text" id="reset-code" placeholder="123456" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm text-center tracking-[0.3em] font-bold focus:border-emerald-500 outline-none transition-all">
      </div>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
        <input type="password" id="reset-password" placeholder="Min. 6 characters" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
      </div>
      <p id="reset-error" class="text-xs text-red-500 hidden text-center"></p>
      <button onclick="handleResetPassword()" id="reset-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
        Save New Password
      </button>
    </div>
  </div>
</div>

<!-- CART DRAWER -->
<div id="cart-overlay" class="cart-overlay fixed inset-0 z-[55] bg-black/40 backdrop-blur-sm" onclick="closeCartDrawer()"></div>
<div id="cart-drawer" class="cart-drawer fixed top-0 right-0 z-[56] w-full max-w-md h-full bg-white shadow-2xl flex flex-col">
  <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white shrink-0">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-emerald-600">shopping_cart</span>
      <h3 class="text-lg font-bold text-gray-900">Your Cart</h3>
      <span id="drawer-item-count" class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">0 items</span>
    </div>
    <button onclick="closeCartDrawer()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-sm transition-all active:scale-90 cursor-pointer">✕</button>
  </div>

  <div class="flex-1 overflow-y-auto px-6 py-4" id="cart-items-container">
    <div id="cart-empty-state" class="flex flex-col items-center justify-center h-full text-center py-12">
      <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
        <span class="material-symbols-outlined text-4xl text-gray-400">shopping_cart</span>
      </div>
      <h4 class="text-lg font-bold text-gray-900 mb-1">Your cart is empty</h4>
      <p class="text-sm text-gray-500 mb-6">Looks like you haven't added any products yet.</p>
      <a href="products.php" onclick="closeCartDrawer()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-3 rounded-full text-sm transition-all hover:shadow-lg active:scale-95 inline-block">
        Browse Products
      </a>
    </div>
    <div id="cart-items-list" class="space-y-4 hidden"></div>
  </div>

  <div id="cart-footer" class="hidden shrink-0 border-t border-gray-100 bg-white px-6 py-4 space-y-3">
    <div class="flex justify-between text-sm">
      <span class="text-gray-500">Subtotal</span>
      <span id="cart-subtotal" class="font-bold text-gray-900">$0.00</span>
    </div>
    <div class="flex justify-between text-sm">
      <span class="text-gray-500">Shipping</span>
      <span class="font-semibold text-emerald-600">FREE</span>
    </div>
    <div class="border-t border-gray-100 pt-3 flex justify-between items-baseline">
      <span class="text-base font-bold text-gray-900">Total</span>
      <span id="cart-total" class="text-2xl font-extrabold text-emerald-700">$0.00</span>
    </div>
    <button onclick="proceedToCheckout()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg active:scale-95 cursor-pointer flex items-center justify-center gap-2 text-base">
      <span class="material-symbols-outlined">lock</span>
      Proceed to Checkout
    </button>
  </div>
</div>
