<!-- FOOTER -->
<footer class="bg-[#0b1b10] text-white mt-auto">
  <div class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">

      <div>
        <a href="index.php" class="text-3xl font-bold mb-4 block">
          <img src="img/logo.jpeg" alt="RegrowthX" class="h-8 sm:h-10 w-auto object-contain bg-white rounded p-1">
        </a>
        <p class="text-gray-300 text-sm leading-relaxed">
          Dermatologist-recommended 5% Minoxidil extra strength topical solution. Formulated to reactivate hair follicles, reduce shedding, and promote thicker hair growth for men.
        </p>
      </div>

      <div>
        <h3 class="text-sm font-bold uppercase tracking-wider mb-5 text-brand-light">Navigation</h3>
        <ul class="space-y-3 text-sm text-gray-300">
          <li><a href="index.php" class="hover:text-white transition">Home</a></li>
          <li><a href="products.php" class="hover:text-white transition">Products Catalog</a></li>
          <li><a href="index.php#about" class="hover:text-white transition">About Formula</a></li>
          <li><a href="contact.php" class="hover:text-white transition">Contact Us</a></li>
        </ul>
      </div>

      <div>
        <h3 class="text-sm font-bold uppercase tracking-wider mb-5 text-brand-light">Formula Standards</h3>
        <ul class="space-y-3 text-sm text-gray-300">
          <li>✓ 5% Minoxidil USP Active</li>
          <li>✓ Unscented & Non-Greasy</li>
          <li>✓ Fast Absorbing Solution</li>
          <li>✓ Quality Tested</li>
          <li>✓ 30-Day Money Back Guarantee</li>
        </ul>
      </div>

      <div>
        <h3 class="text-sm font-bold uppercase tracking-wider mb-5 text-brand-light">Customer Support</h3>
        <div class="space-y-3 text-sm text-gray-300">
          <p>Email: <a href="mailto:rickw@nimexgrp.com" class="hover:underline text-white">rickw@nimexgrp.com</a></p>
          <p>Phone: <a href="tel:17184387400" class="hover:underline text-white">+1 (718) 438-7400</a></p>
          <p>Hours: Mon - Sat (9:00 AM - 6:00 PM EST)</p>
        </div>
        <div class="flex gap-4 mt-6">
          <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-brand-primary flex items-center justify-center transition" aria-label="Facebook">
            <i class="fab fa-facebook-f text-sm"></i>
          </a>
          <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-brand-primary flex items-center justify-center transition" aria-label="Instagram">
            <i class="fab fa-instagram text-sm"></i>
          </a>
          <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-brand-primary flex items-center justify-center transition" aria-label="Twitter">
            <i class="fab fa-x-twitter text-sm"></i>
          </a>
        </div>
      </div>

    </div>

    <div class="border-t border-white/10 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400">
      <p>© 2026 <span class="font-semibold text-white">RegrowthX</span>. All rights reserved. Results may vary by individual.</p>
      <div class="flex gap-6">
        <a href="#" class="hover:text-white transition">Privacy Policy</a>
        <a href="#" class="hover:text-white transition">Terms of Service</a>
        <a href="#" class="hover:text-white transition">Shipping Policy</a>
      </div>
    </div>
  </div>
</footer>

<!-- FLOATING WHATSAPP CHAT WIDGET -->
<a href="https://wa.me/17184387400?text=Hi%20RegrowthX%20Support!%20I%20have%20a%20question%20about%20your%20hair%20regrowth%20products." target="_blank" rel="noopener noreferrer" class="fixed bottom-6 right-6 z-40 group flex items-center gap-2 cursor-pointer" aria-label="Chat on WhatsApp">
  <span class="hidden sm:inline-block bg-white text-gray-800 text-xs font-bold px-3 py-2 rounded-2xl shadow-xl border border-gray-100 group-hover:scale-105 transition-all duration-300">
    Need Help? Chat on WhatsApp 👋
  </span>
  <div class="relative w-14 h-14 rounded-full bg-[#25D366] hover:bg-[#20ba5a] text-white flex items-center justify-center shadow-2xl transition-transform duration-300 group-hover:scale-110 active:scale-95">
    <span class="absolute inset-0 rounded-full bg-[#25D366] animate-ping opacity-30"></span>
    <i class="fab fa-whatsapp text-3xl relative z-10"></i>
  </div>
