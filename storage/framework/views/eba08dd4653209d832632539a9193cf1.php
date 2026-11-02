<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule - Precy Beauty Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        /* Custom scrollbar for better aesthetics */
        #time-slots-container::-webkit-scrollbar { width: 6px; }
        #time-slots-container::-webkit-scrollbar-thumb { background: #1f8716; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#e9f5e9] min-h-screen pb-20">
    <header class="bg-[#1f8716] shadow-lg sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white bg-white shadow-inner">
                    <img src="<?php echo e(asset('storage/logo.png')); ?>" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <h1 class="text-white font-bold text-xl tracking-tight leading-tight uppercase">Precy Beauty Salon</h1>
                    <p class="text-green-100 text-[10px] uppercase tracking-widest font-medium">& Nail Care Services</p>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto mt-12 px-4">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            <div class="flex-1 w-full glass-card rounded-[2.5rem] shadow-2xl border border-white overflow-hidden">
                <div class="bg-[#1f8716] p-8 text-center relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-3xl font-black text-white uppercase tracking-tighter">Reschedule</h2>
                        <p class="text-green-100 text-[10px] uppercase font-bold tracking-[0.2em] opacity-80">Modification Mode</p>
                    </div>
                </div>

                <form action="<?php echo e(route('appointment.update', $appointment->id)); ?>" method="POST" class="p-8 md:p-12 space-y-10">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="space-y-4">
                        <label class="flex items-center text-xs font-black text-gray-400 uppercase tracking-widest">
                            <i data-lucide="calendar" class="w-4 h-4 mr-2 text-[#1f8716]"></i> 1. Select New Date
                        </label>
                        <?php $tomorrow = \Carbon\Carbon::now('Asia/Manila')->addDay()->format('Y-m-d'); ?>
                        <input type="date" name="appointment_date" id="appointment_date"
                            min="<?php echo e($tomorrow); ?>" value="<?php echo e($tomorrow); ?>"
                            class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 focus:border-[#1f8716] focus:ring-4 focus:ring-green-100 outline-none transition-all font-bold text-lg text-gray-700 shadow-sm" required>
                    </div>

                    <div class="space-y-6">
                        <label class="flex items-center text-xs font-black text-gray-400 uppercase tracking-widest">
                            <i data-lucide="clock" class="w-4 h-4 mr-2 text-[#1f8716]"></i> 2. Choose Available Slot
                            <span id="holiday-notice" class="ml-auto text-[10px] text-red-500 hidden font-black tracking-widest uppercase animate-pulse">
                                <i data-lucide="info" class="w-3 h-3 inline mr-1"></i> <span id="notice-text"></span>
                            </span>
                        </label>

                        <div id="time-slots-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            <?php $__currentLoopData = ['09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00', '13:00:00', '13:30:00', '14:00:00', '14:30:00', '15:00:00', '15:30:00', '16:00:00']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="relative group">
                                    <input type="radio" name="appointment_time" value="<?php echo e($slot); ?>" class="peer hidden"
                                        <?php echo e(\Carbon\Carbon::parse($appointment->appointment_at)->format('H:i:s') == $slot ? 'checked' : ''); ?>>
                                    <div class="border-2 border-gray-100 rounded-2xl py-4 text-center text-sm font-black transition-all bg-white text-gray-700 hover:border-[#1f8716] peer-checked:bg-[#1f8716] peer-checked:text-white peer-checked:border-[#1f8716] shadow-sm cursor-pointer hover:shadow-md">
                                        <?php echo e(date('h:i A', strtotime($slot))); ?>

                                    </div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                        <a href="<?php echo e(route('appointment.list', ['phone' => $appointment->phone_number])); ?>"
                           class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] hover:text-red-500 transition-colors">
                            <i data-lucide="chevron-left" class="w-3 h-3 inline mr-1"></i> Cancel Changes
                        </a>
                        <button type="submit" class="w-full md:w-auto bg-[#1B211A] text-white px-12 py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-[#1f8716] shadow-xl transition-all duration-300">
                            Confirm New Schedule
                        </button>
                    </div>
                </form>
            </div>

            <div class="w-full lg:w-96 sticky top-24">
                <div class="bg-[#1f8716] text-white p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                    <i data-lucide="scissors" class="absolute -right-6 -bottom-6 w-32 h-32 opacity-10 -rotate-12"></i>

                    <h3 class="font-black uppercase mb-8 border-b border-white/20 pb-4 text-xs tracking-widest flex items-center justify-between">
                        Current Details <i data-lucide="info" class="w-4 h-4"></i>
                    </h3>

                    <div class="space-y-8">
                        <div>
                            <p class="text-[9px] uppercase font-black opacity-60 tracking-[0.2em] mb-2">Customer</p>
                            <p class="font-bold text-xl"><?php echo e($appointment->first_name); ?> <?php echo e($appointment->last_name); ?></p>
                        </div>

                        <div>
                            <p class="text-[9px] uppercase font-black opacity-60 tracking-[0.2em] mb-2">Services</p>
                            <p class="font-bold text-base leading-tight"><?php echo e($appointment->services->pluck('name')->implode(', ')); ?></p>
                        </div>

                        <div class="bg-white/10 p-5 rounded-2xl border border-white/10">
                            <p class="text-[9px] uppercase font-black opacity-60 tracking-[0.2em] mb-2">Original Time</p>
                            <p class="font-black text-xl"><?php echo e(\Carbon\Carbon::parse($appointment->appointment_at)->format('M d, Y')); ?></p>
                            <p class="text-green-200 font-bold text-lg"><?php echo e(\Carbon\Carbon::parse($appointment->appointment_at)->format('h:i A')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-6 glass-card rounded-[2rem] border border-white shadow-lg">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-amber-500 mt-1"></i>
                        <p class="text-[11px] text-gray-500 font-bold leading-relaxed">
                            <span class="text-gray-800 uppercase block mb-1">Policy Reminder</span>
                            Changes made here will overwrite your previous slot immediately.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();

        const dateEl = document.getElementById('appointment_date');
        const timeInputs = () => document.querySelectorAll('input[name="appointment_time"]');
        const totalDuration = <?php echo e($appointment->services->sum('duration')); ?>;

        dateEl.addEventListener('change', fetchBusySlots);

        function fetchBusySlots() {
            const date = dateEl.value;
            const noticeEl = document.getElementById('holiday-notice');
            const textEl = document.getElementById('notice-text');
            if (!date) return;

            noticeEl.classList.remove('hidden');
            textEl.innerText = "Checking...";

            fetch(`/get-busy-slots?date=${date}`)
                .then(res => res.json())
                .then(data => {
                    noticeEl.classList.add('hidden');
                    const allInputs = timeInputs();
                    allInputs.forEach(input => {
                        input.removeAttribute('data-initially-booked');
                        input.disabled = false;
                    });

                    if (data.isHoliday) {
                        noticeEl.classList.remove('hidden');
                        textEl.innerText = `CLOSED: ${data.reason}`;
                        allInputs.forEach(input => input.setAttribute('data-initially-booked', 'true'));
                    } else if (Array.isArray(data)) {
                        allInputs.forEach(input => {
                            if (data.includes(input.value)) {
                                input.setAttribute('data-initially-booked', 'true');
                            }
                        });
                    }
                    updateTimeSlotsUI(data.isHoliday ? [] : data);
                });
        }

        function updateTimeSlotsUI(initiallyBooked) {
            const allInputs = timeInputs();
            allInputs.forEach(input => {
                const container = input.nextElementSibling;
                const startTimeStr = input.value;
                const [h, m] = startTimeStr.split(':').map(Number);
                let isBlocked = input.hasAttribute('data-initially-booked');

                if (!isBlocked && totalDuration > 30) {
                    for (let offset = 30; offset < totalDuration; offset += 30) {
                        let checkTime = new Date();
                        checkTime.setHours(h, m + offset, 0);
                        let checkTimeStr = checkTime.getHours().toString().padStart(2, '0') + ':' +
                                         checkTime.getMinutes().toString().padStart(2, '0') + ':00';
                        if (initiallyBooked.includes(checkTimeStr)) {
                            isBlocked = true;
                            break;
                        }
                    }
                }

                if (isBlocked) {
                    input.disabled = true;
                    if (input.checked) input.checked = false;
                    container.classList.add('bg-gray-100', 'text-gray-300', 'cursor-not-allowed', 'opacity-50', 'pointer-events-none');
                    container.classList.remove('bg-white', 'text-gray-700', 'hover:border-[#1f8716]');
                } else {
                    input.disabled = false;
                    container.classList.remove('bg-gray-100', 'text-gray-300', 'cursor-not-allowed', 'opacity-50', 'pointer-events-none');
                    container.classList.add('bg-white', 'text-gray-700');
                }
            });
        }

        window.addEventListener('DOMContentLoaded', fetchBusySlots);
    </script>
</body>
</html>
<?php /**PATH C:\Users\Dhabz\salon-app\resources\views/appointment/edit.blade.php ENDPATH**/ ?>