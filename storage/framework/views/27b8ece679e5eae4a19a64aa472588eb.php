<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precy Salon | Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#e9f5e9] min-h-screen flex">

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
                <a href="/calendar" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition group">
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

    <main class="flex-1 ml-64 p-8">


        <div class="flex justify-between items-center mb-10">
            <div></div>

            <?php echo $__env->make('partials.user_dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-green-600">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xs font-black uppercase text-gray-400 mb-2">Today's Sales</h3>
                        <p class="text-2xl font-bold text-gray-800">
                            ₱<?php echo e(number_format($todaySales, 2)); ?>

                        </p>
                        <p class="text-[10px] text-green-600 font-bold mt-1 uppercase tracking-tight">
                            Confirmed Revenue
                        </p>
                    </div>
                    <i data-lucide="philippine-peso" class="text-gray-300"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-green-600">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xs font-black uppercase text-gray-400 mb-2">Total Appointments</h3>
                        <p class="text-4xl font-black text-gray-800"><?php echo e($appointmentCount); ?></p>
                    </div>
                    <i data-lucide="file-text" class="text-gray-300"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-green-600">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xs font-black uppercase text-gray-400 mb-2">Total Clients</h3>
                        <p class="text-4xl font-black text-gray-800"><?php echo e($customerCount || $appointmentCount); ?></p>
                    </div>
                    <i data-lucide="user" class="text-gray-300"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-green-600">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xs font-black uppercase text-gray-400 mb-2">Pending Bookings</h3>
                        <p class="text-4xl font-black text-gray-800"><?php echo e($unpaidCount || $appointmentCount); ?></p>
                    </div>
                    <i data-lucide="clock" class="text-gray-300"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-6 underline decoration-green-400 decoration-4 underline-offset-8">Today's Appointments List</h3>

            <table class="w-full text-center">
                <thead>
                    <tr class="text-gray-400 text-xs uppercase border-b text-center">
                        <th class="py-3">Time</th>
                        <th class="py-3">Client Name</th>
                        <th class="py-3">Service</th>
                        <th class="py-3">Staff</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Action</th>
                    </tr>
                </thead>
               <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-4 text-sm font-bold">
                                <?php echo e(\Carbon\Carbon::parse($app->appointment_at)->format('g:i A')); ?>

                            </td>

                            <td class="py-4 text-sm"><?php echo e($app->first_name); ?> <?php echo e($app->last_name); ?></td>

                            <td class="py-4 text-sm">
                                <div class="flex flex-col gap-1">
                                    <?php $__empty_2 = true; $__currentLoopData = $app->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <span class="block font-medium text-gray-700 whitespace-nowrap">
                                            <?php echo e($service->name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                        <span class="text-gray-400 italic">No services</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-4 text-sm"><?php echo e($app->staff->name ?? 'N/A'); ?></td>

                            <td class="py-4 text-sm">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase <?php echo e($app->status == 'Paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'); ?>">
                                    <?php echo e($app->status ?? 'Pending'); ?>

                                </span>
                            </td>
                            <td class="py-4">
                                <div class="flex flex-col items-center justify-center">
                                    <?php if($app->appt_status !== 'Completed'): ?>
                                        <button type="button"
                                                onclick="completeAppointment(event, <?php echo e($app->id); ?>, this)"
                                                class="flex items-center space-x-1 bg-[#81b281] text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#6a966a] transition shadow-sm active:scale-95">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                            <span>Complete</span>
                                        </button>
                                    <?php else: ?>
                                        <div class="flex flex-col items-center">
                                            <span class="text-green-600 text-[10px] font-bold uppercase flex items-center bg-green-50 px-3 py-1 rounded-lg border border-green-100">
                                                <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-1"></i>
                                                Finished
                                            </span>
                                            
                                            <?php if($app->completed_at): ?>
                                                <span class="text-[9px] text-gray-400 mt-1 italic">
                                                    at <?php echo e(\Carbon\Carbon::parse($app->completed_at)->format('g:i A')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-400 italic">
                                No appointments scheduled for today.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        lucide.createIcons();
        function completeAppointment(event, id, button) {
            event.preventDefault();
            if (!confirm('Mark this service as completed?')) return;

            button.innerHTML = '<span class="animate-pulse">Updating...</span>';
            button.disabled = true;

            fetch(`/appointments/${id}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const container = button.parentElement;
                    // Inject the new badge AND the time returned from the server
                    container.innerHTML = `
                        <div class="flex flex-col items-center">
                            <span class="text-green-600 text-[10px] font-bold uppercase flex items-center bg-green-50 px-3 py-1 rounded-lg border border-green-100">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-1"></i>
                                Finished
                            </span>
                            <span class="text-[9px] text-gray-400 mt-1 italic">
                                at ${data.completed_time}
                            </span>
                        </div>
                    `;
                    if (window.lucide) lucide.createIcons();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Could not update status.');
            });
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/dashboard.blade.php ENDPATH**/ ?>