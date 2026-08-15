<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT full_name, email, phone FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: index.php");
    exit();
}

$pageTitle = "My Profile | RegrowthX";
require_once __DIR__ . '/includes/store-header.php';
?>

<!-- MAIN CONTENT -->
<main class="max-w-3xl mx-auto pt-32 pb-16 px-4">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Profile</h1>
    
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8 space-y-8">
            
            <!-- Personal Info -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Personal Information</h3>
                <form id="profile-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" id="prof-name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                            <input type="email" id="prof-email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly class="w-full px-4 py-3 border-2 border-gray-100 bg-gray-50 rounded-xl text-sm text-gray-500 outline-none cursor-not-allowed" title="Email cannot be changed">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                            <input type="tel" id="prof-phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="10-digit mobile number" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="updateProfile()" id="prof-btn" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">Save Changes</button>
                    </div>
                </form>
            </div>
            
            <!-- Security / Password -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Change Password</h3>
                <form id="password-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password</label>
                        <input type="password" id="pass-current" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                            <input type="password" id="pass-new" placeholder="At least 8 chars, 1 uppercase, 1 number, 1 special" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                            <input type="password" id="pass-confirm" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Must contain uppercase, lowercase, number, and special character.</p>
                    <p id="pass-error" class="text-xs text-red-500 hidden"></p>
                    
                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="updatePassword()" id="pass-btn" class="px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">Update Password</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</main>

<script>
    async function updateProfile() {
        const btn = document.getElementById('prof-btn');
        const name = document.getElementById('prof-name').value.trim();
        const phone = document.getElementById('prof-phone').value.trim();
        
        if(!name) {
            showToast('Name cannot be empty', 'error');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = 'Saving...';
        
        try {
            const res = await fetch('api/update-profile.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update_info', full_name: name, phone: phone })
            });
            const data = await res.json();
            if(data.success) {
                showToast('Profile updated successfully!', 'success');
            } else {
                showToast(data.message || 'Update failed', 'error');
            }
        } catch(e) {
            showToast('Network error', 'error');
        }
        btn.disabled = false;
        btn.innerHTML = 'Save Changes';
    }

    async function updatePassword() {
        const btn = document.getElementById('pass-btn');
        const err = document.getElementById('pass-error');
        const current = document.getElementById('pass-current').value;
        const newPass = document.getElementById('pass-new').value;
        const confirmPass = document.getElementById('pass-confirm').value;

        if(!current || !newPass || !confirmPass) {
            err.innerText = 'All password fields are required';
            err.classList.remove('hidden');
            return;
        }
        
        if (newPass !== confirmPass) {
            err.innerText = 'New passwords do not match';
            err.classList.remove('hidden');
            return;
        }

        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordPattern.test(newPass)) {
            err.innerText = 'Password does not meet strict requirements';
            err.classList.remove('hidden');
            return;
        }

        err.classList.add('hidden');
        btn.disabled = true;
        btn.innerHTML = 'Updating...';
        
        try {
            const res = await fetch('api/update-profile.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update_password', current_password: current, new_password: newPass })
            });
            const data = await res.json();
            if(data.success) {
                showToast('Password updated securely!', 'success');
                document.getElementById('password-form').reset();
            } else {
                err.innerText = data.message || 'Update failed';
                err.classList.remove('hidden');
            }
        } catch(e) {
            err.innerText = 'Network error';
            err.classList.remove('hidden');
        }
        btn.disabled = false;
        btn.innerHTML = 'Update Password';
    }
</script>

<?php require_once __DIR__ . '/includes/store-footer.php'; ?>
