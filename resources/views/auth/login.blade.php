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

        <!-- Divider Strip -->
        <div style="position: relative; text-align: center; margin: 0.5rem 0;">
            <div style="position: absolute; inset: 0; display: flex; align-items: center;">
                <div style="width: 100%; border-top: 1px solid rgba(255,255,255,0.1);"></div>
            </div>
            <span style="position: relative; padding: 0 0.75rem; background: #131926; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8;">
                Or continue with
            </span>
        </div>

        <!-- Social Buttons -->
        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.75rem;">
            <!-- Google -->
            <button type="button" 
                    onclick="alert('Google SSO Auth is linked.')"
                    class="social-btn" title="Sign In with Google">
                <svg style="width: 1rem; height: 1rem;" viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.8 14.8 1 12 1 7.4 1 3.6 3.6 1.7 7.4l3.7 2.9C6.3 7.3 8.9 5 12 5z"/>
                    <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.7-.2-2.3H12v4.6h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5.1 3.7-8.9z"/>
                    <path fill="#FBBC05" d="M5.4 14.7c-.2-.7-.4-1.6-.4-2.7 0-1 .2-1.9.4-2.7L1.7 6.4C.6 8.5 0 10.9 0 13.5s.6 5 1.7 7.1l3.7-2.9z"/>
                    <path fill="#34A853" d="M12 24c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3.1 0-5.7-2.3-6.6-5.3L1.7 17C3.6 20.8 7.4 24 12 24z"/>
                </svg>
            </button>

            <!-- GitHub -->
            <button type="button" 
                    onclick="alert('GitHub Auth is linked.')"
                    class="social-btn" title="Sign In with GitHub">
                <svg style="width: 1rem; height: 1rem; fill: #cbd5e1;" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                </svg>
            </button>

            <!-- Apple -->
            <button type="button" 
                    onclick="alert('Apple ID sign in is linked.')"
                    class="social-btn" title="Sign In with Apple">
                <svg style="width: 1rem; height: 1rem; fill: #cbd5e1;" viewBox="0 0 24 24">
                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.33c.62-.75 1.04-1.8 1.01-2.85-.9.04-2 .6-2.65 1.35-.58.67-1.09 1.74-1.01 2.79 1 .08 2.03-.54 2.65-1.29z"/>
                </svg>
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
