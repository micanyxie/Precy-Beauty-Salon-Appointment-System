<div class="flex justify-between items-center mb-10">
    <div class="relative w-72">
        <input type="text"
               id="{{ $searchId ?? 'generalSearch' }}"
               placeholder="{{ $placeholder ?? 'Search...' }}"
               class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-[#81b281] outline-none shadow-sm italic">
        <i data-lucide="search" class="w-4 h-4 absolute left-4 top-3 text-gray-400"></i>
    </div>

    @include('partials.user_dropdown')
</div>
