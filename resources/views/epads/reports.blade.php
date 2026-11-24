<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse"></span>
                    <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                        {{ __('EPAD Reports & Awards') }}
                    </h2>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">Official Report</span>
                </div>
                <p class="text-sm text-slate-500 mt-0.5">Comprehensive audit reports for digital e-procurements, bid statuses, and submitted values.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('epads.reports.export', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export CSV / Excel</span>
                </a>
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Report</span>
                </button>
                <a href="{{ route('epads.index') }}" class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition shadow-2xs">
                    ← Directory
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Print Header (Visible Only When Printing) -->
            <div class="hidden print:block pb-4 mb-4 border-b border-slate-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-black text-slate-900">EPADS 2.0 DIGITAL PROCUREMENT REPORT</h1>
                        <p class="text-xs text-slate-600 mt-0.5">Generated on {{ date('d F, Y \a\t h:i A') }}</p>
                    </div>
                    <div class="text-right text-xs text-slate-600">
                        <div><strong>Total Records:</strong> {{ $stats['total_count'] }}</div>
                        <div><strong>Total Bid Amount:</strong> PKR {{ number_format($stats['total_bid_amount'], 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- 4 Summary KPI Metric Cards (Calculated from Real Database Records) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 print:grid print:grid-cols-4 print:gap-2">
                <!-- Card 1: Total Records -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-rose">
                        <div class="stat-candy-icon candy-icon-rose">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 flex flex-col justify-center text-left">
                            <span class="stat-card-label text-left">Total Records</span>
                            <div class="w-full flex justify-center my-0.5">
                                <span class="text-xl sm:text-2xl font-black stat-card-value text-rose-800">{{ str_pad($stats['total_count'], 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Filtered report count</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#ffe4e6" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#be123c" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#be123c]">100%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total Accepted Records -->
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
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Accepted bid count</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#d1fae5" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#059669" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $accPct / 100) }}" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#059669]">{{ $accPct }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Rejected Records -->
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
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Rejected bid count</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;">
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
                            <div class="text-[11px] text-slate-500 font-medium truncate text-left">Sum of filtered bids</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#dbeafe" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#0056d2" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#0056d2]">100%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Filter Form -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs print:hidden">
                <form method="GET" action="{{ route('epads.reports') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                        <!-- Date From -->
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">From Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 py-2.5">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">To Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 py-2.5">
                        </div>

                        <!-- Department Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Department Name</label>
                            <select name="department_id" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 py-2.5">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Procurement Type -->
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Procurement Type</label>
                            <select name="procurement_type" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 py-2.5">
                                <option value="">All Types</option>
                                <option value="Petty Purchase" {{ request('procurement_type') == 'Petty Purchase' ? 'selected' : '' }}>Petty Purchase</option>
                                <option value="Request for Quotation" {{ request('procurement_type') == 'Request for Quotation' ? 'selected' : '' }}>Request for Quotation</option>
                            </select>
                        </div>

                        <!-- Status (Accepted / Rejected) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Status</label>
                            <select name="status" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 py-2.5">
                                <option value="">All Statuses</option>
                                <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search & Action Row -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 border-t border-slate-100">
                        <div class="w-full sm:max-w-md">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by items, description, or Ref #..." 
                                   class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 py-2">
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Generate Report</span>
                            </button>

                            <a href="{{ route('epads.reports') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition border border-slate-200">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Report Table (Real Database Records) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden print:border-none print:shadow-none">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-900 text-white font-extrabold uppercase tracking-wider text-[11px] border-b border-slate-800 print:bg-slate-800">
                                <th class="py-3 px-3.5 w-10 text-center">#</th>
                                <th class="py-3 px-3.5 w-28">Ref #</th>
                                <th class="py-3 px-3.5 min-w-[240px]">Items / Work Description</th>
                                <th class="py-3 px-3.5 min-w-[180px]">Department Name</th>
                                <th class="py-3 px-3.5 w-40">Procurement Type</th>
                                <th class="py-3 px-3.5 w-40">Closing Date & Time</th>
                                <th class="py-3 px-3.5 w-24 text-center">Status</th>
                                <th class="py-3 px-3.5 w-36 text-right">Bid Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($epads as $index => $epad)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-3 px-3.5 text-center font-bold text-slate-400">
                                        {{ $epads->firstItem() + $index }}
                                    </td>
                                    <td class="py-3 px-3.5 font-mono font-bold text-slate-800">
                                        @if(!empty($epad->reference_no))
                                            <span class="inline-flex px-2 py-0.5 rounded bg-slate-100 text-slate-800 font-mono text-[11px]">
                                                {{ $epad->reference_no }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">Pending Submission</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3.5">
                                        <div class="font-extrabold text-slate-900">{{ $epad->title }}</div>
                                        @if(!empty($epad->description) && $epad->description !== $epad->title)
                                            <div class="text-[10px] text-slate-500 line-clamp-1 mt-0.5">{{ $epad->description }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3.5 font-bold text-slate-800">
                                        {{ $epad->department?->name ?: 'General' }}
                                    </td>
                                    <td class="py-3 px-3.5">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold {{ $epad->category === 'Request for Quotation' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-800' }}">
                                            {{ $epad->category ?: 'Petty Purchase' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3.5">
                                        <div class="font-bold text-slate-800">{{ $epad->closing_date ? $epad->closing_date->format('d/m/Y') : '-' }}</div>
                                        <div class="text-[10px] text-slate-500">{{ $epad->closing_date ? $epad->closing_date->format('h:i A') : '' }}</div>
                                    </td>
                                    <td class="py-3 px-3.5 text-center">
                                        @if($epad->isAccepted())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-300">
                                                ● Accepted
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-rose-50 text-rose-700 border border-rose-300">
                                                ● Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3.5 text-right font-black text-slate-900">
                                        <span class="text-[10px] text-slate-400 font-bold mr-0.5">PKR</span>{{ number_format((float) $epad->budget, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-12 text-center text-slate-500 font-medium">
                                        No ePads records match the selected filter criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($epads->isNotEmpty())
                            <tfoot>
                                <tr class="bg-slate-50 font-black text-slate-800 border-t-2 border-slate-300">
                                    <td colspan="4" class="py-3 px-3.5 text-left uppercase text-xs tracking-wider">
                                        Total Records: {{ $stats['total_count'] }} &nbsp;|&nbsp;
                                        <span class="text-emerald-700">Accepted: {{ $stats['accepted_count'] }}</span> &nbsp;|&nbsp;
                                        <span class="text-rose-700">Rejected: {{ $stats['rejected_count'] }}</span>
                                    </td>
                                    <td colspan="3" class="py-3 px-3.5 text-right uppercase text-xs tracking-wider">
                                        Total Filtered Bid Value:
                                    </td>
                                    <td class="py-3 px-3.5 text-right font-black text-emerald-800 text-sm">
                                        PKR {{ number_format($stats['total_bid_amount'], 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                @if($epads->hasPages())
                    <div class="p-4 border-t border-slate-100 print:hidden">
                        {{ $epads->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
