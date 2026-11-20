<!-- Two-Tier ERP Navigation Header -->
<div x-data="{ open: false }" class="sticky top-0 z-50 shadow-md">
    <!-- Top Bar: Executive Midnight Slate (#090d16) -->
    <div class="bg-[#090d16] border-b border-slate-800/80 text-white select-none shadow-sm" style="background: linear-gradient(90deg, #090d16 0%, #0f172a 45%, #0d1527 100%);">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 xl:px-10 h-14 flex items-center justify-between gap-4">
            <!-- Left: Logo & Company Pill -->
            <div class="flex items-center gap-3">
                <!-- Logo Monogram & Name: ProWave Technologies -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-[#0056d2] border border-sky-400/40 flex items-center justify-center font-black text-white text-xs shadow-md shadow-blue-500/20 group-hover:border-sky-300 transition">
                        PW
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="font-black text-sm sm:text-[15px] tracking-tight text-white whitespace-nowrap flex items-center gap-1.5">
                            PROWAVE <span class="text-sky-400 font-extrabold">TECHNOLOGIES</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">
                            Tender & Procurement ERP
                        </span>
                    </div>
                </a>

                @auth
                    <!-- Dynamic Client Company Pill (Al-Madina Enterprises) -->
                    <div class="hidden md:inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800/90 border border-slate-700/80 text-slate-200 text-xs sm:text-[13px] font-semibold whitespace-nowrap ms-1 shadow-2xs backdrop-blur-sm">
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
                    <span class="w-6 h-6 rounded-full bg-gradient-to-tr from-sky-500 to-blue-600 flex items-center justify-center text-white shrink-0 shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </span>
                    <span class="font-mono tracking-tight text-xs sm:text-[13px] font-bold text-slate-200">+92 325 9385 085</span>
                </a>

                <!-- Vertical Divider -->
                <span class="text-slate-700 hidden sm:inline">|</span>

                <!-- Support Pill with Headset Icon -->
                <a href="https://wa.me/923259385085" target="_blank" rel="noopener noreferrer" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/90 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs sm:text-[12.5px] font-bold transition shadow-xs whitespace-nowrap" title="Chat on WhatsApp">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Support</span>
                </a>

                <!-- Online Status Pill -->
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-950/70 border border-emerald-500/40 text-emerald-300 text-xs sm:text-[12.5px] font-semibold whitespace-nowrap">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Online</span>
                </span>

                @auth
                    <!-- Quick Bill CTA Button (Vibrant 3D Gradient) -->
                    <a href="{{ route('bills.create') }}" class="hidden lg:inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-gradient-to-r from-blue-600 to-[#0056d2] hover:from-blue-500 hover:to-[#0056d2] text-white text-xs sm:text-[12.5px] font-bold transition shadow-sm border border-blue-400/30 whitespace-nowrap">
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
                                class="relative p-2 text-slate-300 hover:text-white transition rounded-full hover:bg-slate-800/80 focus:outline-none"
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
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-slate-800 to-slate-900 border border-slate-600 flex items-center justify-center text-slate-200">
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

    <!-- Bottom Tier: Luminous Clean Glass Navigation Bar with Dropdowns -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-slate-200/90 text-slate-700 select-none shadow-xs">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="flex items-center justify-between h-13 py-1 gap-3">
                <!-- Navigation Items with Elegant Dropdowns -->
                <div class="hidden lg:flex items-center gap-1 xl:gap-1.5 flex-1 min-w-0">
                    @auth
                        <!-- 1. Dashboard -->
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs xl:text-[13px] whitespace-nowrap transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-[#0056d2] text-white font-extrabold shadow-sm shadow-blue-500/25 border-b-2 border-[#0b327b]' : 'text-slate-700 hover:text-[#0056d2] hover:bg-blue-50/80 font-semibold' }}">
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            <span>{{ __('Dashboard') }}</span>
                        </a>

                        <!-- 2. Tenders & EPAD 2.0 Dropdown -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs xl:text-[13px] whitespace-nowrap transition-all duration-150 {{ request()->routeIs('tenders.*') ? 'bg-gradient-to-r from-blue-600 to-[#0056d2] text-white font-extrabold shadow-sm shadow-blue-500/25 border-b-2 border-[#0b327b]' : 'text-slate-700 hover:text-[#0056d2] hover:bg-blue-50/80 font-semibold' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('tenders.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ __('Tenders') }}</span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase {{ request()->routeIs('tenders.*') ? 'bg-white/20 text-white border border-white/30' : 'bg-rose-100 text-rose-900 border border-rose-200' }}">EPAD</span>
                                <svg class="w-3.5 h-3.5 {{ request()->routeIs('tenders.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                 class="absolute left-0 mt-2 w-72 rounded-2xl bg-white border border-slate-200 shadow-xl shadow-slate-900/10 p-2 z-50 divide-y divide-slate-100"
                                 style="display: none;">
                                
                                <div class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    Procurement & Bidding
                                </div>

                                <div class="py-1 space-y-0.5">
                                    <a href="{{ route('tenders.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('tenders.index') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Tenders Register</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Single Stage 1E & 2E, schedule tracking</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('tenders.create') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('tenders.create') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight flex items-center gap-1.5">
                                                <span>+ Publish Tender</span>
                                                <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-rose-100 text-rose-900 border border-rose-200">EPAD 2.0</span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 font-normal">Publish new tender or EPAD e-tender</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('tenders.reports') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('tenders.reports') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight flex items-center gap-1.5">
                                                <span>Tenders & Work Orders Report</span>
                                                <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800">Audit</span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 font-normal">Won contracts, CDR security & fees</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Billing & Cheques Dropdown -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs xl:text-[13px] whitespace-nowrap transition-all duration-150 {{ request()->routeIs('bills.*') || request()->routeIs('cheques.*') ? 'bg-gradient-to-r from-blue-600 to-[#0056d2] text-white font-extrabold shadow-sm shadow-blue-500/25 border-b-2 border-[#0b327b]' : 'text-slate-700 hover:text-[#0056d2] hover:bg-blue-50/80 font-semibold' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('bills.*') || request()->routeIs('cheques.*') ? 'text-white' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ __('Billing & Cheques') }}</span>
                                <svg class="w-3.5 h-3.5 {{ request()->routeIs('bills.*') || request()->routeIs('cheques.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                 class="absolute left-0 mt-2 w-72 rounded-2xl bg-white border border-slate-200 shadow-xl shadow-slate-900/10 p-2 z-50 divide-y divide-slate-100"
                                 style="display: none;">
                                
                                <div class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    Invoices, Cheques & AGPR
                                </div>

                                <div class="py-1 space-y-0.5">
                                    <a href="{{ route('bills.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('bills.index') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Bills & Invoices Ledger</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Department bills, tax breakdowns & items</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('bills.create') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('bills.create') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">+ Create New Bill</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Generate official invoice with letterhead</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('cheques.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('cheques.index') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight flex items-center gap-1.5">
                                                <span>Cheque Details</span>
                                                <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-amber-100 text-amber-800">GST / SST</span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 font-normal">Cheque payments, deductions & profit</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('cheques.create') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('cheques.create') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-orange-100 text-orange-800 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight flex items-center gap-1.5">
                                                <span>+ New Cheque / AGPR</span>
                                                <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-orange-200 text-orange-900">AGPR</span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 font-normal">Token #, 2-fraction split & AGPR deduction</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Budget & Finance Dropdown -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs xl:text-[13px] whitespace-nowrap transition-all duration-150 {{ request()->routeIs('finance.*') || request()->routeIs('releases.*') || request()->routeIs('expenses.*') || request()->routeIs('banks.*') ? 'bg-gradient-to-r from-blue-600 to-[#0056d2] text-white font-extrabold shadow-sm shadow-blue-500/25 border-b-2 border-[#0b327b]' : 'text-slate-700 hover:text-[#0056d2] hover:bg-blue-50/80 font-semibold' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('finance.*') || request()->routeIs('releases.*') || request()->routeIs('expenses.*') || request()->routeIs('banks.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('Budget & Finance') }}</span>
                                <svg class="w-3.5 h-3.5 {{ request()->routeIs('finance.*') || request()->routeIs('releases.*') || request()->routeIs('expenses.*') || request()->routeIs('banks.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                 class="absolute left-0 mt-2 w-72 rounded-2xl bg-white border border-slate-200 shadow-xl shadow-slate-900/10 p-2 z-50 divide-y divide-slate-100"
                                 style="display: none;">
                                
                                <div class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    Financial Management
                                </div>

                                <div class="py-1 space-y-0.5">
                                    <a href="{{ route('releases.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('releases.*') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Budget Releases</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Fiscal grants, allocations & head breakdown</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('finance.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('finance.index') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Finance Overview</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Income, expenses & net cash position</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('banks.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('banks.*') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Bank Accounts & Vaults</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Manage company bank balances & cash</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('expenses.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('expenses.*') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Operational Expenses</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Petty cash, vendor payments & vouchers</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Setup & Master Data Dropdown -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs xl:text-[13px] whitespace-nowrap transition-all duration-150 {{ request()->routeIs('departments.*') || request()->routeIs('companies.*') || request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('activity-logs.*') ? 'bg-gradient-to-r from-blue-600 to-[#0056d2] text-white font-extrabold shadow-sm shadow-blue-500/25 border-b-2 border-[#0b327b]' : 'text-slate-700 hover:text-[#0056d2] hover:bg-blue-50/80 font-semibold' }}">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('departments.*') || request()->routeIs('companies.*') || request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('activity-logs.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ __('Master Setup') }}</span>
                                <svg class="w-3.5 h-3.5 {{ request()->routeIs('departments.*') || request()->routeIs('companies.*') || request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('activity-logs.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                 class="absolute left-0 mt-2 w-80 rounded-2xl bg-white border border-slate-200 shadow-xl shadow-slate-900/10 p-2 z-50 divide-y divide-slate-100"
                                 style="display: none;">
                                
                                <div class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    Agencies, Companies & System Settings
                                </div>

                                <div class="py-1 space-y-0.5">
                                    <a href="{{ route('departments.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('departments.*') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Departments & Cost Centers</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Client agencies, head codes & offices</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('companies.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('companies.index') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-blue-100 text-[#0056d2] flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Bidding Companies / Firms</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Our contractor entities & profiles</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('companies.letterheads') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('companies.letterhead*') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Official Letterheads</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Header images, body font & signatures</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('companies.invoice-gallery') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('companies.invoice-gallery') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Invoice Templates Gallery</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Preview print formats & styling</div>
                                        </div>
                                    </a>

                                    <a href="{{ route('products.index') }}" @click="open = false" 
                                       class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('products.*') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                        <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">Products & Items Catalog</div>
                                            <div class="text-[11px] text-slate-500 font-normal">Standard inventory & supply item rates</div>
                                        </div>
                                    </a>

                                    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('users.view'))
                                        <a href="{{ route('users.index') }}" @click="open = false" 
                                           class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                            <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            </div>
                                            <div>
                                                <div class="text-xs font-bold leading-tight flex items-center gap-1.5">
                                                    <span>Users & Roles</span>
                                                    <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-rose-100 text-rose-800">RBAC</span>
                                                </div>
                                                <div class="text-[11px] text-slate-500 font-normal">Granular role permissions & accounts</div>
                                            </div>
                                        </a>
                                    @endif

                                    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('activity_logs.view'))
                                        <a href="{{ route('activity-logs.index') }}" @click="open = false" 
                                           class="flex items-start gap-2.5 p-2 rounded-xl transition {{ request()->routeIs('activity-logs.*') ? 'bg-blue-50 text-[#0056d2] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#0056d2]' }}">
                                            <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <div>
                                                <div class="text-xs font-bold leading-tight flex items-center gap-1.5">
                                                    <span>Activity Log</span>
                                                    <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-rose-100 text-rose-800">Audit</span>
                                                </div>
                                                <div class="text-[11px] text-slate-500 font-normal">Live user audit trail & activity history</div>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- 6. To-Do List & Tasks -->
                        <a href="{{ route('todos.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs xl:text-[13px] whitespace-nowrap transition-all duration-150 {{ request()->routeIs('todos.*') ? 'bg-gradient-to-r from-blue-600 to-[#0056d2] text-white font-extrabold shadow-sm shadow-blue-500/25 border-b-2 border-[#0b327b]' : 'text-slate-700 hover:text-[#0056d2] hover:bg-blue-50/80 font-semibold' }}">
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('todos.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span>{{ __('To-Do List') }}</span>
                            @php
                                $navPendingCount = \App\Models\Todo::pending()->count();
                            @endphp
                            @if($navPendingCount > 0)
                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-black rounded-full bg-amber-500 text-white leading-none">
                                    {{ $navPendingCount }}
                                </span>
                            @endif
                        </a>
                    @endauth
                </div>

                <!-- Right Quick CTA Action Buttons -->
                <div class="hidden lg:flex items-center gap-2 shrink-0">
                    <a href="{{ route('tenders.create') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0056d2] hover:text-[#0b327b] bg-blue-50 hover:bg-blue-100 border border-blue-200/90 px-3 py-1.5 rounded-xl transition shadow-2xs whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>+ New Tender</span>
                    </a>

                    <a href="{{ route('bills.create') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/90 px-3 py-1.5 rounded-xl transition shadow-2xs whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>+ New Bill</span>
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

                <!-- Tenders & EPAD Section -->
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Tenders & Bidding</div>
                    <a href="{{ route('tenders.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Tenders Register (Directory)</span>
                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-slate-200 text-slate-700">1E / 2E</span>
                    </a>
                    <a href="{{ route('tenders.create') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>+ Publish Tender / EPAD 2.0</span>
                        <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-rose-100 text-rose-900 border border-rose-200">EPAD</span>
                    </a>
                    <a href="{{ route('tenders.reports') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Tenders & Work Orders Report</span>
                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800">Audit</span>
                    </a>
                </div>

                <!-- Billing & Cheques Section -->
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Billing & Cheques</div>
                    <a href="{{ route('bills.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Bills & Invoices Ledger</span>
                    </a>
                    <a href="{{ route('bills.create') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>+ Create New Bill</span>
                    </a>
                    <a href="{{ route('cheques.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>Cheque Details Register</span>
                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-amber-100 text-amber-800">GST / SST</span>
                    </a>
                    <a href="{{ route('cheques.create') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100">
                        <span>+ New Cheque / AGPR Form</span>
                        <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-orange-200 text-orange-900">AGPR</span>
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

                <!-- Master Setup Section -->
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Master Setup</div>
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
