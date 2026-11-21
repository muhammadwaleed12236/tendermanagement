
import re

nav_path = r"e:\Tender_mng\tender_system\resources\views\layouts\navigation.blade.php"
with open(nav_path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Update style block to include dropdown animation & column items
style_target = "    .hover-bell:hover svg {"
style_replacement = """    @keyframes dropdown-pop {
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

    .hover-bell:hover svg {"""

if style_target in content:
    content = content.replace(style_target, style_replacement, 1)
    print("Style block updated successfully")
else:
    print("Warning: style target not found")

# 2. Update sticky header tag for scroll animation
header_target = '<div x-data="{ open: false }" class="sticky top-0 z-50 shadow-md">'
header_replacement = '<div x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 15)" class="sticky top-0 z-50 transition-all duration-300" :class="{ \\\'shadow-xl shadow-slate-900/10\\\': scrolled }">'

topbar_target = '<div class="bg-[#090d16] border-b border-slate-800/80 text-white select-none shadow-sm" style="background: linear-gradient(90deg, #090d16 0%, #0f172a 45%, #0d1527 100%);">'
topbar_replacement = '<div class="bg-[#090d16] border-b border-slate-800/80 text-white select-none shadow-sm transition-all duration-300" :class="scrolled ? \\\'h-13 sm:h-14\\\' : \\\'h-16 sm:h-17\\\'" style="background: linear-gradient(90deg, #090d16 0%, #0f172a 45%, #0d1527 100%);">'

topbar_inner_target = '<div class="max-w-full px-4 sm:px-6 lg:px-8 xl:px-10 h-16 sm:h-17 flex items-center justify-between gap-4">'
topbar_inner_replacement = '<div class="max-w-full px-4 sm:px-6 lg:px-8 xl:px-10 h-full flex items-center justify-between gap-4">'

if header_target in content:
    content = content.replace(header_target, header_replacement, 1)
    print("Header target replaced")

if topbar_target in content:
    content = content.replace(topbar_target, topbar_replacement, 1)
    print("Topbar target replaced")

if topbar_inner_target in content:
    content = content.replace(topbar_inner_target, topbar_inner_replacement, 1)
    print("Topbar inner target replaced")

# 3. Locate Tier 2
tier2_start = content.find("<!-- Bottom Tier: Luminous Clean Glass Navigation Bar with Dropdowns -->")
tier2_end = content.find("<!-- Mobile Drawer Navigation -->")

if tier2_start == -1 or tier2_end == -1:
    print("Could not find Tier 2 bounds")
    exit(1)

new_tier2 = """<!-- Bottom Tier: Luminous Clean Glass Navigation Bar with Dropdowns -->
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
                            <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-[#0056d2]' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            <span>{{ __('Dashboard') }}</span>
                        </a>

                        <!-- 2. Tenders & EPAD 2.0 (2-Column Mega Dropdown) -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="nav-pill-item inline-flex items-center gap-2 px-3.5 xl:px-4 py-2 sm:py-2.5 rounded-xl text-[13.5px] xl:text-[14px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('tenders.*') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                                <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('tenders.*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ __('Tenders') }}</span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-wider {{ request()->routeIs('tenders.*') ? 'bg-white/20 text-white border border-white/30' : 'bg-rose-100 text-[#881337] border border-rose-200' }}">EPAD</span>
                                <svg class="w-3.5 h-3.5 {{ request()->routeIs('tenders.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute left-0 mt-2 w-[540px] sm:w-[570px] rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-slate-300 shadow-2xl shadow-slate-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none;">
                                
                                <div class="grid grid-cols-2 divide-x divide-slate-100 gap-x-2">
                                    <!-- Column 1: Procurement & Operations -->
                                    <div class="pr-2 space-y-1">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0056d2] border-b border-blue-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#0056d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            <span>Procurement & Bidding</span>
                                        </div>

                                        <a href="{{ route('tenders.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('tenders.index') ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">Tenders Register</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Single Stage 1E & 2E, schedules</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('tenders.create') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('tenders.create') ? 'bg-rose-50 text-[#881337] font-black' : 'text-slate-700 hover:text-[#881337]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-rose-100 text-[#881337] border-b-2 border-rose-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#881337] flex items-center gap-1.5 truncate">
                                                    <span>+ Publish Tender</span>
                                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-rose-100 text-[#881337] border border-rose-200">EPAD</span>
                                                </div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">New tender or EPAD e-tender</div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Column 2: Reports & Evaluations -->
                                    <div class="pl-2 space-y-1">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0d9467] border-b border-emerald-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#0d9467]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            <span>Reports & Audit</span>
                                        </div>

                                        <a href="{{ route('tenders.reports') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('tenders.reports') ? 'bg-emerald-50 text-[#0d9467] font-black' : 'text-slate-700 hover:text-[#0d9467]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-emerald-100 text-[#0d9467] border-b-2 border-emerald-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0d9467] flex items-center gap-1.5 truncate">
                                                    <span>Tenders & Work Orders</span>
                                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-emerald-100 text-[#0d9467] border border-emerald-200">Audit</span>
                                                </div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Won contracts, CDR security & fees</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('tenders.index') }}?status=published" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group text-slate-700 hover:text-[#0056d2]">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">Active Bidding Pipeline</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Upcoming opening dates & status</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Billing & Cheques (2-Column Mega Dropdown) -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="nav-pill-item inline-flex items-center gap-2 px-3.5 xl:px-4 py-2 sm:py-2.5 rounded-xl text-[13.5px] xl:text-[14px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('bills.*') || request()->routeIs('cheques.*') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                                <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('bills.*') || request()->routeIs('cheques.*') ? 'text-white' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ __('Billing & Cheques') }}</span>
                                <svg class="w-3.5 h-3.5 {{ request()->routeIs('bills.*') || request()->routeIs('cheques.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute left-0 mt-2 w-[550px] sm:w-[580px] rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-slate-300 shadow-2xl shadow-slate-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none;">
                                
                                <div class="grid grid-cols-2 divide-x divide-slate-100 gap-x-2">
                                    <!-- Column 1: Bills & Invoices -->
                                    <div class="pr-2 space-y-1">
                                        <div class="px-2.5 pb-2 mb-1.5 text-xs font-black uppercase tracking-wider text-[#0056d2] border-b border-blue-50 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#0056d2]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                            <span>Bills & Invoices</span>
                                        </div>

                                        <a href="{{ route('bills.index') }}" @click="open = false" 
                                           class="dropdown-col-item flex items-center gap-3 px-2.5 py-2 rounded-xl group {{ request()->routeIs('bills.index') ? 'bg-blue-50 text-[#0056d2] font-black' : 'text-slate-700 hover:text-[#0056d2]' }}">
                                            <div class="item-icon-box w-8 h-8 rounded-lg bg-blue-100 text-[#0056d2] border-b-2 border-blue-300 flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[13.5px] font-bold text-slate-800 group-hover:text-[#0056d2] truncate">Bills & Invoices Ledger</div>
                                                <div class="text-[11px] font-medium text-slate-500 truncate">Department bills, taxes & items</div>
                                            </div>
                                        </a>

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
                                    </div>

                                    <!-- Column 2: Cheques & AGPR -->
                                    <div class="pl-2 space-y-1">
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
                                <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('finance.*') || request()->routeIs('releases.*') || request()->routeIs('expenses.*') || request()->routeIs('banks.*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('Budget & Finance') }}</span>
                                <svg class="w-3.5 h-3.5 {{ request()->routeIs('finance.*') || request()->routeIs('releases.*') || request()->routeIs('expenses.*') || request()->routeIs('banks.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute left-0 mt-2 w-[550px] sm:w-[580px] rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-slate-300 shadow-2xl shadow-slate-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none;">
                                
                                <div class="grid grid-cols-2 divide-x divide-slate-100 gap-x-2">
                                    <!-- Column 1: Grants & Accounts -->
                                    <div class="pr-2 space-y-1">
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
                                    <div class="pl-2 space-y-1">
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

                        <!-- 5. Setup & Master Data (2-Column Mega Dropdown - Exact to Reference Image) -->
                        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button @click="open = !open" type="button"
                                    class="nav-pill-item inline-flex items-center gap-2 px-3.5 xl:px-4 py-2 sm:py-2.5 rounded-xl text-[13.5px] xl:text-[14px] font-black tracking-tight whitespace-nowrap {{ request()->routeIs('departments.*') || request()->routeIs('companies.*') || request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('activity-logs.*') ? 'nav-pill-active text-white' : 'text-slate-800 hover:text-[#0056d2]' }}">
                                <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('departments.*') || request()->routeIs('companies.*') || request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('activity-logs.*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ __('Master Setup') }}</span>
                                <svg class="w-3.5 h-3.5 {{ request()->routeIs('departments.*') || request()->routeIs('companies.*') || request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('activity-logs.*') ? 'text-white' : 'text-slate-400' }} transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-120"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute left-0 mt-2 w-[580px] sm:w-[620px] rounded-2xl bg-white border-2 border-slate-200 border-b-4 border-b-slate-300 shadow-2xl shadow-slate-900/15 p-3.5 z-50 nav-dropdown-enter"
                                 style="display: none;">
                                
                                <div class="grid grid-cols-2 divide-x divide-slate-100 gap-x-2">
                                    <!-- Column 1: Agencies & Contractor Firms -->
                                    <div class="pr-2 space-y-1">
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
                                    <div class="pl-2 space-y-1">
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
                            <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('todos.*') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>New Tender</span>
                    </a>

                    <a href="{{ route('bills.create') }}" class="nav-btn-bill px-3.5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-black tracking-tight inline-flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>New Bill</span>
                    </a>
                </div>
            </div>
        </div>"""

content = content[:tier2_start] + new_tier2 + "\n\n        " + content[tier2_end:]

with open(nav_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Applied 2-column mega dropdowns, reduced font size, and scroll animations successfully!")
