<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Precy Beauty Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-[#e9f5e9] min-h-screen pb-20">

    <header class="bg-[#1f8716] shadow-lg sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white bg-white shadow-inner">
                    <img src="{{ asset('storage/logo.png') }}" alt="Salon Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <h1 class="text-white font-bold text-xl tracking-tight leading-tight uppercase">Precy Beauty Salon</h1>
                    <p class="text-green-100 text-[10px] uppercase tracking-widest font-medium">& Nail Care Services</p>
                </div>
            </div>
            <div class="hidden md:block text-green-50 text-sm font-medium italic">
                Professional Care for Your Natural Beauty
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto mt-12 px-4">
        <div class="glass-card rounded-3xl shadow-2xl border border-white overflow-hidden">

            <div class="bg-[#1f8716] p-10 text-center relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter mb-2">Appointment Booking</h2>
                    <p class="text-green-100 text-sm opacity-80 uppercase tracking-widest font-bold">Reserve your slot in seconds</p>
                </div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full"></div>
            </div>
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                    <p class="font-bold uppercase text-xs mb-2">Please fix the following:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customer.store') }}" method="POST" class="p-8 md:p-12 space-y-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div class="space-y-4 col-span-1 md:col-span-2">
                        <label class="flex items-center text-sm font-bold text-gray-700 uppercase tracking-wide">
                            <i data-lucide="scissors" class="w-4 h-4 mr-2 text-[#1f8716]"></i> Select Services (Multiple Allowed)
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($servicesByCategory as $categoryName => $services)
                                <div class="col-span-1 md:col-span-2 mt-2">
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">{{ $categoryName }}</h3>
                                </div>
                                @foreach($services as $service)
                                    <label class="relative flex items-center p-4 bg-white border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-green-200 transition-all has-[:checked]:border-[#1f8716] has-[:checked]:bg-green-50 group">
                                        <input type="checkbox"
                                            name="service_ids[]"
                                            value="{{ $service->id }}"
                                            data-category="{{ $categoryName }}" {{-- Add this line --}}
                                            data-duration="{{ $service->duration }}"
                                            data-price="{{ $service->price }}"
                                            data-name="{{ $service->name }}"
                                            class="service-checkbox hidden"
                                            onchange="handleServiceSelection(this)">

                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <span class="font-bold text-gray-800 group-has-[:checked]:text-[#1f8716]">{{ $service->name }}</span>
                                                <span class="text-[#1f8716] font-black text-sm">₱{{ number_format($service->price, 0) }}</span>
                                            </div>
                                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-tight">{{ $service->duration }} mins</p>
                                        </div>

                                        <div class="ml-4 w-6 h-6 border-2 border-gray-200 rounded-full flex items-center justify-center group-has-[:checked]:bg-[#1f8716] group-has-[:checked]:border-[#1f8716] transition-colors">
                                            <i data-lucide="check" class="w-4 h-4 text-white opacity-0 group-has-[:checked]:opacity-100"></i>
                                        </div>
                                    </label>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-bold text-gray-700 uppercase tracking-wide">
                            <i data-lucide="user" class="w-4 h-4 mr-2 text-[#1f8716]"></i> Preferred Staff
                        </label>
                        <select name="staff_id" class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-[#1f8716] focus:ring-0 outline-none transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected data-duration="0">-- Choose a Staff --</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        // Force the timezone to Manila to ensure 'tomorrow' is actually tomorrow in PH
                        $tomorrow = \Carbon\Carbon::now('Asia/Manila')->addDay()->format('Y-m-d');
                    @endphp

                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-bold text-gray-700 uppercase tracking-wide">
                            <i data-lucide="calendar" class="w-4 h-4 mr-2 text-[#1f8716]"></i> Select Date
                        </label>
                        <input type="date"
                            name="appointment_date"
                            id="appointment_date"
                            {{-- Strictly enforce tomorrow as the minimum --}}
                            min="{{ $tomorrow }}"
                            value="{{ $tomorrow }}"
                            class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-[#1f8716] focus:ring-2 focus:ring-green-100 outline-none transition-all shadow-sm font-semibold text-gray-700"
                            required>
                        <p class="text-[10px] text-[#1f8716] font-bold uppercase tracking-tighter italic">
                            * We only accept bookings at least 24 hours in advance.
                        </p>
                    </div>
                    <div class="space-y-2 col-span-1 md:col-span-2">
                        <label class="flex items-center text-sm font-bold text-gray-700 uppercase tracking-wide">
                            <i data-lucide="user-plus" class="w-4 h-4 mr-2 text-[#1f8716]"></i> Customer Name
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="first_name" placeholder="First Name"
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-[#1f8716] outline-none transition-all shadow-sm" required>
                            <input type="text" name="last_name" placeholder="Last Name"
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-[#1f8716] outline-none transition-all shadow-sm" required>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center text-sm font-bold text-gray-700 uppercase tracking-wide">
                        <i data-lucide="clock" class="w-4 h-4 mr-2 text-[#1f8716]"></i>
                        Available Time Slots
                        <span id="holiday-notice" class="ml-4 text-sm font-bold flex items-center">
                            <i data-lucide="loader-2" class="w-4 h-4 mr-2 animate-spin text-blue-600"></i>
                            <span id="notice-text"></span>
                        </span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        @foreach($slots as $slot)
                            @php $isBusy = in_array($slot, $busyTimes); @endphp
                            <label class="relative group">
                                <input type="radio" name="appointment_time" value="{{ $slot }}" class="peer hidden" {{ $isBusy ? 'disabled data-initially-booked' : '' }}>
                                <div class="border-2 border-gray-100 rounded-xl py-3 text-center text-sm font-bold transition-all
                                    {{ $isBusy ? 'bg-gray-100 text-gray-400 cursor-not-allowed italic' : 'bg-white text-gray-700 hover:border-[#1f8716] peer-checked:bg-[#1f8716] peer-checked:text-white peer-checked:border-[#1f8716] shadow-sm cursor-pointer' }}">
                                    {{ date('h:i A', strtotime($slot)) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-bold text-gray-700 uppercase tracking-wide">
                            <i data-lucide="phone" class="w-4 h-4 mr-2 text-[#1f8716]"></i> Phone Number
                        </label>
                        <input type="text"
                            id="phone_number"
                            name="phone_number"
                            placeholder="09123456789"
                            maxlength="11"
                            inputmode="numeric"
                            class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:ring-0 outline-none transition-all shadow-sm font-mono tracking-widest"
                            required>
                        <p id="phone-error" class="text-[10px] font-bold uppercase hidden"></p>
                    </div>
                </div>

                <div id="booking-summary" class="hidden mb-6 p-6 bg-green-50/50 border-2 border-dashed border-green-200 rounded-2xl shadow-inner">
                    <h4 class="text-xs font-black text-[#1f8716] uppercase tracking-widest mb-4 flex items-center">
                        <i data-lucide="receipt" class="w-4 h-4 mr-2"></i> Booking Summary
                    </h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 font-medium italic">Service Selected</span>
                            <span id="summary-service-name" class="font-bold text-gray-800">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 font-medium italic">Estimated Schedule</span>
                            <span id="summary-time-range" class="font-bold text-gray-800">-</span>
                        </div>
                        <div class="pt-3 border-t border-green-100 flex justify-between items-baseline">
                            <span class="font-black text-gray-800 uppercase text-xs">Total Amount Due</span>
                            <span id="summary-price" class="text-2xl font-black text-[#1f8716] tracking-tighter">₱0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[12px] text-gray-800 font-medium italic">*Pay in cash only at salon</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest max-w-xs text-center md:text-left leading-relaxed">
                        * By clicking confirm, you agree to our salon terms and cancellation policy.
                    </p>
                    <button id="submitButton" type="submit" class="w-full md:w-auto bg-[#1B211A] text-white px-12 py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-[#1f8716] hover:shadow-[0_20px_50px_rgba(31,135,22,0.3)] hover:-translate-y-1 transition-all duration-300">
                        Confirm Appointment
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // --- DYNAMIC SETTINGS FROM ADMIN ---
        const MAX_SERVICES_ALLOWED = {{ $settings->max_services_per_appointment ?? 2 }};

        const dateEl = document.getElementById('appointment_date');
        const staffEl = document.querySelector('select[name="staff_id"]');
        const serviceCheckboxes = document.querySelectorAll('.service-checkbox');

        // 1. Listeners for Date and Staff
        [dateEl, staffEl].forEach(el => {
            el.addEventListener('change', () => {
                fetchBusySlots();
                updateSummary();
            });
        });

        // 2. Listeners for Service Checkboxes
        serviceCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                handleServiceSelection(cb);
                updateSummary();
            });
        });

        // 3. Listener for Time Slot selection (using delegation)
        document.addEventListener('change', (e) => {
            if (e.target.name === 'appointment_time') {
                updateSummary();
            }
        });

        function fetchBusySlots() {
            const staffId = staffEl.value;
            const date = dateEl.value;
            const noticeEl = document.getElementById('holiday-notice');
            const textEl = document.getElementById('notice-text');

            if (!date) return;

            // Show Loader
            if (noticeEl && textEl) {
                noticeEl.classList.remove('hidden', 'text-red-600', 'animate-pulse');
                noticeEl.classList.add('text-blue-600');
                textEl.innerText = " Checking availability...";
                if (window.lucide) { lucide.createIcons(); }
            }

            fetch(`/get-busy-slots?staff_id=${staffId}&date=${date}`)
                .then(res => res.json())
                .then(data => {
                    const allInputs = document.querySelectorAll('input[name="appointment_time"]');

                    // Hide notice by default
                    if (noticeEl) noticeEl.classList.add('hidden');

                    // Reset all slots first
                    allInputs.forEach(input => {
                        input.removeAttribute('data-initially-booked');
                        input.disabled = false;
                    });

                    if (data.isHoliday) {
                        if (noticeEl && textEl) {
                            noticeEl.classList.remove('hidden', 'text-blue-600');
                            noticeEl.classList.add('text-red-600', 'animate-pulse');
                            textEl.innerText = ` (CLOSED: ${data.reason})`;
                        }
                        // Mark all as booked to trigger the "disabled" UI
                        allInputs.forEach(input => input.setAttribute('data-initially-booked', 'true'));
                    }
                    else if (Array.isArray(data)) {
                        allInputs.forEach(input => {
                            if (data.includes(input.value)) {
                                input.setAttribute('data-initially-booked', 'true');
                            }
                        });
                    }

                    // IMPORTANT: This handles the actual CSS/Graying out
                    updateTimeSlots();
                })
                .catch(err => {
                    console.error("Fetch Error:", err);
                    if (noticeEl) noticeEl.classList.add('hidden');
                });
        }

        // THE MISSING FUNCTION THAT WAS CAUSING ERRORS
        function updateTimeSlots() {
            const allInputs = document.querySelectorAll('input[name="appointment_time"]');
            const checkedBoxes = Array.from(document.querySelectorAll('.service-checkbox:checked'));

            // Calculate total duration of currently selected services
            let totalDuration = 0;
            checkedBoxes.forEach(cb => {
                totalDuration += parseInt(cb.getAttribute('data-duration') || 0);
            });

            // Get list of already booked times from the API data
            const initiallyBooked = Array.from(allInputs)
                .filter(i => i.hasAttribute('data-initially-booked'))
                .map(i => i.value);

            allInputs.forEach(input => {
                const container = input.nextElementSibling;
                const startTimeStr = input.value;
                const [h, m] = startTimeStr.split(':').map(Number);

                let isBlocked = input.hasAttribute('data-initially-booked');

                // NEW LOGIC: Check if duration clashes with future appointments
                if (!isBlocked && totalDuration > 0) {
                    for (let offset = 30; offset < totalDuration; offset += 30) {
                        let checkTime = new Date();
                        checkTime.setHours(h, m + offset, 0);
                        let checkTimeStr = checkTime.getHours().toString().padStart(2, '0') + ':' +
                                        checkTime.getMinutes().toString().padStart(2, '0');

                        if (initiallyBooked.includes(checkTimeStr)) {
                            isBlocked = true; // This slot's duration overlaps a future booking
                            break;
                        }
                    }
                }

                if (isBlocked) {
                    input.disabled = true;
                    if (input.checked) input.checked = false;
                    container.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed', 'italic', 'opacity-50', 'pointer-events-none');
                    container.classList.remove('bg-white', 'text-gray-700', 'hover:border-[#1f8716]', 'cursor-pointer');
                } else {
                    input.disabled = false;
                    container.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed', 'italic', 'opacity-50', 'pointer-events-none');
                    container.classList.add('bg-white', 'text-gray-700', 'hover:border-[#1f8716]', 'cursor-pointer');
                }
            });
            updateSummary();
        }

        function handleServiceSelection(clickedCheckbox) {
            const checkedBoxes = Array.from(document.querySelectorAll('.service-checkbox:checked'));

            // Max Services Limit
            if (checkedBoxes.length > MAX_SERVICES_ALLOWED) {
                clickedCheckbox.checked = false;
                alert(`You can only select a maximum of ${MAX_SERVICES_ALLOWED} services.`);
                return;
            }

            // Category Limit (1 per category)
            const category = clickedCheckbox.getAttribute('data-category');
            const sameCat = checkedBoxes.filter(cb => cb !== clickedCheckbox && cb.getAttribute('data-category') === category);

            if (clickedCheckbox.checked && sameCat.length > 0) {
                clickedCheckbox.checked = false;
                alert(`You already selected a service from "${category}".`);
                return;
            }

            updateVisualLimits(checkedBoxes, document.querySelectorAll('.service-checkbox'));
            updateTimeSlots();
        }

        function updateVisualLimits(checkedBoxes, allCheckboxes) {
            const selectedCategories = checkedBoxes.map(cb => cb.getAttribute('data-category'));
            const totalReached = checkedBoxes.length >= MAX_SERVICES_ALLOWED;

            allCheckboxes.forEach(cb => {
                const container = cb.closest('label');
                const category = cb.getAttribute('data-category');

                if (!cb.checked && (totalReached || selectedCategories.includes(category))) {
                    container.classList.add('opacity-40', 'grayscale', 'pointer-events-none');
                } else {
                    container.classList.remove('opacity-40', 'grayscale', 'pointer-events-none');
                }
            });
        }

        function updateSummary() {
            const summaryBox = document.getElementById('booking-summary');
            const checkedBoxes = Array.from(document.querySelectorAll('.service-checkbox:checked'));
            const selectedTimeInput = document.querySelector('input[name="appointment_time"]:checked');

            if (checkedBoxes.length === 0 || !selectedTimeInput) {
                summaryBox.classList.add('hidden');
                return;
            }

            let totalPrice = 0;
            let totalDuration = 0;
            let names = [];

            checkedBoxes.forEach(cb => {
                totalPrice += parseFloat(cb.getAttribute('data-price') || 0);
                totalDuration += parseInt(cb.getAttribute('data-duration') || 0);
                names.push(cb.getAttribute('data-name'));
            });

            const startTimeStr = selectedTimeInput.value;
            const [hours, minutes] = startTimeStr.split(':').map(Number);
            const startDate = new Date();
            startDate.setHours(hours, minutes, 0);
            const endDate = new Date(startDate.getTime() + totalDuration * 60000);

            const timeOpts = { hour: '2-digit', minute: '2-digit', hour12: true };

            document.getElementById('summary-service-name').innerText = names.join(', ');
            document.getElementById('summary-time-range').innerText = `${startDate.toLocaleTimeString([], timeOpts)} - ${endDate.toLocaleTimeString([], timeOpts)} (${totalDuration} mins)`;
            document.getElementById('summary-price').innerText = `₱${totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2})}`;

            summaryBox.classList.remove('hidden');
        }

        // Phone Validation
        // Inside your <script> at the bottom of the blade file
        const phoneInput = document.getElementById('phone_number');
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, ''); // Removes non-numbers

            const errorEl = document.getElementById('phone-error');
            if (this.value.length === 11 && this.value.startsWith('09')) {
                errorEl.innerText = "✓ Valid Number";
                errorEl.className = "text-[10px] font-bold uppercase text-green-600";
                errorEl.classList.remove('hidden');
            } else {
                errorEl.innerText = "Enter 11 digits starting with 09";
                errorEl.className = "text-[10px] font-bold uppercase text-red-500";
                errorEl.classList.remove('hidden');
            }
        });

        // Run on Load
        window.addEventListener('DOMContentLoaded', () => {
            fetchBusySlots();
        });
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedServices = document.querySelectorAll('.service-checkbox:checked').length;
            const selectedTime = document.querySelector('input[name="appointment_time"]:checked');

            if (selectedServices === 0) {
                e.preventDefault();
                alert('Please select at least one service.');
            } else if (!selectedTime) {
                e.preventDefault();
                alert('Please select a time slot.');
            } else {
                console.log("Form is valid, submitting...");
            }
        });
    </script>
</body>
</html>
