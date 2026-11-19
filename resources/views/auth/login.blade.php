<x-guest-layout>
    <!-- 3D Claymorphic Elevated Auth Card (Zero-Scroll Fit & Deep Tactile Shadows) -->
    <div class="clay-card-auth p-5 sm:p-6 space-y-3.5 my-auto relative">
        <!-- Top Funky Glossy Gradient Accent Bar -->
        <div class="h-2 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 absolute top-0 inset-x-0"></div>

        <!-- Top Mascot & Welcome Branding -->
        <div class="text-center space-y-1.5 pt-1">
            <!-- 3D Holographic Mascot Badge (Compact, Elevated) -->
            <div class="mx-auto w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 clay-float"
                 style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%); border: 2px solid rgba(255,255,255,0.85); border-bottom: 3.5px solid #4338ca; box-shadow: 0 14px 28px -4px rgba(139, 92, 246, 0.55), 0 4px 10px rgba(99, 102, 241, 0.3), inset 0 2px 3px rgba(255,255,255,0.85), inset 0 -2px 3px rgba(0,0,0,0.25);">
                <svg class="w-6 h-6 text-white drop-shadow-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    <circle cx="12" cy="16" r="1.5" fill="currentColor"></circle>
                </svg>
            </div>

            <div>
                <div class="inline-flex items-center gap-1.5">
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">
                        Sign In
                    </h1>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black text-white" 
                          style="background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%); box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);">
                        AGPR Portal
                    </span>
                </div>
                <p class="text-[11px] font-semibold text-slate-400 mt-0.5">
                    Enter credentials to access Treasury & Invoicing Engine
                </p>
            </div>
        </div>

        <!-- Session Status (e.g. Password Reset Alert) -->
        @if (session('status'))
            <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800 flex items-center gap-2 shadow-xs">
                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Validation Errors Banner -->
        @if ($errors->any())
            <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-0.5 shadow-xs">
                <div class="font-extrabold flex items-center gap-1.5 text-rose-900 text-[11px]">
                    <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Authentication failed:</span>
                </div>
                <ul class="list-disc list-inside text-[10px] font-medium pl-1 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-3">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-1">
                    Official Email Address
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username"
                           placeholder="director.finance@sindh.gov.pk"
                           class="clay-input w-full pl-9 pr-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none">
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-[11px] font-black text-slate-700 uppercase tracking-wider">
                        Password
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[10px] font-extrabold text-indigo-600 hover:text-indigo-800 hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           placeholder="••••••••••••"
                           class="clay-input w-full pl-9 pr-10 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none">
                    
                    <!-- Eye Toggle Button (Show/Hide Password) -->
                    <button type="button" 
                            id="togglePasswordBtn" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 transition cursor-pointer">
                        <svg id="eyeIconOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eyeIconClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                    <input id="remember_me" 
                           type="checkbox" 
                           name="remember" 
                           class="w-3.5 h-3.5 rounded border-slate-300 text-indigo-600 shadow-xs focus:ring-indigo-500 focus:ring-offset-0 transition cursor-pointer">
                    <span class="text-[11px] font-bold text-slate-600">Keep me logged in</span>
                </label>

                <span class="text-[9px] font-bold text-slate-400 font-mono">256-Bit SSL</span>
            </div>

            <!-- Submit Button (Funky Clay Button) -->
            <div class="pt-1">
                <button type="submit" 
                        class="clay-btn-auth w-full py-2.5 px-4 text-center text-white text-xs font-black tracking-wide flex items-center justify-center gap-2 shadow-md cursor-pointer">
                    <span>Sign In to Dashboard</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Quick Demo Credentials Helper Pill -->
        <div class="pt-2 border-t border-slate-100 text-center flex items-center justify-between">
            <button type="button" 
                    onclick="fillDemoCredentials()" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-700 border border-slate-200 text-[10px] font-bold transition shadow-2xs cursor-pointer">
                <span>⚡</span>
                <span>Auto-fill Demo</span>
            </button>

            @if (Route::has('register'))
                <div class="text-[11px] font-semibold text-slate-500">
                    <a href="{{ route('register') }}" class="font-black text-indigo-600 hover:text-indigo-800 hover:underline">
                        Create Account &rarr;
                    </a>
                </div>
            @endif
        </div>

    </div>

    <!-- Password Visibility & Demo Script -->
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

        function fillDemoCredentials() {
            const emailInput = document.getElementById('email');
            const pwdInput = document.getElementById('password');
            if (emailInput && pwdInput) {
                emailInput.value = 'admin@prowave.com';
                pwdInput.value = 'password';
                pwdInput.focus();
            }
        }
    </script>
</x-guest-layout>