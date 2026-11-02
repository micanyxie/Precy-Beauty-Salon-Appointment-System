<script src="https://cdn.tailwindcss.com"></script>

<div class="flex min-h-screen">
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-[#7cf3d3] to-[#a8e063] items-center justify-center p-12">
        <div class="text-center">
            <img src="{{ asset('storage/logo.png') }}" alt="Salon Logo" class="max-w-md w-full drop-shadow-xl">
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 md:p-20">
            <div class="w-full max-w-sm text-center">
                <h2 class="text-5xl font-bold text-gray-800 mb-12 tracking-tight">Welcome <br> Back</h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-6 text-left" x-data="{ show: false }">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                            <ul class="text-xs text-red-600 italic">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-gray-200 rounded flex items-center p-4">
                        <span class="mr-3">👤</span>
                        <input type="email" name="email" placeholder="Email Address" required
                            class="bg-transparent border-none w-full focus:ring-0 outline-none text-gray-700">
                    </div>

                    <div class="bg-gray-200 rounded flex items-center p-4">
                        <span class="mr-3">🔒</span>
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="Password" required
                            class="bg-transparent border-none w-full focus:ring-0 outline-none text-gray-700">

                        <button type="button" @click="show = !show" class="ml-3 focus:outline-none opacity-50 hover:opacity-100 transition">
                            <span x-show="!show">👁️</span>
                            <span x-show="show" x-cloak>🙈</span>
                        </button>
                    </div>

                    <div class="flex justify-between items-center text-xs font-medium text-gray-600">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="mr-2"> Remember me
                        </label>
                        <a href="#" class="hover:underline">Forgot Password?</a>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#76a376] py-4 font-bold text-white uppercase tracking-widest shadow-sm hover:bg-[#5f855f] transition-all">
                            Log In
                        </button>
                    </div>

                    <div class="text-center text-gray-400 italic text-sm">or</div>

                    <div class="text-center">
                        <p class="text-gray-600 font-bold uppercase text-[10px] tracking-[0.2em]">
                            Authorized Personnel Only
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
