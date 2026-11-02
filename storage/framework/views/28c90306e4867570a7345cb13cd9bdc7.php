<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #81b281; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#e9f5e9] font-sans antialiased">

    <aside class="w-64 bg-[#76a376] flex flex-col min-h-screen shadow-xl fixed">
        <div class="p-6">
            <div class="bg-[#dcf0d1] p-4 rounded-md border border-gray-400 mb-8">
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Admin<br>Dashboard</h2>
            </div>

            <nav class="space-y-3">
                <a href="/admin" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Dashboard</span>
                </a>
                <a href="/reservation" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="list-todo" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Requests</span>
                </a>
                <a href="/calendar" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="calendar" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Calendar</span>
                </a>
                <a href="/services" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="scissors" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Service</span>
                </a>
                <a href="/staff-list" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="users-round" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Staff</span>
                </a>
                <a href="/customers" class="flex items-center space-x-3 bg-[#e9f5e9] p-3 rounded shadow-inner border border-green-200">
                    <i data-lucide="contact-2" class="w-5 h-5 text-green-700"></i>
                    <span class="font-bold text-green-800">Customers</span>
                </a>
                <a href="/payment" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="credit-card" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Payments</span>
                </a>
                <a href="/settings" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="settings" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Settings</span>
                </a>
            </nav>
        </div>
        <div class="mt-auto p-6">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="flex items-center space-x-2 text-white font-bold hover:text-red-200">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8">
        <div class="flex justify-between items-center mb-10">
            <div></div>

            <?php echo $__env->make('partials.user_dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">Customer List</h2>

        <div class="mb-6">
            <div class="relative w-full max-w-md">
                <input type="text" id="customerSearch" placeholder="Search customer name..."
                    class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-[#81b281] outline-none shadow-sm">
                <i data-lucide="search" class="w-5 h-5 absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-xs uppercase font-bold border-b bg-gray-50">
                        <th class="py-5 px-6">Customer Name</th>
                    </tr>
                </thead>
                <tbody id="customerTableBody" class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr onclick="showHistory('<?php echo e($customer->first_name); ?> <?php echo e($customer->last_name); ?>')" class="hover:bg-green-50/50 cursor-pointer transition group">
                        <td class="py-5 px-6 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[#81b281] font-bold text-xs uppercase">
                                    <?php echo e(substr($customer->first_name, 0, 1)); ?><?php echo e(substr($customer->last_name, 0, 1)); ?>

                                </div>
                                <span class="font-bold text-gray-700 uppercase tracking-tight group-hover:text-[#81b281]">
                                    <?php echo e($customer->first_name); ?> <?php echo e($customer->last_name); ?>

                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest group-hover:text-[#81b281] group-hover:translate-x-1 transition-all">View History</span>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 group-hover:text-[#81b281] group-hover:translate-x-1 transition-all"></i>
                            </div>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="historyOverlay" onclick="closeHistory()" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300"></div>

    <div id="historyPanel" class="fixed top-0 right-0 h-full w-full max-w-md bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="p-8 h-full flex flex-col">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 id="historyCustomerName" class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Customer Name</h3>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Appointment History</p>
                </div>
                <button onclick="closeHistory()" class="p-2 hover:bg-red-50 rounded-full transition text-gray-400 hover:text-red-500">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <div id="historyContent" class="flex-1 overflow-y-auto pr-2 custom-scrollbar space-y-4">
                </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function showHistory(name) {
            const panel = document.getElementById('historyPanel');
            const overlay = document.getElementById('historyOverlay');
            const content = document.getElementById('historyContent');
            const nameHeader = document.getElementById('historyCustomerName');

            nameHeader.innerText = name;
            content.innerHTML = '<div class="flex justify-center py-20"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-[#81b281]"></div></div>';

            // Open Animation
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.add('opacity-100'), 10);
            panel.classList.remove('translate-x-full');

            // Fetch History AJAX
            fetch(`/customer-history/${encodeURIComponent(name)}`)
                .then(response => response.text())
                .then(html => {
                    content.innerHTML = html;
                    lucide.createIcons();
                });
        }

        function closeHistory() {
            const panel = document.getElementById('historyPanel');
            const overlay = document.getElementById('historyOverlay');

            panel.classList.add('translate-x-full');
            overlay.classList.remove('opacity-100');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
        let customerSearchTimer;
        const customerSearchInput = document.getElementById('customerSearch');
        const customerTableBody = document.getElementById('customerTableBody');

        customerSearchInput.addEventListener('input', function() {
            clearTimeout(customerSearchTimer);

            customerSearchTimer = setTimeout(() => {
                const query = customerSearchInput.value;

                // Fetch data via AJAX
                fetch(`/customers?search=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Update only the table rows
                    customerTableBody.innerHTML = html;

                    // Re-render Lucide icons for the new rows
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                })
                .catch(error => console.error('Error searching customers:', error));
            }, 300);
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/customers/index.blade.php ENDPATH**/ ?>