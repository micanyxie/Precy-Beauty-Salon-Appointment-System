<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#b6edaa] min-h-screen">
    <div class="bg-[#1f8716] p-4 flex items-center border-b border-gray-700"></div>
    <div class="max-w-4xl mx-auto mt-10 p-8 bg-[#99db93] shadow-2xl rounded-sm">
        <?php if(Route::has('login')): ?>
            <div class="space-x-4">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/admin')); ?>" class="font-black uppercase underline">Go to Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="font-black uppercase underline">Login</a>
                    <a href="<?php echo e(route('register')); ?>" class="font-black uppercase underline">Register</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/welcome.blade.php ENDPATH**/ ?>