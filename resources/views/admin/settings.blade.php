<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precy Salon | Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                <a href="/settings" class="flex items-center space-x-3 bg-[#e9f5e9] p-3 rounded shadow-inner border border-green-200">
                    <i data-lucide="settings" class="w-5 h-5 text-green-700"></i>
                    <span class="font-bold text-green-800">Settings</span>
                </a>
            </nav>
        </div>
        <div class="mt-auto p-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-2 text-white font-bold hover:text-red-200">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-10 ml-64" x-data="{ view: 'hours' }">

        <div class="flex justify-between items-center mb-10">
            <div></div>
            @include('partials.user_dropdown')
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">Settings</h2>

        <div class="inline-flex w-fit bg-gray-100 p-1 rounded-xl border border-gray-200 shadow-inner">
            <button @click="view = 'hours'" :class="view === 'hours' ? 'bg-white text-[#81b281] shadow-md' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase transition-all">Business Hours</button>

            <button @click="view = 'info'" :class="view === 'info' ? 'bg-white text-[#81b281] shadow-md' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase transition-all mx-1">Business Info</button>

            <button @click="view = 'days-off'" :class="view === 'days-off' ? 'bg-white text-[#81b281] shadow-md' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase transition-all">Days Off</button>

            <button @click="view = 'categories'" :class="view === 'categories' ? 'bg-white text-[#81b281] shadow-md' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase transition-all mx-1">Categories</button>

            <button @click="view = 'archive'" :class="view === 'archive' ? 'bg-white text-[#81b281] shadow-md' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase transition-all mx-1">Archive</button>

            <button @click="view = 'notifications'" :class="view === 'notifications' ? 'bg-white text-[#81b281] shadow-md' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase transition-all">Set Notifications</button>
        </div>
        <div class="mt-8 bg-white rounded-2xl shadow-xl p-8 min-h-[500px] border border-gray-100">

        <div x-show="view === 'hours'" x-transition>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black italic text-gray-700">Operational Settings</h3>
            </div>
            <form action="{{ route('settings.update-operational') }}" method="POST">
                @csrf
                <div class="grid gap-4 max-w-2xl">
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl border border-green-100 mb-4">
                        <div>
                            <span class="font-bold text-gray-700 block">Max Services per Appointment</span>
                            <span class="text-[10px] text-gray-400 uppercase">Limit for a single client booking</span>
                        </div>
                        <input type="number" name="max_services" value="{{ $settings->max_services_per_appointment ?? 2 }}"
                            class="w-20 p-2 rounded-lg border border-green-200 text-center font-bold outline-none focus:ring-2 focus:ring-green-400">
                    </div>

                    <div class="grid grid-cols-2 gap-6 p-4 bg-gray-50 rounded-xl">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Opening Time</label>
                            <input type="time" name="opening_time" value="{{ $settings->opening_time ?? '08:00' }}" class="w-full p-2 rounded-lg border">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Closing Time</label>
                            <input type="time" name="closing_time" value="{{ $settings->closing_time ?? '18:00' }}" class="w-full p-2 rounded-lg border">
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-8 bg-[#81b281] text-white px-8 py-2 rounded-lg font-bold hover:bg-[#6c9a6c] transition shadow-lg">Update Operational Settings</button>
            </form>
        </div>
        <div x-show="view === 'info'" x-transition x-cloak>
            <form action="{{ route('settings.update-info') }}" method="POST">
                @csrf
                <h3 class="text-lg font-black italic text-gray-700 mb-6">Business Profile</h3>
                <div class="grid gap-5 max-w-2xl">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Salon Name</label>
                        <input type="text" name="salon_name" value="{{ $settings->salon_name ?? '' }}" class="w-full p-2 bg-white rounded-lg border border-gray-200 font-bold text-gray-700">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ $settings->contact_number ?? '' }}" class="w-full p-2 bg-white rounded-lg border">
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Email</label>
                            <input type="email" name="email" value="{{ $settings->email ?? '' }}" class="w-full p-2 bg-white rounded-lg border">
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-8 bg-[#81b281] text-white px-8 py-2 rounded-lg font-bold">Update Profile Info</button>
            </form>
        </div>

        <div x-show="view === 'days-off'" x-transition x-cloak>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black italic text-gray-700">Closed Dates</h3>
                <button @click="$dispatch('open-holiday-modal')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-1.5 rounded-lg text-xs font-bold flex items-center gap-2 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Holiday
                </button>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px]">
                        <tr>
                            <th class="p-4 text-left">Date</th>
                            <th class="p-4 text-left">Holiday Name</th>
                            <th class="p-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($settings->holidays ?? [] as $date => $data)
                        <tr class="hover:bg-gray-50/50">
                            <td class="p-4 font-bold text-gray-700">
                                {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                            </td>
                            <td class="p-4">
                                @php
                                    // Handle both old string format and new array format
                                    $name = is_array($data) ? $data['name'] : $data;
                                    $isFullDay = is_array($data) ? ($data['full_day'] ?? true) : true;
                                @endphp

                                <span class="text-gray-700 font-bold block">{{ $name }}</span>

                                <div class="text-[10px] font-black uppercase tracking-tighter">
                                    @if($isFullDay)
                                        <span class="text-red-400">Full Day Closure</span>
                                    @else
                                        <span class="text-orange-400">
                                            Closed: {{ \Carbon\Carbon::parse($data['start'])->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($data['end'])->format('g:i A') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('settings.archive-holiday', $date) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-orange-500 transition">
                                        <i data-lucide="archive" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-10 text-center text-gray-400 italic">No holidays set.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div x-show="view === 'categories'" x-transition x-cloak>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black italic text-gray-700">Service Categories</h3>
                <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Manage Active Groups</span>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="mb-10 bg-green-50 p-6 rounded-2xl border border-green-100">
                @csrf
                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 tracking-widest">Add New Category</label>
                <div class="flex gap-3">
                    <input type="text" name="name" placeholder="e.g., Hair Styling, Nail Care" required
                        class="flex-1 p-3 bg-white rounded-xl border border-gray-200 font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#81b281]">
                    <button type="submit" class="bg-[#81b281] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#6c9a6c] transition shadow-md flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        Add Category
                    </button>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px]">
                        <tr>
                            <th class="p-4 text-left">Category Name</th>
                            <th class="p-4 text-left">Created Date</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4">
                                <span class="font-bold text-gray-700">{{ $category->name }}</span>
                            </td>
                            <td class="p-4 text-gray-400 text-xs">
                                {{ $category->created_at->format('M d, Y') }}
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('categories.archive', $category->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-2" title="Move to Archive">
                                        <i data-lucide="archive" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-10 text-center text-gray-400 italic">No active categories. Add one above!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div x-show="view === 'archive'"
            x-data="{ search: '' }"
            x-transition x-cloak>

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black italic text-gray-700">Archive Vault</h3>

                <div class="relative w-64">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" x-model="search" placeholder="Search archive..."
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-[#81b281] transition-all">
                </div>
            </div>

            <div class="mb-10">
                <h4 class="text-[10px] font-black uppercase text-gray-400 mb-3 tracking-widest">Archived Holidays</h4>
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($settings->archived_holidays ?? [] as $date => $data)
                                @php $name = is_array($data) ? $data['name'] : $data; @endphp
                                <tr x-show="search === '' || '{{ strtolower($name) }}'.includes(search.toLowerCase())">
                                    <td class="p-4 font-bold text-gray-600 w-1/3">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                                    <td class="p-4 text-gray-400 italic">{{ $name }}</td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('settings.restore-holiday', $date) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 font-black text-[10px] uppercase transition-all shadow-sm">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="p-6 text-center text-gray-400 italic text-xs">No archived holidays.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-10">
                <h4 class="text-[10px] font-black uppercase text-gray-400 mb-3 tracking-widest">Archived Staff</h4>
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($archivedStaff ?? [] as $staff)
                                <tr x-show="search === '' || '{{ strtolower($staff->name) }}'.includes(search.toLowerCase())"
                                    class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4 font-bold text-gray-700">{{ $staff->name }}</td>
                                    <td class="p-4 text-gray-400 text-xs italic">
                                        Archived on {{ $staff->deleted_at->format('M d, Y') }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('staff.restore', $staff->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 font-black text-[10px] uppercase transition-all shadow-sm">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-10 text-center text-gray-400 italic text-xs">No archived staff.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-10">
                <h4 class="text-[10px] font-black uppercase text-gray-400 mb-3 tracking-widest">Archived Services</h4>
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($archivedServices ?? [] as $service)
                                <tr x-show="search === '' || '{{ strtolower($service->name) }}'.includes(search.toLowerCase())"
                                    class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4 font-bold text-gray-700">{{ $service->name }}</td>
                                    <td class="p-4 text-gray-400 text-xs italic">Php {{ number_format($service->price, 2) }}</td>
                                    <td class="p-4 text-gray-400 text-xs italic">{{ $service->duration }} mins</td>
                                    <td class="p-4 text-gray-400 text-xs italic">{{ $service->category->name ?? 'Uncategorized' }}</td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('services.restore', $service->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 font-black text-[10px] uppercase transition-all shadow-sm">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-400 italic text-xs">No archived services.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h4 class="text-[10px] font-black uppercase text-gray-400 mb-3 tracking-widest">Archived Categories</h4>
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($archivedCategories as $category)
                                <tr x-show="search === '' || '{{ strtolower($category->name) }}'.includes(search.toLowerCase())"
                                    class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4">
                                        <span class="font-bold text-gray-700">{{ $category->name }}</span>
                                    </td>
                                    <td class="p-4 text-gray-400 text-xs italic">
                                        Archived on {{ $category->deleted_at->format('M d, Y') }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('categories.restore', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 font-black text-[10px] uppercase transition-all shadow-sm">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="p-10 text-center text-gray-400 italic text-xs">No archived categories.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="view === 'notifications'" x-transition x-cloak>
            <h3 class="text-lg font-black italic text-gray-700 mb-8">Notification Preferences</h3>
            <div class="max-w-md space-y-6">
                @php $notifs = ['Email Confirmations', 'SMS Reminders', 'Staff Alerts', 'Admin Cancellation Alerts']; @endphp
                @foreach($notifs as $n)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="font-bold text-gray-700">{{ $n }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#81b281]"></div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
    <div x-data="{ open: false, isFullDay: true }"
        x-show="open"
        @open-holiday-modal.window="open = true"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-cloak>

        <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl transform transition-all" @click.away="open = false">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black italic text-gray-800">New Closed Date</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="{{ route('settings.add-holiday') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Date</label>
                        <input type="date" name="holiday_date" required class="w-full p-3 bg-gray-50 border rounded-xl font-bold">
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-green-50 rounded-xl border border-green-100">
                        <input type="checkbox" x-model="isFullDay" name="is_full_day" value="1" class="w-4 h-4 accent-[#81b281]">
                        <span class="text-xs font-bold text-gray-600">This is a Full Day Closure</span>
                    </div>

                    <div x-show="!isFullDay" x-transition class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Closed From</label>
                            <input type="time" name="closed_from" class="w-full p-2 bg-white border rounded-lg font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Until</label>
                            <input type="time" name="closed_until" class="w-full p-2 bg-white border rounded-lg font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Reason / Name</label>
                        <input type="text" name="holiday_name" placeholder="Staff Training" required class="w-full p-3 bg-gray-50 border rounded-xl font-bold">
                    </div>
                </div>
                <button type="submit" class="w-full mt-8 bg-[#81b281] text-white py-3 rounded-xl font-bold shadow-lg">Save Holiday</button>
            </form>
        </div>
    </div>
</body>
</html>
