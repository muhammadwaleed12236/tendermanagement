new_guest = '''<!DOCTYPE html>
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
                            erp: {
                                blue: '#0056d2',
                                green: '#0d9467',
                                orange: '#cd3805',
                                maroon: '#881337',
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900|jet-brains-mono:500,700&display=swap" rel="stylesheet" />

        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
                background-color: #090d16;
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
                background: radial-gradient(circle at 35% 50%, #151d30 0%, #0d1322 50%, #070a12 100%);
            }
            body.lamp-off {
                background: radial-gradient(circle at 35% 50%, #0e1422 0%, #05080f 100%);
            }

            /* 4-Color Floating Dust Particles */
            .particle {
                position: absolute;
                border-radius: 50%;
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

            /* Animated 4-Color Shimmer Strip */
            @keyframes nav-shimmer {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .nav-accent-strip {
                height: 3px;
                width: 100%;
                background: linear-gradient(90deg, #0056d2 0%, #0d9467 33%, #cd3805 66%, #881337 100%);
                background-size: 250% 100%;
                animation: nav-shimmer 7s ease-in-out infinite;
            }

            /* Main Layout Grid Container */
            .auth-container {
                width: 100%;
                max-width: 1140px;
                margin: auto;
                padding: 1.5rem 1rem;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                gap: 3.5rem;
                position: relative;
                z-index: 10;
            }

            @media (max-width: 900px) {
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
                width: 540px;
                height: 390px;
                background: radial-gradient(ellipse at 15% 15%, rgba(0, 86, 210, 0.3) 0%, rgba(205, 56, 5, 0.12) 40%, transparent 75%);
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
                filter: drop-shadow(0 0 10px rgba(205, 56, 5, 0.9));
            }
            .cord-pull:active {
                transform: translateY(12px);
            }

            /* Executive 3D Login Card Matching Dashboard System Theme */
            .luxury-card {
                width: 100%;
                max-width: 470px;
                background: rgba(15, 23, 42, 0.95);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 2px solid #334155;
                border-bottom: 5px solid #0056d2;
                border-radius: 1.75rem;
                padding: 2.25rem 2rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.85), 0 0 35px -5px rgba(0, 86, 210, 0.3);
                position: relative;
                z-index: 20;
            }

            /* High-Contrast Inputs */
            .luxury-input {
                width: 100%;
                background: #0b1120;
                border: 1.5px solid #334155;
                border-radius: 0.85rem;
                padding: 0.8rem 1rem 0.8rem 2.75rem;
                color: #f8fafc;
                font-size: 0.925rem;
                font-weight: 600;
                transition: all 0.22s ease;
                outline: none;
            }
            .luxury-input:focus {
                border-color: #0056d2;
                box-shadow: 0 0 0 3px rgba(0, 86, 210, 0.3), 0 0 16px -2px rgba(0, 86, 210, 0.45);
                background: #111a2e;
            }

            /* 3D Tactile Action Buttons (Zero Gradient, Solid Colors) */
            .login-btn-primary {
                width: 100%;
                background-color: #0056d2;
                border: 1px solid rgba(255, 255, 255, 0.25);
                border-bottom: 4px solid #07255e;
                box-shadow: 0 6px 18px -1px rgba(0, 86, 210, 0.45);
                color: #ffffff;
                font-weight: 900;
                font-size: 0.95rem;
                padding: 0.85rem 1.5rem;
                border-radius: 0.85rem;
                cursor: pointer;
                transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                letter-spacing: 0.02em;
            }
            .login-btn-primary:hover {
                transform: translateY(-2px);
                background-color: #1a6fe3;
                box-shadow: 0 10px 24px -1px rgba(0, 86, 210, 0.6);
            }
            .login-btn-primary:active {
                transform: translateY(2px);
                border-bottom-width: 1.5px;
                box-shadow: 0 2px 6px rgba(0, 86, 210, 0.3);
            }

            /* Quick Role Demo Pills */
            .role-demo-pill {
                padding: 0.5rem 0.65rem;
                border-radius: 0.75rem;
                border: 1.5px solid;
                border-bottom-width: 3px;
                font-size: 0.75rem;
                font-weight: 800;
                display: flex;
                align-items: center;
                gap: 0.45rem;
                cursor: pointer;
                transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
                white-space: nowrap;
            }
            .role-demo-pill:hover {
                transform: translateY(-2px);
            }
            .role-demo-pill:active {
                transform: translateY(1.5px);
                border-bottom-width: 1.5px;
            }
        </style>
    </head>
    <body class="lamp-lit">
        <!-- Floating 4-Color Dust Particles -->
        <div id="particles-container" class="fixed inset-0 pointer-events-none z-0"></div>

        <!-- Top Header Strip Matching Dashboard Tier 1 -->
        <header class="w-full relative z-30 py-3 px-6 sm:px-10 flex items-center justify-between border-b border-slate-800 bg-[#090d16]/95 backdrop-blur-md">
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-lg bg-[#0056d2] border border-sky-300/40 border-b-2.5 border-[#07255e] flex items-center justify-center font-black text-white text-xs shadow-xs group-hover:scale-105 transition">
                        PW
                    </div>
                    <span class="text-xs sm:text-sm font-black tracking-tight text-white uppercase flex items-center gap-1.5">
                        PROWAVE <span class="text-sky-400 font-extrabold">ERP</span>
                    </span>
                </a>
                
                <span class="text-slate-700 hidden sm:inline">|</span>

                <div class="hidden sm:inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800/90 border border-slate-700 text-slate-200 text-xs font-bold shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                    <span class="text-[10px] uppercase font-bold text-slate-400">Client:</span>
                    <span class="font-bold text-white">Al-Madina Enterprises</span>
                    <span class="text-[9px] uppercase font-black px-1.5 py-0.2 rounded bg-sky-500/20 text-sky-300 border border-sky-400/30">VERIFIED</span>
                </div>
            </div>

            <!-- Right Controls -->
            <div class="flex items-center gap-3">
                <a href="tel:+923259385085" class="hidden md:inline-flex items-center gap-1.5 text-xs font-bold text-slate-300 hover:text-white transition font-mono">
                    <span class="w-5 h-5 rounded-full bg-[#0056d2] flex items-center justify-center text-white text-[10px]">📞</span>
                    <span>+92 325 9385 085</span>
                </a>

                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs font-extrabold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Live Portal</span>
                </span>

                <!-- Sound Toggle Button -->
                <button type="button" 
                        id="soundToggleBtn" 
                        onclick="toggleSound()" 
                        class="text-xs font-bold px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 transition flex items-center gap-1.5 cursor-pointer"
                        title="Toggle Lamp Click Sound">
                    <span id="soundIcon">🔊</span>
                    <span id="soundText" class="hidden sm:inline">Sound: On</span>
                </button>
            </div>
        </header>

        <!-- Animated 4-Color Accent Strip between Header and Page -->
        <div class="nav-accent-strip"></div>

        <!-- Main Center Area -->
        <main class="flex-1 flex items-center justify-center relative z-20">
            <div class="auth-container">
                
                <!-- LEFT COLUMN: Animated Desk Lamp & Cord -->
                <div class="lamp-wrapper">
                    <!-- SVG Lamp Vector Graphic -->
                    <svg viewBox="0 0 380 540" class="w-[310px] h-[480px] select-none" fill="none" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">
                        <defs>
                            <!-- Ambient Light Cone Projection extending across to the Login Card -->
                            <linearGradient id="lampConeGradient" x1="0%" y1="0%" x2="100%" y2="85%">
                                <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.65"/>
                                <stop offset="25%" stop-color="#0056d2" stop-opacity="0.38"/>
                                <stop offset="65%" stop-color="#cd3805" stop-opacity="0.14"/>
                                <stop offset="100%" stop-color="#0056d2" stop-opacity="0.0"/>
                            </linearGradient>

                            <!-- Bulb Intense 3D Radial Glow -->
                            <radialGradient id="bulbCenterGlow" cx="50%" cy="45%" r="55%">
                                <stop offset="0%" stop-color="#ffffff" stop-opacity="1"/>
                                <stop offset="35%" stop-color="#f0f9ff" stop-opacity="1"/>
                                <stop offset="65%" stop-color="#7dd3fc" stop-opacity="0.95"/>
                                <stop offset="88%" stop-color="#0284c7" stop-opacity="0.6"/>
                                <stop offset="100%" stop-color="#0056d2" stop-opacity="0"/>
                            </radialGradient>

                            <!-- Inner Dish Golden/Warm Reflector -->
                            <radialGradient id="innerShadeGrad" cx="50%" cy="40%" r="60%">
                                <stop offset="0%" stop-color="#ffffff"/>
                                <stop offset="22%" stop-color="#fed7aa"/>
                                <stop offset="55%" stop-color="#f97316"/>
                                <stop offset="85%" stop-color="#cd3805"/>
                                <stop offset="100%" stop-color="#7c2d12"/>
                            </radialGradient>

                            <!-- Matte Dark Charcoal Lamp Outer Hood Gradient -->
                            <linearGradient id="outerHoodGrad" x1="15%" y1="0%" x2="85%" y2="100%">
                                <stop offset="0%" stop-color="#475569"/>
                                <stop offset="35%" stop-color="#1e293b"/>
                                <stop offset="75%" stop-color="#0f172a"/>
                                <stop offset="100%" stop-color="#020617"/>
                            </linearGradient>

                            <!-- Warm Metallic Joint Gradient -->
                            <linearGradient id="goldBrassGrad" x1="20%" y1="0%" x2="80%" y2="100%">
                                <stop offset="0%" stop-color="#fed7aa"/>
                                <stop offset="30%" stop-color="#cd3805"/>
                                <stop offset="70%" stop-color="#7c2d12"/>
                                <stop offset="100%" stop-color="#fed7aa"/>
                            </linearGradient>

                            <!-- Drop Shadow for Upper Joints -->
                            <filter id="jointShadow" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="1" dy="2" stdDeviation="2" flood-color="#000000" flood-opacity="0.6"/>
                            </filter>
                        </defs>

                        <!-- Outer Ambient Halo Behind Hood -->
                        <ellipse cx="230" cy="145" rx="115" ry="95" id="hoodOuterHalo" 
                                 fill="#0056d2" opacity="0.45" filter="blur(26px)" pointer-events="none" 
                                 style="transition: opacity 0.4s ease;"/>

                        <!-- Light Cone Beam Projecting Down and Across to the Right Card -->
                        <polygon id="lampBeamPolygon" 
                                 points="155,160 305,170 780,410 410,600" 
                                 fill="url(#lampConeGradient)" 
                                 pointer-events="none"
                                 style="transition: opacity 0.4s ease; filter: blur(5px);"/>

                        <!-- 1. Deep Floor Shadow -->
                        <ellipse cx="160" cy="460" rx="115" ry="18" fill="#000000" opacity="0.8" filter="blur(6px)"/>

                        <!-- 2. Lamp Circular Matte Base with Chamfered Ring -->
                        <ellipse cx="160" cy="450" rx="92" ry="16" fill="#1e293b" stroke="#334155" stroke-width="2.5"/>
                        <ellipse cx="160" cy="447" rx="80" ry="12" fill="#0f172a"/>
                        <ellipse cx="160" cy="445" rx="68" ry="9" fill="#1e293b"/>

                        <!-- 3. Lower Brass Joint with Inner Pivot Ring -->
                        <g filter="url(#jointShadow)">
                            <circle cx="160" cy="438" r="19" fill="url(#goldBrassGrad)" stroke="#0f172a" stroke-width="2.5"/>
                            <circle cx="160" cy="438" r="10.5" fill="#0f172a"/>
                            <circle cx="160" cy="438" r="5" fill="url(#goldBrassGrad)"/>
                        </g>

                        <!-- 4. Lower Arm Segment in Tall Standing Mode -->
                        <path d="M160 438 L122 250" stroke="#1e293b" stroke-width="11" stroke-linecap="round"/>
                        <path d="M156 438 L118 250" stroke="#64748b" stroke-width="2"/>
                        <path d="M164 438 L126 250" stroke="#0f172a" stroke-width="2.5"/>

                        <!-- Center Coiled Spring Line along lower arm -->
                        <path d="M157 415 L124 270" stroke="#cd3805" stroke-width="2" stroke-dasharray="3 4"/>

                        <!-- 5. Elbow Pivot Joint with Dual Warm Rings -->
                        <g filter="url(#jointShadow)">
                            <circle cx="122" cy="250" r="17" fill="url(#goldBrassGrad)" stroke="#0f172a" stroke-width="2.5"/>
                            <circle cx="122" cy="250" r="9.5" fill="#0f172a"/>
                            <circle cx="122" cy="250" r="4.5" fill="url(#goldBrassGrad)"/>
                        </g>

                        <!-- 6. Upper Arm Segment Reaching Upward to Elevated Hood -->
                        <path d="M122 250 L176 150" stroke="#1e293b" stroke-width="8.5" stroke-linecap="round"/>
                        <path d="M120 250 L174 150" stroke="#64748b" stroke-width="1.8"/>

                        <!-- Upper Neck Pivot Joint Socket -->
                        <circle cx="176" cy="150" r="7" fill="url(#goldBrassGrad)" stroke="#0f172a" stroke-width="2"/>

                        <!-- 7. Lamp Shade Head (True Smooth Egg-Dome Shape) -->
                        <path d="M128 178 C120 70, 230 45, 298 178 Z" 
                              fill="url(#outerHoodGrad)" stroke="#374151" stroke-width="2"/>

                        <!-- Specular Reflection on Outer Egg Crest -->
                        <path d="M165 85 C195 62, 235 65, 260 98" stroke="#9ca3af" stroke-width="3" stroke-linecap="round" opacity="0.35"/>

                        <!-- Smooth Interior Golden Reflector Dish -->
                        <ellipse cx="213" cy="178" rx="85" ry="32" 
                                 id="lampShadeInner"
                                 fill="url(#innerShadeGrad)" 
                                 stroke="url(#goldBrassGrad)" stroke-width="3.5"
                                 filter="drop-shadow(0 0 18px rgba(205, 56, 5, 0.85))"/>

                        <!-- Glowing Spherical Bulb Recessed Inside Dish -->
                        <circle cx="213" cy="174" r="30" id="bulbHalo" fill="url(#bulbCenterGlow)" pointer-events="none"/>
                        <circle cx="213" cy="172" r="16" id="lampBulb" fill="#ffffff" filter="drop-shadow(0 0 12px #38bdf8) drop-shadow(0 0 22px #0056d2)"/>
                        <circle cx="210" cy="168" r="4.5" fill="#ffffff" opacity="0.95"/>

                        <!-- Interactive Glowing Pull Cord Hanging Down from Bulb Center -->
                        <g class="cord-pull" onclick="toggleLamp()" title="Click to Switch Lamp ON/OFF">
                            <!-- Hanging stem wire -->
                            <line x1="213" y1="188" x2="213" y2="285" stroke="#fed7aa" stroke-width="3" stroke-linecap="round" filter="drop-shadow(0 0 5px #cd3805)"/>
                            
                            <!-- Glowing Warm Capsule Bead with Light Reflection -->
                            <circle cx="213" cy="292" r="22" fill="#cd3805" opacity="0.3" filter="blur(7px)"/>
                            <ellipse cx="213" cy="294" rx="10" ry="13" fill="url(#goldBrassGrad)" stroke="#ffffff" stroke-width="1.8" filter="drop-shadow(0 0 10px #cd3805)"/>
                            <ellipse cx="210" cy="290" rx="3.5" ry="5" fill="#ffffff" opacity="0.9"/>

                            <!-- Interactive Tooltip Capsule Pill -->
                            <rect x="238" y="280" width="88" height="28" rx="14" fill="#0f172a" stroke="#cd3805" stroke-width="1.8"/>
                            <text x="282" y="298" fill="#fb923c" font-size="11" font-weight="800" text-anchor="middle" font-family="system-ui, sans-serif">Click Cord</text>
                        </g>
                    </svg>

                    <!-- Lamp Info Text -->
                    <div class="text-center mt-1">
                        <p class="text-xs font-bold text-sky-400 flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-sky-400" id="lampDot"></span>
                            <span id="lampStatusText">Desk Lamp is ON</span>
                        </p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Click the cord bead to toggle lighting</p>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Executive 3D Login Card -->
                <div class="luxury-card">
                    {{ $slot }}
                </div>

            </div>
        </main>

        <!-- Footer Strip -->
        <footer class="w-full relative z-20 py-3 text-center text-xs text-slate-500 border-t border-slate-800 bg-[#090d16]/95 backdrop-blur-md px-6">
            <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>&copy; {{ date('Y') }} <strong class="text-slate-300">ProWave ERP • Al-Madina Enterprises</strong>. All rights reserved.</div>
                <div class="flex items-center gap-4 text-[11px] text-slate-400">
                    <span class="text-emerald-400 font-bold">● AGPR & Treasury Compliant</span>
                    <span>•</span>
                    <a href="tel:+923259385085" class="hover:text-sky-400 transition font-mono">Helpdesk: +92 325 9385 085</a>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script>
            let lampState = true;
            let soundEnabled = true;

            // Generate 4-Color Floating Particles
            (function createParticles() {
                const container = document.getElementById('particles-container');
                if (!container) return;
                const colors = ['#0056d2', '#0d9467', '#cd3805', '#881337', '#38bdf8'];
                const count = 30;
                for (let i = 0; i < count; i++) {
                    const p = document.createElement('div');
                    p.className = 'particle';
                    const size = Math.random() * 3 + 1.5;
                    p.style.width = size + 'px';
                    p.style.height = size + 'px';
                    p.style.left = (Math.random() * 100) + 'vw';
                    p.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    p.style.animationDuration = (Math.random() * 7 + 6) + 's';
                    p.style.animationDelay = (Math.random() * 5) + 's';
                    p.style.opacity = Math.random() * 0.6 + 0.2;
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
                const hoodOuterHalo = document.getElementById('hoodOuterHalo');
                const shadeInner = document.getElementById('lampShadeInner');
                const bulb = document.getElementById('lampBulb');
                const statusTxt = document.getElementById('lampStatusText');
                const dot = document.getElementById('lampDot');

                if (lampState) {
                    body.classList.remove('lamp-off');
                    body.classList.add('lamp-lit');
                    if (beamPolygon) beamPolygon.style.opacity = '1';
                    if (bulbHalo) bulbHalo.style.opacity = '1';
                    if (hoodOuterHalo) hoodOuterHalo.style.opacity = '0.45';
                    if (shadeInner) shadeInner.setAttribute('fill', 'url(#innerShadeGrad)');
                    if (bulb) bulb.setAttribute('fill', '#ffffff');
                    if (statusTxt) statusTxt.textContent = 'Desk Lamp is ON';
                    if (dot) dot.className = 'w-2 h-2 rounded-full bg-sky-400';
                } else {
                    body.classList.remove('lamp-lit');
                    body.classList.add('lamp-off');
                    if (beamPolygon) beamPolygon.style.opacity = '0';
                    if (bulbHalo) bulbHalo.style.opacity = '0';
                    if (hoodOuterHalo) hoodOuterHalo.style.opacity = '0';
                    if (shadeInner) shadeInner.setAttribute('fill', '#1e2430');
                    if (bulb) bulb.setAttribute('fill', '#334155');
                    if (statusTxt) statusTxt.textContent = 'Desk Lamp is OFF';
                    if (dot) dot.className = 'w-2 h-2 rounded-full bg-slate-600';
                }
            }
        </script>
    </body>
</html>'''

with open(r"e:\Tender_mng\tender_system\resources\views\layouts\guest.blade.php", "w", encoding="utf-8") as f:
    f.write(new_guest)

print("Saved new guest.blade.php successfully")
