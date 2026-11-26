<style>
    /* Animated Glowing Accent Strip between Tier 1 & Tier 2 */
    @keyframes nav-shimmer {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .nav-accent-strip {
        height: 2.5px;
        width: 100%;
        background: linear-gradient(90deg, #0056d2 0%, #38bdf8 25%, #0d9467 50%, #cd3805 75%, #0056d2 100%);
        background-size: 250% 100%;
        animation: nav-shimmer 7s ease-in-out infinite;
    }

    /* Tactile 3D Action Buttons with Smooth Push & Hover Lift */
    .nav-btn-tender {
        background-color: #0056d2;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-bottom: 3.5px solid #07255e;
        box-shadow: 0 4px 14px -1px rgba(0, 86, 210, 0.4);
        color: #ffffff;
        font-weight: 900;
        transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .nav-btn-tender:hover {
        transform: translateY(-2px);
        background-color: #1a6fe3;
        box-shadow: 0 8px 20px -1px rgba(0, 86, 210, 0.55);
    }
    .nav-btn-tender:active {
        transform: translateY(1.5px);
        border-bottom-width: 1.5px;
        box-shadow: 0 2px 6px rgba(0, 86, 210, 0.25);
    }

    .nav-btn-bill {
        background-color: #0d9467;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-bottom: 3.5px solid #064e3b;
        box-shadow: 0 4px 14px -1px rgba(13, 148, 103, 0.4);
        color: #ffffff;
        font-weight: 900;
        transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .nav-btn-bill:hover {
        transform: translateY(-2px);
        background-color: #10b981;
        box-shadow: 0 8px 20px -1px rgba(13, 148, 103, 0.55);
    }
    .nav-btn-bill:active {
        transform: translateY(1.5px);
        border-bottom-width: 1.5px;
        box-shadow: 0 2px 6px rgba(13, 148, 103, 0.25);
    }

    /* Top Bar Generate Bill CTA */
    .nav-btn-quick-bill {
        background-color: #0056d2;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-bottom: 3.5px solid #07255e;
        box-shadow: 0 4px 14px -1px rgba(0, 86, 210, 0.4);
        color: #ffffff;
        font-weight: 900;
        transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .nav-btn-quick-bill:hover {
        transform: translateY(-2px);
        background-color: #1a6fe3;
        box-shadow: 0 8px 20px -1px rgba(0, 86, 210, 0.55);
    }
    .nav-btn-quick-bill:active {
        transform: translateY(1.5px);
        border-bottom-width: 1.5px;
    }

    /* Animated Navigation Links */
    .nav-pill-item {
        transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .nav-pill-item svg.nav-main-icon {
        width: 17px !important;
        height: 17px !important;
        min-width: 17px !important;
        max-width: 17px !important;
        flex-shrink: 0 !important;
        display: inline-block !important;
    }
    .nav-pill-item svg.nav-chevron-icon {
        width: 13px !important;
        height: 13px !important;
        min-width: 13px !important;
        max-width: 13px !important;
        flex-shrink: 0 !important;
        display: inline-block !important;
    }
    .nav-pill-item:hover:not(.nav-pill-active) {
        transform: translateY(-2px);
        background-color: #eff6ff;
        color: #0056d2;
        box-shadow: 0 4px 12px -2px rgba(0, 86, 210, 0.18);
    }
    .nav-pill-active {
        background-color: #0056d2 !important;
        color: #ffffff !important;
        border-bottom: 3.5px solid #07255e !important;
        box-shadow: 0 4px 16px -2px rgba(0, 86, 210, 0.45) !important;
        transform: translateY(-1px);
    }
    .nav-pill-active:hover {
        transform: translateY(-2.5px);
        box-shadow: 0 8px 22px -2px rgba(0, 86, 210, 0.55) !important;
    }

    /* Bell Ring Shake Animation on Hover */
    @keyframes bell-ring {
        0%, 100% { transform: rotate(0deg); }
        15% { transform: rotate(16deg); }
        30% { transform: rotate(-14deg); }
        45% { transform: rotate(12deg); }
        60% { transform: rotate(-8deg); }
        75% { transform: rotate(4deg); }
    }
    @keyframes dropdown-pop {
        0% {
            opacity: 0;
            transform: translateY(-8px) scale(0.97);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    .nav-dropdown-enter {
        animation: dropdown-pop 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .mega-dropdown-panel {
        width: 580px !important;
        min-width: 540px !important;
        max-width: calc(100vw - 2rem) !important;
        box-sizing: border-box !important;
    }
    .mega-dropdown-grid {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        column-gap: 0.75rem !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .mega-dropdown-col {
        min-width: 240px !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .dropdown-col-item {
        transition: all 0.18s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .dropdown-col-item:hover {
        transform: translateX(3px);
        background-color: #f8fafc;
    }
    .dropdown-col-item:hover .item-icon-box {
        transform: scale(1.1);
    }

    .hover-bell:hover svg {
        animation: bell-ring 0.65s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        transform-origin: top center;
    }
</style>
<!-- Two-Tier ERP Navigation Header -->
<div x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 15)" class="sticky top-0 z-50 transition-all duration-300" :class="{ 'shadow-xl shadow-slate-900/10': scrolled }">
    <!-- Top Bar: Executive Midnight Slate (#090d16) -->
    <div class="bg-[#090d16] border-b border-slate-800/80 text-white select-none shadow-sm transition-all duration-300" :class="scrolled ? 'h-13 sm:h-14' : 'h-16 sm:h-17'" style="background: linear-gradient(90deg, #090d16 0%, #0f172a 45%, #0d1527 100%);">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 xl:px-10 h-full flex items-center justify-between gap-4">
            <!-- Left: Logo & Company Pill -->
            <div class="flex items-center gap-3">
                <!-- Logo Monogram & Name: ProWave Technologies -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 rounded-xl bg-[#0056d2] border border-sky-300/40 border-b-3 border-[#07255e] flex items-center justify-center font-black text-white text-sm shadow-md shadow-blue-500/25 group-hover:scale-105 group-hover:-rotate-3 transition duration-200">
                        PW
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="font-black text-[15px] sm:text-[17px] tracking-tight text-white whitespace-nowrap flex items-center gap-1.5">
                            PROWAVE <span class="text-sky-400 font-extrabold">TECHNOLOGIES</span>
                        </span>
                        <span class="text-[11px] text-slate-300 font-bold tracking-wider uppercase">
                            Tender & Procurement ERP
                        </span>
                    </div>
                </a>

                @auth
                    <!-- Dynamic Client Company Pill (Al-Madina Enterprises) -->
                    <div class="hidden md:inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-800/95 border border-slate-700 text-slate-200 text-xs sm:text-[13px] font-bold whitespace-nowrap ms-1.5 shadow-xs backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Client:</span>
                        <span class="truncate max-w-[240px] font-bold text-white tracking-wide">
                            {{ Auth::user()->company_name ?: 'Al-Madina Enterprises' }}
                        </span>
                        <span class="text-[9px] uppercase font-black px-1.5 py-0.2 rounded bg-sky-500/20 text-sky-300 border border-sky-400/30">VERIFIED</span>
                    </div>
                @endauth
            </div>

            <!-- Right: Support Contact, Status & Controls -->
            <div class="flex items-center gap-3 sm:gap-3.5">
                <!-- Phone Contact with Cyan Circular Icon -->
                <a href="tel:+923259385085" class="inline-flex items-center gap-2 text-white font-bold text-xs sm:text-[13px] hover:text-sky-300 transition whitespace-nowrap">
                    <span class="w-7 h-7 rounded-full bg-[#0056d2] border-b-2 border-[#07255e] flex items-center justify-center text-white shrink-0 shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </span>
                    <span class="font-mono tracking-tight text-xs sm:text-[13.5px] font-extrabold text-slate-200">+92 325 9385 085</span>
                </a>

                <!-- Vertical Divider -->
                <span class="text-slate-700 hidden sm:inline">|</span>

                <!-- Support Pill with Headset Icon -->
                <a href="https://wa.me/923259385085" target="_blank" rel="noopener noreferrer" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-800/95 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs sm:text-[13px] font-bold transition shadow-xs whitespace-nowrap hover:scale-105" title="Chat on WhatsApp">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Support</span>
                </a>

                <!-- Online Status Pill -->
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs sm:text-[13px] font-extrabold whitespace-nowrap">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Online</span>
                </span>

                @auth
                    <!-- Quick Bill CTA Button (Vibrant 3D Gradient) -->
                    <a href="{{ route('bills.create') }}" class="nav-btn-quick-bill hidden lg:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-[13px] whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>Generate Bill</span>
                    </a>

                    <!-- Notification Bell Icon with Dropdown -->
                    @php
                        $userUnreadCount = Auth::user()->unreadNotifications()->count();
                        $latestNotifs = Auth::user()->notifications()->latest()->limit(6)->get();
                    @endphp
                    <div class="relative hidden sm:block" x-data="{ notifOpen: false }">
                        <button @click="notifOpen = !notifOpen" 
                                class="hover-bell relative p-2.5 text-slate-300 hover:text-white transition rounded-full hover:bg-slate-800/80 focus:outline-none"
                                title="Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if($userUnreadCount > 0)
                                <span class="absolute top-1 right-1 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-600 text-[9px] font-black text-white items-center justify-center">
                                        {{ $userUnreadCount > 9 ? '9+' : $userUnreadCount }}
                                    </span>
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown Panel -->
                        <div x-show="notifOpen" 
                             @click.away="notifOpen = false" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden text-left" 
                             style="display: none;">
                            
                            <!-- Header -->
                            <div class="px-4 py-3 bg-slate-900 text-white flex items-center justify-between border-b border-slate-800">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm tracking-tight">Notifications</span>
                                    @if($userUnreadCount > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white">
                                            {{ $userUnreadCount }} new
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    @if($userUnreadCount > 0)
                                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sky-400 hover:text-sky-300 font-semibold transition text-[11px]">
                                                Mark all read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Feed Items -->
                            <div class="divide-y divide-slate-100 max-h-84 overflow-y-auto">
                                @forelse($latestNotifs as $item)
                                    @php
                                        $itemData = $item->data;
                                        $unread = is_null($item->read_at);
                                        $lvl = $itemData['level'] ?? 'info';
                                        $iconDot = match($lvl) {
                                            'danger' => 'bg-rose-500',
                                            'warning' => 'bg-amber-500',
                                            'success' => 'bg-emerald-500',
                                            default => 'bg-blue-500',
                                        };
                                    @endphp
                                    <a href="{{ route('notifications.read', $item->id) }}" 
                                       class="block p-3.5 hover:bg-slate-50 transition {{ $unread ? 'bg-sky-50/40' : '' }}">
                                        <div class="flex items-start gap-2.5">
                                            <span class="w-2 h-2 rounded-full {{ $iconDot }} mt-1.5 shrink-0 {{ $unread ? 'ring-2 ring-sky-300 animate-pulse' : 'opacity-50' }}"></span>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-1 mb-0.5">
                                                    <h4 class="text-xs font-bold text-slate-900 truncate {{ $unread ? 'font-black text-slate-950' : 'text-slate-700' }}">
                                                        {{ $itemData['title'] ?? 'System Alert' }}
                                                    </h4>
                                                    <span class="text-[10px] text-slate-400 shrink-0">
                                                        {{ $item->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-[11px] text-slate-600 line-clamp-2 leading-snug">
                                                    {{ $itemData['message'] ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-6 text-center text-slate-400">
                                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                        <p class="text-xs font-semibold text-slate-600">No notifications yet</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">All updates & alerts will appear here</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Footer -->
                            <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                                <a href="{{ route('notifications.index') }}" 
                                   class="text-xs font-bold text-[#0056d2] hover:text-[#0b327b] transition inline-flex items-center gap-1">
                                    <span>View All Notifications & History</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Avatar with Dropdown -->
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="flex items-center justify-center p-0.5 rounded-full text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500 focus:outline-none transition">
                                <div class="w-8 h-8 rounded-full bg-[#0f172a] border border-slate-600 flex items-center justify-center text-slate-200">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-100 bg-slate-50">
                                <div class="font-bold text-sm text-slate-900 truncate">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</div>
                                @if(Auth::user()->company_name)
                                    <div class="text-xs text-blue-600 font-semibold mt-0.5 truncate">{{ Auth::user()->company_name }}</div>
                                @endif
                            </div>

                            <x-dropdown-link :href="route('notifications.index')" class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                <span>{{ __('Notifications') }}</span>
                                @if($userUnreadCount > 0)
                                    <span class="ml-auto px-1.5 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white">{{ $userUnreadCount }}</span>
                                @endif
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ __('Profile & Settings') }}
                            </x-dropdown-link>

                            @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('users.view'))
                                <x-dropdown-link :href="route('users.index')" class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-[#0056d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    {{ __('Users & Roles') }}
                                </x-dropdown-link>
                            @endif

                            @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('activity_logs.view'))
                                <x-dropdown-link :href="route('activity-logs.index')" class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('Activity Log (Audit Trail)') }}
                                </x-dropdown-link>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="flex items-center gap-2 text-sm text-rose-600 hover:text-rose-700 hover:bg-rose-50">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition">
                        Log in
                    </a>
                @endauth

                <!-- Mobile Hamburger Toggle -->
                <div class="-me-2 flex items-center lg:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Animated Accent Strip -->
    <div class="nav-accent-strip"></div>

    <!-- Bottom Tier: Luminous Clean Glass Navigation Bar with Dropdowns -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-slate-200/90 text-slate-700 select-none shadow-xs transition-all duration-300"
         :class="scrolled ? 'h-14 sm:h-14.5 py-1' : 'h-15 sm:h-15.5 py-1.5'">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 xl:px-10 h-full">
            <div class="flex items-center justify-between h-full gap-3">
                <!-- Navigation Items with 2-Column Mega Dropdowns -->
                <div class="hidden lg:flex items-center gap-1 xl:gap-1.5 flex-1 min-w-0">
                    @auth
                        <!-- 1. Dashboard -->
                        <a href="{{ route('dashboard') }}" 
                           class="nav-pill-item inline-flex items-center gap-2 px-3.5 xl:px-4 py-2 sm:py-2.5 rounded-xl text-[13.5px] xl:text-[14px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('dashboard') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                            <svg width="17" height="17" class="nav-main-icon w-[17px] h-[17px] shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-[#0056d2]' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            <span>{{ __('Dashboard') }}</span>
                        </a>

                        <!-- 2. Tenders Management (Independent Option) -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="nav-pill-item inline-flex items-center gap-1.5 px-3 xl:px-3.5 py-2 sm:py-2.5 rounded-xl text-[13px] xl:text-[13.5px] font-black tracking-tight whitespace-nowrap {{ (request()->routeIs('tenders.*') && request('tender_type') !== 'epad' && request('type') !== 'epad') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                                <svg width="17" height="17" class="nav-main-icon w-[17px] h-[17px] shrink-0 {{ (request()->routeIs('tenders.*') && request('tender_type') !== 'epad' && request('type') !== 'epad') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ __('Tender Management System') }}</span>
                                <svg width="13" height="13" class="nav-chevron-icon w-[13px] h-[13px] shrink-0 {{ (request()->routeIs('tenders.*') && request('tender_type') !== 'epad' && request('type') !== 'epad') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute left-0 mt-2 w-[320px] sm:w-[350px] mega-dropdown-panel rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-blue-300 shadow-2xl shadow-blue-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none; width: 340px;">
                                
                                <div class="space-y-1">
                                    <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0056d2] border-b border-blue-100 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-[#0056d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Tenders Operations</span>
                                    </div>

                                    <!-- 1. Create Tender -->
                                    <a href="{{ route('tenders.create') }}" @click="open = false" 
                                       class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('tenders.create') && request('type') !== 'epad' ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                        <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">+ Create New Tender</div>
                                            <div class="text-[11px] font-medium text-slate-500 truncate">Entry & publishing wizard</div>
                                        </div>
                                    </a>

                                    <!-- 2. Tender Details -->
                                    <a href="{{ route('tenders.index') }}" @click="open = false" 
                                       class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('tenders.index') && request('tender_type') !== 'epad' ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                        <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">Tender Details</div>
                                            <div class="text-[11px] font-medium text-slate-500 truncate">Directory of all standard tenders</div>
                                        </div>
                                    </a>

                                    <!-- 3. Tenders Management -->
                                    <a href="{{ route('tenders.reports') }}" @click="open = false" 
                                       class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('tenders.reports') ? 'bg-emerald-50 text-[#0d9467] font-black' : 'text-slate-700 hover:text-[#0d9467]' }}">
                                        <div class="item-icon-box w-8 h-8 rounded-lg bg-emerald-100 text-[#0d9467] border-b-2 border-emerald-300 flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0d9467] truncate">Tender & Contract Management</div>
                                            <div class="text-[11px] font-medium text-slate-500 truncate">Won contracts, CDR security & fees</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 3. EPADS 2.0 (Independent Option) -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="nav-pill-item inline-flex items-center gap-1.5 px-3 xl:px-3.5 py-2 sm:py-2.5 rounded-xl text-[13px] xl:text-[13.5px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('epads.*') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#881337]' }}">
                                <span class="w-2 h-2 rounded-full bg-rose-600 shrink-0"></span>
                                <span>{{ __('EPADS 2.0') }}</span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-wider {{ request()->routeIs('epads.*') ? 'bg-white/20 text-white border border-white/30' : 'bg-rose-100 text-[#881337] border border-rose-200' }}">EPAD</span>
                                <svg width="13" height="13" class="nav-chevron-icon w-[13px] h-[13px] shrink-0 {{ request()->routeIs('epads.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute left-0 mt-2 w-[320px] sm:w-[350px] mega-dropdown-panel rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-rose-300 shadow-2xl shadow-rose-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none; width: 340px;">
                                
                                <div class="space-y-1">
                                    <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#881337] border-b border-rose-100 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-[#881337]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        <span>EPADS 2.0 E-Procurement</span>
                                    </div>

                                    <!-- 1. + Create EPADS 2.0 -->
                                    <a href="{{ route('epads.create') }}" @click="open = false" 
                                       class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('epads.create') ? 'bg-rose-50 text-[#881337] font-black' : 'text-slate-700 hover:text-[#881337]' }}">
                                        <div class="item-icon-box w-8 h-8 rounded-lg bg-rose-100 text-[#881337] border-b-2 border-rose-300 flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#881337] flex items-center gap-1.5 truncate">
                                                <span>+ Create EPADS 2.0</span>
                                            </div>
                                            <div class="text-[11px] font-medium text-slate-500 truncate">Quick e-procurement creation</div>
                                        </div>
                                    </a>

                                    <!-- 2. EPADS 2.0 Directory -->
                                    <a href="{{ route('epads.index') }}" @click="open = false" 
                                       class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('epads.index') ? 'bg-rose-50 text-[#881337] font-black' : 'text-slate-700 hover:text-[#881337]' }}">
                                        <div class="item-icon-box w-8 h-8 rounded-lg bg-rose-100 text-[#881337] border-b-2 border-rose-300 flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#881337] truncate">EPADS 2.0 Directory</div>
                                            <div class="text-[11px] font-medium text-slate-500 truncate">Digital e-procurement contracts</div>
                                        </div>
                                    </a>

                                    <!-- 3. EPAD Reports & Awards -->
                                    <a href="{{ route('epads.reports') }}" @click="open = false" 
                                       class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('epads.reports') ? 'bg-rose-50 text-[#881337] font-black' : 'text-slate-700 hover:text-[#881337]' }}">
                                        <div class="item-icon-box w-8 h-8 rounded-lg bg-emerald-100 text-[#0d9467] border-b-2 border-emerald-300 flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#881337] truncate">EPAD Reports & Awards</div>
                                            <div class="text-[11px] font-medium text-slate-500 truncate">EPAD records & reports</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Billing & Cheques (2-Column Mega Dropdown) -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="nav-pill-item inline-flex items-center gap-2 px-3.5 xl:px-4 py-2 sm:py-2.5 rounded-xl text-[13.5px] xl:text-[14px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('bills.*') || request()->routeIs('cheques.*') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                                <svg width="17" height="17" class="nav-main-icon w-[17px] h-[17px] shrink-0 {{ request()->routeIs('bills.*') || request()->routeIs('cheques.*') ? 'text-white' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ __('Billing & Cheques') }}</span>
                                <svg width="13" height="13" class="nav-chevron-icon w-[13px] h-[13px] shrink-0 {{ request()->routeIs('bills.*') || request()->routeIs('cheques.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute left-0 mt-2 w-[550px] sm:w-[580px] mega-dropdown-panel rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-slate-300 shadow-2xl shadow-slate-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none; width: 570px; min-width: 530px; max-width: calc(100vw - 2rem);">
                                
                                <div class="grid grid-cols-2 mega-dropdown-grid divide-x divide-slate-100 gap-x-2">
                                    <!-- Column 1: Bills & Invoices -->
                                    <div class="pr-2 space-y-1 mega-dropdown-col">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0056d2] border-b border-blue-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#0056d2]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                            <span>Bills & Invoices</span>
                                        </div>

                                        <a href="{{ route('bills.create') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('bills.create') ? 'bg-emerald-50 text-[#0d9467] font-black' : 'text-slate-700 hover:text-[#0d9467]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-emerald-100 text-[#0d9467] border-b-2 border-emerald-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0d9467] truncate">+ Create New Bill</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Official invoice with letterhead</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('bills.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('bills.index') ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">Billing & Invoice Management</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Department bills, taxes & items</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('bills.daily-report') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('bills.daily-report*') ? 'bg-amber-50 text-[#ea580c] font-black' : 'text-slate-700 hover:text-[#ea580c]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-amber-100 text-[#ea580c] border-b-2 border-amber-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#ea580c] flex items-center gap-1.5 truncate">
                                                    <span>Daily Billing Report</span>
                                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-amber-100 text-amber-800 border border-amber-200">Excel</span>
                                                </div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Daily invoices, dispatch dates & remarks</div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Column 2: Cheques & AGPR -->
                                    <div class="pl-2 space-y-1 mega-dropdown-col">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#cd3805] border-b border-orange-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#cd3805]" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                                            <span>Cheques & AGPR Treasury</span>
                                        </div>

                                        <a href="{{ route('cheques.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('cheques.index') ? 'bg-orange-50 text-[#cd3805] font-black' : 'text-slate-700 hover:text-[#cd3805]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-orange-100 text-[#cd3805] border-b-2 border-orange-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#cd3805] flex items-center gap-1.5 truncate">
                                                    <span>Cheque Details</span>
                                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-orange-100 text-[#cd3805] border border-orange-200">GST/SST</span>
                                                </div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Cheque payments, deductions & profit</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('cheques.create') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('cheques.create') ? 'bg-orange-50 text-[#cd3805] font-black' : 'text-slate-700 hover:text-[#cd3805]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-orange-100 text-[#cd3805] border-b-2 border-orange-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#cd3805] flex items-center gap-1.5 truncate">
                                                    <span>+ New Cheque / AGPR</span>
                                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-orange-200 text-[#cd3805] border border-orange-300">AGPR</span>
                                                </div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Token #, 2-fraction split & deduction</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Budget & Finance (2-Column Mega Dropdown) -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="nav-pill-item inline-flex items-center gap-2 px-3.5 xl:px-4 py-2 sm:py-2.5 rounded-xl text-[13.5px] xl:text-[14px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('finance.*') || request()->routeIs('releases.*') || request()->routeIs('expenses.*') || request()->routeIs('banks.*') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                                <svg width="17" height="17" class="nav-main-icon w-[17px] h-[17px] shrink-0 {{ request()->routeIs('finance.*') || request()->routeIs('releases.*') || request()->routeIs('expenses.*') || request()->routeIs('banks.*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('Budget & Finance') }}</span>
                                <svg width="13" height="13" class="nav-chevron-icon w-[13px] h-[13px] shrink-0 {{ request()->routeIs('finance.*') || request()->routeIs('releases.*') || request()->routeIs('expenses.*') || request()->routeIs('banks.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute left-0 lg:left-1/2 lg:-translate-x-1/4 mt-2 w-[550px] sm:w-[580px] mega-dropdown-panel rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-slate-300 shadow-2xl shadow-slate-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none; width: 570px; min-width: 530px; max-width: calc(100vw - 2rem);">
                                
                                <div class="grid grid-cols-2 mega-dropdown-grid divide-x divide-slate-100 gap-x-2">
                                    <!-- Column 1: Grants & Accounts -->
                                    <div class="pr-2 space-y-1 mega-dropdown-col">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0d9467] border-b border-emerald-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#0d9467]" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                                            <span>Grants & Accounts</span>
                                        </div>

                                        <a href="{{ route('releases.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('releases.*') ? 'bg-emerald-50 text-[#0d9467] font-black' : 'text-slate-700 hover:text-[#0d9467]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-emerald-100 text-[#0d9467] border-b-2 border-emerald-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0d9467] truncate">Budget Releases</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Fiscal grants, allocations & heads</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('banks.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('banks.*') ? 'bg-orange-50 text-[#cd3805] font-black' : 'text-slate-700 hover:text-[#cd3805]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-orange-100 text-[#cd3805] border-b-2 border-orange-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#cd3805] truncate">Bank Accounts & Vaults</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Manage company bank balances</div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Column 2: Analytics & Expenses -->
                                    <div class="pl-2 space-y-1 mega-dropdown-col">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0056d2] border-b border-blue-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#0056d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span>Analytics & Expenses</span>
                                        </div>

                                        <a href="{{ route('finance.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('finance.index') ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">Finance Overview</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Income, expenses & net cash position</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('expenses.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('expenses.*') ? 'bg-rose-50 text-[#881337] font-black' : 'text-slate-700 hover:text-[#881337]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-rose-100 text-[#881337] border-b-2 border-rose-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#881337] truncate">Operational Expenses</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Petty cash, vendor vouchers</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Reports & Analytics (2-Column Mega Dropdown) -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="nav-pill-item inline-flex items-center gap-2 px-3.5 xl:px-4 py-2 sm:py-2.5 rounded-xl text-[13.5px] xl:text-[14px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('reports.*') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                                <svg width="17" height="17" class="nav-main-icon w-[17px] h-[17px] shrink-0 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ __('Reports & Analytics') }}</span>
                                <svg width="13" height="13" class="nav-chevron-icon w-[13px] h-[13px] shrink-0 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute left-0 mt-2 w-[550px] sm:w-[580px] mega-dropdown-panel rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-slate-300 shadow-2xl shadow-slate-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none; width: 570px; min-width: 530px; max-width: calc(100vw - 2rem);">
                                
                                <div class="grid grid-cols-2 mega-dropdown-grid divide-x divide-slate-100 gap-x-2">
                                    <!-- Column 1: Core Procurement Reports -->
                                    <div class="pr-2 space-y-1 mega-dropdown-col">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-amber-700 border-b border-amber-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span>Master & Procurement</span>
                                        </div>

                                        <!-- Master Data Management -->
                                        <a href="{{ route('reports.master-data') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('reports.master-data') ? 'bg-amber-50 text-amber-900 font-black' : 'text-slate-700 hover:text-amber-800' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-amber-100 text-amber-800 border-b-2 border-amber-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-amber-800 flex items-center gap-1.5 truncate">
                                                    <span>Master Data Management</span>
                                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-amber-100 text-amber-900 border border-amber-200">CORE</span>
                                                </div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Years, heads, cost center & item breakdown</div>
                                            </div>
                                        </a>

                                        <!-- Daily Billing Report -->
                                        <a href="{{ route('bills.daily-report') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('bills.daily-report') ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">Daily Billing Report</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Day-to-day processing ledger & totals</div>
                                            </div>
                                        </a>

                                        <!-- Tender & Contract Management -->
                                        <a href="{{ route('tenders.reports') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('tenders.reports') ? 'bg-emerald-50 text-[#0d9467] font-black' : 'text-slate-700 hover:text-[#0d9467]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-emerald-100 text-[#0d9467] border-b-2 border-emerald-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0d9467] flex items-center gap-1.5 truncate">
                                                    <span>Tender & Contract Management</span>
                                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-emerald-100 text-[#0d9467] border border-emerald-200">Contracts</span>
                                                </div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Won contracts, CDR security & fees</div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Column 2: Financial Compliance & Overview Hub -->
                                    <div class="pl-2 space-y-1 mega-dropdown-col">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0056d2] border-b border-blue-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#0056d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span>Finance & Directory</span>
                                        </div>

                                        <!-- Budget Releases -->
                                        <a href="{{ route('releases.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('releases.*') ? 'bg-indigo-50 text-[#312e81] font-black' : 'text-slate-700 hover:text-[#312e81]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-indigo-100 text-[#312e81] border-b-2 border-indigo-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#312e81] truncate">Budget & Releases</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Ceiling, expenditure & released balances</div>
                                            </div>
                                        </a>

                                        <!-- Cheques & AGPR Register -->
                                        <a href="{{ route('cheques.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('cheques.*') ? 'bg-amber-50 text-[#854d0e] font-black' : 'text-slate-700 hover:text-[#854d0e]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-amber-100 text-[#854d0e] border-b-2 border-amber-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#854d0e] flex items-center gap-1.5 truncate">
                                                    <span>Cheques Register</span>
                                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-amber-100 text-[#854d0e] border border-amber-200">GST/SST</span>
                                                </div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Deductions, status & AGPR delivery</div>
                                            </div>
                                        </a>

                                        <!-- Reports Hub -->
                                        <a href="{{ route('reports.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('reports.index') ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">All Reports Hub</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Comprehensive analytics directory</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Setup & Master Data (2-Column Mega Dropdown - Exact to Reference Image) -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="nav-pill-item inline-flex items-center gap-2 px-3.5 xl:px-4 py-2 sm:py-2.5 rounded-xl text-[13.5px] xl:text-[14px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('departments.*') || request()->routeIs('companies.*') || request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('activity-logs.*') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                                <svg width="17" height="17" class="nav-main-icon w-[17px] h-[17px] shrink-0 {{ request()->routeIs('departments.*') || request()->routeIs('companies.*') || request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('activity-logs.*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ __('Master Data Management') }}</span>
                                <svg width="13" height="13" class="nav-chevron-icon w-[13px] h-[13px] shrink-0 {{ request()->routeIs('departments.*') || request()->routeIs('companies.*') || request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('activity-logs.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute right-0 mt-2 w-[580px] sm:w-[620px] mega-dropdown-panel rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-slate-300 shadow-2xl shadow-slate-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none; width: 600px; min-width: 560px; max-width: calc(100vw - 2rem);">
                                
                                <div class="grid grid-cols-2 mega-dropdown-grid divide-x divide-slate-100 gap-x-2">
                                    <!-- Column 1: Agencies & Contractor Firms -->
                                    <div class="pr-2 space-y-1 mega-dropdown-col">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0056d2] border-b border-blue-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#0056d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                            <span>Agencies & Contractor Firms</span>
                                        </div>

                                        <a href="{{ route('departments.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('departments.*') ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">Departments & Cost Centers</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Client agencies, head codes & offices</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('companies.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('companies.index') ? 'bg-emerald-50 text-[#0d9467] font-black' : 'text-slate-700 hover:text-[#0d9467]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-emerald-100 text-[#0d9467] border-b-2 border-emerald-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0d9467] truncate">Bidding Companies</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Our contractor entities & profiles</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('companies.letterheads') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('companies.letterhead*') ? 'bg-orange-50 text-[#cd3805] font-black' : 'text-slate-700 hover:text-[#cd3805]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-orange-100 text-[#cd3805] border-b-2 border-orange-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#cd3805] truncate">Official Letterheads</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Header images, body font & sign</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('companies.invoice-gallery') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('companies.invoice-gallery') ? 'bg-rose-50 text-[#881337] font-black' : 'text-slate-700 hover:text-[#881337]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-rose-100 text-[#881337] border-b-2 border-rose-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#881337] truncate">Invoice Templates</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Print templates & letterhead layouts</div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Column 2: Items Catalog & System Controls -->
                                    <div class="pl-2 space-y-1 mega-dropdown-col">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0d9467] border-b border-emerald-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#0d9467]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                            <span>Catalog & Security</span>
                                        </div>

                                        <a href="{{ route('products.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('products.*') ? 'bg-emerald-50 text-[#0d9467] font-black' : 'text-slate-700 hover:text-[#0d9467]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-emerald-100 text-[#0d9467] border-b-2 border-emerald-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0d9467] truncate">Products & Items Catalog</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Standard inventory & supply item rates</div>
                                            </div>
                                        </a>

                                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('users.view'))
                                            <a href="{{ route('users.index') }}" @click="open = false" 
                                               class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                                <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] flex items-center gap-1.5 truncate">
                                                        <span>Users & Roles</span>
                                                        <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-blue-100 text-[#0056d2] border border-blue-200">RBAC</span>
                                                    </div>
                                                    <div class="text-[11px] font-medium text-slate-500 truncate">Granular role permissions & accounts</div>
                                                </div>
                                            </a>
                                        @endif

                                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('activity_logs.view'))
                                            <a href="{{ route('activity-logs.index') }}" @click="open = false" 
                                               class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('activity-logs.*') ? 'bg-rose-50 text-[#881337] font-black' : 'text-slate-700 hover:text-[#881337]' }}">
                                                <div class="item-icon-box w-8 h-8 rounded-lg bg-rose-100 text-[#881337] border-b-2 border-rose-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#881337] flex items-center gap-1.5 truncate">
                                                        <span>Activity Log</span>
                                                        <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-rose-100 text-[#881337] border border-rose-200">Audit</span>
                                                    </div>
                                                    <div class="text-[11px] font-medium text-slate-500 truncate">Live user audit trail & activity history</div>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. To-Do List & Tasks -->
                        <a href="{{ route('todos.index') }}" 
                           class="nav-pill-item inline-flex items-center gap-2 px-3.5 xl:px-4 py-2 sm:py-2.5 rounded-xl text-[13.5px] xl:text-[14px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('todos.*') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                            <svg width="17" height="17" class="nav-main-icon w-[17px] h-[17px] shrink-0 {{ request()->routeIs('todos.*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span>{{ __('To-Do List') }}</span>
                            @php
                                $navPendingCount = \App\Models\Todo::pending()->count();
                            @endphp
                            @if($navPendingCount > 0)
                                <span class="inline-flex items-center justify-center px-1.5 py-0.2 text-[10px] font-black rounded-full bg-amber-500 text-white leading-none">
                                    {{ $navPendingCount }}
                                </span>
                            @endif
                        </a>
                    @endauth
                </div>

                <!-- Right Quick CTA Action Buttons (Solid 3D Tactile Buttons) -->
                <div class="hidden lg:flex items-center gap-2 shrink-0">
                    <a href="{{ route('tenders.create') }}" class="nav-btn-tender px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-black tracking-tight inline-flex items-center gap-1.5 whitespace-nowrap">
                        <svg width="16" height="16" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>New Tender</span>
                    </a>

                    <a href="{{ route('bills.create') }}" class="nav-btn-bill px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-black tracking-tight inline-flex items-center gap-1.5 whitespace-nowrap">
                        <svg width="16" height="16" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>New Bill</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="lg:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-4 max-h-[85vh] overflow-y-auto" 
             style="display: none;">
            @auth
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-[#0056d2] border border-blue-200' : 'text-slate-700 hover:bg-slate-100' }}">
                    <svg class="w-4 h-4 text-[#0056d2]" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    <span>{{ __('Dashboard') }}</span>
                </a>

                <!-- To-Do List -->
                <a href="{{ route('todos.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('todos.*') ? 'bg-blue-50 text-[#0056d2] border border-blue-200' : 'text-slate-700 hover:bg-slate-100' }}">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span>{{ __('To-Do List & Reminders') }}</span>
                    </div>
                    @if(isset($navPendingCount) && $navPendingCount > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-500 text-white">
                            {{ $navPendingCount }}
                        </span>
                    @endif
                </a>

                <!-- Tender Management System Section -->
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Tender Management System</div>
                    <a href="{{ route('tenders.create') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>+ Create New Tender</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-blue-100 text-blue-800">Wizard</span>
                    </a>
                    <a href="{{ route('tenders.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Tender Details</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-slate-200 text-slate-700">Directory</span>
                    </a>
                    <a href="{{ route('tenders.reports') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Tender & Contract Management</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800">Contracts</span>
                    </a>
                </div>

                <!-- EPADS 2.0 Section (Independent) -->
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-rose-800">EPADS 2.0</div>
                    <a href="{{ route('epads.create') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-rose-50">
                        <span>+ Create EPADS 2.0</span>
                    </a>
                    <a href="{{ route('epads.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-rose-50">
                        <span>EPADS 2.0 Directory</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-rose-100 text-rose-900 border border-rose-200">EPAD</span>
                    </a>
                    <a href="{{ route('epads.reports') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-rose-50">
                        <span>EPAD Reports & Awards</span>
                    </a>
                </div>

                <!-- Billing & Cheques Section -->
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Billing & Cheques</div>
                    <a href="{{ route('bills.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Billing & Invoice Management</span>
                    </a>
                    <a href="{{ route('bills.create') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>+ Create New Bill</span>
                    </a>
                    <a href="{{ route('cheques.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Cheque Details Register</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-amber-100 text-amber-800">GST / SST</span>
                    </a>
                    <a href="{{ route('cheques.create') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>+ New Cheque / AGPR Form</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-orange-200 text-orange-900">AGPR</span>
                    </a>
                </div>

                <!-- Budget & Finance Section -->
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Budget & Finance</div>
                    <a href="{{ route('releases.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Budget Releases</span>
                    </a>
                    <a href="{{ route('finance.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Finance & Treasury Overview</span>
                    </a>
                    <a href="{{ route('banks.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Bank Accounts & Vaults</span>
                    </a>
                    <a href="{{ route('expenses.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Operational Expenses</span>
                    </a>
                </div>

                <!-- Reports & Analytics Section -->
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-amber-700 flex items-center justify-between">
                        <span>Reports & Analytics</span>
                        <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-amber-100 text-amber-900 border border-amber-200">CORE</span>
                    </div>
                    <a href="{{ route('reports.master-data') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Master Data Management</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-amber-100 text-amber-900 font-bold">MDM</span>
                    </a>
                    <a href="{{ route('bills.daily-report') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Daily Billing Report</span>
                    </a>
                    <a href="{{ route('tenders.reports') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Tender & Contract Management</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800">Contracts</span>
                    </a>
                    <a href="{{ route('releases.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Budget & Releases Report</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>All Reports & Analytics Hub</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-blue-100 text-blue-800">Hub</span>
                    </a>
                </div>

                <!-- Master Data Management Section -->
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Master Data Management</div>
                    <a href="{{ route('departments.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Departments & Cost Centers</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Bidding Companies</span>
                    </a>
                    <a href="{{ route('companies.letterheads') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Official Letterheads</span>
                    </a>
                    <a href="{{ route('companies.invoice-gallery') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Invoice Templates Gallery</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Products Catalog</span>
                    </a>
                    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('users.view'))
                        <a href="{{ route('users.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                            <span>Users & Roles (RBAC)</span>
                        </a>
                    @endif
                    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('activity_logs.view'))
                        <a href="{{ route('activity-logs.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                            <span>Activity Log (Audit Trail)</span>
                        </a>
                    @endif
                </div>

                <!-- Actions -->
                <div class="pt-3 border-t border-slate-200 space-y-2">
                    <a href="{{ route('notifications.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-[#0056d2] bg-blue-50 hover:bg-blue-100">
                        <span>🔔 {{ __('Notifications') }}</span>
                        @if($userUnreadCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white">{{ $userUnreadCount }}</span>
                        @endif
                    </a>
                    <div class="flex items-center justify-between pt-1">
                        <a href="{{ route('bills.create') }}" class="text-xs font-bold text-[#0056d2] hover:text-[#0b327b]">
                            + {{ __('Generate New Bill') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>
</div>
