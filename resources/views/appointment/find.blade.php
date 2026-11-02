<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#b6edaa] min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-white border-2 border-black p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
        <h2 class="text-2xl font-black uppercase mb-2">Manage Booking</h2>
        <p class="text-xs font-bold uppercase mb-6 text-gray-500">Find your appointments by phone number.</p>

        <form action="{{ route('appointment.search') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-xs font-black uppercase mb-1">Phone Number</label>
                <input type="text" name="phone" placeholder="e.g. 09123456789" required
                       class="w-full p-3 border-2 border-black focus:outline-none focus:bg-green-50">
            </div>

            <button type="submit" class="w-full bg-[#1f8716] text-white py-3 font-black uppercase tracking-widest hover:bg-black transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                Search Appointments
            </button>
        </form>

        @if(session('error'))
            <p class="mt-4 text-red-600 font-bold text-xs uppercase">{{ session('error') }}</p>
        @endif

        <a href="/" class="block mt-6 text-center text-xs font-black uppercase underline">Back to Home</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 1. Check if the session has the message
        @if(session('reschedule_success'))
            console.log("Session detected!"); // Check your browser console (F12) to see if this runs

            Swal.fire({
                title: 'APPOINTMENT RESCHEDULED!',
                text: "{{ session('reschedule_success') }}",
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
    <script>
        // 1. Check if the session has the message
        @if(session('cancel_success'))
            console.log("Session detected!"); // Check your browser console (F12) to see if this runs

            Swal.fire({
                title: 'APPOINTMENT CANCELLED!',
                text: "{{ session('cancel_success') }}",
                icon: 'success',
                confirmButtonColor: '#1f8716',
                confirmButtonText: 'UNDERSTOOD',
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
