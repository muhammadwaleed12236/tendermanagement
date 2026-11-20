<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-xs font-bold text-emerald-400 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Validation Errors Banner -->
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-xs text-rose-300 space-y-1">
            <div class="font-extrabold flex items-center gap-1.5 text-rose-400 text-xs">
                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Please check your credentials:</span>
            </div>
            <ul class="list-disc list-inside text-[11px] text-rose-300 pl-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 1rem;">
        @csrf

        <!-- Username or Email Field -->
        <div>
            <label for="email" style="display: block; font-size: 0.75rem; font-weight: 700; color: #cbd5e1; margin-bottom: 0.35rem;">
                Username or Email
            </label>
            <div style="position: relative; width: 100%;">
                <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #64748b; pointer-events: none; display: flex; align-items: center;">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                       placeholder="CodeByUsman"
                       class="luxury-input">
            </div>
        </div>

        <!-- Password Field -->
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                <label for="password" style="font-size: 0.75rem; font-weight: 700; color: #cbd5e1;">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.75rem; font-weight: 700; color: #fbbf24; text-decoration: none;">
                        Forgot?
                    </a>
                @endif
            </div>
            <div style="position: relative; width: 100%;">
                <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #64748b; pointer-events: none; display: flex; align-items: center;">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                       style="padding-right: 2.75rem;">
                
                <!-- Eye Toggle -->
                <button type="button" 
                        onclick="togglePasswordVisibility()" 
                        style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; display: flex; align-items: center;">
                    <svg id="eyeIconOpen" style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eyeIconClosed" style="width: 1rem; height: 1rem; display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Remember Me Checkbox -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.25rem;">
            <label for="remember_me" style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none;">
                <input id="remember_me" 
                       type="checkbox" 
                       name="remember" 
                       style="width: 1rem; height: 1rem; accent-color: #f59e0b; cursor: pointer;">
                <span style="font-size: 0.75rem; font-weight: 600; color: #cbd5e1;">Remember me</span>
            </label>

            <button type="button" 
                    onclick="fillDemoCredentials()" 
                    style="background: none; border: none; font-size: 0.7rem; font-weight: 700; color: #fbbf24; cursor: pointer;">
                ⚡ Auto-fill Demo
            </button>
        </div>

        <!-- Submit Button -->
        <div style="padding-top: 0.5rem;">
            <button type="submit" class="golden-btn">
                <span>Sign In &rarr;</span>
            </button>
        </div>
    </form>

    <!-- Password Visibility & Demo Script -->
    <script>
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeIconOpen');
            const eyeClosed = document.getElementById('eyeIconClosed');
            
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'inline-block';
            } else {
                pwdInput.type = 'password';
                eyeOpen.style.display = 'inline-block';
                eyeClosed.style.display = 'none';
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
