<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#b6edaa] min-h-screen"> <div class="bg-[#1f8716] p-4 flex items-center border-b border-gray-700">
    <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-400 bg-white">
        <img src="<?php echo e(asset('storage/logo.png')); ?>" alt="Salon Logo" class="w-full h-full object-cover">
    </div>  <div class = "font-bold text-2xl text-white ml-4"> PRECY BEAUTY SALON AND NAIL CARE </div> </div>

   <div class="max-w-5xl mx-auto mt-10 p-8 bg-[#99db93] shadow-2xl rounded-sm">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-[#1b4332] uppercase">History: <?php echo e($name); ?></h2>
            <a href="/customers" class="bg-black text-white px-4 py-2 text-xs font-bold uppercase">Back to List</a>
        </div>

        <table class="w-full bg-white border-collapse">
            <thead>
                <tr class="bg-[#1f8716] text-white uppercase text-xs">
                    <th class="p-4 text-left">Date</th>
                    <th class="p-4 text-left">Service</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b">
                    <td class="p-4"><?php echo e(date('M d, Y', strtotime($app->appointment_at))); ?></td>
                    <td class="p-4 uppercase font-semibold"><?php echo e($app->service->name); ?></td>
                    <td class="p-4 text-center">
                        <span class="<?php echo e($app->status == 'Unpaid' ? 'text-red-600' : 'text-green-600'); ?> font-black uppercase">
                            <?php echo e($app->status); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</body>

</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/customers/show.blade.php ENDPATH**/ ?>