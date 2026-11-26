<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-orange-100/80 text-orange-800 border border-orange-200">
                        Public & Private Procurement
                    </span>
                    <span class="text-stone-300">•</span>
                    <span class="text-xs font-semibold text-stone-500">Tenders Register & Directory</span>
                </div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight flex items-center gap-2">
                    <span>{{ __('Tender Register Management') }}</span>
                    <span class="text-xs font-mono font-bold bg-stone-200 text-stone-700 px-2.5 py-0.5 rounded-full border border-stone-300">
                        {{ $totalCount }} {{ Str::plural('Tender', $totalCount) }}
                    </span>
                </h2>
                <p class="text-xs text-stone-600 mt-0.5">
                    Official directory of all standard Single Stage 1E and 2E tenders, categories, opening dates, and master records.
                </p>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex items-center gap-2">
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

            @if(session('info'))
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-2xl text-blue-800 text-xs font-bold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-700 font-bold">&times;</button>
                </div>
            @endif

            @php
                $inProgressPct = $totalCount > 0 ? min(100, round(($inProgressCount / $totalCount) * 100)) : 0;
                $wonPct = $totalCount > 0 ? min(100, round(($wonCount / $totalCount) * 100)) : 0;
                $lostPct = $totalCount > 0 ? min(100, round(($lostCount / $totalCount) * 100)) : 0;
                $cancelledPct = $totalCount > 0 ? min(100, round((($cancelledCount ?? 0) / $totalCount) * 100)) : 0;
                $twoEnvPct = $totalCount > 0 ? min(100, round(($twoEnvelopeCount / $totalCount) * 100)) : 0;
            @endphp

            <!-- Circular Ring 3D KPI Metric Cards (4 Relocated Cards from Create Tender) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
                <!-- 1. Total Tender Created (Electric Blue 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-blue">
                        <div class="stat-candy-icon candy-icon-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center items-center text-center">
                            <span class="stat-card-label text-center text-xs sm:text-[13.5px] font-black tracking-wider block w-full">Total Tender Created</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value">{{ str_pad($totalTenderCreated ?? 0, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-center w-full">All tenders logged</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 64px; height: 64px;" title="100% Created">
                            <svg class="w-[64px] h-[64px] -rotate-90" viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#dbeafe" stroke-width="3.5" />
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#0056d2" stroke-width="3.5" stroke-dasharray="100.5" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-sm font-black font-mono text-[#0056d2]">100%</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Total Active Tender (Cyan / Sky 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-cyan">
                        <div class="stat-candy-icon candy-icon-blue" style="background: #0284c7 !important;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center items-center text-center">
                            <span class="stat-card-label text-center text-xs sm:text-[13.5px] font-black tracking-wider block w-full" style="color: #0284c7 !important;">Total Active Tender</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value" style="color: #0284c7 !important;">{{ str_pad($totalActiveTender ?? 0, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-center w-full">Bidding open</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 64px; height: 64px;" title="{{ $activePct }}% Active">
                            <svg class="w-[64px] h-[64px] -rotate-90" viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#e0f2fe" stroke-width="3.5" />
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#0284c7" stroke-width="3.5" 
                                        stroke-dasharray="100.5" stroke-dashoffset="{{ 100.5 - (100.5 * $activePct / 100) }}" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-sm font-black font-mono text-[#0284c7]">{{ $activePct }}%</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Total Tender Interested (Emerald Green 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-green">
                        <div class="stat-candy-icon candy-icon-green">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center items-center text-center">
                            <span class="stat-card-label text-center text-xs sm:text-[13.5px] font-black tracking-wider block w-full">Total Tender Interested</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value">{{ str_pad($totalInterested ?? 0, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-center w-full">Ready to bid</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 64px; height: 64px;" title="{{ $interestedPct }}% Interested">
                            <svg class="w-[64px] h-[64px] -rotate-90" viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#d1fae5" stroke-width="3.5" />
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#0d9467" stroke-width="3.5" 
                                        stroke-dasharray="100.5" stroke-dashoffset="{{ 100.5 - (100.5 * $interestedPct / 100) }}" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-sm font-black font-mono text-[#0d9467]">{{ $interestedPct }}%</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Total Not Interested (Maroon 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-maroon">
                        <div class="stat-candy-icon candy-icon-maroon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center items-center text-center">
                            <span class="stat-card-label text-center text-xs sm:text-[13.5px] font-black tracking-wider block w-full">Total Not Interested</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value">{{ str_pad($totalNotInterested ?? 0, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-center w-full">Skipped / Archived</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 64px; height: 64px;" title="{{ $notInterestedPct }}% Not Interested">
                            <svg class="w-[64px] h-[64px] -rotate-90" viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#ffe4e6" stroke-width="3.5" />
                                <circle cx="20" cy="20" r="16" fill="none" stroke="#881337" stroke-width="3.5" 
                                        stroke-dasharray="100.5" stroke-dashoffset="{{ 100.5 - (100.5 * $notInterestedPct / 100) }}" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-sm font-black font-mono text-[#881337]">{{ $notInterestedPct }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Bar Card -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-5 shadow-xs">
                <form method="GET" action="{{ route('tenders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Search Keywords</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tender No, Description, Ref..." 
                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <!-- 2. Bidding Procedure Filter (Moved to Position 2) -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Bidding Procedure</label>
                        <select name="bidding_procedure" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Procedures</option>
                            <option value="single_stage_one_envelope" {{ request('bidding_procedure') === 'single_stage_one_envelope' ? 'selected' : '' }}>Single Stage One Envelope</option>
                            <option value="single_stage_two_envelope" {{ request('bidding_procedure') === 'single_stage_two_envelope' ? 'selected' : '' }}>Single Stage Two Envelope</option>
                        </select>
                    </div>

                    <!-- 3. Department Filter (Moved to Position 3) -->
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

                    <!-- 4. Tender / Bid Opening Date Filter (New Field in Position 4) -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Tender / Bid Opening Date</label>
                        <input type="date" name="opening_date" value="{{ request('opening_date') }}" 
                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <!-- 5. Action Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 py-2 px-4 bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold rounded-full transition flex items-center justify-center gap-2 shadow-xs">
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('tenders.index') }}" class="py-2 px-4 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-full transition flex items-center justify-center shadow-xs">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tenders Table matching user's exact uploaded table format -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-stone-100/80 text-stone-800 border-b border-[#e5ded4] text-[11px] font-black uppercase tracking-wider text-center">
                                <th class="py-3 px-3 text-center w-12 border-r border-[#e5ded4]">S. No.</th>
                                <th class="py-3 px-3.5 min-w-[150px] border-r border-[#e5ded4] text-center">Vendor Name</th>
                                <th class="py-3 px-3.5 min-w-[160px] border-r border-[#e5ded4] text-center">Department Name</th>
                                <th class="py-3 px-4 min-w-[280px] border-r border-[#e5ded4] text-center">Tender Description</th>
                                <th class="py-3 px-3 min-w-[150px] border-r border-[#e5ded4] text-center">Tender No.</th>
                                <th class="py-3 px-3 min-w-[150px] border-r border-[#e5ded4] text-center">Bidding Procedure</th>
                                <th class="py-3 px-3 min-w-[150px] border-r border-[#e5ded4] text-center">Tender Submission<br><span class="text-[10px] font-semibold text-stone-500">Date/Time</span></th>
                                <th class="py-3 px-3 min-w-[180px] border-r border-[#e5ded4] text-center">Tender / Bid Opening Date<br><span class="text-[10px] font-semibold text-stone-500">(Technical & Financial)</span></th>
                                <th class="py-3 px-3 min-w-[160px] border-r border-[#e5ded4] text-center">Interest Decision</th>
                                <th class="py-3 px-3 text-center min-w-[90px]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e5ded4] text-stone-800">
                            @forelse($tenders as $index => $tender)
                                <tr class="transition-colors {{ $tender->isNotInterested() ? 'bg-rose-50/40 hover:bg-rose-50/60 border-l-4 border-l-rose-500' : ($tender->isWon() ? 'bg-emerald-50/10 hover:bg-amber-50/20' : ($tender->isLost() ? 'bg-rose-50/10 hover:bg-amber-50/20' : 'hover:bg-amber-50/20')) }}">
                                    <!-- 1. S. No -->
                                    <td class="py-3 px-3 text-center font-bold text-stone-600 border-r border-[#e5ded4]">
                                        {{ str_pad($tenders->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <!-- 2. Vendor Name (Dedicated Column before Department Name) -->
                                    <td class="py-3 px-3.5 border-r border-[#e5ded4] text-left">
                                        @if($tender->company)
                                            <div class="font-bold text-stone-900 leading-snug">
                                                {{ $tender->company->company_name ?: $tender->company->name }}
                                            </div>
                                            @if($tender->company->city || $tender->company->strn_number)
                                                <span class="text-[10px] text-stone-400 font-medium">
                                                    {{ $tender->company->city ?: 'Verified Vendor' }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-stone-400 text-xs font-semibold">-</span>
                                        @endif
                                    </td>

                                    <!-- 3. Department Name (Left-Aligned) -->
                                    <td class="py-3 px-3.5 border-r border-[#e5ded4] text-left">
                                        <div class="font-bold text-stone-900 leading-snug">
                                            {{ $tender->department ? $tender->department->name : '-' }}
                                        </div>
                                    </td>

                                    <!-- 4. Tender Description (Left-Aligned) -->
                                    <td class="py-3 px-4 border-r border-[#e5ded4] text-left">
                                        <a href="{{ route('tenders.show', $tender) }}" class="font-bold text-stone-900 hover:text-orange-700 transition leading-snug block">
                                            {{ $tender->title }}
                                        </a>
                                        @if(!empty($tender->description) && $tender->description !== $tender->title)
                                            <div class="text-[11px] text-stone-500 line-clamp-1 mt-0.5">{{ $tender->description }}</div>
                                        @endif
                                    </td>

                                    <!-- 5. Tender No. (Center-Aligned) -->
                                    <td class="py-3 px-3 font-mono font-bold text-stone-900 border-r border-[#e5ded4] text-center">
                                        <div>{{ $tender->effective_tender_no }}</div>
                                        @if($tender->isEpad())
                                            <div class="mt-0.5 flex justify-center">
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-900 border border-rose-200">
                                                    EPAD 2.0
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 6. Bidding Procedure (Center-Aligned) -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4] text-center">
                                        @if($tender->isTwoEnvelope())
                                            <div class="flex justify-center">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-orange-100 text-orange-900 border border-orange-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-600"></span>
                                                    Single Stage 2E
                                                </span>
                                            </div>
                                            <div class="text-[10px] font-bold mt-1 text-stone-500 text-center">
                                                @if($tender->current_stage === 'stage_1')
                                                    Current: <span class="text-orange-700">Stage 1 (Technical)</span>
                                                @elseif($tender->current_stage === 'stage_2')
                                                    Current: <span class="text-blue-700">Stage 2 (Financial)</span>
                                                @else
                                                    Stage: <span class="text-stone-700">Completed</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex justify-center">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-stone-100 text-stone-800 border border-stone-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-stone-600"></span>
                                                    Single Stage 1E
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 7. Tender Submission Date/Time (Center-Aligned) -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4] text-center">
                                        @if($tender->isEpad())
                                            <div class="font-black text-rose-950">{{ ($tender->closing_date ?: $tender->submission_deadline)?->format('d/m/Y') }}</div>
                                            <div class="text-[10px] font-bold text-rose-700">
                                                Closing: {{ ($tender->closing_date ?: $tender->submission_deadline)?->format('h:i A') }}
                                            </div>
                                        @else
                                            <div class="font-bold text-stone-900">{{ $tender->submission_deadline ? $tender->submission_deadline->format('d/m/Y') : '-' }}</div>
                                            <div class="text-[10px] font-semibold text-stone-500">
                                                up to {{ $tender->submission_deadline ? $tender->submission_deadline->format('h:i A') : '-' }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 8. Tender / Bid Opening Date (Center-Aligned) -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4] text-center">
                                        @if($tender->isEpad())
                                            <div class="font-bold text-stone-900">{{ ($tender->closing_date ?: $tender->submission_deadline)?->format('d/m/Y') }}</div>
                                            <div class="text-[10px] font-semibold text-rose-700">Digital Auto-Open</div>
                                        @elseif($tender->isTwoEnvelope())
                                            <!-- Technical Opening Date -->
                                            <div class="text-[11px] text-center">
                                                <span class="text-stone-500 font-bold">Tech:</span>
                                                @if($tender->technical_opening_date || $tender->opening_date)
                                                    <span class="font-bold text-stone-900">
                                                        {{ ($tender->technical_opening_date ?: $tender->opening_date)->format('d/m/Y \a\t h:i A') }}
                                                    </span>
                                                @else
                                                    <span class="text-stone-400">-</span>
                                                @endif
                                            </div>

                                            <!-- Financial Opening Date -->
                                            <div class="text-[11px] mt-0.5 text-center">
                                                <span class="text-stone-500 font-bold">Fin:</span>
                                                @if($tender->financial_opening_date)
                                                    <span class="font-bold text-emerald-800 bg-emerald-50 px-1 py-0.5 rounded">
                                                        {{ $tender->financial_opening_date->format('d/m/Y \a\t h:i A') }}
                                                    </span>
                                                @elseif($tender->stage_1_status === 'disqualified')
                                                    <span class="text-rose-600 font-semibold italic text-[10px]">Cancelled (Stage 1 Lost)</span>
                                                @else
                                                    <span class="text-orange-700 italic text-[10px]">Pending Stage 1 Win</span>
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

                                    <!-- 9. Interest Decision (Interested / Not Interested Controls) -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4] text-center">
                                        @if($tender->isNotInterested())
                                            <div class="flex flex-col items-center justify-center gap-1">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-black bg-rose-100 text-rose-900 border border-rose-300 shadow-2xs">
                                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                    Not Interested
                                                </span>
                                                <a href="{{ route('tenders.bidding-prep', $tender) }}" class="text-[10px] font-bold text-emerald-700 hover:text-emerald-800 hover:underline inline-flex items-center gap-0.5">
                                                    <span>Switch to Interested</span>
                                                    <span>&rarr;</span>
                                                </a>
                                            </div>
                                        @elseif($tender->isInterested() && $tender->is_fully_processed)
                                            <div class="flex flex-col items-center justify-center gap-1">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-900 border border-emerald-300 shadow-2xs">
                                                    <svg class="w-3.5 h-3.5 text-emerald-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                    Interested (Ready)
                                                </span>
                                                <form method="POST" action="{{ route('tenders.interest-status', $tender) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="not_interested">
                                                    <button type="submit" class="text-[10px] font-semibold text-stone-400 hover:text-rose-700 hover:underline transition">
                                                        Mark Not Interested
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <!-- Initial Entry / Pending Decision -->
                                            <div class="flex items-center justify-center gap-1.5">
                                                <a href="{{ route('tenders.bidding-prep', $tender) }}" 
                                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-black bg-emerald-600 hover:bg-emerald-500 text-white shadow-2xs transition"
                                                   title="Proceed to Bidding Prep (Document Fee & Bid Security)">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                    <span>Interested</span>
                                                </a>

                                                <form method="POST" action="{{ route('tenders.interest-status', $tender) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="not_interested">
                                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-white hover:bg-rose-50 text-stone-600 hover:text-rose-700 border border-stone-200 hover:border-rose-300 shadow-2xs transition"
                                                            title="Mark as Not Interested (Do Not Bid)">
                                                        <svg class="w-3.5 h-3.5 text-stone-400 group-hover:text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                        <span>Not Interested</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 10. Action (ONLY View and Edit) -->
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('tenders.show', $tender) }}" 
                                               class="p-1.5 text-stone-600 hover:text-orange-700 hover:bg-stone-100 rounded-lg transition" title="View Tender Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            @if(Auth::check() && Auth::user()->isAdmin())
                                                <a href="{{ route('tenders.edit', $tender) }}" 
                                                   class="p-1.5 text-stone-600 hover:text-blue-700 hover:bg-stone-100 rounded-lg transition" title="Edit Tender Details">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 text-center text-stone-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mb-2">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <div class="font-bold text-stone-700 text-sm">No tenders found</div>
                                            <div class="text-xs text-stone-500 mt-0.5">Click "Create New Tender" to publish your first tender.</div>
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
