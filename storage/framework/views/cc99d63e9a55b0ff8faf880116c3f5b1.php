<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Appointments</title>
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
                <a href="/reservation" class="flex items-center space-x-3 bg-[#e9f5e9] p-3 rounded shadow-inner border border-green-200">
                    <i data-lucide="list-todo" class="w-5 h-5 text-green-700"></i>
                    <span class="font-bold text-green-800">Requests</span>
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
                <a href="/customers" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="contact-2" class="w-5 h-5  text-gray-600"></i>
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
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-orange-100 mb-10">
            <div class="bg-orange-50 px-6 py-4 border-b border-orange-100 flex justify-between items-center">
                <h3 class="font-black text-orange-800 uppercase tracking-tight flex items-center">
                    <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                    Pending Requests
                </h3>
                <span class="bg-orange-200 text-orange-800 text-xs font-bold px-2 py-1 rounded-full">
                    <?php echo e($appointments->where('approval_status', 'Pending')->count()); ?> New
                </span>
            </div>

            <table class="w-full text-left">
                <tbody class="divide-y divide-gray-50">
                    <?php $__currentLoopData = $appointments->where('approval_status', 'Pending'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6">
                            <p class="font-bold text-gray-800 uppercase text-sm"><?php echo e($req->first_name); ?> <?php echo e($req->last_name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(\Carbon\Carbon::parse($req->appointment_at)->format('M d, h:i A')); ?></p>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-wrap gap-1">
                                <?php $__currentLoopData = $req->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded font-bold uppercase"><?php echo e($svc->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end space-x-2">
                                <form action="<?php echo e(route('appointment.approval_status', [$req->id, 'Approved'])); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="p-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition shadow-sm">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <form action="<?php echo e(route('appointment.approval_status', [$req->id, 'Declined'])); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </main>
     <script>
        lucide.createIcons();
    </script>
</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/admin/appt_approve.blade.php ENDPATH**/ ?>