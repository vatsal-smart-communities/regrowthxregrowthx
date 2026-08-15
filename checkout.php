<?php 
require_once __DIR__ . '/config/payments.php';
$pageTitle = "Secure Checkout - RegrowthX";
require_once __DIR__ . '/includes/store-header.php'; 
?>
<script type="text/javascript" src="<?= SQUARE_ENV === 'production' ? 'https://web.squarecdn.com/v1/square.js' : 'https://sandbox.web.squarecdn.com/v1/square.js' ?>"></script>

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
                                    <label for="state" class="block text-sm font-medium text-gray-700">State *</label>
                                    <div class="mt-1">
                                        <select id="state" name="state" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm px-4 py-2.5 border bg-white">
                                            <option value="" disabled selected>Select US State</option>
                                            <option value="AL">Alabama (AL)</option>
                                            <option value="AK">Alaska (AK)</option>
                                            <option value="AZ">Arizona (AZ)</option>
                                            <option value="AR">Arkansas (AR)</option>
                                            <option value="CA">California (CA)</option>
                                            <option value="CO">Colorado (CO)</option>
                                            <option value="CT">Connecticut (CT)</option>
                                            <option value="DE">Delaware (DE)</option>
                                            <option value="FL">Florida (FL)</option>
                                            <option value="GA">Georgia (GA)</option>
                                            <option value="HI">Hawaii (HI)</option>
                                            <option value="ID">Idaho (ID)</option>
                                            <option value="IL">Illinois (IL)</option>
                                            <option value="IN">Indiana (IN)</option>
                                            <option value="IA">Iowa (IA)</option>
                                            <option value="KS">Kansas (KS)</option>
                                            <option value="KY">Kentucky (KY)</option>
                                            <option value="LA">Louisiana (LA)</option>
                                            <option value="ME">Maine (ME)</option>
                                            <option value="MD">Maryland (MD)</option>
                                            <option value="MA">Massachusetts (MA)</option>
                                            <option value="MI">Michigan (MI)</option>
                                            <option value="MN">Minnesota (MN)</option>
                                            <option value="MS">Mississippi (MS)</option>
                                            <option value="MO">Missouri (MO)</option>
                                            <option value="MT">Montana (MT)</option>
                                            <option value="NE">Nebraska (NE)</option>
                                            <option value="NV">Nevada (NV)</option>
                                            <option value="NH">New Hampshire (NH)</option>
                                            <option value="NJ">New Jersey (NJ)</option>
                                            <option value="NM">New Mexico (NM)</option>
                                            <option value="NY">New York (NY)</option>
                                            <option value="NC">North Carolina (NC)</option>
                                            <option value="ND">North Dakota (ND)</option>
                                            <option value="OH">Ohio (OH)</option>
                                            <option value="OK">Oklahoma (OK)</option>
                                            <option value="OR">Oregon (OR)</option>
                                            <option value="PA">Pennsylvania (PA)</option>
                                            <option value="RI">Rhode Island (RI)</option>
                                            <option value="SC">South Carolina (SC)</option>
                                            <option value="SD">South Dakota (SD)</option>
                                            <option value="TN">Tennessee (TN)</option>
                                            <option value="TX">Texas (TX)</option>
                                            <option value="UT">Utah (UT)</option>
                                            <option value="VT">Vermont (VT)</option>
                                            <option value="VA">Virginia (VA)</option>
                                            <option value="WA">Washington (WA)</option>
                                            <option value="WV">West Virginia (WV)</option>
                                            <option value="WI">Wisconsin (WI)</option>
                                            <option value="WY">Wyoming (WY)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="pincode" class="block text-sm font-medium text-gray-700">ZIP Code *</label>
                                    <div class="mt-1">
                                        <input type="text" id="pincode" name="pincode" pattern="[0-9]{5}(-[0-9]{4})?" required placeholder="e.g. 90210" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm px-4 py-2.5 border">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined text-brand-primary mr-2">payment</span>
                                <h2 class="text-lg font-bold text-brand-dark">Payment Method</h2>
                            </div>
                            <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">🔒 256-Bit Encrypted</span>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Option 1: Square Payments (Online) -->
                            <label onclick="togglePaymentMethod('SQUARE')" class="payment-method-card flex flex-col p-4 border-2 rounded-xl border-emerald-600 bg-emerald-50/30 cursor-pointer transition-all shadow-sm">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start">
                                        <input type="radio" name="payment_method" id="method-square" value="SQUARE" checked class="h-4 w-4 text-emerald-600 border-gray-300 focus:ring-emerald-500 mt-1">
                                        <div class="ml-3">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900 text-sm">Credit / Debit Card</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-black text-white uppercase tracking-wider">Fast & Secure</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-0.5">Powered by Square Payments</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-emerald-800">
                                        <span class="material-symbols-outlined text-xl">credit_card</span>
                                    </div>
                                </div>
                                
                                <!-- Square Card Container (Hidden by default, shown when Square is selected) -->
                                <div id="square-payment-container" class="mt-4 pt-4 border-t border-emerald-200">
                                    <div id="card-container" class="bg-white rounded-lg p-2 border border-gray-300"></div>
                                </div>
                            </label>                            <!-- Option 2: Cash on Delivery -->
                            <label onclick="togglePaymentMethod('COD')" class="payment-method-card flex items-start justify-between p-4 border-2 rounded-xl border-gray-200 bg-white hover:border-gray-300 cursor-pointer transition-all">
                                <div class="flex items-start">
                                    <input type="radio" name="payment_method" value="COD" class="h-4 w-4 text-brand-primary border-gray-300 focus:ring-brand-primary mt-1">
                                    <div class="ml-3">
                                        <span class="font-bold text-gray-900 text-sm">Cash on Delivery (COD)</span>
                                        <p class="text-xs text-gray-500 mt-0.5">Pay in cash when your order is delivered to your doorstep.</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-gray-400 text-xl">payments</span>
                            </label>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" id="submit-btn" class="w-full flex items-center justify-center px-6 py-4 border border-transparent text-lg font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 shadow-lg cursor-pointer active:scale-98">
                        <span id="btn-text" class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                            <span id="btn-label-text">Pay Securely via Square</span>
                        </span>
                        <div id="btn-spinner" class="spinner hidden ml-2"></div>
                    </button>
                    <p class="text-xs text-center text-gray-500 mt-3 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[16px] mr-1 text-emerald-600">verified_user</span>
                        Protected by Square Payments & 256-Bit SSL Encryption
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
                                <span id="summary-subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Shipping</span>
                                <span class="text-emerald-600 font-medium">FREE US Shipping</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Sales Tax</span>
                                <span class="text-emerald-600 font-medium">Calculated at Checkout</span>
                            </div>
                            <div class="border-t border-gray-100 pt-4 flex justify-between">
                                <span class="text-base font-bold text-gray-900">Total</span>
                                <span id="summary-total" class="text-2xl font-bold text-brand-dark">$0.00</span>
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
                                <span>Free US<br>Delivery</span>
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
                <p id="success-amount" class="text-lg font-semibold text-gray-900 mb-4">$0.00</p>
                
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
            return '$' + Number(amount).toFixed(2);
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

        let squareCard;
        
        async function initializeSquare() {
            if (!window.Square) {
                console.error('Square.js failed to load properly');
                return;
            }
            try {
                const payments = window.Square.payments('<?= SQUARE_APP_ID ?>', '<?= SQUARE_LOCATION_ID ?>');
                squareCard = await payments.card();
                await squareCard.attach('#card-container');
            } catch (e) {
                console.error('Initializing Square failed', e);
            }
        }

        function togglePaymentMethod(method) {
            const cards = document.querySelectorAll('.payment-method-card');
            const labelText = document.getElementById('btn-label-text');
            const squareContainer = document.getElementById('square-payment-container');
            
            cards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                if (radio && radio.value === method) {
                    radio.checked = true;
                    card.className = "payment-method-card flex flex-col p-4 border-2 rounded-xl border-emerald-600 bg-emerald-50/30 cursor-pointer transition-all shadow-sm";
                } else {
                    card.className = "payment-method-card flex flex-col p-4 border-2 rounded-xl border-gray-200 bg-white hover:border-gray-300 cursor-pointer transition-all";
                }
            });

            if (method === 'SQUARE') {
                if (labelText) labelText.innerText = "Pay Securely via Square";
                if (squareContainer) squareContainer.classList.remove('hidden');
            } else {
                if (labelText) labelText.innerText = "Place Order (Cash on Delivery)";
                if (squareContainer) squareContainer.classList.add('hidden');
            }
        }

        // Handle checkout form submit
        document.getElementById('checkout-form').addEventListener('submit', async (e) => {
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
                // Tokenize Square Payment if selected
                if (data.payment_method === 'SQUARE') {
                    if (!squareCard) {
                        throw new Error("Payment gateway is not initialized. Please check your Square API credentials.");
                    }
                    const result = await squareCard.tokenize();
                    if (result.status === 'OK') {
                        data.payment_token = result.token;
                    } else {
                        throw new Error(result.errors[0].message || 'Failed to process card details.');
                    }
                }

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
                    const orderNum = result.order_number || ('#RGX-' + Math.random().toString(36).substring(2, 8).toUpperCase());
                    
                    if (data.payment_method === 'SQUARE') {
                        document.getElementById('checkout-container').classList.add('hidden');
                        document.getElementById('checkout-container').classList.remove('flex');
                        document.getElementById('success-screen').classList.remove('hidden');
                        document.getElementById('success-order-number').textContent = orderNum;
                        document.getElementById('success-amount').textContent = formatINR(cartTotal) + ' (Square Online Payment)';
                        document.getElementById('success-email').textContent = data.email;
                    } else {
                        document.getElementById('checkout-container').classList.add('hidden');
                        document.getElementById('checkout-container').classList.remove('flex');
                        document.getElementById('success-screen').classList.remove('hidden');
                        document.getElementById('success-order-number').textContent = orderNum;
                        document.getElementById('success-amount').textContent = formatINR(cartTotal) + ' (Cash on Delivery)';
                        document.getElementById('success-email').textContent = data.email;
                    }
                    
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
        document.addEventListener('DOMContentLoaded', () => {
            initCheckout();
            initializeSquare();
        });
    </script>

<?php require_once __DIR__ . '/includes/store-footer.php'; ?>
