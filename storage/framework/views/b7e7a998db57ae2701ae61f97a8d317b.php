<div class="flex justify-between items-center mb-10">
    <div class="relative w-72">
        <input type="text"
               id="<?php echo e($searchId ?? 'generalSearch'); ?>"
               placeholder="<?php echo e($placeholder ?? 'Search...'); ?>"
               class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-[#81b281] outline-none shadow-sm italic">
        <i data-lucide="search" class="w-4 h-4 absolute left-4 top-3 text-gray-400"></i>
    </div>

    <?php echo $__env->make('partials.user_dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/partials/header.blade.php ENDPATH**/ ?>