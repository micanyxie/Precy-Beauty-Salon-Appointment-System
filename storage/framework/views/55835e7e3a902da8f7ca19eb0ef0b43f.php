<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Precy Beauty Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#e9f5e9] min-h-screen">

<div class="flex h-screen overflow-hidden" x-data="{ view: 'daily' }">

    <aside class="w-64 bg-[#76a376] flex flex-col h-screen shadow-xl fixed left-0 top-0">
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
                <a href="/calendar" class="flex items-center space-x-3 bg-[#e9f5e9] p-3 rounded shadow-inner border border-green-200">
                    <i data-lucide="calendar" class="w-5 h-5 text-green-700"></i>
                    <span class="font-bold text-green-800">Calendar</span>
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
                    <i data-lucide="contact-2" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Customers</span>
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

    <main class="flex-1 ml-64 p-8 min-h-screen overflow-y-auto bg-[#e9f5e9]">

        <div class="flex justify-between items-center mb-10">
            <div></div>

            <?php echo $__env->make('partials.user_dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Appointment List</h2>
                <p class="text-sm text-gray-500 font-medium italic">Showing approved bookings for <?php echo e($baseDate->format('F Y')); ?></p>
            </div>

            <div class="flex space-x-2">
                <a href="/calendar?date=<?php echo e($prevMonth); ?>" class="p-2 border rounded-lg bg-white hover:bg-gray-50 transition shadow-sm text-gray-600">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </a>
                <div class="px-4 py-2 bg-white border rounded-lg font-bold text-gray-700 shadow-sm">
                    <?php echo e($baseDate->format('M Y')); ?>

                </div>
                <a href="/calendar?date=<?php echo e($nextMonth); ?>" class="p-2 border rounded-lg bg-white hover:bg-gray-50 transition shadow-sm text-gray-600">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b text-[10px] uppercase font-black text-gray-400 tracking-widest text-center">
                        <th class="py-4 px-6">Date & Time</th>
                        <th class="py-4 px-6">Customer</th>
                        <th class="py-4 px-6">Services</th>
                        <th class="py-4 px-6">Staff</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Requested At</th>
                        <th class="py-4 px-6">Rescheduled At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-center">
                    <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-green-50/30 transition group">
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-800 text-sm"><?php echo e(\Carbon\Carbon::parse($app->appointment_at)->format('M d, Y')); ?></span>
                                    <span class="text-[#81b281] font-bold text-xs"><?php echo e(\Carbon\Carbon::parse($app->appointment_at)->format('h:i A')); ?></span>
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <span class="font-bold text-gray-700 uppercase tracking-tighter"><?php echo e($app->first_name); ?> <?php echo e($app->last_name); ?></span>
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1 text-center justify-center">
                                    <?php $__currentLoopData = $app->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold rounded uppercase border">
                                            <?php echo e($service->name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-[#dcf0d1] rounded-full flex items-center justify-center">
                                        <i data-lucide="user" class="w-3 h-3 text-[#76a376]"></i>
                                    </div>
                                    <span class="text-xs text-gray-500 italic"><?php echo e($app->staff->name ?? 'Unassigned'); ?></span>
                                </div>
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
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-800 text-sm"><?php echo e(\Carbon\Carbon::parse($app->created_at)->format('M d, Y')); ?></span>
                                    <span class="text-[#81b281] font-bold text-xs"><?php echo e(\Carbon\Carbon::parse($app->created_at)->format('h:i A')); ?></span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-800 text-sm"><?php echo e(\Carbon\Carbon::parse($app->updated_at)->format('M d, Y')); ?></span>
                                    <span class="text-[#81b281] font-bold text-xs"><?php echo e(\Carbon\Carbon::parse($app->updated_at)->format('h:i A')); ?></span>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="py-20">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <i data-lucide="calendar-x" class="w-8 h-8 text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-400 font-medium italic">No appointments found for this month.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-between items-center text-[10px] font-black uppercase text-gray-400 tracking-widest">
            <span>Precy Beauty Salon Management System</span>
            <span>Total: <?php echo e($appointments->count()); ?> Appointments</span>
        </div>
    </main>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/calendar/index.blade.php ENDPATH**/ ?>