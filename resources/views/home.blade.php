<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Precy Beauty Salon & Nail Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#f0f9f0]">

    <section
        class="relative text-white py-24 px-6 text-center bg-cover bg-center"
        style="background-image: url('{{ asset('storage/home_bg.png') }}');"
    >

        <div class="relative z-10">
            <h1 class="text-6xl font-black uppercase mb-4">Precy Beauty Salon AND NAIL CARE</h1>
            <p class="text-xl mb-10 max-w-2xl mx-auto">
                Experience premium hair and nail care in a relaxing sanctuary. Your beauty is our priority.
            </p>

            <a href="/book" class="bg-black text-white px-12 py-5 font-bold uppercase tracking-widest hover:scale-105 transition-transform inline-block shadow-2xl">
                Book An Appointment
            </a>

            <div class="mt-4">
                <p class="text-white opacity-80 text-sm">Reschedule or cancel your appointment?</p>
                <a href="/find-appointment" class="text-white underline font-bold hover:text-black">
                    Click here
                </a>
            </div>
        </div>
    </section>


    <section class="max-w-6xl mx-auto py-20 px-6">
        <h2 class="text-3xl font-black mb-12 uppercase border-b-4 border-black inline-block">Services Offered</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="bg-white p-6 border-2 border-black flex justify-between items-center shadow-[8px_8px_0px_0px_rgba(31,135,22,1)]">
                <span class="text-xl font-bold uppercase">{{ $service->name }}</span>
            </div>
            @endforeach
        </div>
    </section>

    <section class="bg-[#b6edaa] py-20 px-6">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16">
            <div>
                <h3 class="text-4xl font-black uppercase mb-8">Contact Us</h3>
                <p class="font-bold text-lg mb-4 italic">"Transforming beauty one client at a time."</p>
                <div class="space-y-4">
                    <p id="phone" class="flex items-center gap-3"><i data-lucide="phone"></i> +63 936 234 3892</p>
                    <p id="address" class="flex items-center gap-3"><i data-lucide="map-pin"></i> Ria bldg., Door #1, JR. Borja St., Cogon Market,Cagayan de Oro City, Philippines</p>
                    <p id="hours" class="flex items-center gap-3"><i data-lucide="clock"></i> Mon-Sat: 9:00 AM - 6:00 PM</p>
                </div>
            </div>
            <div class="border-4 border-black h-64 bg-gray-300 flex items-center justify-center font-bold uppercase">
                <iframe src="https://www.google.com/maps/embed?pb=!4v1773838546036!6m8!1m7!1s5mErrDkDCr3XAofZzUNyRg!2m2!1d8.47668884042893!2d124.6522532565913!3f263.40268!4f0!5f0.7820865974627469" width="600" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    <script>lucide.createIcons();</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. Check if the session has the message
        @if(session('booking_success'))
            console.log("Session detected!"); // Check your browser console (F12) to see if this runs

            Swal.fire({
                title: 'BOOKING CONFIRMED!',
                text: "{{ session('booking_success') }}",
                icon: 'success',
                confirmButtonColor: '#1f8716',
                confirmButtonText: 'GREAT!',
                customClass: {
                    popup: 'border-4 border-black rounded-none shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]',
                    title: 'font-black uppercase',
                    confirmButton: 'bg-black text-white px-10 py-3 font-black uppercase'
                }
            });
        @endif
    </script>

</body>
</html>
