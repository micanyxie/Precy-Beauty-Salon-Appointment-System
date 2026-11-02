<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precy Salon | Staff Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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
                <a href="/calendar" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="calendar" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Calendar</span>
                </a>
                <a href="/services" class="flex items-center space-x-3 bg-white p-3 rounded shadow-sm hover:bg-gray-100 transition">
                    <i data-lucide="scissors" class="w-5 h-5 text-gray-600"></i>
                    <span class="font-bold text-gray-700">Service</span>
                </a>
                <a href="/staff-list" class="flex items-center space-x-3 bg-[#e9f5e9] p-3 rounded shadow-inner border border-green-200">
                    <i data-lucide="users-round" class="w-5 h-5 text-green-700"></i>
                    <span class="font-bold text-green-800">Staff</span>
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

        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-300 pb-2">Staff List</h1>

        <div class="flex justify-between items-center mb-6">
            <div></div>
            <?php if(auth()->user()->role === 'admin'): ?>
                <button onclick="toggleStaffModal()" class="bg-[#00c2e0] text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-[#00acc1] flex items-center space-x-2">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Add Staff</span>
                </button>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 mb-10">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-sm font-bold border-b">
                        <th class="py-4">Staff Name</th>
                        <th class="py-4">Availability</th>
                        <?php if(auth()->user()->role === 'admin'): ?>
                            <th class="py-4 text-center">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $staffMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 font-bold text-gray-700"><?php echo e($member->name); ?></td>

                        <td class="py-4">
                            <span class="<?php echo e($member->availability == 'Available' ? 'text-green-600' : 'text-red-500'); ?> font-medium">
                                <?php echo e($member->availability ?? 'Available'); ?>

                            </span>
                        </td>

                        <?php if(auth()->user()->role === 'admin'): ?>
                            <td class="py-4 px-8 text-center">
                                <div class="flex items-center justify-center space-x-3">

                                    <!-- EDIT -->
                                    <button
                                        onclick="openEditModal(<?php echo e($member->id); ?>, <?php echo json_encode($member->name, 15, 512) ?>, <?php echo json_encode($member->availability, 15, 512) ?>)"
                                        class="flex items-center space-x-1 p-1.5 text-blue-500 hover:bg-blue-50 rounded-md transition">
                                        <i data-lucide="pencil-line" class="w-4 h-4"></i>
                                        <span class="text-xs font-semibold">Edit</span>
                                    </button>

                                    <!-- ARCHIVE -->
                                    <button
                                        onclick="openArchiveModal(<?php echo e($member->id); ?>, <?php echo json_encode($member->name, 15, 512) ?>)"
                                        class="flex items-center space-x-1 p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-md transition">
                                        <i data-lucide="archive" class="w-4 h-4"></i>
                                        <span class="text-xs font-semibold">Archive</span>
                                    </button>

                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php if(session('success')): ?>
    <div id="toast-success" class="fixed top-5 right-5 z-[100] flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-xl border-l-4 border-green-500 transform translate-y-0 transition-all duration-500" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
        </div>
        <div class="ml-3 text-sm font-bold text-gray-700"><?php echo e(session('success')); ?></div>
        <button type="button" onclick="closeToast()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5 inline-flex h-8 w-8">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
    <?php endif; ?>

    <script>
      lucide.createIcons();

      function toggleStaffModal() {
        const modal = document.getElementById('staffModal');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Optional: Close modal if user clicks outside the white box
    window.onclick = function(event) {
        const modal = document.getElementById('staffModal');
        if (event.target == modal) {
            toggleStaffModal();
        }
    }
    function toggleEditModal() {
        const modal = document.getElementById('editStaffModal');
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }

    function openEditModal(id, name, availability) {
        // 1. Set the form action URL
        const form = document.getElementById('editStaffForm');
        form.action = `/staff-list/${id}`; // Adjust this if your route is different

        // 2. Fill the input fields
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_availability').value = availability;

        // 3. Show the modal
        toggleEditModal();
    }
    function toggleDeleteModal() {
        const modal = document.getElementById('deleteStaffModal');
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }

    function openDeleteModal(id, name) {
        const form = document.getElementById('deleteStaffForm');
        const nameSpan = document.getElementById('delete_staff_name');

        // Set the action URL to match your staff-list path
        form.action = '/staff-list/' + id;

        // Update the name in the message
        nameSpan.innerText = name;

        toggleDeleteModal();
    }
    function closeToast() {
        const toast = document.getElementById('toast-success');
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            setTimeout(() => toast.remove(), 500);
        }
    }

    // Auto-close after 3 seconds
    if (document.getElementById('toast-success')) {
        setTimeout(closeToast, 3000);
    }
    function toggleArchiveModal() {
        const modal = document.getElementById('archiveStaffModal');
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }

    function openArchiveModal(id, name) {
        const form = document.getElementById('archiveStaffForm');
        const nameSpan = document.getElementById('archive_staff_name');

        form.action = '/staff-list/archive/' + id; // route
        nameSpan.innerText = name;

        toggleArchiveModal();
    }
    </script>
    <div id="staffModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all border-t-8 border-[#76a376]">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-2xl text-gray-800 uppercase tracking-wider">Register New Staff</h2>
                    <button onclick="toggleStaffModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form action="<?php echo e(route('staff.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php echo csrf_field(); ?>
                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Full Name</label>
                        <input type="text" name="name" placeholder="e.g. Mark Johnson" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-400 outline-none shadow-sm" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Availability Status</label>
                        <select name="availability" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-400 outline-none shadow-sm">
                            <option value="Available">Daily</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="toggleStaffModal()" class="px-6 py-3 rounded-lg font-bold text-gray-500 hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button type="submit" class="bg-[#00c2e0] text-white px-8 py-3 rounded-lg font-bold  hover:bg-[#3b5f3b] transition shadow-lg">
                            Register Staff
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="editStaffModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl border-t-8 border-[#76a376]">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-2xl text-gray-800 uppercase">Edit Staff Member</h2>
                    <button onclick="toggleEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form id="editStaffForm" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Full Name</label>
                        <input type="text" name="name" id="edit_name" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Availability Status</label>
                        <select name="availability" id="edit_availability" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-400 outline-none">
                            <option value="Available">Daily</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="toggleEditModal()" class="px-6 py-3 rounded-lg font-bold text-gray-500 hover:bg-gray-100">Cancel</button>
                        <button type="submit" class="bg-[#00c2e0] text-white px-8 py-3 rounded-lg font-bold  hover:bg-[#3b5f3b] transition shadow-lg">
                            Update Staff
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="archiveStaffModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md border-t-8 border-yellow-500">
            <div class="p-8 text-center">

                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-6">
                    <i data-lucide="archive" class="h-10 w-10 text-yellow-600"></i>
                </div>

                <h2 class="font-bold text-2xl text-gray-800 mb-2">Archive Staff?</h2>
                <p class="text-gray-500 mb-8">
                    Are you sure you want to archive
                    <span id="archive_staff_name" class="font-bold text-gray-800"></span>?
                </p>

                <form id="archiveStaffForm" method="POST" class="flex space-x-3 justify-center">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <button type="button" onclick="toggleArchiveModal()" class="px-6 py-3 rounded-lg font-bold text-gray-500 hover:bg-gray-100">
                        Cancel
                    </button>

                    <button type="submit" class="bg-yellow-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-yellow-600">
                        Confirm Archive
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/staff/index.blade.php ENDPATH**/ ?>