</a>

<!-- GLOBAL JAVASCRIPT HELPER SCRIPTS -->
<script>
  let currentUser = null;
  let resetEmailTarget = '';

  function formatINR(amount) {
    return '$' + Number(amount).toFixed(2);
  }
  function formatUSD(amount) {
    return '$' + Number(amount).toFixed(2);
  }

  function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    const colors = { success: 'bg-emerald-600', error: 'bg-red-600', info: 'bg-gray-900' };
    const icons = { success: 'check_circle', error: 'error', info: 'info' };
    toast.className = `toast ${colors[type] || colors.info} text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-2 text-sm font-semibold mb-2 min-w-[250px]`;
    toast.innerHTML = `<span class="material-symbols-outlined text-lg">${icons[type] || 'info'}</span> ${message}`;
    container.appendChild(toast);
    requestAnimationFrame(() => { requestAnimationFrame(() => { toast.classList.add('show'); }); });
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 400);
    }, 3000);
  }

  /* ===== AUTH MODAL LOGIC ===== */
  function openAuthModal() {
    const modal = document.getElementById('auth-modal');
    if (modal) {
      modal.classList.add('open');
      document.body.style.overflow = 'hidden';
      const emailInput = document.getElementById('login-email');
      if (emailInput) emailInput.focus();
    }
  }

  function closeAuthModal() {
    const modal = document.getElementById('auth-modal');
    if (modal) {
      modal.classList.remove('open');
      document.body.style.overflow = '';
      switchAuthView('login');
    }
  }

  function switchAuthView(view) {
    const views = ['login', 'signup', 'forgot', 'reset', 'signup-verify'];
    views.forEach(v => {
      const el = document.getElementById('auth-step-' + v);
      if (el) el.classList.add('hidden');
    });

    const activeEl = document.getElementById('auth-step-' + view);
    if (activeEl) activeEl.classList.remove('hidden');

    const titleEl = document.getElementById('auth-modal-title');
    const subtitleEl = document.getElementById('auth-modal-subtitle');
    
    if (view === 'login') {
      if (titleEl) titleEl.innerText = 'Welcome Back';
      if (subtitleEl) subtitleEl.innerText = 'Login to your account';
    } else if (view === 'signup') {
      if (titleEl) titleEl.innerText = 'Create Account';
      if (subtitleEl) subtitleEl.innerText = 'Join RegrowthX today';
    } else if (view === 'forgot') {
      if (titleEl) titleEl.innerText = 'Reset Password';
      if (subtitleEl) subtitleEl.innerText = 'We will send a reset code to your email';
    } else if (view === 'reset') {
      if (titleEl) titleEl.innerText = 'Set New Password';
      if (subtitleEl) subtitleEl.innerText = 'Enter the code and your new password';
    } else if (view === 'signup-verify') {
      if (titleEl) titleEl.innerText = 'Verify Your Email';
      if (subtitleEl) subtitleEl.innerText = 'Enter the 6-digit code sent to your email';
    }
    
    ['login-error', 'signup-error', 'forgot-error', 'reset-error', 'signup-verify-error'].forEach(id => {
      const err = document.getElementById(id);
      if (err) err.classList.add('hidden');
    });
  }

  async function handleLogin() {
    const email = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;
    const errorEl = document.getElementById('login-error');
    const btn = document.getElementById('login-btn');

    if (!email || !password) {
      errorEl.innerText = 'Please enter both email and password';
      errorEl.classList.remove('hidden');
      return;
    }
    errorEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Logging in...';

    try {
      const res = await fetch('api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      const data = await res.json();

      if (data.success) {
        if (data.user.role === 'admin') {
            window.location.href = 'admin/index.php';
            return;
        }
        currentUser = data.user;
        updateAuthUI(true);
        closeAuthModal();
        showToast('Logged in successfully!', 'success');
        if (window._pendingCheckout) {
          window._pendingCheckout = false;
          window.location.href = 'checkout.php';
        }
      } else {
        errorEl.innerText = data.message;
        errorEl.classList.remove('hidden');
      }
    } catch (err) {
      errorEl.innerText = 'Network error. Please try again.';
      errorEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.innerHTML = 'Login';
  }

  // Global object to store signup data before verification
  let pendingSignupData = null;

  async function handleSignup() {
    const name = document.getElementById('signup-name').value.trim();
    const email = document.getElementById('signup-email').value.trim();
    const phone = document.getElementById('signup-phone').value.trim();
    const password = document.getElementById('signup-password').value;
    const confirmPassword = document.getElementById('signup-confirm-password').value;
    const errorEl = document.getElementById('signup-error');
    const btn = document.getElementById('signup-btn');

    if (!name || !email || !phone || !password || !confirmPassword) {
      errorEl.innerText = 'All fields are required';
      errorEl.classList.remove('hidden');
      return;
    }
    
    if (password !== confirmPassword) {
      errorEl.innerText = 'Passwords do not match';
      errorEl.classList.remove('hidden');
      return;
    }

    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordPattern.test(password)) {
      errorEl.innerText = 'Password does not meet strict requirements';
      errorEl.classList.remove('hidden');
      return;
    }

    errorEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Verifying Email...';

    try {
      const res = await fetch('api/send-signup-otp.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
      });
      const data = await res.json();

      if (data.success) {
        pendingSignupData = { full_name: name, email, phone, password };
        switchAuthView('signup-verify');
        showToast('Verification code sent to your email', 'success');
      } else {
        errorEl.innerText = data.message;
        errorEl.classList.remove('hidden');
      }
    } catch (err) {
      errorEl.innerText = 'Network error. Please try again.';
      errorEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.innerHTML = 'Create Account';
  }

  async function handleSignupVerify() {
    const otp = document.getElementById('signup-otp').value.trim();
    const errorEl = document.getElementById('signup-verify-error');
    const btn = document.getElementById('signup-verify-btn');

    if (!otp) {
      errorEl.innerText = 'Please enter the verification code';
      errorEl.classList.remove('hidden');
      return;
    }

    errorEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Creating Account...';

    try {
      const payload = { ...pendingSignupData, otp };
      const res = await fetch('api/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json();

      if (data.success) {
        currentUser = data.user;
        updateAuthUI(true);
        closeAuthModal();
        showToast('Account created successfully!', 'success');
        if (window._pendingCheckout) {
          window._pendingCheckout = false;
          window.location.href = 'checkout.php';
        }
      } else {
        errorEl.innerText = data.message;
        errorEl.classList.remove('hidden');
      }
    } catch (err) {
      errorEl.innerText = 'Network error. Please try again.';
      errorEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.innerHTML = 'Verify & Create Account';
  }

  async function handleForgotPassword() {
    const email = document.getElementById('forgot-email').value.trim();
    const errorEl = document.getElementById('forgot-error');
    const btn = document.getElementById('forgot-btn');

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      errorEl.innerText = 'Please enter a valid email address';
      errorEl.classList.remove('hidden');
      return;
    }
    errorEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = 'Sending...';

    try {
      const res = await fetch('api/forgot-password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
      });
      const data = await res.json();

      if (data.success) {
        resetEmailTarget = email;
        switchAuthView('reset');
        showToast('Reset code sent to your email', 'success');
      } else {
        errorEl.innerText = data.message;
        errorEl.classList.remove('hidden');
      }
    } catch (err) {
      errorEl.innerText = 'Network error. Please try again.';
      errorEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.innerHTML = 'Send Reset Code';
  }

  async function handleResetPassword() {
    const code = document.getElementById('reset-code').value.trim();
    const password = document.getElementById('reset-password').value;
    const errorEl = document.getElementById('reset-error');
    const btn = document.getElementById('reset-btn');

    if (!code || !password) {
      errorEl.innerText = 'Please enter the code and a new password';
      errorEl.classList.remove('hidden');
      return;
    }
    errorEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = 'Saving...';

    try {
      const res = await fetch('api/reset-password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: resetEmailTarget, code, password })
      });
      const data = await res.json();

      if (data.success) {
        switchAuthView('login');
        showToast('Password reset successfully! Please log in.', 'success');
      } else {
        errorEl.innerText = data.message;
        errorEl.classList.remove('hidden');
      }
    } catch (err) {
      errorEl.innerText = 'Network error. Please try again.';
      errorEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.innerHTML = 'Save New Password';
  }

  function updateAuthUI(loggedIn) {
    const loginBtn = document.getElementById('nav-login-btn');
    const userInfo = document.getElementById('nav-user-info');
    const mobileLoginBtn = document.getElementById('mobile-login-btn');

    if (loggedIn && currentUser) {
      if (loginBtn) loginBtn.style.display = 'none';
      if (userInfo) {
        userInfo.classList.remove('hidden');
        userInfo.classList.add('flex');
      }
      const initial = (currentUser.name || currentUser.email || 'U').charAt(0).toUpperCase();
      const avatar = document.getElementById('nav-user-avatar');
      const name = document.getElementById('nav-user-name');
      const email = document.getElementById('dropdown-email');
      
      if (avatar) avatar.innerText = initial;
      if (name) name.innerText = currentUser.name || currentUser.email.split('@')[0];
      if (email) email.innerText = currentUser.email;
      if (mobileLoginBtn) mobileLoginBtn.classList.add('hidden');
    } else {
      if (loginBtn) loginBtn.style.display = '';
      if (userInfo) {
        userInfo.classList.add('hidden');
        userInfo.classList.remove('flex');
      }
      if (mobileLoginBtn) mobileLoginBtn.classList.remove('hidden');
    }
  }

  function toggleUserMenu() {
    const dd = document.getElementById('user-dropdown');
    if (dd) dd.classList.toggle('hidden');
  }

  document.addEventListener('click', (e) => {
    const dropdown = document.getElementById('user-dropdown');
    const container = document.getElementById('nav-user-info');
    if (dropdown && container && !container.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });

  async function handleLogout() {
    try { await fetch('api/logout.php', { method: 'POST' }); } catch(e) {}
    currentUser = null;
    updateAuthUI(false);
    showToast('Logged out successfully', 'info');
    setTimeout(() => { window.location.href = 'index.php'; }, 500);
  }

  async function checkAuthState() {
    try {
      const res = await fetch('api/get-user.php');
      const data = await res.json();
      if (data.logged_in) {
        currentUser = data.user;
        updateAuthUI(true);
      }
    } catch(e) {}
  }

  function toggleMobileMenu() {
    const mm = document.getElementById('mobile-menu');
    if (mm) mm.classList.toggle('hidden');
  }

  function toggleCartDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    if (drawer && overlay) {
      if (drawer.classList.contains('open')) {
        closeCartDrawer();
      } else {
        openCartDrawer();
      }
    }
  }

  function openCartDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    if (drawer && overlay) {
      drawer.classList.add('open');
      overlay.classList.add('open');
      document.body.style.overflow = 'hidden';
      refreshCartUI();
    }
  }

  function closeCartDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    if (drawer && overlay) {
      drawer.classList.remove('open');
      overlay.classList.remove('open');
      document.body.style.overflow = '';
    }
  }

  async function addToCartAPI(variantId, quantity) {
    try {
      const res = await fetch('api/cart-add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ variant_id: variantId, quantity: quantity || 1 })
      });
      const data = await res.json();
      if (data.success) {
        updateCartBadge(data.item_count);
        showToast('Added to cart! ✓', 'success');
        openCartDrawer();
      } else {
        showToast(data.message || 'Failed to add to cart', 'error');
      }
    } catch (e) { showToast('Network error', 'error'); }
  }

  async function updateCartItemAPI(variantId, quantity) {
    try {
      const res = await fetch('api/cart-update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ variant_id: variantId, quantity })
      });
      const data = await res.json();
      if (data.success) {
        updateCartBadge(data.item_count);
        renderCartItems(data.cart, data.cart_total, data.item_count);
      }
      return data;
    } catch (err) {
      showToast('Network error', 'error');
      return null;
    }
  }

  async function removeCartItemAPI(variantId) {
    try {
      const res = await fetch('api/cart-remove.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ variant_id: variantId })
      });
      const data = await res.json();
      if (data.success) {
        updateCartBadge(data.item_count);
        renderCartItems(data.cart, data.cart_total, data.item_count);
        showToast('Item removed', 'info');
      }
      return data;
    } catch (err) {
      showToast('Network error', 'error');
      return null;
    }
  }

  function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge-count');
    if (badge) {
      badge.innerText = count;
      if (count > 0) badge.classList.remove('hidden');
      else badge.classList.add('hidden');
    }
  }

  function renderCartItems(cart, cartTotal, itemCount) {
    const listEl = document.getElementById('cart-items-list');
    const emptyEl = document.getElementById('cart-empty-state');
    const footerEl = document.getElementById('cart-footer');
    const countEl = document.getElementById('drawer-item-count');

    if (countEl) countEl.innerText = itemCount + (itemCount === 1 ? ' item' : ' items');

    if (!cart || cart.length === 0 || itemCount === 0) {
      if (listEl) {
        listEl.classList.add('hidden');
        listEl.innerHTML = '';
      }
      if (emptyEl) emptyEl.classList.remove('hidden');
      if (footerEl) footerEl.classList.add('hidden');
      return;
    }

    if (emptyEl) emptyEl.classList.add('hidden');
    if (listEl) listEl.classList.remove('hidden');
    if (footerEl) {
      footerEl.classList.remove('hidden');
      footerEl.classList.add('flex', 'flex-col');
    }

    let html = '';
    const cartArr = Array.isArray(cart) ? cart : Object.values(cart);
    
    cartArr.forEach(item => {
      const itemName = item.title || item.item_name || 'RegrowthX Minoxidil 5%';
      const unitPrice = item.price_inr || item.unit_price || 0;
      const totalPrice = item.total_price || (unitPrice * item.quantity);
      const imagePath = item.image_path || 'img/product-box-bottle.jpg';
      const variantName = item.variant_name || '';

      html += `
        <div class="flex gap-4 p-3 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-sm transition-shadow" data-variant-id="${item.variant_id}">
          <div class="w-20 h-20 rounded-xl bg-[#0d1e12] flex items-center justify-center shrink-0 overflow-hidden">
            <img src="${imagePath}" alt="${variantName}" class="max-h-full max-w-full object-contain rounded-lg">
          </div>
          <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-gray-900 truncate">${itemName}</h4>
            <p class="text-xs text-gray-500 mt-0.5">${variantName}</p>
            <div class="flex items-center justify-between mt-2">
              <div class="inline-flex items-center border border-gray-200 rounded-lg bg-white">
                <button onclick="updateCartItemAPI(${item.variant_id}, ${item.quantity - 1})" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg text-sm font-bold cursor-pointer">−</button>
                <span class="w-8 text-center text-sm font-bold text-gray-900">${item.quantity}</span>
                <button onclick="updateCartItemAPI(${item.variant_id}, ${item.quantity + 1})" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg text-sm font-bold cursor-pointer">+</button>
              </div>
              <div class="text-right">
                <p class="text-sm font-bold text-gray-900">${formatINR(totalPrice)}</p>
                ${item.quantity > 1 ? `<p class="text-[10px] text-gray-400">${formatINR(unitPrice)} each</p>` : ''}
              </div>
            </div>
          </div>
          <button onclick="removeCartItemAPI(${item.variant_id})" class="self-start p-1 text-gray-400 hover:text-red-500 transition-colors cursor-pointer" title="Remove">
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>
      `;
    });
    if (listEl) listEl.innerHTML = html;

    const subTotalEl = document.getElementById('cart-subtotal');
    const totEl = document.getElementById('cart-total');
    if (subTotalEl) subTotalEl.innerText = formatINR(cartTotal);
    if (totEl) totEl.innerText = formatINR(cartTotal);
  }

  async function refreshCartUI() {
    try {
      const res = await fetch('api/cart-get.php');
      const data = await res.json();
      if (data.success) {
        updateCartBadge(data.item_count);
        renderCartItems(data.cart, data.cart_total, data.item_count);
      }
    } catch(e) {}
  }

  function proceedToCheckout() {
    closeCartDrawer();
    if (!currentUser) {
      window._pendingCheckout = true;
      openAuthModal();
      showToast('Please login to continue checkout', 'info');
    } else {
      window.location.href = 'checkout.php';
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    checkAuthState();
    refreshCartUI();
  });
</script>
</body>
</html>
