<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Al-Madina / ProWave') }} - Sign In</title>

        <!-- Tailwind Play CDN for guaranteed 100% styled utility rendering anywhere -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            amber: {
                                400: '#fbbf24',
                                500: '#f59e0b',
                                600: '#d97706',
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jet-brains-mono:500,700&display=swap" rel="stylesheet" />

        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
                background-color: #0d1117;
                color: #e2e8f0;
                min-height: 100vh;
                width: 100%;
                overflow-x: hidden;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                transition: background 0.5s ease;
            }

            body.lamp-lit {
                background: radial-gradient(circle at 35% 50%, #20170f 0%, #111520 50%, #0a0d14 100%);
            }
            body.lamp-off {
                background: radial-gradient(circle at 35% 50%, #10141d 0%, #080a0f 100%);
            }

            /* Floating warm dust particles */
            .particle {
                position: absolute;
                border-radius: 50%;
                background: rgba(245, 158, 11, 0.5);
                filter: blur(1px);
                pointer-events: none;
                animation: floatUp linear infinite;
            }

            @keyframes floatUp {
                0% { transform: translateY(100vh) translateX(0); opacity: 0; }
                20% { opacity: 0.8; }
                80% { opacity: 0.5; }
                100% { transform: translateY(-10vh) translateX(30px); opacity: 0; }
            }

            /* Main Layout Grid Container */
            .auth-container {
                width: 100%;
                max-width: 1100px;
                margin: auto;
                padding: 1.5rem 1rem;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                gap: 3rem;
                position: relative;
                z-index: 10;
            }

            @media (max-width: 860px) {
                .auth-container {
                    flex-direction: column;
                    gap: 1.5rem;
                    padding: 1rem;
                }
                .lamp-wrapper {
                    display: none;
                }
            }

            /* Lamp Section */
            .lamp-wrapper {
                position: relative;
                width: 320px;
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            /* Light Beam projecting from lamp to the login card */
            .lamp-cone {
                position: absolute;
                top: 75px;
                left: 170px;
                width: 520px;
                height: 380px;
                background: radial-gradient(ellipse at 15% 15%, rgba(245, 158, 11, 0.35) 0%, rgba(245, 158, 11, 0.12) 40%, transparent 75%);
                clip-path: polygon(0% 12%, 100% 55%, 75% 100%, 0% 30%);
                pointer-events: none;
                z-index: 5;
                transition: opacity 0.4s ease;
                filter: blur(3px);
            }
            .lamp-off .lamp-cone {
                opacity: 0 !important;
            }

            .cord-pull {
                cursor: pointer;
                transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            .cord-pull:hover {
                filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.9));
            }
            .cord-pull:active {
                transform: translateY(12px);
            }

            /* Dark Luxury Login Card */
            .luxury-card {
                width: 100%;
                max-width: 440px;
                background: rgba(18, 24, 38, 0.9);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-top: 1px solid rgba(255, 255, 255, 0.18);
                border-radius: 1.75rem;
                padding: 2.25rem 2rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 30px -5px rgba(245, 158, 11, 0.15);
                position: relative;
                z-index: 20;
            }

            /* Form Elements */
            .luxury-input {
                width: 100%;
                background: #0f1523;
                border: 1px solid rgba(255, 255, 255, 0.12);
                border-radius: 0.75rem;
                padding: 0.75rem 1rem 0.75rem 2.6rem;
                color: #f8fafc;
                font-size: 0.875rem;
                transition: all 0.25s ease;
                outline: none;
            }
            .luxury-input:focus {
                border-color: #f59e0b;
                box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25), 0 0 16px -2px rgba(245, 158, 11, 0.35);
                background: #141c2e;
            }

            .golden-btn {
                width: 100%;
                background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
                color: #0b0f17;
                font-weight: 800;
                font-size: 0.875rem;
                padding: 0.85rem 1.5rem;
                border: none;
                border-radius: 0.75rem;
                box-shadow: 0 10px 25px -4px rgba(245, 158, 11, 0.5);
                cursor: pointer;
                transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }
            .golden-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 30px -4px rgba(245, 158, 11, 0.7);
                filter: brightness(1.05);
            }
            .golden-btn:active {
                transform: translateY(1px);
            }

            .social-btn {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 0.75rem;
                padding: 0.65rem 0;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .social-btn:hover {
                background: rgba(255, 255, 255, 0.12);
                border-color: rgba(245, 158, 11, 0.4);
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body class="lamp-lit">
        <!-- Floating Dust Particles -->
        <div id="particles-container" class="fixed inset-0 pointer-events-none z-0"></div>

        <!-- Top Header Strip -->
        <header class="w-full relative z-30 py-3.5 px-6 sm:px-10 flex items-center justify-between border-b border-white/5 bg-black/20 backdrop-blur-md">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
                <span class="text-xs font-black tracking-wider text-slate-200 uppercase">
                    Al-Madina / ProWave Portal
                </span>
                <span class="text-[10px] font-bold text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-500/20">
                    Live Portal
                </span>
            </div>

            <!-- Sound Toggle Button -->
            <button type="button" 
                    id="soundToggleBtn" 
                    onclick="toggleSound()" 
                    class="text-xs font-bold px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition flex items-center gap-1.5 cursor-pointer">
                <span id="soundIcon">🔊</span>
                <span id="soundText">Sound: On</span>
            </button>
        </header>

        <!-- Main Center Area -->
        <main class="flex-1 flex items-center justify-center relative z-20">
            <div class="auth-container">
                
                <!-- LEFT COLUMN: Animated Desk Lamp & Cord -->
                <div class="lamp-wrapper">
                    <!-- SVG Lamp Vector Graphic with Integrated Light Beam from Bulb -->
                    <svg viewBox="0 0 340 440" class="w-[300px] h-[390px] select-none" fill="none" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">
                        <defs>
                            <!-- Light beam gradient radiating outwards from the bulb -->
                            <linearGradient id="bulbBeamGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.45"/>
                                <stop offset="40%" stop-color="#f59e0b" stop-opacity="0.22"/>
                                <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.0"/>
                            </linearGradient>

                            <!-- Radial glow around bulb -->
                            <radialGradient id="bulbRadialGlow" cx="50%" cy="50%" r="50%">
                                <stop offset="0%" stop-color="#ffffff" stop-opacity="1"/>
                                <stop offset="35%" stop-color="#fbbf24" stop-opacity="0.8"/>
                                <stop offset="70%" stop-color="#f59e0b" stop-opacity="0.4"/>
                                <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                            </radialGradient>

                            <filter id="bulbGlowFilter" x="-50%" y="-50%" width="200%" height="200%">
                                <feGaussianBlur stdDeviation="6" result="blur"/>
                                <feComposite in="SourceGraphic" in2="blur" operator="over"/>
                            </filter>
                        </defs>

                        <!-- Realistic Angled Light Beam Projection Originating Exactly at Bulb (230, 150) -->
                        <polygon id="lampBeamPolygon" 
                                 points="192,162 272,138 720,380 400,560" 
                                 fill="url(#bulbBeamGrad)" 
                                 pointer-events="none"
                                 style="transition: opacity 0.4s ease; filter: blur(3px);"/>

                        <!-- Shadow -->
                        <ellipse cx="140" cy="405" rx="85" ry="14" fill="#000000" opacity="0.6" filter="blur(4px)"/>
                        
                        <!-- Base -->
                        <ellipse cx="140" cy="395" rx="70" ry="12" fill="#1e2430" stroke="#f59e0b" stroke-width="2.5"/>
                        <ellipse cx="140" cy="392" rx="58" ry="9" fill="#2d3545"/>
                        <circle cx="140" cy="392" r="10" fill="#f59e0b" stroke="#111827" stroke-width="2"/>

                        <!-- Lower Arm -->
                        <path d="M140 392 L120 280" stroke="#252d3d" stroke-width="12" stroke-linecap="round"/>
                        <path d="M140 392 L120 280" stroke="#f59e0b" stroke-width="2" stroke-dasharray="6 8"/>

                        <!-- Lower Pivot Joint -->
                        <circle cx="120" cy="280" r="12" fill="#111827" stroke="#f59e0b" stroke-width="4"/>
                        <circle cx="120" cy="280" r="4" fill="#fbbf24"/>

                        <!-- Upper Arm -->
                        <path d="M120 280 L185 155" stroke="#252d3d" stroke-width="10" stroke-linecap="round"/>

                        <!-- Upper Pivot Joint -->
                        <circle cx="185" cy="155" r="10" fill="#111827" stroke="#f59e0b" stroke-width="3"/>
                        <circle cx="185" cy="155" r="3.5" fill="#fbbf24"/>

                        <!-- Neck -->
                        <path d="M185 155 L215 130" stroke="#1e2430" stroke-width="8" stroke-linecap="round"/>

                        <!-- Lamp Shade Hood -->
                        <path d="M175 125 C185 65, 275 90, 275 155 C250 175, 190 170, 175 125 Z" 
                              fill="#181e29" stroke="#334155" stroke-width="2"/>
                        
                        <!-- Inner Golden Cup Lip -->
                        <ellipse cx="230" cy="152" rx="46" ry="20" transform="rotate(-18 230 152)" 
                                 id="lampShadeInner"
                                 fill="#f59e0b" opacity="0.95" stroke="#fbbf24" stroke-width="2"
                                 filter="drop-shadow(0 0 12px rgba(245, 158, 11, 0.9))"/>

                        <!-- Soft Bulb Glow Halo -->
                        <circle cx="230" cy="150" r="28" id="bulbHalo" fill="url(#bulbRadialGlow)" pointer-events="none" style="transition: opacity 0.4s ease;"/>

                        <!-- Bulb -->
                        <ellipse cx="230" cy="150" rx="14" ry="11" 
                                 id="lampBulb"
                                 fill="#ffffff" 
                                 filter="drop-shadow(0 0 15px #f59e0b) drop-shadow(0 0 25px #fbbf24)"/>

                        <!-- Interactive Pull Cord -->
                        <g class="cord-pull" onclick="toggleLamp()" title="Click to Switch Lamp ON/OFF">
                            <line x1="225" y1="168" x2="225" y2="245" stroke="#fbbf24" stroke-width="2.5" stroke-dasharray="3 3"/>
                            <circle cx="225" cy="252" r="8" fill="#fbbf24" stroke="#d97706" stroke-width="2" filter="drop-shadow(0 0 6px #f59e0b)"/>
                            <circle cx="223" cy="250" r="2.5" fill="#ffffff" opacity="0.8"/>
                            <rect x="242" y="240" width="84" height="24" rx="12" fill="#1e2430" stroke="#f59e0b" stroke-width="1.2"/>
                            <text x="284" y="256" fill="#fbbf24" font-size="10" font-weight="bold" text-anchor="middle">Click Cord</text>
                        </g>
                    </svg>

                    <!-- Lamp Info Text -->
                    <div class="text-center mt-1">
                        <p class="text-xs font-bold text-amber-400 flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-400" id="lampDot"></span>
                            <span id="lampStatusText">Desk Lamp is ON</span>
                        </p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Click the gold cord to toggle light mode</p>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Luxury Dark Login Card -->
                <div class="luxury-card">
                    <!-- Title & Subtitle -->
                    <div class="mb-5">
                        <h2 class="text-3xl font-extrabold text-white tracking-tight">
                            Welcome
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Sign in to continue to your account
                        </p>
                    </div>

                    <!-- Sign In / Sign Up Switcher Pill -->
                    <div class="mb-5 p-1 bg-black/40 rounded-xl border border-white/5 grid grid-cols-2 gap-1 text-xs font-bold text-center">
                        <span class="py-2 rounded-lg bg-white/10 text-white shadow-xs">
                            Sign In
                        </span>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="py-2 rounded-lg text-slate-400 hover:text-white transition">
                                Sign Up
                            </a>
                        @else
                            <span class="py-2 rounded-lg text-slate-600 cursor-not-allowed">
                                Sign Up
                            </span>
                        @endif
                    </div>

                    {{ $slot }}
                </div>

            </div>
        </main>

        <!-- Footer Strip -->
        <footer class="w-full relative z-20 py-3 text-center text-xs text-slate-500 border-t border-white/5 bg-black/30 backdrop-blur-md px-6">
            <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>&copy; {{ date('Y') }} <strong>Al-Madina / ProWave ERP</strong>. All rights reserved.</div>
                <div class="flex items-center gap-4 text-[11px] text-slate-400">
                    <span>AGPR & Treasury Compliant</span>
                    <span>•</span>
                    <a href="tel:+923259385085" class="hover:text-amber-400 transition font-mono">Helpdesk: +92 325 9385 085</a>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script>
            let lampState = true;
            let soundEnabled = true;

            // Generate dust particles
            (function createParticles() {
                const container = document.getElementById('particles-container');
                if (!container) return;
                const count = 25;
                for (let i = 0; i < count; i++) {
                    const p = document.createElement('div');
                    p.className = 'particle';
                    const size = Math.random() * 3 + 1.5;
                    p.style.width = size + 'px';
                    p.style.height = size + 'px';
                    p.style.left = (Math.random() * 100) + 'vw';
                    p.style.animationDuration = (Math.random() * 7 + 6) + 's';
                    p.style.animationDelay = (Math.random() * 5) + 's';
                    p.style.opacity = Math.random() * 0.7 + 0.2;
                    container.appendChild(p);
                }
            })();

            // Audio Click Synthesizer
            function playClickSound(isOn) {
                if (!soundEnabled) return;
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(isOn ? 880 : 440, ctx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(isOn ? 1200 : 220, ctx.currentTime + 0.08);

                    gain.gain.setValueAtTime(0.18, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.08);

                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.09);
                } catch (e) {}
            }

            function toggleSound() {
                soundEnabled = !soundEnabled;
                const icon = document.getElementById('soundIcon');
                const txt = document.getElementById('soundText');
                if (soundEnabled) {
                    icon.textContent = '🔊';
                    txt.textContent = 'Sound: On';
                } else {
                    icon.textContent = '🔇';
                    txt.textContent = 'Sound: Muted';
                }
            }

            function toggleLamp() {
                lampState = !lampState;
                playClickSound(lampState);

                const body = document.body;
                const beamPolygon = document.getElementById('lampBeamPolygon');
                const bulbHalo = document.getElementById('bulbHalo');
                const shadeInner = document.getElementById('lampShadeInner');
                const bulb = document.getElementById('lampBulb');
                const statusTxt = document.getElementById('lampStatusText');
                const dot = document.getElementById('lampDot');

                if (lampState) {
                    body.classList.remove('lamp-off');
                    body.classList.add('lamp-lit');
                    if (beamPolygon) beamPolygon.style.opacity = '1';
                    if (bulbHalo) bulbHalo.style.opacity = '1';
                    if (shadeInner) shadeInner.setAttribute('fill', '#f59e0b');
                    if (bulb) bulb.setAttribute('fill', '#ffffff');
                    if (statusTxt) statusTxt.textContent = 'Desk Lamp is ON';
                    if (dot) dot.className = 'w-2 h-2 rounded-full bg-amber-400';
                } else {
                    body.classList.remove('lamp-lit');
                    body.classList.add('lamp-off');
                    if (beamPolygon) beamPolygon.style.opacity = '0';
                    if (bulbHalo) bulbHalo.style.opacity = '0';
                    if (shadeInner) shadeInner.setAttribute('fill', '#1e2430');
                    if (bulb) bulb.setAttribute('fill', '#334155');
                    if (statusTxt) statusTxt.textContent = 'Desk Lamp is OFF';
                    if (dot) dot.className = 'w-2 h-2 rounded-full bg-slate-600';
                }
            }
        </script>
    </body>
</html>
