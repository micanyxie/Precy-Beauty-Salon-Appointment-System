<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#e9f5e9] font-sans antialiased flex min-h-screen">
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
                <a href="/services" class="flex items-center space-x-3 bg-[#e9f5e9] p-3 rounded shadow-inner border border-green-200">
                    <i data-lucide="scissors" class="w-5 h-5 text-green-700"></i>
                    <span class="font-bold text-green-800">Service</span>
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


        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-300 pb-2">Services</h2>

        <div class="flex flex-col md:flex-row items-center gap-4 mb-8">
            <div class="relative w-full md:w-72">
                <input type="text" id="serviceSearch" placeholder="Search service name..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl border-2 border-gray-200 bg-white text-sm focus:border-[#81b281] outline-none shadow-sm italic transition-all">
                <i data-lucide="search" class="w-4 h-4 absolute left-4 top-3 text-gray-400"></i>
            </div>

            <div class="relative w-full md:w-56">
                <select id="categoryFilter"
                    class="w-full pl-10 pr-4 py-2 rounded-xl border-2 border-gray-200 bg-white text-sm font-bold text-gray-700 focus:border-[#81b281] outline-none shadow-sm appearance-none transition-all">
                    <option value="all">All Categories</option>
                    <?php $__currentLoopData = \App\Models\ServiceCategory::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e(strtolower($cat->name)); ?>"><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <i data-lucide="filter" class="w-4 h-4 absolute left-4 top-3 text-gray-400"></i>
            </div>

            <?php if(auth()->user()->role === 'admin'): ?>
                <div class="md:ml-auto">
                    <button onclick="toggleServiceModal()" class="bg-[#00c2e0] text-white px-4 py-2 rounded-xl font-bold shadow-md hover:bg-[#00acc1] flex items-center space-x-2 transition-all">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        <span>Add Service</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>
       <div class="bg-white rounded-sm shadow-lg overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase font-bold border-b bg-gray-50/50">
                        <th class="py-4 px-8 w-1/3">Service Name</th>
                        <th class="py-4 px-8 text-center">Price</th>
                        <th class="py-4 px-8 text-center">Duration (mins)</th>
                        <th class="py-4 px-8 text-center">Category</th>
                        <?php if(auth()->user()->role === 'admin'): ?>
                            <th class="py-4 px-8 text-right">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="serviceList" class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-green-50/30 transition group">
                        <td class="py-4 px-8">
                            <span class="font-bold text-gray-700 uppercase tracking-wide text-sm"><?php echo e($service->name); ?></span>
                        </td>
                        <td class="py-4 px-8 text-center">
                            <span class="font-bold text-gray-700 text-sm italic">Php <?php echo e(number_format($service->price, 2)); ?></span>
                        </td>
                        <td class="py-4 px-8 text-center">
                            <span class="text-gray-500 text-sm font-medium italic"><?php echo e($service->duration); ?></span>
                        </td>
                        <td class="py-4 px-8 text-center">
                            <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold uppercase tracking-tighter">
                                <?php echo e($service->category->name ?? 'Uncategorized'); ?>

                            </span>
                        </td>
                        <?php if(auth()->user()->role === 'admin'): ?>
                            <td class="py-4 px-8">
                                <div class="flex justify-end">
                                    <button onclick="openEditModal(<?php echo e($service->id); ?>, '<?php echo e(addslashes($service->name)); ?>', <?php echo e($service->price); ?>, <?php echo e($service->duration); ?>, <?php echo e($service->service_category_id ?? 'null'); ?>)"
                                            class="flex items-center space-x-1 p-1.5 text-blue-500 hover:bg-blue-50 rounded-md transition">
                                        <i data-lucide="pencil-line" class="w-4 h-4"></i>
                                        <span class="text-xs font-semibold">Edit</span>
                                    </button>
                                <div>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr id="noResults" class="hidden">
                        <td colspan="5" class="py-10 text-center text-gray-400 italic">
                            No services found matching your criteria.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
    <div id="serviceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl border-t-8 border-[#76a376]">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-2xl text-gray-800 uppercase tracking-wider">Add New Service</h2>
                    <button onclick="toggleServiceModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <form action="<?php echo e(route('services.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php echo csrf_field(); ?>
                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Service Name</label>
                        <input type="text" name="name" placeholder="e.g. Eyelash Extension" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-400 outline-none shadow-sm" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Category</label>
                        <select name="service_category_id" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-400 outline-none shadow-sm" required>
                            <option value="" disabled selected>Select a Category</option>
                            <?php $__currentLoopData = \App\Models\ServiceCategory::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Price (Php)</label>
                        <input type="number" name="price" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-400 outline-none shadow-sm" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Duration (Mins)</label>
                        <input type="number" name="duration" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-400 outline-none shadow-sm" required>
                    </div>
                    <div class="md:col-span-2 flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="toggleServiceModal()" class="px-6 py-3 rounded-lg font-bold text-gray-500 hover:bg-gray-100 transition">Cancel</button>
                        <button type="submit" class="bg-[#00c2e0] text-white px-8 py-3 rounded-lg font-bold hover:bg-[#3b5f3b] transition shadow-lg">Save Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="editServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl border-t-8 border-[#76a376]">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-2xl text-gray-800 uppercase">Edit Service</h2>
                    <button onclick="toggleEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form id="editServiceForm" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Service Name</label>
                        <input type="text" name="name" id="edit_name" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Price</label>
                        <input type="number" name="price" id="edit_price" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Duration</label>
                        <input type="number" name="duration" id="edit_duration" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-bold text-gray-600 mb-1 italic">Category</label>
                        <select name="service_category_id" id="edit_category" class="border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-400 outline-none" required>
                            <?php $__currentLoopData = \App\Models\ServiceCategory::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="toggleEditModal()" class="px-6 py-3 rounded-lg font-bold text-gray-500 hover:bg-gray-100">Cancel</button>
                        <button type="submit" class="bg-[#00c2e0] text-white px-8 py-3 rounded-lg font-bold hover:bg-[#3b5f3b] transition shadow-lg">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="deleteServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md border-t-8 border-red-500">
            <div class="p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                    <i data-lucide="alert-triangle" class="h-10 w-10 text-red-600"></i>
                </div>
                <h2 class="font-bold text-2xl text-gray-800 mb-2">Delete Service?</h2>
                <p class="text-gray-500 mb-8">Are you sure you want to delete <span id="delete_service_name" class="font-bold text-gray-800"></span>? This cannot be undone.</p>
                <form id="deleteServiceForm" method="POST" class="flex space-x-3 justify-center">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="button" onclick="toggleDeleteModal()" class="px-6 py-3 rounded-lg font-bold text-gray-500 hover:bg-gray-100 transition">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition shadow-lg">Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function toggleServiceModal() {
            const modal = document.getElementById('serviceModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function toggleEditModal() {
            const modal = document.getElementById('editServiceModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        // Add 'categoryId' to the arguments
        function openEditModal(id, name, price, duration, categoryId) {
            const form = document.getElementById('editServiceForm');
            form.action = `/services/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_duration').value = duration;

            // Fixed: Target the correct ID "edit_category"
            const categorySelect = document.getElementById('edit_category');
            if (categorySelect && categoryId) {
                categorySelect.value = categoryId;
            }

            toggleEditModal();
        }

        function toggleDeleteModal() {
            const modal = document.getElementById('deleteServiceModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function openDeleteModal(id, name) {
            const form = document.getElementById('deleteServiceForm');
            const nameSpan = document.getElementById('delete_service_name');

            // Ensure the ID is being passed correctly to the URL
            form.action = '/services/' + id;

            // Update the name in the warning message
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

        if (document.getElementById('toast-success')) {
            setTimeout(closeToast, 3000);
        }

        // Close on outside click
        window.onclick = function(event) {
            const sm = document.getElementById('serviceModal');
            const em = document.getElementById('editServiceModal');
            const dm = document.getElementById('deleteServiceModal');
            if (event.target == sm) toggleServiceModal();
            if (event.target == em) toggleEditModal();
            if (event.target == dm) toggleDeleteModal();
        }
        // Service Search Logic
        const searchInput = document.getElementById('serviceSearch');
        const categoryFilter = document.getElementById('categoryFilter');
        const tableRows = document.querySelectorAll('#serviceList tr');

        function filterServices() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = categoryFilter.value.toLowerCase();

            tableRows.forEach(row => {
                // Cells: 0 = Name, 3 = Category
                const serviceName = row.cells[0].textContent.toLowerCase();
                const serviceCategory = row.cells[3].textContent.toLowerCase().trim();

                const matchesSearch = serviceName.includes(searchTerm);
                const matchesCategory = (selectedCategory === 'all' || serviceCategory === selectedCategory);

                // Show row only if it matches BOTH criteria
                if (matchesSearch && matchesCategory) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
            const visibleRows = Array.from(tableRows).filter(tr => tr.style.display !== 'none').length;
            document.getElementById('noResults').classList.toggle('hidden', visibleRows > 0);
        }

        // Listen for typing and dropdown changes
        searchInput.addEventListener('keyup', filterServices);
        categoryFilter.addEventListener('change', filterServices);
    </script>
</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/services/index.blade.php ENDPATH**/ ?>