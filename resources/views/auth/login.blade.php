<x-guest-layout>
    <!-- Brand Header & 4 Theme Identifiers -->
    <div class="mb-5">
        <div class="flex items-center justify-between mb-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-[#0056d2] border border-sky-300/40 border-b-3 border-[#07255e] flex items-center justify-center font-black text-white text-sm shadow-md shadow-blue-500/30">
                    PW
                </div>
                <div>
                    <div class="text-sm font-black tracking-tight text-white flex items-center gap-1.5">
                        PROWAVE <span class="text-sky-400 font-extrabold">ERP</span>
                    </div>
                    <div class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">
                        Al-Madina Enterprises
                    </div>
                </div>
            </div>

            <!-- 4-Color Dashboard Theme Indicators (Blue, Green, Orange, Maroon) -->
            <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-full bg-slate-800/90 border border-slate-700/80 shadow-2xs" title="ERP 4-Pillar System">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0056d2] shadow-xs" title="Tenders (Deep Blue)"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-[#0d9467] shadow-xs" title="Billing (Emerald Green)"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-[#cd3805] shadow-xs" title="Cheques (Warm Orange)"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-[#881337] shadow-xs" title="Budget (Deep Maroon)"></span>
            </div>
        </div>

        <!-- 4-Color Accent Line -->
        <div class="h-[2.5px] w-full rounded-full bg-gradient-to-r from-[#0056d2] via-[#0d9467] via-[#cd3805] to-[#881337] mb-4 opacity-90"></div>

        <h2 class="text-2xl sm:text-[27px] font-black text-white tracking-tight">
            Portal Sign In
        </h2>
        <p class="text-xs sm:text-[13px] font-semibold text-slate-400 mt-1">
            Enter your credentials to access tenders, billing & treasury
        </p>
    </div>

    <!-- Session Status Banner (Green Theme) -->
    @if (session('status'))
        <div class="mb-4 p-3 rounded-xl bg-[#0d9467]/15 border border-[#0d9467]/40 text-xs font-bold text-emerald-300 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Validation Errors Banner (Maroon Theme) -->
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-[#881337]/20 border border-[#881337]/50 text-xs text-rose-300 space-y-1">
            <div class="font-extrabold flex items-center gap-1.5 text-rose-300 text-xs">
                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Please check your credentials:</span>
            </div>
            <ul class="list-disc list-inside text-[11px] text-rose-200 pl-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Username or Email Field -->
        <div>
            <label for="email" class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider mb-1.5">
                Username or Email
            </label>
            <div class="relative w-full">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sky-400 pointer-events-none flex items-center">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </span>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username"
                       placeholder="admin@prowave.com"
                       class="luxury-input">
            </div>
        </div>

        <!-- Password Field -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="text-xs font-extrabold text-slate-300 uppercase tracking-wider">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition">
                        Forgot Password?
                    </a>
                @endif
            </div>
            <div class="relative w-full">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sky-400 pointer-events-none flex items-center">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       placeholder="••••••••••••"
                       class="luxury-input" 
                       style="padding-right: 2.85rem;">
                
                <!-- Eye Toggle -->
                <button type="button" 
                        onclick="togglePasswordVisibility()" 
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition flex items-center p-1"
                        title="Toggle password view">
                    <svg id="eyeIconOpen" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eyeIconClosed" class="w-4.5 h-4.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Remember Me Checkbox & Security Badge -->
        <div class="flex items-center justify-between pt-1 pb-1">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input id="remember_me" 
                       type="checkbox" 
                       name="remember" 
                       style="accent-color: #0056d2;"
                       class="w-4 h-4 rounded border-slate-700 bg-slate-900 cursor-pointer">
                <span class="text-xs font-bold text-slate-300">Remember me</span>
            </label>

            <span class="text-[11px] font-bold text-slate-400">
                🔒 256-Bit SSL Secured
            </span>
        </div>

        <!-- Submit Button (Solid 3D Tactile Deep Blue Button) -->
        <div class="pt-2">
            <button type="submit" class="login-btn-primary">
                <span>Sign In to Portal &rarr;</span>
            </button>
        </div>
    </form>

    <!-- Password Visibility Script -->
    <script>
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeIconOpen');
            const eyeClosed = document.getElementById('eyeIconClosed');
            
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                pwdInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>