<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('tenders.index') }}" class="text-xs font-bold text-orange-700 hover:text-orange-800 transition flex items-center gap-1 print:hidden">
                        &larr; Back to Tenders Directory
                    </a>
                    <span class="text-stone-300 print:hidden">•</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-900 border border-emerald-300">
                        Official Procurement & Financial Report
                    </span>
                </div>
                <h2 class="font-black text-xl sm:text-2xl text-stone-900 leading-tight flex items-center flex-wrap gap-2">
                    <span>{{ __('Tender & Contract Management') }}</span>
                    <span class="text-xs font-mono font-bold bg-stone-200 text-stone-700 px-2.5 py-0.5 rounded-full border border-stone-300 shrink-0">
                        {{ $totalCount }} Records
                    </span>
                </h2>
                <p class="text-xs text-stone-600 mt-0.5">
                    Executive overview of won tenders, contract work orders, tender fees paid with bank sources, and active bid securities (CDR / Pay Order).
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center flex-wrap gap-2 print:hidden">
                <a href="{{ route('tenders.reports.export', request()->query()) }}" class="px-3.5 sm:px-4 py-2 text-xs font-bold bg-stone-900 hover:bg-stone-800 text-white rounded-xl shadow-xs transition flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 text-stone-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Print Official Report</span>
                </a>

                <a href="{{ route('tenders.create') }}" class="px-3.5 sm:px-4 py-2 text-xs font-bold bg-orange-600 hover:bg-orange-500 text-white rounded-xl shadow-xs transition flex items-center gap-1.5 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>New Tender</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Custom CSS to Guarantee Pixel-Perfect 3D Card Visuals matching client reference -->
    <style>
        .stat-card-3d-wrap {
            position: relative;
            transition: all 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
        }
        .stat-card-3d-wrap:hover {
            transform: translateY(-4px);
        }
        .stat-card-3d {
            position: relative;
            background: #ffffff;
            border-radius: 1.45rem;
            transition: all 0.25s ease;
        }
        .stat-card-vertical {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: space-between !important;
            text-align: center !important;
            min-height: 172px !important;
            padding: 1.25rem 0.75rem !important;
        }
        .stat-card-vertical .stat-candy-icon {
            margin: 0 auto 0.45rem auto !important;
            width: 2.85rem !important;
            height: 2.85rem !important;
            border-radius: 1rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }
        .stat-card-vertical .stat-card-label {
            text-align: center !important;
            width: 100% !important;
            margin-bottom: 0.25rem !important;
            font-size: 0.82rem !important; /* +2px from 0.72rem */
            font-weight: 900 !important;
            letter-spacing: 0.05em !important;
            text-transform: uppercase !important;
            display: block !important;
        }
        .stat-card-vertical .stat-card-value {
            display: block !important;
            text-align: center !important;
            width: 100% !important;
            font-weight: 900 !important;
            color: #1c1917 !important;
            line-height: 1.2 !important;
        }

        /* 3D Borders Preserving Existing System Geometry */
        .stat-card-blue { border: 2.5px solid #0056d2 !important; border-bottom: 6px solid #07255e !important; box-shadow: 0 14px 28px -6px rgba(0, 86, 210, 0.22) !important; }
        .stat-card-rose { border: 2.5px solid #e11d48 !important; border-bottom: 6px solid #881337 !important; box-shadow: 0 14px 28px -6px rgba(225, 29, 72, 0.22) !important; }
        .stat-card-navy { border: 2.5px solid #2B2D4B !important; border-bottom: 6px solid #141525 !important; box-shadow: 0 14px 28px -6px rgba(43, 45, 75, 0.22) !important; }
        .stat-card-green { border: 2.5px solid #0d9467 !important; border-bottom: 6px solid #064e3b !important; box-shadow: 0 14px 28px -6px rgba(13, 148, 103, 0.22) !important; }
        .stat-card-maroon { border: 2.5px solid #dc2626 !important; border-bottom: 6px solid #7f1d1d !important; box-shadow: 0 14px 28px -6px rgba(220, 38, 38, 0.22) !important; }
        .stat-card-cyan { border: 2.5px solid #0284c7 !important; border-bottom: 6px solid #0369a1 !important; box-shadow: 0 14px 28px -6px rgba(2, 132, 199, 0.22) !important; }
        .stat-card-orange { border: 2.5px solid #ea580c !important; border-bottom: 6px solid #9a3412 !important; box-shadow: 0 14px 28px -6px rgba(234, 88, 12, 0.22) !important; }
        .stat-card-lime { border: 2.5px solid #65a30d !important; border-bottom: 6px solid #3f6212 !important; box-shadow: 0 14px 28px -6px rgba(101, 163, 13, 0.22) !important; }

        /* Candy Icon Backgrounds */
        .candy-icon-blue { background: #0056d2 !important; border-top: 1.5px solid rgba(255,255,255,0.4) !important; border-bottom: 3.5px solid #07255e !important; box-shadow: 0 8px 16px rgba(0, 86, 210, 0.35) !important; }
        .candy-icon-rose { background: #e11d48 !important; border-top: 1.5px solid rgba(255,255,255,0.4) !important; border-bottom: 3.5px solid #881337 !important; box-shadow: 0 8px 16px rgba(225, 29, 72, 0.35) !important; }
        .candy-icon-navy { background: #2B2D4B !important; border-top: 1.5px solid rgba(255,255,255,0.3) !important; border-bottom: 3.5px solid #141525 !important; box-shadow: 0 8px 16px rgba(43, 45, 75, 0.35) !important; }
        .candy-icon-green { background: #0d9467 !important; border-top: 1.5px solid rgba(255,255,255,0.4) !important; border-bottom: 3.5px solid #064e3b !important; box-shadow: 0 8px 16px rgba(13, 148, 103, 0.35) !important; }
        .candy-icon-maroon { background: #dc2626 !important; border-top: 1.5px solid rgba(255,255,255,0.4) !important; border-bottom: 3.5px solid #7f1d1d !important; box-shadow: 0 8px 16px rgba(220, 38, 38, 0.35) !important; }
        .candy-icon-cyan { background: #0284c7 !important; border-top: 1.5px solid rgba(255,255,255,0.4) !important; border-bottom: 3.5px solid #0369a1 !important; box-shadow: 0 8px 16px rgba(2, 132, 199, 0.35) !important; }
        .candy-icon-orange { background: #ea580c !important; border-top: 1.5px solid rgba(255,255,255,0.4) !important; border-bottom: 3.5px solid #9a3412 !important; box-shadow: 0 8px 16px rgba(234, 88, 12, 0.35) !important; }
        .candy-icon-lime { background: #65a30d !important; border-top: 1.5px solid rgba(255,255,255,0.4) !important; border-bottom: 3.5px solid #3f6212 !important; box-shadow: 0 8px 16px rgba(101, 163, 13, 0.35) !important; }

        /* Typography Colors matching reference */
        .stat-card-blue .stat-card-label { color: #0056d2 !important; }
        .stat-card-rose .stat-card-label { color: #e11d48 !important; }
        .stat-card-navy .stat-card-label { color: #2B2D4B !important; }
        .stat-card-green .stat-card-label { color: #0d9467 !important; }
        .stat-card-maroon .stat-card-label { color: #dc2626 !important; }
        .stat-card-cyan .stat-card-label { color: #0284c7 !important; }
        .stat-card-orange .stat-card-label { color: #ea580c !important; }
        .stat-card-lime .stat-card-label { color: #65a30d !important; }

        /* Responsive Rules: Zero Horizontal Scroll & Fluid Cards */
        .reports-page {
            overflow-x: clip;
            max-width: 100%;
        }
        .reports-page input,
        .reports-page select {
            min-width: 0;
            max-width: 100%;
        }
        .reports-6card-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 0.75rem;
            width: 100%;
        }
        @media (min-width: 380px) {
            .reports-6card-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 640px) {
            .reports-6card-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 0.85rem;
            }
        }
        @media (min-width: 1280px) {
            .reports-6card-grid {
                grid-template-columns: repeat(6, minmax(0, 1fr));
            }
        }
        .reports-6card-grid .stat-card-3d-wrap,
        .reports-6card-grid .stat-card-3d {
            min-width: 0;
            max-width: 100%;
            overflow: hidden;
        }
        .reports-6card-grid .stat-card-vertical {
            min-height: 156px !important;
            padding: 1rem 0.65rem !important;
        }
        .reports-6card-grid .stat-card-vertical .stat-card-label {
            font-size: clamp(0.64rem, 0.78vw, 0.82rem) !important;
            line-height: 1.25 !important;
        }
        .reports-6card-grid .stat-card-vertical .stat-card-value {
            font-size: clamp(0.95rem, 1.3vw, 1.35rem) !important;
            word-break: break-word;
            white-space: normal !important;
        }

        /* Responsive Report Table vs Cards (Zero Horizontal Scroll) */
        .reports-desktop-table-wrap {
            display: none;
        }
        .reports-mobile-cards {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1rem;
            padding: 1rem;
        }
        @media (min-width: 768px) {
            .reports-mobile-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                padding: 1.25rem;
            }
        }
        @media (min-width: 1400px) {
            .reports-desktop-table-wrap {
                display: block;
                width: 100%;
                overflow: hidden;
            }
            .reports-mobile-cards {
                display: none;
            }
        }
        @media print {
            .reports-desktop-table-wrap {
                display: block !important;
                overflow: visible !important;
            }
            .reports-mobile-cards {
                display: none !important;
            }
        }
        .reports-desktop-table {
            width: 100%;
            table-layout: auto;
        }
        .reports-desktop-table th,
        .reports-desktop-table td {
            word-break: break-word;
            overflow-wrap: anywhere;
            padding: 0.6rem 0.35rem;
            font-size: 11px;
        }
        @media (min-width: 1680px) {
            .reports-desktop-table th,
            .reports-desktop-table td {
                padding: 0.7rem 0.55rem;
                font-size: 11.5px;
            }
        }
    </style>

    <div class="reports-page py-4 sm:py-6 font-sans">
        <div class="max-w-full px-3 sm:px-6 lg:px-8 space-y-5 sm:space-y-6">

            <!-- Active Card Filter Indicator (if filtered) -->
            @if(request('card_filter'))
                <div class="p-3.5 bg-blue-50 border border-blue-200 rounded-2xl flex items-center justify-between text-xs text-blue-900 font-bold shadow-2xs print:hidden">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Active Filter: <strong class="uppercase text-blue-950 font-black">{{ str_replace('_', ' ', request('card_filter')) }}</strong></span>
                    </div>
                    <a href="{{ route('tenders.reports') }}" class="px-3 py-1 bg-white hover:bg-stone-50 text-stone-700 border border-stone-300 rounded-xl text-[11px] font-black transition flex items-center gap-1">
                        &times; Clear Filter
                    </a>
                </div>
            @endif

            <!-- ------------------------------------------------------------- -->
            <!-- SECTION 1: TENDER & WORKFLOW STATUS (6 CARDS)                 -->
            <!-- ------------------------------------------------------------- -->
            <div class="space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h3 class="font-black text-lg text-stone-900 tracking-tight">Tender & Workflow Status</h3>
                    
                    <!-- Top Controls (Live EPADS Sync, Category Filter & Export Status) -->
                    <div class="flex items-center flex-wrap gap-2 print:hidden" x-data="{ catOpen: false }">
                        <div class="relative">
                            <button @click="catOpen = !catOpen" type="button" class="px-3.5 py-1.5 bg-white hover:bg-stone-50 border border-[#e5ded4] text-stone-700 rounded-xl text-xs font-bold shadow-2xs transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                <span>{{ request('category') ?: 'All Categories' }}</span>
                                <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="catOpen" @click.outside="catOpen = false" class="absolute left-0 sm:left-auto sm:right-0 mt-1 w-56 bg-white border border-[#e5ded4] rounded-2xl shadow-xl p-2 z-30 space-y-1 text-xs" style="display: none;">
                                <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="block px-3 py-1.5 rounded-lg hover:bg-stone-50 font-bold {{ !request('category') ? 'text-orange-600 bg-orange-50' : 'text-stone-700' }}">All Categories</a>
                                <a href="{{ request()->fullUrlWithQuery(['category' => 'Audio-Video & IT Systems']) }}" class="block px-3 py-1.5 rounded-lg hover:bg-stone-50 font-semibold text-stone-700">Audio-Video & IT Systems</a>
                                <a href="{{ request()->fullUrlWithQuery(['category' => 'Civil Works & Construction']) }}" class="block px-3 py-1.5 rounded-lg hover:bg-stone-50 font-semibold text-stone-700">Civil Works & Construction</a>
                                <a href="{{ request()->fullUrlWithQuery(['category' => 'Medical & Healthcare Logistics']) }}" class="block px-3 py-1.5 rounded-lg hover:bg-stone-50 font-semibold text-stone-700">Medical & Healthcare Logistics</a>
                                <a href="{{ request()->fullUrlWithQuery(['category' => 'Electrical & Power Systems']) }}" class="block px-3 py-1.5 rounded-lg hover:bg-stone-50 font-semibold text-stone-700">Electrical & Power Systems</a>
                            </div>
                        </div>

                        <button type="button" onclick="window.print()" class="px-3.5 py-1.5 bg-white hover:bg-stone-50 border border-[#e5ded4] text-stone-700 rounded-xl text-xs font-bold shadow-2xs transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Export Status</span>
                        </button>
                    </div>
                </div>

                <!-- ROW 1 CARDS: 6 CARDS -->
                <div class="reports-6card-grid">
                    <!-- 1. TOTAL TENDER (Blue) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'total']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-blue {{ in_array(request('card_filter'), ['total', 'total_tenders']) ? 'ring-4 ring-[#0056d2]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-blue w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">TOTAL TENDER</span>
                                    <div class="stat-card-value text-[32px] font-black my-0.5 leading-tight text-center" style="color: #1c1917 !important;">{{ str_pad($totalCount, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">All Listed Tender</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 2. CANCELLED TENDER (Rose) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'cancelled']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-rose {{ in_array(request('card_filter'), ['cancelled', 'cancelled_tenders']) ? 'ring-4 ring-[#e11d48]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-rose w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">CANCELLED TENDER</span>
                                    <div class="stat-card-value text-[32px] font-black my-0.5 leading-tight text-center" style="color: #1c1917 !important;">{{ str_pad($cancelledCount, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Closed / Dropped</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 3. PENDING TENDER (Navy) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'pending']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-navy {{ in_array(request('card_filter'), ['pending', 'pending_tenders']) ? 'ring-4 ring-[#2B2D4B]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-navy w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">PENDING TENDER</span>
                                    <div class="stat-card-value text-[32px] font-black my-0.5 leading-tight text-center" style="color: #1c1917 !important;">{{ str_pad($pendingCount, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Action Pending / Not Started</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 4. AWARDED TENDER (Green) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'awarded']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-green {{ in_array(request('card_filter'), ['awarded', 'awarded_tenders']) ? 'ring-4 ring-[#0d9467]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-green w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">AWARDED TENDER</span>
                                    <div class="stat-card-value text-[32px] font-black my-0.5 leading-tight text-center" style="color: #1c1917 !important;">{{ str_pad($wonCount, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Won Contract</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 5. NOT AWARDED TENDER (Maroon) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'not_awarded']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-maroon {{ in_array(request('card_filter'), ['not_awarded', 'not_awarded_tenders']) ? 'ring-4 ring-[#881337]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-maroon w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">NOT AWARDED TENDER</span>
                                    <div class="stat-card-value text-[32px] font-black my-0.5 leading-tight text-center" style="color: #1c1917 !important;">{{ str_pad($lostCount, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Unsuccessful Bid</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 6. PENDING AWARD DOCS (Cyan) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'pending_docs']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-cyan {{ in_array(request('card_filter'), ['pending_docs', 'pending_award_docs']) ? 'ring-4 ring-[#0284c7]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-cyan w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">PENDING AWARD DOCS</span>
                                    <div class="stat-card-value text-[32px] font-black my-0.5 leading-tight text-center" style="color: #1c1917 !important;">{{ str_pad($pendingDocsCount, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">LOI / LOA Processing</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ------------------------------------------------------------- -->
            <!-- SECTION 2: BIDDING & SECURITY FINANCIALS (6 CARDS)            -->
            <!-- ------------------------------------------------------------- -->
            <div class="space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h3 class="font-black text-lg text-stone-900 tracking-tight">Bidding & Security Financials</h3>

                    <!-- Controls: Filter by Fiscal Year & Export Report -->
                    <div class="flex items-center flex-wrap gap-2 print:hidden" x-data="{ fyOpen: false }">
                        <div class="relative">
                            <button @click="fyOpen = !fyOpen" type="button" class="px-3.5 py-1.5 bg-white hover:bg-stone-50 border border-[#e5ded4] text-stone-700 rounded-xl text-xs font-bold shadow-2xs transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                <span>Filter by Fiscal Year</span>
                                <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="fyOpen" @click.outside="fyOpen = false" class="absolute left-0 sm:left-auto sm:right-0 mt-1 w-48 bg-white border border-[#e5ded4] rounded-2xl shadow-xl p-2 z-30 space-y-1 text-xs" style="display: none;">
                                <a href="{{ request()->fullUrlWithQuery(['fy' => null]) }}" class="block px-3 py-1.5 rounded-lg hover:bg-stone-50 font-bold {{ !request('fy') ? 'text-orange-600 bg-orange-50' : 'text-stone-700' }}">All Fiscal Years</a>
                                <a href="{{ request()->fullUrlWithQuery(['fy' => '2026-27']) }}" class="block px-3 py-1.5 rounded-lg hover:bg-stone-50 font-semibold text-stone-700">FY 2026-2027</a>
                                <a href="{{ request()->fullUrlWithQuery(['fy' => '2025-26']) }}" class="block px-3 py-1.5 rounded-lg hover:bg-stone-50 font-semibold text-stone-700">FY 2025-2026</a>
                            </div>
                        </div>

                        <a href="{{ route('tenders.reports.export', request()->query()) }}" class="px-3.5 py-1.5 bg-white hover:bg-stone-50 border border-[#e5ded4] text-stone-700 rounded-xl text-xs font-bold shadow-2xs transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Export Report</span>
                        </a>
                    </div>
                </div>

                <!-- ROW 2 CARDS: 6 FINANCIAL CARDS -->
                <div class="reports-6card-grid">
                    <!-- 1. AWARDED VALUE (Green) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'awarded_value']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-green {{ in_array(request('card_filter'), ['awarded_value', 'awarded']) ? 'ring-4 ring-[#0d9467]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-green w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">AWARDED VALUE</span>
                                    <div class="stat-card-value text-lg sm:text-[21px] font-black my-0.5 leading-tight text-center truncate w-full" style="color: #1c1917 !important;">PKR {{ number_format($totalWonValue) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Total Work Order</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 2. TENDER FEES (Orange) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'tender_fees']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-orange {{ request('card_filter') === 'tender_fees' ? 'ring-4 ring-[#ea580c]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-orange w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">TENDER FEES</span>
                                    <div class="stat-card-value text-lg sm:text-[21px] font-black my-0.5 leading-tight text-center truncate w-full" style="color: #1c1917 !important;">PKR {{ number_format($totalTenderFeesPaid) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Paid Amount</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 3. BID SECURITY PAID (Navy/Dark Blue) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'bid_security_paid']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-navy {{ in_array(request('card_filter'), ['bid_security_paid', 'bid_sec_paid']) ? 'ring-4 ring-[#2B2D4B]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-navy w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">BID SECURITY PAID</span>
                                    <div class="stat-card-value text-lg sm:text-[21px] font-black my-0.5 leading-tight text-center truncate w-full" style="color: #1c1917 !important;">PKR {{ number_format($totalBidSecuritySubmitted) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Locked in CDRs / Bank Guarantees</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 4. BID SECURITY REFUNDED (Cyan) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'bid_security_refunded']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-cyan {{ in_array(request('card_filter'), ['bid_security_refunded', 'bid_sec_refunded']) ? 'ring-4 ring-[#0284c7]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-cyan w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a5 5 0 015 5v2m-15-7l4-4m-4 4l4 4"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">BID SECURITY REFUNDED</span>
                                    <div class="stat-card-value text-lg sm:text-[21px] font-black my-0.5 leading-tight text-center truncate w-full" style="color: #1c1917 !important;">PKR {{ number_format($totalBidSecurityReleased) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Returned</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 5. PERF. SECURITY PAID (Blue) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'perf_security_paid']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-blue {{ in_array(request('card_filter'), ['perf_security_paid', 'perf_sec_paid']) ? 'ring-4 ring-[#0056d2]/30 scale-[1.02]' : '' }}">
                                <div class="stat-candy-icon candy-icon-blue w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center">PERF. SECURITY PAID</span>
                                    <div class="stat-card-value text-lg sm:text-[21px] font-black my-0.5 leading-tight text-center truncate w-full" style="color: #1c1917 !important;">PKR {{ number_format($totalPerfSecurityPaid) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Retention & Guarantees</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- 6. PERF. SECURITY RELEASED (Lime) -->
                    <div class="stat-card-3d-wrap group">
                        <a href="{{ request()->fullUrlWithQuery(['card_filter' => 'perf_security_released']) }}" class="block">
                            <div class="stat-card-3d stat-card-vertical stat-card-lime {{ in_array(request('card_filter'), ['perf_security_released', 'perf_sec_released']) ? 'ring-4 ring-[#65a30d]/30 scale-[1.02]' : '' }}" style="border: 2.5px solid #65a30d !important; border-bottom: 6px solid #3f6212 !important; box-shadow: 0 14px 28px -6px rgba(101, 163, 13, 0.22) !important;">
                                <div class="stat-candy-icon candy-icon-lime w-12 h-12 rounded-2xl flex items-center justify-center shrink-0" style="background: #65a30d !important; border-top: 1.5px solid rgba(255,255,255,0.4) !important; border-bottom: 3.5px solid #3f6212 !important; box-shadow: 0 8px 16px rgba(101, 163, 13, 0.35) !important;">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                </div>
                                <div class="w-full flex flex-col items-center">
                                    <span class="stat-card-label text-[16px] font-black uppercase tracking-wider block text-center" style="color: #65a30d !important;">PERF. SECURITY RELEASED</span>
                                    <div class="stat-card-value text-lg sm:text-[21px] font-black my-0.5 leading-tight text-center truncate w-full" style="color: #1c1917 !important;">PKR {{ number_format($totalPerfSecurityReleased) }}</div>
                                    <div class="text-[12px] text-stone-500 font-medium truncate text-center w-full">Returned Guarantee</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- FILTER BAR (Hidden on Print) -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-5 shadow-xs print:hidden">
                <form method="GET" action="{{ route('tenders.reports') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    @if(request('card_filter'))
                        <input type="hidden" name="card_filter" value="{{ request('card_filter') }}">
                    @endif

                    <!-- Search Keyword -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Search Keyword</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tender #, Work Order #, Ref #, CDR #..." 
                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <!-- Status Filter (Synced with Starting 6 Summary Cards) -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Status</label>
                        @php $currStatus = request('status') ?: request('outcome'); @endphp
                        <select name="status" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Statuses</option>
                            <option value="cancelled" {{ $currStatus === 'cancelled' ? 'selected' : '' }}>Cancelled Tender</option>
                            <option value="pending" {{ $currStatus === 'pending' ? 'selected' : '' }}>Pending Tender</option>
                            <option value="awarded" {{ in_array($currStatus, ['awarded', 'won']) ? 'selected' : '' }}>Awarded Tender</option>
                            <option value="not_awarded" {{ in_array($currStatus, ['not_awarded', 'lost']) ? 'selected' : '' }}>Not Awarded Tender</option>
                            <option value="pending_docs" {{ $currStatus === 'pending_docs' ? 'selected' : '' }}>Pending Award Docs</option>
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Department</label>
                        <select name="department_id" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 py-2 px-3 bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold rounded-xl transition">
                            Apply
                        </button>
                        <a href="{{ route('tenders.reports') }}" class="py-2 px-3 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- PRINTABLE REPORT HEADER (Only visible on Print) -->
            <div class="hidden print:block border-b-2 border-stone-800 pb-4 mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-black text-stone-900 uppercase">Tender & Contract Management Ledger</h1>
                        <p class="text-xs text-stone-600">Official Audit & Executive Summary Report — Generated on {{ now()->format('d/m/Y h:i A') }}</p>
                    </div>
                    <div class="text-right text-xs">
                        <div class="font-bold">Total Tenders: {{ $totalCount }}</div>
                        <div class="font-bold text-emerald-800">Won Value: PKR {{ number_format($totalWonValue) }}</div>
                    </div>
                </div>
            </div>

            <!-- COMPREHENSIVE REPORT TABLE & RESPONSIVE LEDGER CARDS -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] shadow-xs overflow-hidden print:border-none print:shadow-none">
                <div class="reports-desktop-table-wrap">
                    <table class="reports-desktop-table w-full text-center border-collapse text-xs print:text-[10px]">
                        <thead>
                            <tr class="bg-stone-100/90 text-stone-900 border-b border-[#e5ded4] text-[10px] font-black uppercase tracking-wider print:bg-stone-200">
                                <th class="py-2.5 px-2 text-center w-10 border-r border-[#e5ded4]">S. No.</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Vendor Name</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Department Name</th>
                                <th class="py-2.5 px-2.5 w-[14%] border-r border-[#e5ded4]">Tender Description</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Tender No.</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Bidding Procedure</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Work Order / Award Value</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Tender Fee</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Budget (PKR)</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Bid Security (CDR / PO)</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Performance Security (PKR)</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Status</th>
                                <th class="py-2.5 px-2 text-center border-r border-[#e5ded4]">Received Award Documents</th>
                                <th class="py-2.5 px-2 text-center w-24 print:hidden">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e5ded4] text-stone-800">
                            @forelse($tenders as $idx => $t)
                                @php
                                    $postDocsHtml = '';
                                    if ($t->isWon()) {
                                        if ($t->isDocumentsCompleted()) {
                                            $postDocsHtml = '<span class="text-[11px] font-black text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Complete ✓</span>';
                                        } else {
                                            $postDocsHtml = '<span class="text-[11px] font-black text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-300">Pending (' . $t->completedDocumentsCount() . '/2) ⚠️</span>';
                                        }
                                    } else {
                                        $postDocsHtml = '<span class="text-[11px] font-black text-stone-500 bg-stone-100 px-2 py-0.5 rounded border border-stone-200">-</span>';
                                    }

                                    $feeHtml = 'Unpaid / Pending';
                                    if ($t->is_tender_fee_paid) {
                                        $feeHtml = '<span class="font-mono font-bold text-stone-900">PKR ' . number_format($t->tender_fee_amount ?: 0, 2) . '</span> <span class="text-[10px] font-bold text-emerald-700 ml-1">Paid ✓</span>';
                                    } elseif ($t->tender_fee_amount) {
                                        $feeHtml = '<span class="font-mono font-bold text-stone-900">PKR ' . number_format($t->tender_fee_amount, 2) . '</span> <span class="text-[10px] font-bold text-rose-600 ml-1">Unpaid</span>';
                                    }

                                    $bidSecHtml = '-';
                                    if ($t->bid_security_amount) {
                                        $bidSecStatusText = $t->bid_security_status === 'released_returned' ? 'Refunded / Released' : 'Active';
                                        $bidSecHtml = '<span class="font-mono font-bold text-stone-900">PKR ' . number_format($t->bid_security_amount, 2) . '</span> <span class="text-[10px] font-bold text-blue-700 ml-1">' . $bidSecStatusText . '</span>';
                                    }

                                    $perfSecHtml = $t->isPerformanceSecurityPaid()
                                        ? ('<span class="font-mono font-bold text-emerald-700">PKR ' . number_format($t->performance_security_amount, 2) . '</span> <span class="text-[10px] font-bold text-emerald-700 ml-1">Paid ✓</span>')
                                        : '<span class="text-stone-500 font-bold">No Fees</span>';

                                    $tenderModalJson = json_encode([
                                        'effective_tender_no' => $t->effective_tender_no,
                                        'description' => $t->description ?: ($t->title ?: 'No description provided.'),
                                        'technical_eligibility' => $t->technical_eligibility ?: ($t->requirements ?: 'Valid PEC Licence'),
                                        'download_url' => route('tenders.download-document', $t),
                                        'has_document' => (bool)$t->document_path,
                                        'department' => $t->department?->name ?: 'General',
                                        'firm' => $t->company ? ($t->company->company_name ?: $t->company->name) : 'Al Madina Enterprises',
                                        'procedure' => $t->procedure_label,
                                        'contact' => $t->contact_person ?: '-',
                                        'post_docs_html' => $postDocsHtml,
                                        'deadline' => $t->submission_date ? $t->submission_date->format('d/m/Y \a\t h:i A') : ($t->submission_deadline ? $t->submission_deadline->format('d/m/Y \a\t h:i A') : '-'),
                                        'opening' => $t->opening_date ? $t->opening_date->format('d/m/Y') : '-',
                                        'budget' => $t->budget ? 'PKR ' . number_format($t->budget, 2) : '-',
                                        'awarded' => ($t->isWon() && ($t->awarded_amount || $t->budget)) ? ('PKR ' . number_format($t->awarded_amount ?: $t->budget, 2)) : '-',
                                        'fee_html' => $feeHtml,
                                        'bid_sec_html' => $bidSecHtml,
                                        'perf_sec_html' => $perfSecHtml,
                                    ]);
                                @endphp
                                <tr class="hover:bg-amber-50/20 transition-colors {{ $t->isWon() ? 'bg-emerald-50/15' : '' }}">
                                    <!-- 1. S. No. -->
                                    <td class="py-3 px-3 text-center font-bold text-stone-600 border-r border-[#e5ded4]">
                                        {{ str_pad($tenders->firstItem() + $idx, 2, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <!-- 2. Vendor Name -->
                                    <td class="py-3 px-3 text-center font-bold text-[#c2410c] border-r border-[#e5ded4]">
                                        {{ $t->company ? ($t->company->company_name ?: $t->company->name) : '-' }}
                                    </td>

                                    <!-- 3. Department Name -->
                                    <td class="py-3 px-4 text-center font-bold text-stone-900 border-r border-[#e5ded4]">
                                        {{ $t->department ? $t->department->name : '-' }}
                                    </td>

                                    <!-- 4. Tender Description -->
                                    <td class="py-3 px-4 text-center border-r border-[#e5ded4]">
                                        <a href="{{ route('tenders.show', ['tender' => $t, 'from' => 'reports']) }}" class="font-bold text-stone-900 hover:text-orange-700 transition leading-snug inline-block max-w-md">
                                            {{ $t->title }}
                                        </a>
                                    </td>

                                    <!-- 5. Tender No. -->
                                    <td class="py-3 px-3 text-center font-mono font-bold text-stone-900 border-r border-[#e5ded4]">
                                        {{ $t->effective_tender_no }}
                                        @if($t->isEpad())
                                            <div class="mt-0.5">
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-900 border border-rose-200">
                                                    EPAD 2.0
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 6. Bidding Procedure -->
                                    <td class="py-3 px-3 text-center text-xs font-semibold text-stone-700 border-r border-[#e5ded4]">
                                        {{ $t->procedure_label }}
                                    </td>

                                    <!-- 7. Work Order / Award Value (Sirf PKR amount) -->
                                    <td class="py-3 px-3 text-center border-r border-[#e5ded4]">
                                        @if($t->isWon())
                                            <span class="font-mono font-black text-emerald-800 text-sm">
                                                PKR {{ number_format($t->awarded_amount ?: $t->budget, 2) }}
                                            </span>
                                        @else
                                            <span class="text-stone-400 font-mono">-</span>
                                        @endif
                                    </td>

                                    <!-- 8. Tender Fee (Sirf PKR amount + Paid/Unpaid tag) -->
                                    <td class="py-3 px-3 text-center border-r border-[#e5ded4]">
                                        @if($t->tender_fee_amount || $t->is_tender_fee_paid)
                                            <div class="inline-flex items-center justify-center gap-1.5 flex-wrap">
                                                <span class="font-mono font-black text-stone-900 text-sm">PKR {{ number_format($t->tender_fee_amount ?: 0, 2) }}</span>
                                                @if($t->is_tender_fee_paid)
                                                    <span class="text-[9px] font-extrabold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Paid ✓</span>
                                                @else
                                                    <span class="text-[9px] font-extrabold text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">Unpaid</span>
                                                @endif
                                            </div>
                                        @elseif($t->tender_type === 'epad')
                                            <span class="text-rose-700 font-bold text-[10px] bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">EPAD (Free)</span>
                                        @else
                                            <span class="text-stone-400 font-mono text-xs">No Fee</span>
                                        @endif
                                    </td>

                                    <!-- 9. Budget (PKR) -->
                                    <td class="py-3 px-3 text-center font-mono font-black text-stone-900 text-sm border-r border-[#e5ded4]">
                                        {{ $t->budget ? 'PKR ' . number_format($t->budget, 2) : '-' }}
                                    </td>

                                    <!-- 10. Bid Security (CDR / PO) (After Budget, Sirf PKR amount + Active/Refunded tag) -->
                                    <td class="py-3 px-3 text-center border-r border-[#e5ded4]">
                                        @if($t->bid_security_amount)
                                            <div class="inline-flex items-center justify-center gap-1.5 flex-wrap">
                                                <span class="font-mono font-black text-blue-900 text-sm">PKR {{ number_format($t->bid_security_amount, 2) }}</span>
                                                @if($t->bid_security_status === 'released_returned')
                                                    <span class="text-[9px] font-extrabold text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded border border-teal-200">Refunded</span>
                                                @else
                                                    <span class="text-[9px] font-extrabold text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200">Active</span>
                                                @endif
                                            </div>
                                        @elseif($t->tender_type === 'epad')
                                            <span class="text-stone-400 font-mono text-xs">N/A</span>
                                        @else
                                            <span class="text-stone-400 font-mono">-</span>
                                        @endif
                                    </td>

                                    <!-- 11. Performance Security (PKR) -->
                                    <td class="py-3 px-3 text-center border-r border-[#e5ded4]">
                                        @if($t->isPerformanceSecurityPaid())
                                            <div class="inline-flex items-center justify-center gap-1.5 flex-wrap">
                                                <span class="font-mono font-black text-emerald-800 text-sm">PKR {{ number_format($t->performance_security_amount, 2) }}</span>
                                                <span class="text-[9px] font-extrabold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Paid</span>
                                            </div>
                                        @else
                                            <span class="text-stone-400 font-bold text-xs">No Fees</span>
                                        @endif
                                    </td>

                                    <!-- 12. Status (Centered) -->
                                    <td class="py-3 px-3 text-center border-r border-[#e5ded4]">
                                        @if($t->isWon())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                                🏆 WON
                                            </span>
                                        @elseif($t->isLost())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-900 border border-rose-300">
                                                ❌ LOST
                                            </span>
                                        @elseif($t->status === 'cancelled' || $t->tender_outcome === 'cancelled')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-stone-200 text-stone-800 border border-stone-300">
                                                Cancelled
                                            </span>
                                        @elseif($t->status === 'draft')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 border border-amber-300">
                                                Draft
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-900 border border-blue-200">
                                                Active
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 13. Received Award Documents (Centered) -->
                                    <td class="py-3 px-3 text-center border-r border-[#e5ded4]">
                                        @if($t->isWon())
                                            @if($t->isDocumentsCompleted())
                                                <span class="text-[10px] font-extrabold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                    Complete ✓
                                                </span>
                                            @else
                                                <span class="text-[10px] font-extrabold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-300">
                                                    Pending ({{ $t->completedDocumentsCount() }}/2) ⚠️
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-stone-400 font-mono">-</span>
                                        @endif
                                    </td>

                                    <!-- 14. Action (4 Icons: Edit, Delete, View, Quick View) -->
                                    <td class="py-3 px-3 text-center print:hidden">
                                        <div class="flex items-center justify-center gap-1">
                                            @if(Auth::check() && Auth::user()->isAdmin())
                                                <!-- Edit -->
                                                <a href="{{ route('tenders.edit', $t) }}" class="p-1.5 text-stone-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition inline-flex items-center justify-center" title="Edit Tender">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>

                                                <!-- Delete -->
                                                <button type="button"
                                                        onclick="openDeleteReportTenderModal('{{ route('tenders.destroy', $t) }}', '{{ addslashes($t->effective_tender_no) }}')"
                                                        class="p-1.5 text-stone-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition inline-flex items-center justify-center"
                                                        title="Delete Tender">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endif

                                            <!-- View -->
                                            <a href="{{ route('tenders.show', ['tender' => $t, 'from' => 'reports']) }}" class="p-1.5 text-stone-600 hover:text-orange-700 hover:bg-orange-50 rounded-lg transition inline-flex items-center justify-center" title="View Tender Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>

                                            <!-- Quick View -->
                                            <button type="button" 
                                                    onclick='openReportTenderModal({!! htmlspecialchars($tenderModalJson, ENT_QUOTES, "UTF-8") !!})' 
                                                    class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition inline-flex items-center justify-center" 
                                                    title="Quick View Summary">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="py-12 text-center text-stone-500">
                                        No tenders match the selected criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile & Tablet Responsive Ledger Cards (< 1400px, hidden on print) -->
                <div class="reports-mobile-cards print:hidden">
                    @forelse($tenders as $idx => $t)
                        @php
                            $postDocsHtml = '';
                            if ($t->isWon()) {
                                if ($t->isDocumentsCompleted()) {
                                    $postDocsHtml = '<span class="text-[11px] font-black text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Complete ✓</span>';
                                } else {
                                    $postDocsHtml = '<span class="text-[11px] font-black text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-300">Pending (' . $t->completedDocumentsCount() . '/2) ⚠️</span>';
                                }
                            } else {
                                $postDocsHtml = '<span class="text-[11px] font-black text-stone-500 bg-stone-100 px-2 py-0.5 rounded border border-stone-200">-</span>';
                            }

                            $feeHtml = 'Unpaid / Pending';
                            if ($t->is_tender_fee_paid) {
                                $feeHtml = '<span class="font-mono font-bold text-stone-900">PKR ' . number_format($t->tender_fee_amount ?: 0, 2) . '</span> <span class="text-[10px] font-bold text-emerald-700 ml-1">Paid ✓</span>';
                            } elseif ($t->tender_fee_amount) {
                                $feeHtml = '<span class="font-mono font-bold text-stone-900">PKR ' . number_format($t->tender_fee_amount, 2) . '</span> <span class="text-[10px] font-bold text-rose-600 ml-1">Unpaid</span>';
                            }

                            $bidSecHtml = '-';
                            if ($t->bid_security_amount) {
                                $bidSecStatusText = $t->bid_security_status === 'released_returned' ? 'Refunded / Released' : 'Active';
                                $bidSecHtml = '<span class="font-mono font-bold text-stone-900">PKR ' . number_format($t->bid_security_amount, 2) . '</span> <span class="text-[10px] font-bold text-blue-700 ml-1">' . $bidSecStatusText . '</span>';
                            }

                            $perfSecHtml = $t->isPerformanceSecurityPaid()
                                ? ('<span class="font-mono font-bold text-emerald-700">PKR ' . number_format($t->performance_security_amount, 2) . '</span> <span class="text-[10px] font-bold text-emerald-700 ml-1">Paid ✓</span>')
                                : '<span class="text-stone-500 font-bold">No Fees</span>';

                            $tenderModalJsonCard = json_encode([
                                'effective_tender_no' => $t->effective_tender_no,
                                'description' => $t->description ?: ($t->title ?: 'No description provided.'),
                                'technical_eligibility' => $t->technical_eligibility ?: ($t->requirements ?: 'Valid PEC Licence'),
                                'download_url' => route('tenders.download-document', $t),
                                'has_document' => (bool)$t->document_path,
                                'department' => $t->department?->name ?: 'General',
                                'firm' => $t->company ? ($t->company->company_name ?: $t->company->name) : 'Al Madina Enterprises',
                                'procedure' => $t->procedure_label,
                                'contact' => $t->contact_person ?: '-',
                                'post_docs_html' => $postDocsHtml,
                                'deadline' => $t->submission_date ? $t->submission_date->format('d/m/Y \a\t h:i A') : ($t->submission_deadline ? $t->submission_deadline->format('d/m/Y \a\t h:i A') : '-'),
                                'opening' => $t->opening_date ? $t->opening_date->format('d/m/Y') : '-',
                                'budget' => $t->budget ? 'PKR ' . number_format($t->budget, 2) : '-',
                                'awarded' => ($t->isWon() && ($t->awarded_amount || $t->budget)) ? ('PKR ' . number_format($t->awarded_amount ?: $t->budget, 2)) : '-',
                                'fee_html' => $feeHtml,
                                'bid_sec_html' => $bidSecHtml,
                                'perf_sec_html' => $perfSecHtml,
                            ]);
                        @endphp
                        <div class="rounded-2xl border border-[#e5ded4] p-4 flex flex-col justify-between gap-3 shadow-2xs {{ $t->isWon() ? 'bg-emerald-50/20' : 'bg-[#FCFAF7]' }}">
                            <!-- Header: S.No, Tender No, Status -->
                            <div class="flex items-start justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="px-2 py-0.5 rounded-lg bg-stone-200 text-stone-700 font-mono font-black text-[11px] shrink-0">
                                        #{{ str_pad($tenders->firstItem() + $idx, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="font-mono font-black text-xs text-stone-900 break-all">{{ $t->effective_tender_no }}</span>
                                    @if($t->isEpad())
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-900 border border-rose-200 shrink-0">EPAD 2.0</span>
                                    @endif
                                </div>
                                <div>
                                    @if($t->isWon())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">🏆 WON</span>
                                    @elseif($t->isLost())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-900 border border-rose-300">❌ LOST</span>
                                    @elseif($t->status === 'cancelled' || $t->tender_outcome === 'cancelled')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-stone-200 text-stone-800 border border-stone-300">Cancelled</span>
                                    @elseif($t->status === 'draft')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 border border-amber-300">Draft</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-900 border border-blue-200">Active</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Title -->
                            <div>
                                <a href="{{ route('tenders.show', ['tender' => $t, 'from' => 'reports']) }}" class="font-black text-sm text-stone-900 hover:text-orange-700 transition leading-snug block">
                                    {{ $t->title }}
                                </a>
                            </div>

                            <!-- Vendor, Dept, Procedure -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs bg-white rounded-xl p-2.5 border border-[#e5ded4]">
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 block">Vendor</span>
                                    <span class="font-bold text-[#c2410c] block truncate">{{ $t->company ? ($t->company->company_name ?: $t->company->name) : '-' }}</span>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 block">Department</span>
                                    <span class="font-bold text-stone-900 block truncate">{{ $t->department ? $t->department->name : '-' }}</span>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 block">Procedure</span>
                                    <span class="font-semibold text-stone-700 block truncate">{{ $t->procedure_label }}</span>
                                </div>
                            </div>

                            <!-- Financials & Securities Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs bg-white rounded-xl p-2.5 border border-[#e5ded4]">
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 block">Award Value</span>
                                    @if($t->isWon())
                                        <span class="font-mono font-black text-emerald-800 block truncate">PKR {{ number_format($t->awarded_amount ?: $t->budget, 2) }}</span>
                                    @else
                                        <span class="text-stone-400 font-mono">-</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 block">Budget</span>
                                    <span class="font-mono font-black text-stone-900 block truncate">{{ $t->budget ? 'PKR ' . number_format($t->budget, 2) : '-' }}</span>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 block">Tender Fee</span>
                                    @if($t->tender_fee_amount || $t->is_tender_fee_paid)
                                        <span class="font-mono font-bold text-stone-900 block truncate">PKR {{ number_format($t->tender_fee_amount ?: 0, 2) }} ({{ $t->is_tender_fee_paid ? 'Paid' : 'Unpaid' }})</span>
                                    @elseif($t->tender_type === 'epad')
                                        <span class="text-rose-700 font-bold text-[10px]">EPAD (Free)</span>
                                    @else
                                        <span class="text-stone-400">No Fee</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 block">Bid Security</span>
                                    @if($t->bid_security_amount)
                                        <span class="font-mono font-bold text-blue-900 block truncate">PKR {{ number_format($t->bid_security_amount, 2) }}</span>
                                    @else
                                        <span class="text-stone-400 font-mono">{{ $t->tender_type === 'epad' ? 'N/A' : '-' }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 block">Perf. Security</span>
                                    @if($t->isPerformanceSecurityPaid())
                                        <span class="font-mono font-bold text-emerald-800 block truncate">PKR {{ number_format($t->performance_security_amount, 2) }}</span>
                                    @else
                                        <span class="text-stone-400 font-bold">No Fees</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase text-stone-400 block">Award Docs</span>
                                    @if($t->isWon())
                                        <span class="text-[10px] font-extrabold {{ $t->isDocumentsCompleted() ? 'text-emerald-800' : 'text-amber-800' }}">
                                            {{ $t->isDocumentsCompleted() ? 'Complete ✓' : 'Pending (' . $t->completedDocumentsCount() . '/2)' }}
                                        </span>
                                    @else
                                        <span class="text-stone-400 font-mono">-</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions Footer -->
                            <div class="pt-2 border-t border-[#e5ded4] flex items-center justify-end gap-1.5">
                                @if(Auth::check() && Auth::user()->isAdmin())
                                    <a href="{{ route('tenders.edit', $t) }}" class="p-1.5 text-stone-600 hover:text-blue-700 bg-white border border-stone-200 rounded-lg" title="Edit Tender">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button type="button" onclick="openDeleteReportTenderModal('{{ route('tenders.destroy', $t) }}', '{{ addslashes($t->effective_tender_no) }}')" class="p-1.5 text-stone-600 hover:text-rose-700 bg-white border border-stone-200 rounded-lg" title="Delete Tender">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                                <a href="{{ route('tenders.show', ['tender' => $t, 'from' => 'reports']) }}" class="p-1.5 text-stone-600 hover:text-orange-700 bg-white border border-stone-200 rounded-lg" title="View Tender Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <button type="button" onclick='openReportTenderModal({!! htmlspecialchars($tenderModalJsonCard, ENT_QUOTES, "UTF-8") !!})' class="px-2.5 py-1.5 text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-[11px] font-bold inline-flex items-center gap-1" title="Quick View Summary">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Quick View</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-10 text-center text-stone-500">
                            No tenders match the selected criteria.
                        </div>
                    @endforelse
                </div>

                @if($tenders->hasPages())
                    <div class="p-4 border-t border-[#e5ded4] bg-stone-50/50 print:hidden">
                        {{ $tenders->links() }}
                    </div>
                @endif
            </div>

            <!-- PRINTABLE AUDIT SIGNATURE BLOCKS -->
            <div class="hidden print:grid grid-cols-3 gap-8 pt-12 text-center text-xs text-stone-800">
                <div class="border-t border-stone-800 pt-2">
                    <div class="font-bold">Prepared By:</div>
                    <div class="text-stone-600 mt-1">Tender & Bidding Officer</div>
                </div>
                <div class="border-t border-stone-800 pt-2">
                    <div class="font-bold">Verified By:</div>
                    <div class="text-stone-600 mt-1">Head of Finance & Treasury</div>
                </div>
                <div class="border-t border-stone-800 pt-2">
                    <div class="font-bold">Approved By:</div>
                    <div class="text-stone-600 mt-1">Managing Director / CEO</div>
                </div>
            </div>

        </div>
    </div>

    <!-- QUICK VIEW TENDER INFORMATION MODAL -->
    <div id="reportTenderInfoModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6" style="background-color: rgba(28, 25, 23, 0.65); backdrop-filter: blur(4px);">
        <div class="max-w-5xl w-full overflow-hidden flex flex-col max-h-[92vh] shadow-2xl" style="background-color: #f4efe6; border-radius: 24px; border: 1px solid #e5ded4;">
            <!-- Modal Header -->
            <div class="flex items-center justify-between shrink-0" style="background-color: #ffffff; padding: 16px 24px; border-bottom: 1px solid #e5ded4;">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-black text-sm text-stone-900 truncate">Tender Specifications & Scope of Work</h4>
                        <span id="repModalHeaderNo" class="text-xs font-mono text-stone-500 font-bold"></span>
                    </div>
                </div>
                <button type="button" onclick="closeReportTenderModal()" class="w-8 h-8 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-600 hover:text-stone-900 flex items-center justify-center font-bold text-lg leading-none transition">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto overflow-x-hidden p-3.5 sm:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 items-start">
                    
                    <!-- Left Card: Scope & Eligibility & Document -->
                    <div class="lg:col-span-7 space-y-5 shadow-sm p-4 sm:p-6 min-w-0" style="background-color: #ffffff; border-radius: 20px; border: 1px solid #e5ded4;">
                        <!-- Scope of Work & Tender Description -->
                        <div>
                            <h4 class="text-[11px] font-black text-stone-500 uppercase tracking-wider mb-2">Scope of Work & Tender Description</h4>
                            <div id="repModalDesc" class="text-sm font-semibold text-stone-900 leading-relaxed whitespace-pre-line" style="background-color: #fcfaf7; padding: 14px 16px; border-radius: 14px; border: 1px solid #e5ded4;">
                            </div>
                        </div>

                        <hr style="border-color: #e5ded4;">

                        <!-- Technical Eligibility & Requirements -->
                        <div>
                            <h4 class="text-[11px] font-black text-stone-500 uppercase tracking-wider mb-2">Technical Eligibility & Requirements</h4>
                            <div id="repModalElig" class="text-xs text-stone-800 font-medium leading-relaxed min-h-[60px]" style="background-color: #fcfaf7; padding: 14px 16px; border-radius: 14px; border: 1px solid #e5ded4;">
                            </div>
                        </div>

                        <hr style="border-color: #e5ded4;">

                        <!-- Tender Document Download Row -->
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-stone-900">Tender Document</div>
                                    <div class="text-xs text-stone-500">Download attached official tender document</div>
                                </div>
                            </div>
                            <a id="repModalDownloadLink" href="#" target="_blank" class="px-4 py-2.5 bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold rounded-xl transition inline-flex items-center gap-2 shrink-0">
                                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Download Tender Document</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right Card: Tender Summary & Parameters -->
                    <div class="lg:col-span-5 space-y-4 shadow-sm p-4 sm:p-6 min-w-0" style="background-color: #ffffff; border-radius: 20px; border: 1px solid #e5ded4;">
                        <h4 class="font-black text-stone-900 text-xs uppercase tracking-wider pb-2" style="border-bottom: 1px solid #e5ded4;">Tender Summary & Parameters</h4>

                        <div class="space-y-2.5 divide-y divide-[#e5ded4] text-xs">
                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Tender No.</span>
                                <span id="repModalParamNo" class="font-mono font-bold text-stone-900 text-right"></span>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Department</span>
                                <span id="repModalParamDept" class="font-bold text-stone-900 text-right"></span>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Vendor Name</span>
                                <span id="repModalParamFirm" class="font-bold text-[#c2410c] text-right"></span>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Bidding Procedure</span>
                                <span id="repModalParamProc" class="font-bold text-stone-900 text-right"></span>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Contact Person</span>
                                <span id="repModalParamContact" class="font-bold text-stone-900 text-right"></span>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Post-Award Docs</span>
                                <div id="repModalParamDocs" class="text-right"></div>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Submission Deadline</span>
                                <span id="repModalParamDeadline" class="font-bold text-stone-900 text-right"></span>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Bid Opening Date</span>
                                <span id="repModalParamOpening" class="font-bold text-stone-900 text-right"></span>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Estimated Budget</span>
                                <span id="repModalParamBudget" class="font-mono font-bold text-emerald-700 text-right"></span>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Awarded Work Order</span>
                                <span id="repModalParamAwarded" class="font-mono font-bold text-emerald-700 text-right"></span>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Tender Document Fee</span>
                                <div id="repModalParamFee" class="text-right text-stone-500"></div>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Bid Security (CDR)</span>
                                <div id="repModalParamBidSec" class="text-right"></div>
                            </div>

                            <div class="pt-2 flex justify-between items-center gap-2">
                                <span class="text-stone-500 font-medium">Performance Security</span>
                                <div id="repModalParamPerfSec" class="text-right"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end shrink-0" style="background-color: #ffffff; padding: 14px 24px; border-top: 1px solid #e5ded4;">
                <button type="button" onclick="closeReportTenderModal()" class="px-5 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-bold transition">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal for Reports -->
    <div id="deleteReportTenderModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background-color: rgba(28, 25, 23, 0.65); backdrop-filter: blur(4px);">
        <div class="bg-white rounded-2xl border border-[#e5ded4] shadow-2xl max-w-md w-full p-6 space-y-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-stone-900">Delete Tender</h3>
                    <p class="text-xs text-stone-600 mt-1">
                        Are you sure you want to delete tender <strong id="deleteReportTenderLabel" class="text-stone-900"></strong>? This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-stone-100">
                <button type="button" onclick="closeDeleteReportTenderModal()" class="px-4 py-2 text-xs font-bold text-stone-700 bg-stone-100 hover:bg-stone-200 rounded-xl transition">
                    Cancel
                </button>
                <form id="deleteReportTenderForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs transition">
                        Yes, Delete Tender
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReportTenderModal(data) {
            document.getElementById('repModalHeaderNo').textContent = data.effective_tender_no || '';
            document.getElementById('repModalDesc').textContent = data.description || '';
            document.getElementById('repModalElig').textContent = data.technical_eligibility || 'Valid PEC Licence';
            document.getElementById('repModalDownloadLink').href = data.download_url || '#';
            
            document.getElementById('repModalParamNo').textContent = data.effective_tender_no || '';
            document.getElementById('repModalParamDept').textContent = data.department || '-';
            document.getElementById('repModalParamFirm').textContent = data.firm || '-';
            document.getElementById('repModalParamProc').textContent = data.procedure || '-';
            document.getElementById('repModalParamContact').textContent = data.contact || '-';
            document.getElementById('repModalParamDocs').innerHTML = data.post_docs_html || '-';
            document.getElementById('repModalParamDeadline').textContent = data.deadline || '-';
            document.getElementById('repModalParamOpening').textContent = data.opening || '-';
            document.getElementById('repModalParamBudget').textContent = data.budget || '-';
            document.getElementById('repModalParamAwarded').textContent = data.awarded || '-';
            document.getElementById('repModalParamFee').innerHTML = data.fee_html || '-';
            document.getElementById('repModalParamBidSec').innerHTML = data.bid_sec_html || '-';
            document.getElementById('repModalParamPerfSec').innerHTML = data.perf_sec_html || 'No Fees';

            const modal = document.getElementById('reportTenderInfoModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeReportTenderModal() {
            const modal = document.getElementById('reportTenderInfoModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function openDeleteReportTenderModal(actionUrl, tenderLabel) {
            document.getElementById('deleteReportTenderForm').action = actionUrl;
            document.getElementById('deleteReportTenderLabel').textContent = tenderLabel || 'this tender';
            const modal = document.getElementById('deleteReportTenderModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeDeleteReportTenderModal() {
            const modal = document.getElementById('deleteReportTenderModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</x-app-layout>
