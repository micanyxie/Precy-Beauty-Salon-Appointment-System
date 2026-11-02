<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#b6edaa] p-6">

    <div class="max-w-3xl mx-auto p-8 bg-white border-2 border-black shadow-[10px_10px_0px_0px_rgba(31,135,22,1)]">
        <div class="mb-6">
            <a href="/" class="inline-flex items-center gap-2 bg-[#8dcc87] border-black px-4 py-2 text-xs font-black uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Home
            </a>
        </div>
        <div class="flex justify-between items-center mb-8 border-b-2 border-black pb-4">
            <h2 class="text-2xl font-black uppercase">My Appointments</h2>
            <span class="bg-[#b6edaa] px-3 py-1 border border-black font-bold text-sm">{{ $phone }}</span>
        </div>

       @foreach($appointments as $app)
        <div class="bg-white border-2 border-black p-6 mb-6 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[-2px] hover:translate-y-[-2px] hover:shadow-[8px_8px_0px_0px_rgba(31,135,22,1)] transition-all">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-grow">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="{{ $app->approval_status == 'Approved' ? 'bg-[#1f8716]' : 'bg-yellow-500' }} text-white text-[10px] font-black px-2 py-0.5 uppercase tracking-widest">
                            {{ $app->approval_status }}
                        </span>
                        <span class="text-xs font-bold text-gray-400">ID: #00{{ $app->id }}</span>
                    </div>

                    <h3 class="font-black text-xl uppercase leading-tight mb-1">
                        {{ $app->services->pluck('name')->implode(', ') }}
                    </h3>

                    <div class="flex items-center text-[#1f8716] font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($app->appointment_at)->format('F d, Y @ h:i A') }}
                    </div>

                    <div class="text-sm font-black text-gray-700 mt-1">
                        TOTAL: Php {{ number_format($app->services->sum('price'), 0) }}
                    </div>
                </div>

                <div class="flex flex-row md:flex-col gap-2 w-full md:w-40">
                    <a href="{{ route('appointment.edit', $app->id) }}"
                    class="flex-1 bg-white border-2 border-black text-center py-2 text-xs font-black uppercase hover:bg-[#b6edaa] transition-colors shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[3px] active:translate-y-[3px]">
                        Reschedule
                    </a>

                    <form action="{{ route('appointment.cancel', $app->id) }}" method="POST" id="cancel-form-{{ $app->id }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete({{ $app->id }})"
                                class="w-full bg-[#ff6b6b] border-2 border-black text-white py-2 text-xs font-black uppercase hover:bg-red-600 transition-colors shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[3px] active:translate-y-[3px]">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'ARE YOU SURE?',
            text: "This will permanently cancel your appointment.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000000', // Black button
            cancelButtonColor: '#d33',    // Red button
            confirmButtonText: 'YES, CANCEL IT!',
            cancelButtonText: 'NO, KEEP IT',
            buttonsStyling: false,
            customClass: {
                popup: 'border-4 border-black rounded-none shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]',
                title: 'font-black uppercase text-xl',
                confirmButton: 'bg-black text-white px-6 py-3 font-black uppercase mr-2 hover:bg-red-600',
                cancelButton: 'border-2 border-black px-6 py-3 font-black uppercase text-black hover:bg-gray-100'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // If they click confirm, submit the specific form
                document.getElementById('cancel-form-' + id).submit();
            }
        });
    }

    </script>
    <script>
        // Check for Reschedule Success
        @if(session('reschedule_success'))
            Swal.fire({
                title: 'SUCCESS!',
                text: "{{ session('reschedule_success') }}",
                icon: 'success',
                confirmButtonText: 'AWESOME',
                buttonsStyling: false,
                customClass: {
                    popup: 'border-4 border-black rounded-none shadow-[10px_10px_0px_0px_rgba(31,135,22,1)]',
                    title: 'font-black uppercase text-xl',
                    confirmButton: 'bg-black text-white px-6 py-3 font-black uppercase hover:bg-[#1f8716] transition-colors',
                }
            });
        @endif

        // Check for Cancellation Success
        @if(session('cancel_success'))
            Swal.fire({
                title: 'CANCELLED',
                text: "{{ session('cancel_success') }}",
                icon: 'info',
                confirmButtonText: 'GOT IT',
                buttonsStyling: false,
                customClass: {
                    popup: 'border-4 border-black rounded-none shadow-[10px_10px_0px_0px_rgba(220,38,38,1)]',
                    title: 'font-black uppercase text-xl',
                    confirmButton: 'bg-black text-white px-6 py-3 font-black uppercase hover:bg-gray-800 transition-colors',
                }
            });
        @endif
    </script>
</body>
