<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ProWave ERP') }} - Sign In</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Claymorphic Styles for Auth Pages (100vh Zero-Scroll Viewport) -->
        <style>
            html, body {
                height: 100%;
                overflow: hidden;
            }

            .clay-canvas-auth {
                background-color: #f3f6fd;
                background-image: 
                    radial-gradient(circle at 10% 10%, rgba(167, 139, 250, 0.28) 0%, transparent 45%),
                    radial-gradient(circle at 90% 12%, rgba(244, 114, 182, 0.25) 0%, transparent 45%),
                    radial-gradient(circle at 85% 85%, rgba(96, 165, 250, 0.28) 0%, transparent 45%),
                    radial-gradient(circle at 15% 90%, rgba(52, 211, 153, 0.25) 0%, transparent 45%),
                    radial-gradient(circle at 50% 50%, rgba(251, 191, 36, 0.14) 0%, transparent 55%);
                height: 100vh;
                max-height: 100vh;
                position: relative;
                overflow: hidden;
            }

            .clay-card-auth {
                background: #ffffff;
                border-radius: 2rem;
                border: 2px solid rgba(255, 255, 255, 0.95);
                border-bottom: 6px solid #e0e7ff;
                box-shadow: 
                    0 35px 70px -15px rgba(99, 102, 241, 0.28),
                    0 20px 40px -10px rgba(15, 23, 42, 0.16),
                    0 8px 16px -4px rgba(15, 23, 42, 0.08),
                    inset 0 3px 6px #ffffff,
                    inset 0 -3px 6px rgba(99, 102, 241, 0.05);
                position: relative;
                overflow: hidden;
                transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.35s ease, border-color 0.35s ease;
            }

            .clay-card-auth:hover {
                transform: translateY(-5px);
                border-bottom: 8px solid #c7d2fe;
                box-shadow: 
                    0 45px 85px -15px rgba(99, 102, 241, 0.35),
                    0 25px 48px -10px rgba(15, 23, 42, 0.22),
                    inset 0 3px 6px #ffffff;
            }

            .clay-input {
                background-color: #f8fafc;
                border: 1.5px solid #e2e8f0;
                border-top: 2.5px solid #cbd5e1;
                border-radius: 1rem;
                box-shadow: 
                    inset 0 2px 5px rgba(15, 23, 42, 0.07),
                    inset 0 1px 2px rgba(15, 23, 42, 0.04),
                    0 1px 1px #ffffff;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .clay-input:focus {
                background-color: #ffffff;
                border-color: #6366f1;
                border-top-color: #6366f1;
                box-shadow: 
                    0 0 0 4px rgba(99, 102, 241, 0.16),
                    0 10px 22px -6px rgba(99, 102, 241, 0.22),
                    inset 0 1px 2px #ffffff;
                outline: none;
            }

            .clay-btn-auth {
                background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
                border-radius: 1.1rem;
                border: 1px solid rgba(255, 255, 255, 0.6);
                border-bottom: 5px solid #4338ca;
                box-shadow: 
                    0 16px 32px -6px rgba(139, 92, 246, 0.52),
                    0 6px 14px -2px rgba(99, 102, 241, 0.35),
                    inset 0 2px 3px rgba(255, 255, 255, 0.65),
                    inset 0 -2px 3px rgba(0, 0, 0, 0.2);
                transition: all 0.15s ease-out;
            }
            .clay-btn-auth:hover {
                transform: translateY(-2px);
                border-bottom-width: 6px;
                box-shadow: 
                    0 22px 38px -6px rgba(236, 72, 153, 0.55),
                    0 10px 18px -3px rgba(139, 92, 246, 0.4);
            }
            .clay-btn-auth:active {
                transform: translateY(3px);
                border-bottom-width: 1.5px;
                box-shadow: 
                    0 4px 10px rgba(139, 92, 246, 0.3),
                    inset 0 2px 4px rgba(0, 0, 0, 0.25);
            }

            @keyframes clayFloat {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-6px); }
            }
            .clay-float {
                animation: clayFloat 4s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans text-slate-800 antialiased clay-canvas-auth flex flex-col justify-between h-screen max-h-screen overflow-hidden">
        <!-- Floating 3D Decorative Clay Orbs (Background Polish) -->
        <div class="hidden lg:block pointer-events-none">
            <!-- Orb 1: Candy Peach/Orange Clay Sphere -->
            <div class="absolute top-12 right-20 w-14 h-14 rounded-full clay-float" 
                 style="background: radial-gradient(circle at 35% 30%, #ffedd5 0%, #fb923c 55%, #ea580c 100%); box-shadow: 0 16px 32px -4px rgba(234, 88, 12, 0.35), inset -2px -4px 6px rgba(0,0,0,0.15), inset 2px 4px 6px rgba(255,255,255,0.7); animation-delay: 0.3s;"></div>

            <!-- Orb 2: Lavender Violet Clay Pill -->
            <div class="absolute bottom-20 left-16 w-12 h-16 rounded-full clay-float" 
                 style="background: radial-gradient(circle at 35% 30%, #f3e8ff 0%, #c084fc 55%, #7e22ce 100%); box-shadow: 0 16px 28px -4px rgba(126, 34, 206, 0.3), inset -2px -4px 6px rgba(0,0,0,0.15), inset 2px 4px 6px rgba(255,255,255,0.7); animation-delay: 1.2s; transform: rotate(18deg);"></div>

            <!-- Orb 3: Mint Aqua Clay Bubble -->
            <div class="absolute top-36 left-24 w-10 h-10 rounded-full clay-float" 
                 style="background: radial-gradient(circle at 35% 30%, #dcfce7 0%, #34d399 55%, #059669 100%); box-shadow: 0 12px 22px -4px rgba(5, 150, 105, 0.3), inset -2px -3px 5px rgba(0,0,0,0.15), inset 2px 3px 5px rgba(255,255,255,0.7); animation-delay: 2s;"></div>
        </div>

        <!-- Top Support Strip for Guest / Login (Slim) -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 text-xs text-slate-600 py-2 px-4 sm:px-8 relative z-20 shrink-0">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-extrabold text-slate-900 tracking-tight">ProWave Technologies ERP</span>
                    <span class="hidden sm:inline-block px-2 py-0.5 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200">v2.4 Live</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="tel:+923259385085" class="font-bold text-indigo-700 font-mono hover:text-indigo-900 transition inline-flex items-center gap-1.5 bg-indigo-50/80 hover:bg-indigo-100/80 px-2.5 py-1 rounded-lg border border-indigo-200/80 text-[11px]">
                        <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>Support: +92 325 9385 085</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Auth Form Area (Centered with zero scroll) -->
        <main class="flex-1 flex flex-col justify-center items-center py-2 px-4 sm:px-6 relative z-10 min-h-0">
            <div class="w-full sm:max-w-[420px]">
                {{ $slot }}
            </div>
        </main>

        <!-- Modern Footer Strip (Slim) -->
        <footer class="py-2 text-center text-[11px] text-slate-500 border-t border-slate-200/80 bg-white/60 backdrop-blur-md relative z-20 shrink-0">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between px-6 gap-1">
                <div>&copy; {{ date('Y') }} <strong>ProWave Technologies</strong>. Departmental Procurement & Financial Management.</div>
                <div class="flex items-center gap-2.5 text-[10px] text-slate-400 font-medium">
                    <span>AGPR Invoicing Engine</span>
                    <span>•</span>
                    <span>256-Bit SSL Encrypted</span>
                </div>
            </div>
        </footer>
    </body>
</html>