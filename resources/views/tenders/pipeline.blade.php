<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-blue-100/80 text-blue-800 border border-blue-200">
                        Procurement Pipeline & Schedule
                    </span>
                    <span class="text-stone-300">•</span>
                    <span class="text-xs font-semibold text-stone-500">Upcoming Opening Dates & Stage Results</span>
                </div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight flex items-center gap-2">
                    <span>{{ __('Active Bidding Pipeline') }}</span>
                    <span class="text-xs font-mono font-bold bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full border border-blue-300">
                        {{ str_pad($totalActiveCount, 2, '0', STR_PAD_LEFT) }} Active
                    </span>
                </h2>
                <p class="text-xs text-stone-600 mt-0.5">
                    Real-time tracking of open bids, upcoming technical & financial opening dates, and stage qualifications.
                </p>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex items-center gap-2">
                <a href="{{ route('tenders.index') }}" class="px-4 py-2 text-xs font-bold bg-white hover:bg-stone-50 text-stone-700 border border-[#e5ded4] rounded-full shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Tenders Register</span>
                </a>

                <a href="{{ route('tenders.reports') }}" class="px-4 py-2 text-xs font-bold bg-stone-900 hover:bg-stone-800 text-white rounded-full shadow-xs transition flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500 shrink-0"></span>
                    <span>Tender & Contract Management</span>
                </a>

                @if(Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('tenders.create') }}" class="px-4 py-2 text-xs font-bold bg-orange-600 hover:bg-orange-500 text-white rounded-full shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Create New Tender</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            <!-- 4 Pipeline Summary KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
                <!-- 1. Total Active In Pipeline (Electric Blue) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-blue">
                        <div class="stat-candy-icon candy-icon-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center text-left">
                            <span class="stat-card-label text-left">Active Pipeline</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value">{{ str_pad($totalActiveCount, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">In-Progress Tenders</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 64px; height: 64px;" title="Active Tenders">
                            <svg class="w-[64px] h-[64px] -rotate-90" viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#dbeafe" stroke-width="3.5" />
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#0056d2" stroke-width="3.5" stroke-dasharray="100.5" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-sm font-black font-mono text-[#0056d2]">{{ str_pad($totalActiveCount, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Stage 1 Technical Pending (#2B2D4B Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-navy">
                        <div class="stat-candy-icon candy-icon-navy">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center text-left">
                            <span class="stat-card-label text-left" style="color: #2B2D4B !important;">Stage 1 (Technical)</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value" style="color: #2B2D4B !important;">{{ str_pad($stage1PendingCount, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Tech Eval In Progress</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 64px; height: 64px;" title="Stage 1 Pending">
                            <svg class="w-[64px] h-[64px] -rotate-90" viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#e2e4ee" stroke-width="3.5" />
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#2B2D4B" stroke-width="3.5" stroke-dasharray="100.5" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-sm font-black font-mono text-[#2B2D4B]">{{ str_pad($stage1PendingCount, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Stage 2 Financial Pending (Emerald Green) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-green">
                        <div class="stat-candy-icon candy-icon-green">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center text-left">
                            <span class="stat-card-label text-left">Stage 2 (Financial)</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value">{{ str_pad($stage2PendingCount, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Awaiting Financial Open</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 64px; height: 64px;" title="Stage 2 Pending">
                            <svg class="w-[64px] h-[64px] -rotate-90" viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#d1fae5" stroke-width="3.5" />
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#0d9467" stroke-width="3.5" stroke-dasharray="100.5" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-sm font-black font-mono text-[#0d9467]">{{ str_pad($stage2PendingCount, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Upcoming Openings (Sunset Orange) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-orange">
                        <div class="stat-candy-icon candy-icon-orange">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center text-left">
                            <span class="stat-card-label text-left">Upcoming Openings</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value">{{ str_pad($upcomingOpeningsCount, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Opening Dates Scheduled</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 64px; height: 64px;" title="Upcoming Openings">
                            <svg class="w-[64px] h-[64px] -rotate-90" viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#ffedd5" stroke-width="3.5" />
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#cd3805" stroke-width="3.5" stroke-dasharray="100.5" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-sm font-black font-mono text-[#cd3805]">{{ str_pad($upcomingOpeningsCount, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Bar Card -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-5 shadow-xs">
                <form method="GET" action="{{ route('tenders.pipeline') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- Search -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Search Pipeline</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tender No, Description..." 
                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
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

                    <!-- Bidding Procedure -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Procedure</label>
                        <select name="bidding_procedure" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Procedures</option>
                            <option value="single_stage_one_envelope" {{ request('bidding_procedure') === 'single_stage_one_envelope' ? 'selected' : '' }}>Single Stage 1E</option>
                            <option value="single_stage_two_envelope" {{ request('bidding_procedure') === 'single_stage_two_envelope' ? 'selected' : '' }}>Single Stage 2E</option>
                        </select>
                    </div>

                    <!-- Stage Filter -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Pipeline Stage</label>
                        <select name="stage" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Active Stages</option>
                            <option value="stage_1" {{ request('stage') === 'stage_1' ? 'selected' : '' }}>Stage 1 (Technical Bid)</option>
                            <option value="stage_2" {{ request('stage') === 'stage_2' ? 'selected' : '' }}>Stage 2 (Financial Bid)</option>
                            <option value="1e" {{ request('stage') === '1e' ? 'selected' : '' }}>Single Stage 1E Direct</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold rounded-full transition flex items-center justify-center gap-2 shadow-xs">
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('tenders.pipeline') }}" class="py-2 px-4 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-full transition flex items-center justify-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Active Pipeline Table -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-stone-100/80 text-stone-800 border-b border-[#e5ded4] text-[11px] font-black uppercase tracking-wider">
                                <th class="py-3 px-3 text-center w-12 border-r border-[#e5ded4]">S. No.</th>
                                <th class="py-3 px-3.5 min-w-[160px] border-r border-[#e5ded4]">Department Name</th>
                                <th class="py-3 px-4 min-w-[280px] border-r border-[#e5ded4]">Tender Description</th>
                                <th class="py-3 px-3 min-w-[140px] border-r border-[#e5ded4]">Tender No.</th>
                                <th class="py-3 px-3 min-w-[150px] border-r border-[#e5ded4]">Procedure</th>
                                <th class="py-3 px-3 min-w-[160px] border-r border-[#e5ded4]">Submission Deadline</th>
                                <th class="py-3 px-3 min-w-[200px] border-r border-[#e5ded4]">Upcoming Opening Dates</th>
                                <th class="py-3 px-3 min-w-[150px] border-r border-[#e5ded4] text-center">Bidding Stage / Status</th>
                                <th class="py-3 px-3 text-center min-w-[100px]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e5ded4] text-stone-800">
                            @forelse($tenders as $index => $tender)
                                <tr class="hover:bg-amber-50/20 transition-colors">
                                    <!-- 1. S. No. -->
                                    <td class="py-3 px-3 text-center font-bold text-stone-600 border-r border-[#e5ded4]">
                                        {{ str_pad($tenders->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <!-- 2. Department Name -->
                                    <td class="py-3 px-3.5 border-r border-[#e5ded4]">
                                        <div class="font-bold text-stone-900 leading-snug">
                                            {{ $tender->department ? $tender->department->name : '-' }}
                                        </div>
                                        @if($tender->department && $tender->department->code)
                                            <span class="text-[10px] font-mono font-bold text-stone-400 uppercase">
                                                {{ $tender->department->code }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 3. Tender Description -->
                                    <td class="py-3 px-4 border-r border-[#e5ded4]">
                                        <a href="{{ route('tenders.show', $tender) }}" class="font-bold text-stone-900 hover:text-orange-700 transition leading-snug block">
                                            {{ $tender->title }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-1">
                                            @if($tender->company)
                                                <span class="text-[10px] font-bold text-orange-800 bg-orange-100/70 px-1.5 py-0.2 rounded">
                                                    {{ $tender->company->company_name ?: $tender->company->name }}
                                                </span>
                                            @endif
                                            @if($tender->budget > 0)
                                                <span class="text-[10px] font-semibold text-stone-500">
                                                    Est. Budget: PKR {{ number_format($tender->budget) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- 4. Tender No. -->
                                    <td class="py-3 px-3 font-mono font-bold text-stone-900 border-r border-[#e5ded4]">
                                        {{ $tender->effective_tender_no }}
                                        <div class="mt-0.5">
                                            @if($tender->isEpad())
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-900 border border-rose-200">
                                                    EPAD 2.0
                                                </span>
                                            @else
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider bg-stone-100 text-stone-600 border border-stone-200">
                                                    Tender
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- 5. Bidding Procedure -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4]">
                                        @if($tender->isTwoEnvelope())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-orange-100 text-orange-900 border border-orange-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-600"></span>
                                                Single Stage 2E
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-stone-100 text-stone-800 border border-stone-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-stone-600"></span>
                                                Single Stage 1E
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 6. Submission Deadline -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4]">
                                        @php
                                            $deadline = $tender->isEpad() ? ($tender->closing_date ?: $tender->submission_deadline) : $tender->submission_deadline;
                                        @endphp
                                        @if($deadline)
                                            <div class="font-bold text-stone-900">{{ $deadline->format('d/m/Y') }}</div>
                                            <div class="text-[10px] font-semibold text-stone-500">{{ $deadline->format('h:i A') }}</div>
                                            @if($deadline->isFuture())
                                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                                    Due {{ $deadline->diffForHumans() }}
                                                </span>
                                            @else
                                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-stone-100 text-stone-600 border border-stone-200">
                                                    Submission Ended
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-stone-400 font-mono">-</span>
                                        @endif
                                    </td>

                                    <!-- 7. Upcoming Opening Dates -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4]">
                                        @if($tender->isEpad())
                                            <div class="font-bold text-stone-900">{{ ($tender->closing_date ?: $tender->submission_deadline)?->format('d/m/Y') }}</div>
                                            <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200">
                                                Digital Auto-Open
                                            </span>
                                        @elseif($tender->isTwoEnvelope())
                                            <!-- Technical Opening Date -->
                                            <div class="text-[11px]">
                                                <span class="text-stone-500 font-bold">Tech Opening:</span>
                                                @if($tender->technical_opening_date || $tender->opening_date)
                                                    <span class="font-bold text-stone-900">
                                                        {{ ($tender->technical_opening_date ?: $tender->opening_date)->format('d/m/Y h:i A') }}
                                                    </span>
                                                @else
                                                    <span class="text-stone-400">-</span>
                                                @endif
                                            </div>

                                            <!-- Financial Opening Date -->
                                            <div class="text-[11px] mt-1">
                                                <span class="text-stone-500 font-bold">Fin Opening:</span>
                                                @if($tender->financial_opening_date)
                                                    <span class="font-bold text-emerald-800 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                                                        {{ $tender->financial_opening_date->format('d/m/Y h:i A') }}
                                                    </span>
                                                @elseif($tender->stage_1_status === 'disqualified')
                                                    <span class="text-rose-600 font-semibold italic text-[10px]">Stage 1 Disqualified</span>
                                                @else
                                                    <span class="text-orange-700 italic text-[10px] bg-orange-50 px-1.5 py-0.5 rounded border border-orange-200">Pending Stage 1 Qualification</span>
                                                @endif
                                            </div>
                                        @else
                                            @if($tender->opening_date)
                                                <div class="font-bold text-stone-900">{{ $tender->opening_date->format('d/m/Y') }}</div>
                                                <div class="text-[10px] font-semibold text-stone-500">
                                                    At {{ $tender->opening_date->format('h:i A') }}
                                                </div>
                                            @else
                                                <span class="text-stone-400 font-mono">-</span>
                                            @endif
                                        @endif
                                    </td>

                                    <!-- 8. Bidding Stage / Status -->
                                    <td class="py-3 px-3 text-center border-r border-[#e5ded4]">
                                        @if($tender->isTwoEnvelope())
                                            @if($tender->current_stage === 'stage_1')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-blue-100 text-blue-900 border border-blue-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                                    Stage 1 (Technical)
                                                </span>
                                            @elseif($tender->current_stage === 'stage_2')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                                    Stage 2 (Financial)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-stone-100 text-stone-800 border border-stone-200">
                                                    Evaluation Done
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-blue-100 text-blue-900 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                                Active Bid Open
                                            </span>
                                        @endif

                                        @if($tender->bid_security_amount > 0)
                                            <div class="mt-1 text-[10px] font-bold text-stone-500">
                                                CDR: PKR {{ number_format($tender->bid_security_amount) }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 9. Action -->
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('tenders.show', $tender) }}" 
                                               class="px-2.5 py-1 text-xs font-bold bg-stone-900 hover:bg-stone-800 text-white rounded-full transition shadow-xs flex items-center gap-1"
                                               title="View & Manage Bidding Stages">
                                                <span>Manage</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 text-center text-stone-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mb-2">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div class="font-bold text-stone-700 text-sm">No active pipeline tenders found</div>
                                            <div class="text-xs text-stone-500 mt-0.5">Tenders currently in bidding and evaluation will appear here.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tenders->hasPages())
                    <div class="p-4 border-t border-[#e5ded4] bg-stone-50/50">
                        {{ $tenders->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
