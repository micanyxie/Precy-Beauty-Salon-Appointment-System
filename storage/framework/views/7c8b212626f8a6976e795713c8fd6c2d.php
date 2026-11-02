<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#e9f5e9] font-sans antialiased">

    <?php if(session('success')): ?>
    <div id="toast-success" class="fixed top-5 right-5 z-[100] transform transition-all duration-500 translate-y-0 opacity-100">
        <div class="bg-white border-l-8 border-green-500 shadow-2xl rounded-xl p-5 flex items-center space-x-4 min-w-[300px]">
            <div class="bg-green-100 p-2 rounded-full">
                <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
            </div>
            <div>
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Success</h3>
                <p class="text-sm text-gray-500 font-bold"><?php echo e(session('success')); ?></p>
            </div>
            <button onclick="closeToast()" class="text-gray-400 hover:text-gray-600 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
    <?php endif; ?>

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
                    <i data-lucide="contact-2" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Staff</span>
                </a>
                <a href="/customers" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="contact-2" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Customers</span>
                </a>
                <a href="/payment" class="flex items-center space-x-3 bg-[#e9f5e9] p-3 rounded shadow-inner border border-green-200">
                    <i data-lucide="credit-card" class="w-5 h-5 text-green-700"></i>
                    <span class="font-bold text-green-800">Payments</span>
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

            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">Payments</h2>

            <div class="flex justify-between items-center mb-6">
                <div class="relative w-80">
                    <input type="text" id="searchInput" placeholder="Search payments..."
                        class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-[#81b281] outline-none shadow-sm">
                    <i data-lucide="search" class="w-4 h-4 absolute left-4 top-2.5 text-gray-400"></i>
                </div>

            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase font-bold border-b bg-gray-50">
                            <th class="py-5 px-6">Contact (Client)</th>
                            <th class="py-5 px-6">Status</th>
                            <th class="py-5 px-6">Type</th>
                            <th class="py-5 px-6">Method</th>
                            <th class="py-5 px-6">Amount</th>
                            <th class="py-5 px-6">Date</th>
                            <?php if(auth()->user()->role === 'admin'): ?>
                                <th class="py-5 px-6 text-right">Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="paymentTableBody" class="divide-y divide-gray-100">
                       <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-green-50/50 transition duration-150">
                            <td class="py-4 px-6 font-bold text-gray-700 uppercase tracking-tight">
                                <?php echo e($app->first_name); ?> <?php echo e($app->last_name); ?>

                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase shadow-sm
                                    <?php echo e($app->status == 'Paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                                    <?php echo e($app->status); ?>

                                </span>

                                <div class="text-[9px] mt-1 text-gray-400 font-bold uppercase">
                                    Booking: <?php echo e($app->approval_status ?? 'Approved'); ?>

                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-500 text-sm font-medium italic">Payment</td>
                            <td class="py-4 px-6 text-gray-600 text-sm font-bold"><?php echo e($app->payment_method ?? 'Cash'); ?></td>

                            <td class="py-4 px-6 font-black text-[#81b281] text-lg">
                                Php <?php echo e(number_format($app->services->sum('price'), 2)); ?>

                            </td>

                            <td class="py-4 px-6 text-gray-500 text-sm font-bold italic">
                                <?php echo e(\Carbon\Carbon::parse($app->appointment_at)->format('d M, Y')); ?>

                            </td>

                            <?php if(auth()->user()->role === 'admin'): ?>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <?php if($app->status !== 'Paid'): ?>
                                            <button type="button"
                                                    onclick="openConfirmModal(event, <?php echo e($app->id); ?>, '<?php echo e($app->first_name); ?> <?php echo e($app->last_name); ?>')"
                                                    class="flex items-center space-x-1 bg-green-500 text-white px-3 py-1 rounded-md text-xs font-bold hover:bg-green-600 transition shadow-sm">
                                                <i data-lucide="check" class="w-3 h-3"></i>
                                                <span>Pay</span>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-[10px] font-bold uppercase flex items-center">
                                                <i data-lucide="check-circle-2" class="w-3 h-3 mr-1 text-green-500"></i>
                                                Settled
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>


            </div>
        </main>
    </div>

    <div id="paymentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md border-t-8 border-[#00c2e0] transform transition-all">
            <form action="<?php echo e(route('payment.store')); ?>" method="POST" class="p-8">
                <?php echo csrf_field(); ?>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-black text-2xl text-gray-800 uppercase tracking-tighter">Record Payment</h2>
                    <button type="button" onclick="togglePaymentModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2 block">Select Appointment/Client</label>
                        <select name="appointment_id" class="w-full border-2 border-gray-100 p-4 rounded-xl bg-gray-50 text-gray-700 font-bold focus:border-[#00c2e0] outline-none transition">
                            <?php $__currentLoopData = $unpaidAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unpaid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($unpaid->id); ?>">
                                    <?php echo e($unpaid->first_name); ?> <?php echo e($unpaid->last_name); ?> -
                                    ₱<?php echo e(number_format($unpaid->services->sum('price'), 2)); ?>

                                    (<?php echo e($unpaid->services->count()); ?> services)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2 block">Payment Method</label>
                        <select name="payment_method" class="w-full border-2 border-gray-100 p-4 rounded-xl bg-gray-50 text-gray-700 font-bold focus:border-[#00c2e0] outline-none transition">
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-10">
                    <button type="button" onclick="togglePaymentModal()" class="px-6 py-3 text-gray-400 font-black uppercase text-sm hover:text-gray-600 tracking-widest">Cancel</button>
                    <button type="submit" class="bg-[#00c2e0] text-white px-8 py-3 rounded-xl font-black uppercase text-sm shadow-lg hover:bg-[#00acc1] transition tracking-widest">
                        Process Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="confirmPaymentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md border-t-8 border-green-500">
            <div class="p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <i data-lucide="banknote" class="h-10 w-10 text-green-600"></i>
                </div>

                <h2 class="font-bold text-2xl text-gray-800 mb-2 italic uppercase">Confirm Payment</h2>
                <p class="text-gray-500 mb-6">Are you sure you want to mark the appointment for <span id="confirm_client_name" class="font-black text-gray-800"></span> as <span class="text-green-600 font-bold">PAID</span>?</p>

                <form id="confirmPaymentForm" method="POST" class="flex space-x-3 justify-center">
                    <?php echo csrf_field(); ?>
                    <button type="button" onclick="toggleConfirmModal()" class="px-6 py-3 rounded-lg font-bold text-gray-400 hover:bg-gray-100 transition uppercase text-xs tracking-widest">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-green-700 transition shadow-lg uppercase text-xs tracking-widest">
                        Confirm & Settle
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize Icons
        lucide.createIcons();

        // Modal Toggle
        function togglePaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        // Close modal on escape key
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('paymentModal');
                if (!modal.classList.contains('hidden')) togglePaymentModal();
            }
        });
        function toggleConfirmModal() {
            const modal = document.getElementById('confirmPaymentModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function openConfirmModal(event, id, name) {
            // 1. Stop the page from refreshing or submitting any parent forms
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const form = document.getElementById('confirmPaymentForm');
            const nameSpan = document.getElementById('confirm_client_name');

            // 2. Set the action URL dynamically
            form.action = '/appointments/' + id + '/pay';

            // 3. Put the client's name in the message
            nameSpan.innerText = name;

            // 4. Show the modal
            toggleConfirmModal();
        }

        function toggleConfirmModal() {
            const modal = document.getElementById('confirmPaymentModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
        // Function to close toast manually or via timer
        function closeToast() {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 500);
            }
        }

        // Auto-close the toast after 4 seconds
        if (document.getElementById('toast-success')) {
            setTimeout(closeToast, 4000);
        }
        let searchTimer;
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('paymentTableBody');

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);

            searchTimer = setTimeout(() => {
                const query = searchInput.value;

                fetch(`/payment?search=${query}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('paymentTableBody').innerHTML = html;

                    // RE-INITIALIZE ICONS HERE
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            }, 300);
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/payment/index.blade.php ENDPATH**/ ?>