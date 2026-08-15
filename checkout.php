<?php 
$pageTitle = "Secure Checkout - RegrowthX";
require_once __DIR__ . '/includes/store-header.php'; 
?>

    <style>
        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 3px solid #ffffff;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #3a6332;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #3a6332;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        .success-checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #3a6332;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .success-checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }
        @keyframes stroke { 100% { stroke-dashoffset: 0; } }
        @keyframes scale { 0%, 100% { transform: none; } 50% { transform: scale3d(1.1, 1.1, 1); } }
        @keyframes fill { 100% { box-shadow: inset 0px 0px 0px 30px rgba(58, 99, 50, 0.1); } }
    </style>

    <!-- Main Content -->
    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full pt-28">
        <!-- Empty Cart Message -->
        <div id="empty-cart-message" class="hidden text-center py-20 bg-white rounded-2xl shadow-sm">
            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">shopping_cart</span>
            <h2 class="text-2xl font-bold text-brand-dark mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-6">Looks like you haven't added anything to your cart yet.</p>
            <a href="index.php" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-brand-primary hover:bg-brand-dark transition-colors duration-200">
                Continue Shopping
            </a>
        </div>

        <!-- Checkout Layout -->
        <div id="checkout-container" class="hidden flex-col lg:flex-row gap-8">
            <!-- Left Column: Form -->
            <div class="lg:w-2/3">
                <form id="checkout-form" class="space-y-6">
                    <!-- Shipping Address Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center">
                            <span class="material-symbols-outlined text-brand-primary mr-2">location_on</span>
                            <h2 class="text-lg font-bold text-brand-dark">Shipping Address</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                    <div class="mt-1">
                                        <input type="text" id="full_name" name="full_name" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm px-4 py-2.5 border">
                                    </div>
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                    <div class="mt-1">
                                        <input type="email" id="email" name="email" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm px-4 py-2.5 border">
                                    </div>
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            +91
                                        </span>
                                        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required placeholder="10-digit number" class="flex-1 block w-full rounded-none rounded-r-lg sm:text-sm border-gray-300 focus:ring-brand-primary focus:border-brand-primary px-4 py-2.5 border">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address Line *</label>
                                    <div class="mt-1">
                                        <textarea id="address" name="address" rows="2" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm px-4 py-2.5 border" placeholder="House/Flat No., Building Name, Street"></textarea>
                                    </div>
                                </div>

                                <div class="sm:col-span-2 lg:col-span-1">
                                    <label for="landmark" class="block text-sm font-medium text-gray-700">Landmark (Optional)</label>
                                    <div class="mt-1">
                                        <input type="text" id="landmark" name="landmark" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm px-4 py-2.5 border">
                                    </div>
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                                    <div class="mt-1">
                                        <input type="text" id="city" name="city" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm px-4 py-2.5 border">
                                    </div>
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="state" class="block text-sm font-medium text-gray-700">State / UT *</label>
                                    <div class="mt-1">
                                        <select id="state" name="state" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm px-4 py-2.5 border bg-white">
                                            <option value="" disabled selected>Select State</option>
                                            <optgroup label="States">
                                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                <option value="Assam">Assam</option>
                                                <option value="Bihar">Bihar</option>
                                                <option value="Chhattisgarh">Chhattisgarh</option>
                                                <option value="Goa">Goa</option>
                                                <option value="Gujarat">Gujarat</option>
                                                <option value="Haryana">Haryana</option>
                                                <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                <option value="Jharkhand">Jharkhand</option>
                                                <option value="Karnataka">Karnataka</option>
                                                <option value="Kerala">Kerala</option>
                                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                <option value="Maharashtra">Maharashtra</option>
                                                <option value="Manipur">Manipur</option>
                                                <option value="Meghalaya">Meghalaya</option>
                                                <option value="Mizoram">Mizoram</option>
                                                <option value="Nagaland">Nagaland</option>
                                                <option value="Odisha">Odisha</option>
                                                <option value="Punjab">Punjab</option>
                                                <option value="Rajasthan">Rajasthan</option>
                                                <option value="Sikkim">Sikkim</option>
                                                <option value="Tamil Nadu">Tamil Nadu</option>
                                                <option value="Telangana">Telangana</option>
                                                <option value="Tripura">Tripura</option>
                                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                <option value="Uttarakhand">Uttarakhand</option>
                                                <option value="West Bengal">West Bengal</option>
                                            </optgroup>
                                            <optgroup label="Union Territories">
                                                <option value="Andaman & Nicobar Islands">Andaman & Nicobar Islands</option>
                                                <option value="Chandigarh">Chandigarh</option>
                                                <option value="Dadra & Nagar Haveli and Daman & Diu">Dadra & Nagar Haveli and Daman & Diu</option>
                                                <option value="Delhi">Delhi</option>
                                                <option value="Jammu & Kashmir">Jammu & Kashmir</option>
                                                <option value="Ladakh">Ladakh</option>
                                                <option value="Lakshadweep">Lakshadweep</option>
                                                <option value="Puducherry">Puducherry</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="pincode" class="block text-sm font-medium text-gray-700">Pincode *</label>
                                    <div class="mt-1">
                                        <input type="text" id="pincode" name="pincode" pattern="[0-9]{6}" required placeholder="6-digit code" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm px-4 py-2.5 border">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center">
                            <span class="material-symbols-outlined text-brand-primary mr-2">payment</span>
                            <h2 class="text-lg font-bold text-brand-dark">Payment Method</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <label class="flex items-center justify-between p-4 border rounded-xl border-brand-primary bg-brand-primary/5 cursor-pointer">
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" value="COD" checked class="h-4 w-4 text-brand-primary border-gray-300 focus:ring-brand-primary">
                                    <span class="ml-3 font-medium text-gray-900">Cash on Delivery (COD)</span>
                                </div>
                                <span class="material-symbols-outlined text-gray-400">payments</span>
                            </label>

                            <label class="flex items-center justify-between p-4 border rounded-xl border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed">
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" value="ONLINE" disabled class="h-4 w-4 text-gray-400 border-gray-300">
                                    <span class="ml-3 font-medium text-gray-500">Online Payment</span>
                                    <span class="ml-3 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Coming Soon</span>
                                </div>
                                <span class="material-symbols-outlined text-gray-400">credit_card</span>
                            </label>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" id="submit-btn" class="w-full flex items-center justify-center px-6 py-4 border border-transparent text-lg font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200 shadow-md">
                        <span id="btn-text" class="flex items-center">
                            <span class="material-symbols-outlined mr-2 text-[20px]">lock</span>
                            Place Order
                        </span>
                        <div id="btn-spinner" class="spinner hidden ml-2"></div>
                    </button>
                    <p class="text-xs text-center text-gray-500 mt-3 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[16px] mr-1">shield</span>
                        Your personal information is secure and encrypted
                    </p>
                </form>
            </div>

            <!-- Right Column: Order Summary (Sticky) -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-brand-dark">Order Summary</h2>
                    </div>
                    
                    <div class="p-6">
                        <!-- Cart Items -->
                        <div id="summary-items" class="space-y-4 mb-6 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            <!-- Items will be injected here via JS -->
                            <div class="flex items-center justify-center py-4 text-gray-400">
                                <div class="spinner border-gray-300 border-t-brand-primary w-6 h-6"></div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4 space-y-3">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span id="summary-subtotal">₹0</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Shipping</span>
                                <span class="text-emerald-600 font-medium">FREE</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>GST (18%)</span>
                                <span class="text-emerald-600 font-medium">Included</span>
                            </div>
                            <div class="border-t border-gray-100 pt-4 flex justify-between">
                                <span class="text-base font-bold text-gray-900">Total</span>
                                <span id="summary-total" class="text-2xl font-bold text-brand-dark">₹0</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t border-gray-100">
                        <div class="flex justify-center space-x-6 text-xs text-gray-500">
                            <div class="flex flex-col items-center text-center">
                                <span class="text-lg mb-1">🔒</span>
                                <span>Secure<br>Payment</span>
                            </div>
                            <div class="flex flex-col items-center text-center">
                                <span class="text-lg mb-1">🚚</span>
                                <span>Free<br>Delivery</span>
                            </div>
                            <div class="flex flex-col items-center text-center">
                                <span class="text-lg mb-1">↩️</span>
                                <span>30-Day<br>Returns</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Success Screen -->
        <div id="success-screen" class="hidden max-w-2xl mx-auto bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden text-center p-10 relative">
            <svg class="success-checkmark mb-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="success-checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="success-checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
            
            <h1 class="text-3xl font-bold text-brand-dark mb-2">Order Placed Successfully!</h1>
            <p class="text-gray-500 mb-8">Thank you for your purchase.</p>
            
            <div class="bg-brand-bg rounded-2xl p-6 mb-8 text-left border border-brand-primary/20">
                <p class="text-sm text-gray-500 mb-1">Order Number</p>
                <p id="success-order-number" class="text-xl font-bold text-brand-dark tracking-wide mb-4">#RGX-XXXXXX</p>
                
                <p class="text-sm text-gray-500 mb-1">Amount Paid</p>
                <p id="success-amount" class="text-lg font-semibold text-gray-900 mb-4">₹0</p>
                
                <p class="text-sm text-gray-600 border-t border-gray-200 pt-4 mt-2">
                    You will receive an order confirmation email shortly at <br>
                    <span id="success-email" class="font-medium text-gray-900"></span>
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button disabled class="px-6 py-3 border border-gray-200 text-base font-medium rounded-xl text-gray-400 bg-gray-50 cursor-not-allowed">
                    Track Order
                </button>
                <a href="index.php" class="px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-brand-primary hover:bg-emerald-700 transition-colors">
                    Continue Shopping
                </a>
            </div>
        </div>
    </main>

    <!-- Toast Notification -->
    <div id="toast">An error occurred.</div>

    <!-- Custom CSS for Scrollbar -->
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>

    <!-- JavaScript Logic -->
    <script>
        function formatINR(amount) {
            return '₹' + Number(amount).toLocaleString('en-IN');
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'show';
            setTimeout(() => { toast.className = toast.className.replace('show', ''); }, 3000);
        }

        let cartTotal = 0;

        async function initCheckout() {
            try {
                // Fetch User info to pre-fill
                const userRes = await fetch('api/get-user.php');
                if (userRes.ok) {
                    const userData = await userRes.json();
                    if (userData && userData.email) {
                        document.getElementById('email').value = userData.email;
                    }
                    if (userData && userData.name) {
                        document.getElementById('full_name').value = userData.name;
                    }
                }
            } catch (e) {
                console.warn('Could not fetch user info', e);
            }

            try {
                // Fetch Cart items
                const cartRes = await fetch('api/cart-get.php');
                if (!cartRes.ok) throw new Error('Failed to fetch cart');
                const cartData = await cartRes.json();

                if (!cartData || !cartData.cart || cartData.cart.length === 0) {
                    document.getElementById('checkout-container').classList.add('hidden');
                    document.getElementById('empty-cart-message').classList.remove('hidden');
                    return;
                }

                // Render cart items
                const summaryContainer = document.getElementById('summary-items');
                summaryContainer.innerHTML = '';
                cartTotal = 0;

                cartData.cart.forEach(item => {
                    const itemName = item.title || item.item_name || 'RegrowthX Minoxidil 5%';
                    const unitPrice = item.price_inr || item.unit_price || 0;
                    const totalPrice = item.total_price || (unitPrice * item.quantity);
                    const imagePath = item.image_path || 'img/product-box-bottle.jpg';
                    const variantName = item.variant_name || '';

                    cartTotal += totalPrice;
                    
                    const itemHtml = `
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-16 h-16 border border-gray-200 rounded-lg overflow-hidden bg-[#0d1e12]">
                                <img src="${imagePath}" alt="${variantName}" class="w-full h-full object-contain">
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-medium text-gray-900">${itemName}</h4>
                                <p class="text-xs text-gray-500 mt-1">${variantName}</p>
                                <div class="flex justify-between mt-2">
                                    <span class="text-sm text-gray-500">Qty: ${item.quantity}</span>
                                    <span class="text-sm font-medium text-gray-900">${formatINR(totalPrice)}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    summaryContainer.insertAdjacentHTML('beforeend', itemHtml);
                });

                // Update totals
                document.getElementById('summary-subtotal').textContent = formatINR(cartTotal);
                document.getElementById('summary-total').textContent = formatINR(cartTotal);
                
                // Show checkout layout
                document.getElementById('checkout-container').classList.remove('hidden');
                document.getElementById('checkout-container').classList.add('flex');

            } catch (error) {
                console.error("Error initializing checkout:", error);
                document.getElementById('summary-items').innerHTML = '<p class="text-sm text-red-500 py-4 text-center">Unable to load cart items.</p>';
            }
        }

        // Handle Form Submission
        document.getElementById('checkout-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');
            
            const form = e.target;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Gather form data
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            data.customer_name = data.full_name || '';
            data.address_line = data.address || '';
            data.total_amount = cartTotal;

            // Loading state
            btn.disabled = true;
            btnText.classList.add('opacity-0');
            spinner.classList.remove('hidden');

            try {
                const response = await fetch('api/create-order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                let result;
                try {
                    result = await response.json();
                } catch(e) {
                    throw new Error('Invalid response from server');
                }

                if (response.ok && result.success !== false) {
                    // Success
                    document.getElementById('checkout-container').classList.add('hidden');
                    document.getElementById('checkout-container').classList.remove('flex');
                    
                    document.getElementById('success-screen').classList.remove('hidden');
                    document.getElementById('success-order-number').textContent = result.order_number || ('#RGX-' + Math.random().toString(36).substring(2, 8).toUpperCase());
                    document.getElementById('success-amount').textContent = formatINR(cartTotal);
                    document.getElementById('success-email').textContent = data.email;
                    
                } else {
                    throw new Error(result.message || 'Failed to place order');
                }

            } catch (error) {
                showToast(error.message);
            } finally {
                // Reset button state
                btn.disabled = false;
                btnText.classList.remove('opacity-0');
                spinner.classList.add('hidden');
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', initCheckout);
    </script>

<?php require_once __DIR__ . '/includes/store-footer.php'; ?>
