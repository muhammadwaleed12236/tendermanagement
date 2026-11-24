<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                    <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                        {{ __('EPADS 2.0 Directory') }}
                    </h2>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-rose-100 text-[#881337] border border-rose-200">EPAD 2.0</span>
                </div>
                <p class="text-sm text-slate-500 mt-0.5">Track digital e-procurement records, quotation bids, and procurement outcomes.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('epads.reports') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>EPAD Reports & Awards</span>
                </a>
                <a href="{{ route('epads.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-700 hover:bg-rose-800 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Create ePads</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        refModalOpen: false,
        refModalEpadId: null,
        refModalTitle: '',
        refModalValue: '',
        statusModalOpen: false,
        statusModalEpadId: null,
        statusModalTitle: '',
        statusModalValue: 'Accepted'
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold text-xs">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold text-xs">✕</button>
                </div>
            @endif

            <!-- 4 Summary KPI Metric Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
                <!-- Card 1: Total Records -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-rose">
                        <div class="stat-candy-icon candy-icon-rose">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center text-left">
                            <span class="stat-card-label text-left">Total ePads Records</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value">{{ str_pad($stats['total_count'], 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">All digital procurements</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="100% Total">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#ffe4e6" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#be123c" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#be123c]">100%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total Accepted -->
                @php
                    $accPct = $stats['total_count'] > 0 ? min(100, round(($stats['accepted_count'] / $stats['total_count']) * 100)) : 0;
                @endphp
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-green">
                        <div class="stat-candy-icon candy-icon-green">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center text-left">
                            <span class="stat-card-label text-left">Total Accepted</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value text-emerald-700">{{ str_pad($stats['accepted_count'], 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Awarded & accepted bids</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="{{ $accPct }}% Accepted">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#d1fae5" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#059669" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $accPct / 100) }}" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#059669]">{{ $accPct }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Rejected -->
                @php
                    $rejPct = $stats['total_count'] > 0 ? min(100, round(($stats['rejected_count'] / $stats['total_count']) * 100)) : 0;
                @endphp
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-maroon">
                        <div class="stat-candy-icon candy-icon-maroon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center text-left">
                            <span class="stat-card-label text-left">Total Rejected</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value text-rose-700">{{ str_pad($stats['rejected_count'], 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Disqualified & rejected</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="{{ $rejPct }}% Rejected">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#ffe4e6" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#e11d48" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $rejPct / 100) }}" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#e11d48]">{{ $rejPct }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Bid Amount -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-blue">
                        <div class="stat-candy-icon candy-icon-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center text-left">
                            <span class="stat-card-label text-left">Total Bid Amount</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value text-blue-700">
                                    <span class="text-xs mr-1 font-bold text-blue-600">PKR</span>{{ number_format($stats['total_bid_amount'], 0) }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Cumulative submitted value</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="Cumulative Total">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#dbeafe" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#0056d2" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#0056d2]">100%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                <form method="GET" action="{{ route('epads.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Search Keywords</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Items, Ref #, Department..." 
                                   class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 pl-8 py-2.5">
                            <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Department</label>
                        <select name="department_id" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 py-2.5">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Procurement Type -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Procurement Type</label>
                        <select name="procurement_type" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 py-2.5">
                            <option value="">All Types</option>
                            <option value="Petty Purchase" {{ request('procurement_type') == 'Petty Purchase' ? 'selected' : '' }}>Petty Purchase</option>
                            <option value="Request for Quotation" {{ request('procurement_type') == 'Request for Quotation' ? 'selected' : '' }}>Request for Quotation</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Status</label>
                        <div class="flex gap-2">
                            <select name="status" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 py-2.5">
                                <option value="">All Statuses</option>
                                <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <button type="submit" class="px-4 py-2.5 bg-rose-700 hover:bg-rose-800 text-white text-xs font-bold rounded-xl transition shadow-xs shrink-0">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ePads Records Table (Exact Column Order as Client Specified) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-900 text-white font-extrabold uppercase tracking-wider text-[11px] border-b border-slate-800">
                                <th class="py-3.5 px-4 w-12 text-center">#</th>
                                <th class="py-3.5 px-4 w-32">Ref #</th>
                                <th class="py-3.5 px-4 min-w-[240px]">Items / Work Description</th>
                                <th class="py-3.5 px-4 min-w-[180px]">Department Name</th>
                                <th class="py-3.5 px-4 w-44">Procurement Type</th>
                                <th class="py-3.5 px-4 w-44">Closing Date & Time</th>
                                <th class="py-3.5 px-4 w-28 text-center">Status</th>
                                <th class="py-3.5 px-4 w-36 text-right">Bid Amount</th>
                                <th class="py-3.5 px-4 w-28 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($epads as $index => $epad)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <!-- 1. # -->
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                        {{ $epads->firstItem() + $index }}
                                    </td>

                                    <!-- 2. Ref # (Manually entered after bid submission) -->
                                    <td class="py-3.5 px-4 font-mono font-bold">
                                        @if(!empty($epad->reference_no))
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-rose-50 text-[#881337] border border-rose-200 text-xs">
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

                                    <!-- 3. Items / Work Description -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-extrabold text-slate-900 line-clamp-2">
                                            {{ $epad->title }}
                                        </div>
                                        @if(!empty($epad->description) && $epad->description !== $epad->title)
                                            <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $epad->description }}</div>
                                        @endif
                                    </td>

                                    <!-- 4. Department Name -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-800">
                                            {{ $epad->department?->name ?: 'General Procurement' }}
                                        </div>
                                        @if($epad->department?->code)
                                            <span class="text-[10px] font-mono font-semibold bg-slate-100 text-slate-600 px-1.5 py-0.2 rounded mt-0.5 inline-block">
                                                {{ $epad->department->code }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 5. Procurement Type (Petty Purchase or Request for Quotation) -->
                                    <td class="py-3.5 px-4">
                                        @if($epad->category === 'Request for Quotation')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                Request for Quotation
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Petty Purchase
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 6. Closing Date & Time -->
                                    <td class="py-3.5 px-4">
                                        @if($epad->closing_date)
                                            <div class="font-bold text-slate-900">
                                                {{ $epad->closing_date->format('d/m/Y') }}
                                            </div>
                                            <div class="text-[11px] font-semibold text-slate-500 mt-0.5">
                                                Closing: {{ $epad->closing_date->format('h:i A') }}
                                            </div>
                                            @php
                                                $now = now();
                                                $isFuture = $epad->closing_date->isFuture();
                                                $diffHours = $now->diffInHours($epad->closing_date, false);
                                                $diffMins = $now->diffInMinutes($epad->closing_date, false) % 60;
                                            @endphp
                                            @if($isFuture && $diffHours < 24)
                                                <span class="text-[10px] font-extrabold text-rose-600 mt-0.5 inline-block">
                                                    {{ $diffHours }}h {{ abs($diffMins) }}m Left
                                                </span>
                                            @elseif(!$isFuture)
                                                <span class="text-[10px] font-bold text-slate-400 mt-0.5 inline-block">
                                                    Expired
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <!-- 7. Status (Accepted: Green, Rejected: Red) -->
                                    <td class="py-3.5 px-4 text-center">
                                        @if($epad->isAccepted())
                                            <button type="button" 
                                                    @click="statusModalOpen = true; statusModalEpadId = {{ $epad->id }}; statusModalTitle = '{{ addslashes($epad->title) }}'; statusModalValue = 'Rejected'"
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-300 hover:bg-emerald-100 transition shadow-2xs"
                                                    title="Click to toggle status">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                <span>Accepted</span>
                                            </button>
                                        @else
                                            <button type="button" 
                                                    @click="statusModalOpen = true; statusModalEpadId = {{ $epad->id }}; statusModalTitle = '{{ addslashes($epad->title) }}'; statusModalValue = 'Accepted'"
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-rose-50 text-rose-700 border border-rose-300 hover:bg-rose-100 transition shadow-2xs"
                                                    title="Click to toggle status">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                                <span>Rejected</span>
                                            </button>
                                        @endif
                                    </td>

                                    <!-- 8. Bid Amount -->
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="font-black text-slate-900 text-sm">
                                            <span class="text-[11px] font-bold text-slate-400 mr-0.5">PKR</span>{{ number_format((float) $epad->budget, 2) }}
                                        </div>
                                    </td>

                                    <!-- 9. Action -->
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="relative inline-block text-left" x-data="{ actOpen: false }" @click.outside="actOpen = false">
                                            <button type="button" @click="actOpen = !actOpen" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg border border-slate-200 transition">
                                                <span>Actions</span>
                                                <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </button>

                                            <div x-show="actOpen" 
                                                 x-transition 
                                                 class="absolute right-0 z-20 mt-1 w-44 rounded-xl bg-white p-1 shadow-xl border border-slate-200 text-left text-xs divide-y divide-slate-100"
                                                 style="display: none;">
                                                <div class="py-1">
                                                    <a href="{{ route('epads.edit', $epad) }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-50 font-semibold transition">
                                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>Edit Record</span>
                                                    </a>
                                                    <button type="button" 
                                                            @click="refModalOpen = true; refModalEpadId = {{ $epad->id }}; refModalTitle = '{{ addslashes($epad->title) }}'; refModalValue = '{{ addslashes($epad->reference_no ?? '') }}'; actOpen = false;"
                                                            class="w-full flex items-center gap-2 px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-50 font-semibold transition">
                                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                                        <span>{{ $epad->reference_no ? 'Change Ref #' : '+ Enter Ref #' }}</span>
                                                    </button>
                                                </div>

                                                <div class="py-1">
                                                    <form method="POST" action="{{ route('epads.update-status', $epad) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($epad->isAccepted())
                                                            <input type="hidden" name="status" value="Rejected">
                                                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-1.5 rounded-lg text-rose-700 hover:bg-rose-50 font-semibold transition">
                                                                <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                                                                <span>Mark as Rejected</span>
                                                            </button>
                                                        @else
                                                            <input type="hidden" name="status" value="Accepted">
                                                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-1.5 rounded-lg text-emerald-700 hover:bg-emerald-50 font-semibold transition">
                                                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                                                <span>Mark as Accepted</span>
                                                            </button>
                                                        @endif
                                                    </form>
                                                </div>

                                                <div class="py-1">
                                                    <form method="POST" action="{{ route('epads.destroy', $epad) }}" onsubmit="return confirm('Are you sure you want to delete this ePads record?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-1.5 rounded-lg text-rose-600 hover:bg-rose-50 font-semibold transition">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            <span>Delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-12 text-center text-slate-500 font-medium">
                                        <div class="max-w-sm mx-auto space-y-3">
                                            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-xl font-bold">📋</div>
                                            <div class="font-bold text-slate-800 text-sm">No ePads records found</div>
                                            <p class="text-xs text-slate-500">There are no ePads digital procurement records registered yet.</p>
                                            <a href="{{ route('epads.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-700 hover:bg-rose-800 text-white text-xs font-bold rounded-lg transition shadow-xs">
                                                + Create First ePads Record
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($epads->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $epads->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- Modal: Quick Update Ref # After Bid Submission -->
        <div x-show="refModalOpen" 
             x-transition 
             class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200" @click.away="refModalOpen = false">
                <form :action="'/epads/' + refModalEpadId + '/ref'" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-rose-600 flex items-center justify-center text-white text-xs font-black">#</span>
                            <div>
                                <h3 class="text-sm font-bold tracking-tight">Enter Ref # for Submitted Bid</h3>
                                <p class="text-[11px] text-slate-300 truncate max-w-xs" x-text="refModalTitle"></p>
                            </div>
                        </div>
                        <button type="button" @click="refModalOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg">✕</button>
                    </div>

                    <div class="p-5 space-y-3">
                        <p class="text-xs text-slate-600 leading-relaxed">
                            The <strong>Ref #</strong> is manually entered after the bid has been submitted on the ePads portal. Enter the official reference number below:
                        </p>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bid Reference Number (Ref #) *</label>
                            <input type="text" name="ref_no" x-model="refModalValue" required placeholder="e.g. P210075 or EPAD-2026-904" 
                                   class="w-full font-mono text-sm font-bold rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 py-2.5">
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="refModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-rose-700 hover:bg-rose-800 text-white text-xs font-bold rounded-xl shadow-xs transition">Save Ref #</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Quick Update Status (Accepted / Rejected) -->
        <div x-show="statusModalOpen" 
             x-transition 
             class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200" @click.away="statusModalOpen = false">
                <form :action="'/epads/' + statusModalEpadId + '/status'" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-emerald-600 flex items-center justify-center text-white text-xs font-black">✓</span>
                            <div>
                                <h3 class="text-sm font-bold tracking-tight">Update ePads Status</h3>
                                <p class="text-[11px] text-slate-300 truncate max-w-xs" x-text="statusModalTitle"></p>
                            </div>
                        </div>
                        <button type="button" @click="statusModalOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg">✕</button>
                    </div>

                    <div class="p-5 space-y-3">
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Update the official procurement outcome for this record:
                        </p>
                        
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <label class="cursor-pointer border-2 p-3 rounded-xl flex items-center gap-2.5 transition"
                                   :class="statusModalValue === 'Accepted' ? 'border-emerald-600 bg-emerald-50/70 text-emerald-900 font-bold' : 'border-slate-200 hover:bg-slate-50 text-slate-700'">
                                <input type="radio" name="status" value="Accepted" x-model="statusModalValue" class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <div class="text-xs font-extrabold text-emerald-700">● Accepted</div>
                                    <div class="text-[10px] text-slate-500">Bid Approved</div>
                                </div>
                            </label>

                            <label class="cursor-pointer border-2 p-3 rounded-xl flex items-center gap-2.5 transition"
                                   :class="statusModalValue === 'Rejected' ? 'border-rose-600 bg-rose-50/70 text-rose-900 font-bold' : 'border-slate-200 hover:bg-slate-50 text-slate-700'">
                                <input type="radio" name="status" value="Rejected" x-model="statusModalValue" class="text-rose-600 focus:ring-rose-500">
                                <div>
                                    <div class="text-xs font-extrabold text-rose-700">● Rejected</div>
                                    <div class="text-[10px] text-slate-500">Bid Rejected</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="statusModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-rose-700 hover:bg-rose-800 text-white text-xs font-bold rounded-xl shadow-xs transition">Update Status</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
