<div class="flex items-center space-x-4">
    <div class="relative inline-block" x-data="{ bellOpen: false, activeTab: 'Declined' }">
        <button @click="bellOpen = !bellOpen" @click.outside="bellOpen = false" class="relative p-1 focus:outline-none group">
            <i data-lucide="bell" class="w-6 h-6 text-gray-600 group-hover:text-green-600 transition"></i>

            @php
                // Fetch ALL activity
                $allActivity = \App\Models\Appointment::latest()->take(20)->get();

                $recentDeclined = $allActivity->where('approval_status', 'Declined');
                $recentApproved = $allActivity->where('approval_status', 'Approved');

                // NEW: Fetch appointments that were marked 'Completed' on the dashboard
                // We show the most recent ones that need confirmation
                $recentCompleted = $allActivity->where('appt_status', 'Completed');

                $pendingCount = \App\Models\Appointment::where('approval_status', 'Pending')->count();
            @endphp

            @if($pendingCount > 0)
                <span class="absolute top-0 right-0 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            @endif
        </button>

        <div x-show="bellOpen"
             x-cloak
             @click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             class="absolute right-0 mt-3 w-[360px] bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
             style="display: none;">

            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <span class="text-xs font-bold uppercase text-gray-700 tracking-wider">Notifications</span>
            </div>

            <div class="px-4 pt-2 border-b border-gray-100 flex gap-4 text-xs font-medium text-gray-500 bg-white">
                @foreach(['Approved', 'Declined', 'Request', 'Completion'] as $tab)
                    <button
                        type="button"
                        @click="activeTab = '{{ $tab }}'"
                        :class="{ 'text-gray-900 border-b-2 border-green-600 pb-2': activeTab === '{{ $tab }}' }"
                        class="pb-2 hover:text-green-600 transition focus:outline-none">
                        {{ $tab }}
                    </button>
                @endforeach
            </div>

            <div class="max-h-[350px] overflow-y-auto bg-white">

                <div x-show="activeTab === 'Approved'" class="divide-y divide-gray-50">
                    @forelse($recentApproved as $req)
                        <div class="px-4 py-3 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                                <p class="text-xs font-medium text-gray-700">
                                    <span class="font-bold">{{ $req->first_name }}</span> was approved.
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="px-4 py-8 text-center text-xs text-gray-400 italic">No recent approvals.</p>
                    @endforelse
                </div>

                <div x-show="activeTab === 'Declined'" class="py-2">
                    @forelse($recentDeclined as $req)
                        <div class="px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100 last:border-0 relative">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex-shrink-0"></div>

                                <div class="flex-1 text-xs">
                                    <span class="font-normal text-gray-600">The appointment of
                                        <p class="font-bold text-gray-800 leading-tight">
                                            {{ $req->first_name }} {{ $req->last_name }}
                                            <span class="font-normal text-gray-600"> was declined.</span>
                                        </p>
                                    <p class="text-[10px] text-gray-400 mt-1">
                                        {{ $req->updated_at->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="flex-shrink-0 flex items-center gap-2">
                                    <span class="hidden group-hover:block text-[9px] bg-gray-800 text-white px-2 py-0.5 rounded font-medium shadow-lg">
                                        Mark as read
                                    </span>
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-blue-500"></i>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="px-4 py-8 text-center text-xs text-gray-400 italic">No declined appointments.</p>
                    @endforelse
                </div>

                <div x-show="activeTab === 'Request'">
                    @if($pendingCount > 0)
                        <div class="px-4 py-6 text-center">
                            <p class="text-xs text-gray-600 font-medium">You have {{ $pendingCount }} new requests waiting.</p>
                            <a href="/reservation" class="inline-block mt-3 px-4 py-2 bg-green-600 text-white text-[10px] rounded-lg font-bold uppercase tracking-wider hover:bg-green-700 transition">
                                Go to Requests Page ({{ $pendingCount }})
                            </a>
                        </div>
                    @else
                        <p class="px-4 py-8 text-center text-xs text-gray-400 italic">All requests handled.</p>
                    @endif
                </div>

                <div x-show="activeTab === 'Completion'" class="divide-y divide-gray-50">
                    @forelse($recentCompleted as $req)
                        <div class="px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100 last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="sparkles" class="w-4 h-4 text-green-600"></i>
                                </div>
                                <div class="flex-1 text-xs">
                                    <p class="font-medium text-gray-800 leading-tight">
                                        Service finished for <span class="font-bold">{{ $req->first_name }}</span>
                                    </p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">
                                        Completed at {{ \Carbon\Carbon::parse($req->completed_at)->format('g:i A') }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="px-4 py-8 text-center text-xs text-gray-400 italic">No services recently completed.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="relative" x-data="{ userOpen: false }">
        <div @click="userOpen = !userOpen" @click.outside="userOpen = false" class="flex items-center space-x-2 cursor-pointer group">
            <span class="font-bold text-sm text-gray-700 group-hover:text-green-600 transition-colors">
                {{ Auth::user()->name }}
            </span>

            <div class="w-9 h-9 bg-gray-200 rounded-full border border-gray-200 overflow-hidden shadow-sm group-hover:border-green-400 transition-all">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=81b281&color=fff"
                    alt="{{ Auth::user()->name }}">
            </div>
        </div>

        <div x-show="userOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50"
             style="display: none;">

            <div class="px-4 py-2 border-b border-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                Account
            </div>

            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                <i data-lucide="user-cog" class="w-4 h-4 mr-2 text-gray-400"></i>
                Profile
            </a>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                <i data-lucide="settings" class="w-4 h-4 mr-2 text-gray-400"></i>
                Settings
            </a>
            @endif

            <div class="border-t border-gray-50 mt-2 pt-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center">
                        <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
