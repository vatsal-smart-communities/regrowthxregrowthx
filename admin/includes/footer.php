        </main>
    </div>

    <!-- Admin Scripts -->
    <script>
        function showAdminToast(message, type = 'success') {
            const container = document.getElementById('admin-toast-container');
            const toast = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-emerald-600' : 'bg-red-600';
            
            toast.className = `${bgColor} text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2 transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <span class="material-symbols-outlined text-sm">${type === 'success' ? 'check_circle' : 'error'}</span>
                <span class="text-sm font-semibold">${message}</span>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Animate out
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        async function handleAdminLogout() {
            try {
                await fetch('../api/logout.php', { method: 'POST' });
                window.location.href = '../index.php';
            } catch (err) {
                console.error(err);
            }
        }
    </script>
</body>
</html>
