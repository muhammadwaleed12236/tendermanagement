<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-stone-900 tracking-tight">
                    {{ __('EPADS 2.0 Management') }}
                </h2>
                <p class="text-xs text-stone-500 mt-0.5 font-medium">EPAD 2.0 E-Procurement</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Search matching top bar in Screenshot 03 -->
                <form method="GET" action="{{ route('epads.index') }}" class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search" 
                           class="text-xs rounded-xl border-stone-200 pl-8 pr-3 py-2 focus:border-stone-400 focus:ring-0 w-44 sm:w-56 bg-white text-stone-800 placeholder-stone-400 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>

                <!-- + Create EPADS 2.0 Button -->
                <a href="{{ route('epads.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-black rounded-xl shadow-xs transition">
                    <span>+ Create EPADS 2.0</span>
                </a>

                <!-- Export Button -->
                <a href="{{ route('epads.export', request()->all()) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-stone-50 text-stone-700 border border-stone-200 text-xs font-bold rounded-xl shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export</span>
                </a>

                <!-- Filter Toggle Button -->
                <button type="button" @click="filterOpen = !filterOpen" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-stone-50 text-stone-700 border border-stone-200 text-xs font-bold rounded-xl shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Custom CSS for 3D Cards matching Screenshot 03 exactly -->
    <style>
        .epad-card-wrap {
            position: relative;
            background: #ffffff;
            border-radius: 1.15rem;
            transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
            padding: 0.95rem 0.85rem;
            display: flex;
            flex-direction: column;
            text-align: center;
            justify-content: space-between;
            min-height: 125px;
            text-decoration: none;
        }
        .epad-card-wrap:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px -4px rgba(15, 23, 42, 0.12);
        }
        .epad-card-label {
            font-size: 0.72rem !important;
            font-weight: 900 !important;
            letter-spacing: 0.05em !important;
            text-transform: uppercase !important;
            line-height: 1.2;
            display: block;
        }
        .epad-card-value {
            font-size: 1.35rem;
            font-weight: 900;
            line-height: 1.1;
        }
        .epad-card-sub {
            font-size: 0.68rem;
            font-weight: 500;
            color: #64748b;
            line-height: 1.2;
        }
        .candy-icon-box {
            width: 2.35rem;
            height: 2.35rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #ffffff;
        }

        /* 3D Borders & Shadow Themes matching Screenshot 03 */
        .card-theme-blue { border: 2px solid #2563eb; border-bottom: 4px solid #1d4ed8; }
        .card-theme-blue .epad-card-label { color: #1e40af; }
        .card-theme-blue .epad-card-value { color: #0f172a; }
        .candy-blue { background: #2563eb; border-top: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35); }

        .card-theme-green { border: 2px solid #059669; border-bottom: 4px solid #047857; }
        .card-theme-green .epad-card-label { color: #065f46; }
        .card-theme-green .epad-card-value { color: #065f46; }
        .candy-green { background: #059669; border-top: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 4px 10px rgba(5, 150, 105, 0.35); }

        .card-theme-amber { border: 2px solid #d97706; border-bottom: 4px solid #b45309; }
        .card-theme-amber .epad-card-label { color: #92400e; }
        .card-theme-amber .epad-card-value { color: #92400e; }
        .candy-amber { background: #d97706; border-top: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 4px 10px rgba(217, 119, 6, 0.35); }

        .card-theme-rose { border: 2px solid #9f1239; border-bottom: 4px solid #881337; }
        .card-theme-rose .epad-card-label { color: #881337; }
        .card-theme-rose .epad-card-value { color: #881337; }
        .candy-rose { background: #9f1239; border-top: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 4px 10px rgba(159, 18, 57, 0.35); }

        .card-theme-teal { border: 2px solid #0284c7; border-bottom: 4px solid #0369a1; }
        .card-theme-teal .epad-card-label { color: #075985; }
        .card-theme-teal .epad-card-value { color: #0f172a; }
        .candy-teal { background: #0284c7; border-top: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 4px 10px rgba(2, 132, 199, 0.35); }

        .card-theme-indigo { border: 2px solid #1e40af; border-bottom: 4px solid #1e3a8a; }
        .card-theme-indigo .epad-card-label { color: #1e3a8a; }
        .card-theme-indigo .epad-card-value { color: #0f172a; }
        .candy-indigo { background: #1e40af; border-top: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 4px 10px rgba(30, 64, 175, 0.35); }

        .active-filter-ring {
            ring: 3px solid #ea580c;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.35) !important;
        }
    </style>

    <div class="py-6 sm:py-8" x-data="{
        filterOpen: true,
        viewModalOpen: false,
        activeRecord: null,
        deleteModalOpen: false,
        deleteId: null,
        deleteRef: '',
        refModalOpen: false,
        refModalEpadId: null,
        refModalTitle: '',
        refModalValue: '',
        settingsModalOpen: false,
        openView(record) {
            this.activeRecord = record;
            this.viewModalOpen = true;
        },
        confirmDelete(id, ref) {
            this.deleteId = id;
            this.deleteRef = ref;
            this.deleteModalOpen = true;
        }
    }">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-black text-sm">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-rose-700 hover:text-rose-950 font-black text-sm">✕</button>
                </div>
            @endif

            <!-- Sub-bar: Folder & Active Records matching Screenshot 03 -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-stone-800">
                    <svg class="w-5 h-5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span class="font-black text-sm sm:text-base tracking-tight text-stone-900">EPADS Petty Cash / RFQ / Awards & Reports</span>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-black bg-blue-50 text-blue-800 border border-blue-200">
                        [{{ $stats['total_count'] }} Active {{ Str::plural('Record', $stats['total_count']) }}]
                    </span>
                </div>
            </div>

            <!-- 6 Stat Cards Matching Screenshot 03 Exactly -->
            @php
                $activeCard = request('card_filter');
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-3.5">
                <!-- Card 1: TOTAL EPAD RECORDS -->
                <a href="{{ route('epads.index', array_merge(request()->except(['card_filter', 'page']), ['card_filter' => 'total'])) }}" 
                   class="epad-card-wrap card-theme-blue group {{ $activeCard === 'total' ? 'active-filter-ring' : '' }}">
                    <span class="epad-card-label">TOTAL EPAD RECORDS</span>
                    <div class="flex items-center justify-between gap-2 my-1.5">
                        <div class="candy-icon-box candy-blue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="epad-card-value font-mono">{{ str_pad($stats['total_count'], 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="w-7 h-7 rounded-full border-2 border-blue-200 bg-blue-50/80 flex items-center justify-center text-[10px] font-black text-blue-700 shrink-0">
                            100%
                        </div>
                    </div>
                    <span class="epad-card-sub truncate">All digital procurement entries</span>
                </a>

                <!-- Card 2: TOTAL ACCEPTED -->
                <a href="{{ route('epads.index', array_merge(request()->except(['card_filter', 'page']), ['card_filter' => 'accepted'])) }}" 
                   class="epad-card-wrap card-theme-green group {{ $activeCard === 'accepted' ? 'active-filter-ring' : '' }}">
                    <span class="epad-card-label">TOTAL ACCEPTED</span>
                    <div class="flex items-center justify-between gap-2 my-1.5">
                        <div class="candy-icon-box candy-green">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="epad-card-value font-mono text-emerald-700">{{ str_pad($stats['accepted_count'], 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="w-7 h-7 rounded-full border-2 border-emerald-200 bg-emerald-50/80 flex items-center justify-center text-[10px] font-black text-emerald-700 shrink-0">
                            {{ $stats['accepted_pct'] }}%
                        </div>
                    </div>
                    <span class="epad-card-sub truncate">Awarded & accepted tenders</span>
                </a>

                <!-- Card 3: TOTAL UNDER EVALUATION -->
                <a href="{{ route('epads.index', array_merge(request()->except(['card_filter', 'page']), ['card_filter' => 'under_eval'])) }}" 
                   class="epad-card-wrap card-theme-amber group {{ $activeCard === 'under_eval' ? 'active-filter-ring' : '' }}">
                    <span class="epad-card-label">TOTAL UNDER EVALUATION</span>
                    <div class="flex items-center justify-between gap-2 my-1.5">
                        <div class="candy-icon-box candy-amber">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="epad-card-value font-mono text-amber-700">{{ str_pad($stats['under_eval_count'], 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="w-7 h-7 rounded-full border-2 border-amber-200 bg-amber-50/80 flex items-center justify-center text-[10px] font-black text-amber-700 shrink-0">
                            {{ $stats['under_eval_pct'] }}%
                        </div>
                    </div>
                    <span class="epad-card-sub truncate">Pending technical & financial review</span>
                </a>

                <!-- Card 4: TOTAL REJECTED -->
                <a href="{{ route('epads.index', array_merge(request()->except(['card_filter', 'page']), ['card_filter' => 'rejected'])) }}" 
                   class="epad-card-wrap card-theme-rose group {{ $activeCard === 'rejected' ? 'active-filter-ring' : '' }}">
                    <span class="epad-card-label">TOTAL REJECTED</span>
                    <div class="flex items-center justify-between gap-2 my-1.5">
                        <div class="candy-icon-box candy-rose">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <span class="epad-card-value font-mono text-rose-800">{{ str_pad($stats['rejected_count'], 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="w-7 h-7 rounded-full border-2 border-rose-200 bg-rose-50/80 flex items-center justify-center text-[10px] font-black text-rose-800 shrink-0">
                            {{ $stats['rejected_pct'] }}%
                        </div>
                    </div>
                    <span class="epad-card-sub truncate">Disqualified & rejected</span>
                </a>

                <!-- Card 5: TOTAL DOCUMENTS PENDING -->
                <a href="{{ route('epads.index', array_merge(request()->except(['card_filter', 'page']), ['card_filter' => 'pending_docs'])) }}" 
                   class="epad-card-wrap card-theme-teal group {{ $activeCard === 'pending_docs' ? 'active-filter-ring' : '' }}">
                    <span class="epad-card-label">TOTAL DOCUMENTS PENDING</span>
                    <div class="flex items-center justify-between gap-2 my-1.5">
                        <div class="candy-icon-box candy-teal">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="epad-card-value font-mono text-slate-800">{{ str_pad($stats['pending_docs_count'], 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="w-7 h-7 rounded-full border-2 border-sky-200 bg-sky-50/80 flex items-center justify-center text-[10px] font-black text-sky-700 shrink-0">
                            {{ $stats['pending_docs_pct'] }}%
                        </div>
                    </div>
                    <span class="epad-card-sub truncate">Pending required documents</span>
                </a>

                <!-- Card 6: TOTAL BID AMOUNT -->
                <a href="{{ route('epads.index', array_merge(request()->except(['card_filter', 'page']), ['card_filter' => 'bid_amount'])) }}" 
                   class="epad-card-wrap card-theme-indigo group {{ $activeCard === 'bid_amount' ? 'active-filter-ring' : '' }}">
                    <span class="epad-card-label">TOTAL BID AMOUNT</span>
                    <div class="flex items-center justify-between gap-1.5 my-1.5">
                        <div class="candy-icon-box candy-indigo">
                            <span class="font-black text-xs font-mono">PKR</span>
                        </div>
                        <div class="min-w-0 flex-1 text-center">
                            <span class="text-xs font-black text-stone-500 mr-0.5">PKR</span>
                            <span class="epad-card-value font-mono text-xs sm:text-sm font-black">{{ number_format($stats['total_bid_amount'], 0) }}</span>
                        </div>
                        <div class="w-7 h-7 rounded-full border-2 border-indigo-200 bg-indigo-50/80 flex items-center justify-center text-[10px] font-black text-indigo-700 shrink-0">
                            100%
                        </div>
                    </div>
                    <span class="epad-card-sub truncate">Cumulative submitted bids</span>
                </a>
            </div>

            <!-- Complete Filter Bar Matching Screenshot 03 -->
            <div x-show="filterOpen" x-transition class="bg-white p-4 rounded-2xl border border-stone-200/90 shadow-2xs">
                <form method="GET" action="{{ route('epads.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3 items-end">
                    @if(request('card_filter'))
                        <input type="hidden" name="card_filter" value="{{ request('card_filter') }}">
                    @endif

                    <!-- 1. SEARCH KEYWORDS -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-600 uppercase tracking-wider mb-1">SEARCH KEYWORDS</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by Items, Ref #..." 
                                   class="w-full text-xs font-medium rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2 pl-7 placeholder-stone-400">
                            <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>

                    <!-- 2. DATE WISE -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-600 uppercase tracking-wider mb-1">DATE WISE</label>
                        <input type="date" 
                               name="date_from" 
                               value="{{ request('date_from') }}" 
                               class="w-full text-xs font-semibold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2 text-stone-800">
                    </div>

                    <!-- 3. VENDOR NAME -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-600 uppercase tracking-wider mb-1">VENDOR NAME</label>
                        <select name="company_id" class="w-full text-xs font-semibold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2 bg-white text-stone-800">
                            <option value="">All Vendors</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ (request('company_id') == $company->id || request('vendor_id') == $company->id) ? 'selected' : '' }}>
                                    {{ $company->company_name ?: $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 4. DEPARTMENT NAME -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-600 uppercase tracking-wider mb-1">DEPARTMENT NAME</label>
                        <select name="department_id" class="w-full text-xs font-semibold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2 bg-white text-stone-800">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }} {{ $dept->code ? '(' . $dept->code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 5. PROCUREMENT TYPE -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-600 uppercase tracking-wider mb-1">PROCUREMENT TYPE</label>
                        <select name="procurement_type" class="w-full text-xs font-semibold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2 bg-white text-stone-800">
                            <option value="">All Types</option>
                            <option value="Open Competitive Bid." {{ request('procurement_type') === 'Open Competitive Bid.' ? 'selected' : '' }}>Open Competitive Bid.</option>
                            <option value="Petty Purchase" {{ request('procurement_type') === 'Petty Purchase' ? 'selected' : '' }}>Petty Purchase</option>
                            <option value="Request for Quotation" {{ request('procurement_type') === 'Request for Quotation' ? 'selected' : '' }}>Request for Quotation</option>
                        </select>
                    </div>

                    <!-- 6. STATUS -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-600 uppercase tracking-wider mb-1">STATUS</label>
                        <select name="status" class="w-full text-xs font-semibold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2 bg-white text-stone-800">
                            <option value="">All Statuses</option>
                            <option value="Accepted / Awarded" {{ str_contains(request('status', ''), 'Accept') ? 'selected' : '' }}>Accepted / Awarded</option>
                            <option value="Under Evaluation / Pending" {{ (str_contains(request('status', ''), 'Evaluation') || str_contains(request('status', ''), 'Pending')) ? 'selected' : '' }}>Under Evaluation / Pending</option>
                            <option value="Rejected / Disqualified" {{ str_contains(request('status', ''), 'Reject') ? 'selected' : '' }}>Rejected / Disqualified</option>
                        </select>
                    </div>

                    <!-- 7. ACTIONS (Clear Filters, Filter, Settings) -->
                    <div class="flex items-center gap-2 justify-end">
                        @if(request()->hasAny(['search', 'company_id', 'vendor_id', 'department_id', 'procurement_type', 'status', 'date_from', 'card_filter']))
                            <a href="{{ route('epads.index') }}" class="text-[11px] font-bold text-stone-500 hover:text-stone-800 whitespace-nowrap transition">
                                Clear Filters
                            </a>
                        @endif

                        <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white text-xs font-black rounded-xl transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <span>Filter</span>
                        </button>

                        <button type="button" @click="settingsModalOpen = true" class="p-2 border border-stone-200 rounded-xl hover:bg-stone-50 text-stone-600 transition" title="Filter Settings">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- 11-Column Table Matching Screenshot 03 Exactly -->
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#0f172a] text-white font-extrabold uppercase tracking-wider text-[11px] border-b border-stone-800">
                                <th class="py-3.5 px-3 text-center w-12">#</th>
                                <th class="py-3.5 px-3.5 min-w-[150px]">VENDOR NAME</th>
                                <th class="py-3.5 px-3.5 min-w-[170px]">DEPARTMENT NAME</th>
                                <th class="py-3.5 px-3 text-center min-w-[140px]">PROCUREMENT TYPE</th>
                                <th class="py-3.5 px-3 text-center min-w-[110px]">REF #</th>
                                <th class="py-3.5 px-4 min-w-[240px]">ITEMS / WORK DESCRIPTION</th>
                                <th class="py-3.5 px-3.5 min-w-[130px]">CLOSING DATE & TIME</th>
                                <th class="py-3.5 px-3.5 text-right min-w-[110px]">BID AMOUNT</th>
                                <th class="py-3.5 px-3 text-center min-w-[110px]">STATUS</th>
                                <th class="py-3.5 px-3.5 text-center min-w-[150px]">ATTACHMENT</th>
                                <th class="py-3.5 px-3 text-center min-w-[120px]">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 font-sans">
                            @forelse($epads as $index => $epad)
                                <tr class="hover:bg-stone-50/70 transition">
                                    <!-- 1. # -->
                                    <td class="py-3.5 px-3 text-center font-bold text-stone-500 font-mono">
                                        {{ str_pad($epads->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <!-- 2. VENDOR NAME -->
                                    <td class="py-3.5 px-3.5">
                                        <div class="font-extrabold text-stone-900 leading-tight">
                                            {{ $epad->vendor_name }}
                                        </div>
                                    </td>

                                    <!-- 3. DEPARTMENT NAME -->
                                    <td class="py-3.5 px-3.5">
                                        <div class="font-bold text-stone-800 leading-snug">
                                            {{ $epad->department?->name ?: 'General Procurement' }}
                                        </div>
                                        @if($epad->department?->code)
                                            <span class="text-[10px] font-mono font-bold bg-stone-100 text-stone-600 px-1.5 py-0.2 rounded mt-0.5 inline-block border border-stone-200">
                                                {{ $epad->department->code }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 4. PROCUREMENT TYPE -->
                                    <td class="py-3.5 px-3 text-center">
                                        @php
                                            $cat = $epad->category ?: 'Petty Purchase';
                                        @endphp
                                        @if($cat === 'Request for Quotation')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-800 border border-indigo-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                Request for Quotation
                                            </span>
                                        @elseif($cat === 'Open Competitive Bid.')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-teal-50 text-teal-800 border border-teal-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                                Open Competitive Bid.
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100/80 text-amber-900 border border-amber-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Petty Purchase
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 5. REF # -->
                                    <td class="py-3.5 px-3 text-center">
                                        @if(!empty($epad->reference_no))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-rose-100/90 text-rose-950 border border-rose-200">
                                                {{ $epad->reference_no }}
                                            </span>
                                        @else
                                            <button type="button" 
                                                    @click="refModalOpen = true; refModalEpadId = {{ $epad->id }}; refModalTitle = '{{ addslashes($epad->title) }}'; refModalValue = ''"
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold text-blue-600 bg-blue-50 border border-dashed border-blue-300 hover:bg-blue-100 transition"
                                                    title="Ref # is entered manually after bid submission. Click to enter.">
                                                <span>+ Add Ref #</span>
                                            </button>
                                        @endif
                                    </td>

                                    <!-- 6. ITEMS / WORK DESCRIPTION -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-extrabold text-stone-900 line-clamp-1">
                                            {{ $epad->title }}
                                        </div>
                                        @if(!empty($epad->description) && $epad->description !== $epad->title)
                                            <div class="text-[11px] text-stone-500 line-clamp-2 mt-0.5 leading-snug">
                                                {{ $epad->description }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 7. CLOSING DATE & TIME -->
                                    <td class="py-3.5 px-3.5">
                                        @if($epad->closing_date)
                                            <div class="font-extrabold text-stone-900 text-xs">
                                                {{ $epad->closing_date->format('d/m/Y') }}
                                            </div>
                                            <div class="text-[10px] font-semibold text-stone-500 mt-0.5">
                                                Closing: {{ $epad->closing_date->format('h:i A') }}
                                            </div>
                                            @if(!$epad->closing_date->isFuture())
                                                <span class="text-[9px] font-bold text-stone-500 bg-stone-100 border border-stone-200 px-1.5 py-0.2 rounded mt-0.5 inline-block">
                                                    Expired
                                                </span>
                                            @else
                                                <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.2 rounded mt-0.5 inline-block">
                                                    Active
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-stone-400">-</span>
                                        @endif
                                    </td>

                                    <!-- 8. BID AMOUNT -->
                                    <td class="py-3.5 px-3.5 text-right">
                                        <div class="text-[10px] font-black text-stone-400">PKR</div>
                                        <div class="font-black text-stone-900 text-xs font-mono">
                                            {{ number_format((float) $epad->budget, 0) }}
                                        </div>
                                    </td>

                                    <!-- 9. STATUS -->
                                    <td class="py-3.5 px-3 text-center">
                                        @if($epad->isAccepted())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100/90 text-emerald-900 border border-emerald-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                Accepted
                                            </span>
                                        @elseif($epad->isUnderEvaluation())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100/90 text-amber-900 border border-amber-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                Under Evaluation
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-100/90 text-rose-900 border border-rose-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                                Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 10. ATTACHMENT -->
                                    <td class="py-3.5 px-3.5 text-center">
                                        @if($epad->hasLoiAttachment())
                                            <a href="{{ route('epads.download-loi', $epad) }}" 
                                               target="_blank" 
                                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black bg-cyan-50 hover:bg-cyan-100 text-cyan-900 border border-cyan-200 transition shadow-2xs">
                                                <span>📄 LOI (Letter of Intent) ↗</span>
                                            </a>
                                        @else
                                            <span class="text-stone-400 text-xs font-semibold">-</span>
                                        @endif
                                    </td>

                                    <!-- 11. ACTION (4 Icon Action Buttons matching Screenshot 03) -->
                                    <td class="py-3.5 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Action 1: View Details (Eye) -->
                                            <button type="button" 
                                                    @click="openView({{ json_encode([
                                                        'id' => $epad->id,
                                                        'ref' => $epad->reference_no ?: $epad->tender_no ?: 'N/A',
                                                        'vendor' => $epad->vendor_name,
                                                        'department' => $epad->department?->name ?: 'General Procurement',
                                                        'dept_code' => $epad->department?->code ?: '',
                                                        'procurement_type' => $epad->category ?: 'Petty Purchase',
                                                        'title' => $epad->title,
                                                        'description' => $epad->description,
                                                        'closing_date' => $epad->closing_date ? $epad->closing_date->format('d/m/Y h:i A') : 'N/A',
                                                        'is_expired' => $epad->closing_date ? !$epad->closing_date->isFuture() : false,
                                                        'budget' => number_format((float) $epad->budget, 2),
                                                        'status' => $epad->status ?: 'Accepted / Awarded',
                                                        'has_loi' => $epad->hasLoiAttachment(),
                                                        'loi_url' => route('epads.download-loi', $epad),
                                                        'edit_url' => route('epads.edit', $epad),
                                                    ]) }})"
                                                    class="p-1.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-100 transition" 
                                                    title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>

                                            <!-- Action 2: Edit Record (Pencil) -->
                                            <a href="{{ route('epads.edit', $epad) }}" 
                                               class="p-1.5 rounded-lg text-stone-500 hover:text-blue-600 hover:bg-blue-50 transition" 
                                               title="Edit Record">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>

                                            <!-- Action 3: Download LOI (Download Tray) -->
                                            @if($epad->hasLoiAttachment())
                                                <a href="{{ route('epads.download-loi', $epad) }}" 
                                                   target="_blank"
                                                   class="p-1.5 rounded-lg text-stone-500 hover:text-emerald-600 hover:bg-emerald-50 transition" 
                                                   title="Download Letter of Intent (LOI)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                </a>
                                            @else
                                                <button type="button" 
                                                        disabled 
                                                        class="p-1.5 rounded-lg text-stone-300 cursor-not-allowed" 
                                                        title="No LOI Attachment">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                </button>
                                            @endif

                                            <!-- Action 4: Delete Record (Trash) -->
                                            <button type="button" 
                                                    @click="confirmDelete({{ $epad->id }}, '{{ addslashes($epad->reference_no ?: $epad->title) }}')"
                                                    class="p-1.5 rounded-lg text-stone-500 hover:text-rose-600 hover:bg-rose-50 transition" 
                                                    title="Delete Record">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="p-12 text-center text-stone-500">
                                        <div class="max-w-sm mx-auto space-y-3">
                                            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center mx-auto text-xl font-bold">📋</div>
                                            <div class="font-extrabold text-stone-900 text-sm">No EPADS 2.0 records found</div>
                                            <p class="text-xs text-stone-500">There are no digital e-procurement records matching the current filters.</p>
                                            <a href="{{ route('epads.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-black rounded-xl transition shadow-xs">
                                                + Create EPADS 2.0
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Table Pagination / Footer Matching Screenshot 03 -->
                <div class="p-4 bg-stone-50/50 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-stone-500 font-semibold">
                    <div>
                        Showing {{ $epads->firstItem() ?? 0 }} to {{ $epads->lastItem() ?? 0 }} of {{ $epads->total() }} records
                    </div>
                    <div>
                        @if($epads->hasPages())
                            {{ $epads->links() }}
                        @else
                            <span>Page 1 of 1</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- 1. View Details Modal -->
        <div x-show="viewModalOpen" 
             x-transition 
             class="fixed inset-0 z-50 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden border border-stone-200" @click.away="viewModalOpen = false">
                <!-- Header -->
                <div class="px-6 py-4 bg-[#0f172a] text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                        <h3 class="text-sm font-extrabold tracking-tight">EPADS 2.0 Record Details</h3>
                    </div>
                    <button type="button" @click="viewModalOpen = false" class="text-stone-400 hover:text-white font-black text-sm">✕</button>
                </div>

                <!-- Body -->
                <template x-if="activeRecord">
                    <div class="p-6 space-y-4 text-xs">
                        <!-- Top Badges -->
                        <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                            <div>
                                <span class="text-[10px] font-black text-stone-400 uppercase tracking-wider block">Reference / Bid ID</span>
                                <span class="font-mono text-sm font-black text-stone-900" x-text="activeRecord.ref"></span>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-stone-400 uppercase tracking-wider block text-right">Status</span>
                                <span class="px-2.5 py-1 rounded-full text-xs font-black inline-block mt-0.5"
                                      :class="activeRecord.status.includes('Accept') ? 'bg-emerald-100 text-emerald-800' : (activeRecord.status.includes('Reject') ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800')"
                                      x-text="activeRecord.status"></span>
                            </div>
                        </div>

                        <!-- 2-col metadata grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] font-black text-stone-400 uppercase tracking-wider block">Vendor Name</span>
                                <p class="font-bold text-stone-800 text-xs mt-0.5" x-text="activeRecord.vendor"></p>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-stone-400 uppercase tracking-wider block">Department</span>
                                <p class="font-bold text-stone-800 text-xs mt-0.5">
                                    <span x-text="activeRecord.department"></span>
                                    <template x-if="activeRecord.dept_code">
                                        <span class="text-[10px] font-mono font-bold bg-stone-100 px-1 py-0.2 rounded ml-1" x-text="activeRecord.dept_code"></span>
                                    </template>
                                </p>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-stone-400 uppercase tracking-wider block">Procurement Type</span>
                                <p class="font-bold text-stone-800 text-xs mt-0.5" x-text="activeRecord.procurement_type"></p>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-stone-400 uppercase tracking-wider block">Bid Amount</span>
                                <p class="font-black text-stone-900 text-xs font-mono mt-0.5">
                                    <span class="text-[10px] text-stone-400 mr-0.5">PKR</span>
                                    <span x-text="activeRecord.budget"></span>
                                </p>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] font-black text-stone-400 uppercase tracking-wider block">Closing Date & Time</span>
                                <p class="font-bold text-stone-800 text-xs mt-0.5 flex items-center gap-1.5">
                                    <span x-text="activeRecord.closing_date"></span>
                                    <template x-if="activeRecord.is_expired">
                                        <span class="text-[9px] font-bold text-stone-500 bg-stone-100 px-1.5 py-0.2 rounded">Expired</span>
                                    </template>
                                </p>
                            </div>
                        </div>

                        <!-- Items Description -->
                        <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
                            <span class="text-[10px] font-black text-stone-500 uppercase tracking-wider block mb-1">Items / Work Description</span>
                            <p class="font-bold text-stone-900 text-xs" x-text="activeRecord.title"></p>
                            <template x-if="activeRecord.description && activeRecord.description !== activeRecord.title">
                                <p class="text-[11px] text-stone-600 mt-1 leading-relaxed" x-text="activeRecord.description"></p>
                            </template>
                        </div>

                        <!-- LOI Attachment Preview / Link -->
                        <div class="p-3 bg-stone-50 rounded-xl border border-stone-200 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-black text-stone-500 uppercase tracking-wider block">Letter of Intent (LOI) Attachment</span>
                                <template x-if="activeRecord.has_loi">
                                    <span class="text-emerald-700 font-bold text-[11px] flex items-center gap-1 mt-0.5">
                                        <span>✓ Document Uploaded & Available</span>
                                    </span>
                                </template>
                                <template x-if="!activeRecord.has_loi">
                                    <span class="text-stone-400 font-medium text-[11px] mt-0.5 block">No LOI file attached</span>
                                </template>
                            </div>
                            <template x-if="activeRecord.has_loi">
                                <a :href="activeRecord.loi_url" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition text-xs flex items-center gap-1 shadow-xs">
                                    <span>Download LOI</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </template>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                            <button type="button" @click="viewModalOpen = false" class="px-4 py-2 bg-white border border-stone-200 hover:bg-stone-50 text-stone-700 font-bold rounded-xl transition text-xs shadow-2xs">
                                Close
                            </button>
                            <a :href="activeRecord.edit_url" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-black rounded-xl transition text-xs shadow-xs">
                                Edit Record
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- 2. Delete Confirmation Modal -->
        <div x-show="deleteModalOpen" 
             x-transition 
             class="fixed inset-0 z-50 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-stone-200" @click.away="deleteModalOpen = false">
                <form :action="'/epads/' + deleteId" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="p-6 space-y-4 text-center">
                        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto text-xl font-bold">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-stone-900 tracking-tight">Delete EPADS 2.0 Record?</h3>
                            <p class="text-xs text-stone-500 mt-1">
                                Are you sure you want to delete e-Pads record <span class="font-mono font-bold text-stone-800" x-text="deleteRef"></span>? This action cannot be undone.
                            </p>
                        </div>
                        <div class="pt-2 flex items-center justify-center gap-3">
                            <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 bg-white border border-stone-200 hover:bg-stone-50 text-stone-700 font-bold rounded-xl transition text-xs shadow-2xs">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-black rounded-xl transition text-xs shadow-xs">
                                Yes, Delete Record
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Quick Update Ref # Modal -->
        <div x-show="refModalOpen" 
             x-transition 
             class="fixed inset-0 z-50 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-stone-200" @click.away="refModalOpen = false">
                <form :action="'/epads/' + refModalEpadId + '/ref'" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="p-4 bg-stone-900 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-orange-600 flex items-center justify-center text-white text-xs font-black">#</span>
                            <div>
                                <h3 class="text-sm font-bold tracking-tight">Enter Ref # for Submitted Bid</h3>
                                <p class="text-[11px] text-stone-300 truncate max-w-xs" x-text="refModalTitle"></p>
                            </div>
                        </div>
                        <button type="button" @click="refModalOpen = false" class="text-stone-400 hover:text-white font-bold text-sm">✕</button>
                    </div>

                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                                e-Pads Reference Number <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" 
                                   name="reference_no" 
                                   x-model="refModalValue" 
                                   required 
                                   placeholder="e.g. EP-2026-9842" 
                                   class="w-full text-xs font-mono font-bold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 p-2.5">
                            <p class="text-[10px] text-stone-400 mt-1">Official Reference Number generated by e-Pads portal</p>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="refModalOpen = false" class="px-4 py-2 text-xs font-bold text-stone-600 hover:bg-stone-100 rounded-xl transition">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-black rounded-xl shadow-xs transition">
                                Save Reference #
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- 4. Settings Modal -->
        <div x-show="settingsModalOpen" 
             x-transition 
             class="fixed inset-0 z-50 bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-stone-200" @click.away="settingsModalOpen = false">
                <div class="p-4 bg-stone-900 text-white flex items-center justify-between">
                    <h3 class="text-sm font-bold tracking-tight">Dashboard Settings</h3>
                    <button type="button" @click="settingsModalOpen = false" class="text-stone-400 hover:text-white font-bold text-sm">✕</button>
                </div>
                <div class="p-5 space-y-3 text-xs text-stone-600">
                    <p class="font-bold text-stone-900">EPADS 2.0 Management Preferences</p>
                    <p class="text-[11px] text-stone-500">Configure default records per page and automatic refresh intervals.</p>
                    <div class="pt-2 flex justify-end">
                        <button type="button" @click="settingsModalOpen = false" class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white font-bold rounded-xl text-xs transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>