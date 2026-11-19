<x-guest-layout>
    <!-- 3D Claymorphic Elevated Register Card (Zero-Scroll Fit & Deep Tactile Shadows) -->
    <div class="clay-card-auth p-5 sm:p-6 space-y-3.5 my-auto relative">
        <!-- Top Funky Glossy Gradient Accent Bar -->
        <div class="h-2 w-full bg-gradient-to-r from-emerald-400 via-cyan-500 to-indigo-500 absolute top-0 inset-x-0"></div>

        <!-- Top Mascot & Register Branding -->
        <div class="text-center space-y-1.5 pt-1">
            <!-- 3D Holographic Mascot Badge (Compact, Elevated) -->
            <div class="mx-auto w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 clay-float"
                 style="background: linear-gradient(135deg, #10b981 0%, #06b6d4 50%, #6366f1 100%); border: 2px solid rgba(255,255,255,0.85); border-bottom: 3.5px solid #059669; box-shadow: 0 14px 28px -4px rgba(16, 185, 129, 0.55), 0 4px 10px rgba(6, 182, 212, 0.3), inset 0 2px 3px rgba(255,255,255,0.85), inset 0 -2px 3px rgba(0,0,0,0.25);">
                <svg class="w-6 h-6 text-white drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>

            <div>
                <div class="inline-flex items-center gap-1.5">
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">
                        Create Account
                    </h1>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black text-white" 
                          style="background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%); box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);">
                        Portal Access
                    </span>
                </div>
                <p class="text-[11px] font-semibold text-slate-400 mt-0.5">
                    Register official user for Departmental Treasury Management
                </p>
            </div>
        </div>

        <!-- Validation Errors Banner -->
        @if ($errors->any())
            <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-0.5 shadow-xs">
                <div class="font-extrabold flex items-center gap-1.5 text-rose-900 text-[11px]">
                    <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Please correct the errors:</span>
                </div>
                <ul class="list-disc list-inside text-[10px] font-medium pl-1 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-2.5">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="name" class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-0.5">
                    Full Name / Designation
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus 
                           autocomplete="name"
                           placeholder="Engr. Abdul Rehman"
                           class="clay-input w-full pl-9 pr-3.5 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none">
                </div>
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-0.5">
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
                           autocomplete="username"
                           placeholder="rehman@prowave.com"
                           class="clay-input w-full pl-9 pr-3.5 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none">
                </div>
            </div>

            <!-- Password & Confirm Password (2-column layout to save vertical space!) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label for="password" class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-0.5">
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="Min 8 chars"
                               class="clay-input w-full px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-0.5">
                        Confirm
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Re-enter"
                               class="clay-input w-full px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-1">
                <button type="submit" 
                        class="clay-btn-auth w-full py-2.5 px-4 text-center text-white text-xs font-black tracking-wide flex items-center justify-center gap-2 shadow-md cursor-pointer">
                    <span>Complete Registration</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Footer: Already Registered Link -->
        <div class="text-center text-[11px] font-semibold text-slate-500 pt-2 border-t border-slate-100">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-black text-indigo-600 hover:text-indigo-800 hover:underline">
                Sign In Instead &rarr;
            </a>
        </div>

    </div>
</x-guest-layout>