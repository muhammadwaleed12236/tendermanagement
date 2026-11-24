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
                <h2 class="font-black text-2xl text-stone-900 leading-tight flex items-center gap-2">
                    <span>{{ __('Tenders, Work Orders & Financial Securities Report') }}</span>
                    <span class="text-xs font-mono font-bold bg-stone-200 text-stone-700 px-2.5 py-0.5 rounded-full border border-stone-300">
                        {{ $totalCount }} Records
                    </span>
                </h2>
                <p class="text-xs text-stone-600 mt-0.5">
                    Executive overview of won tenders, contract work orders, tender fees paid with bank sources, and active bid securities (CDR / Pay Order).
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 print:hidden">
                <button type="button" onclick="window.print()" class="px-4 py-2 text-xs font-bold bg-stone-900 hover:bg-stone-800 text-white rounded-xl shadow-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Official Report</span>
                </button>

                <a href="{{ route('tenders.create') }}" class="px-4 py-2 text-xs font-bold bg-orange-600 hover:bg-orange-500 text-white rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ New Tender</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Circular Ring KPI Metric Cards (Printable & Screen) -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                <!-- 1. Total Won Work Orders Value -->
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Awarded Work Order Value</div>
                        <div class="text-xl sm:text-2xl font-black text-emerald-950 mt-0.5">PKR {{ number_format($totalWonValue) }}</div>
                        <div class="text-[11px] text-stone-500">{{ $wonTenders->count() }} Tenders Awarded</div>
                    </div>
                    <div class="w-12 h-12 rounded-full border-4 border-emerald-500 flex items-center justify-center shrink-0">
                        <span class="text-xs font-black text-emerald-700">WON</span>
                    </div>
                </div>

                <!-- 2. Total Budget Volume -->
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-stone-600">Total Awarded Work Order Value</div>
                        <div class="text-xl sm:text-2xl font-black text-stone-900 mt-0.5">PKR {{ number_format($totalBudget) }}</div>
                        <div class="text-[11px] text-stone-500">All {{ $totalCount }} tenders listed</div>
                    </div>
                    <div class="w-12 h-12 rounded-full border-4 border-stone-300 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>

                <!-- 3. Total Tender Fees Paid -->
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-orange-700">Total Tender Fees Paid</div>
                        <div class="text-xl sm:text-2xl font-black text-orange-950 mt-0.5">PKR {{ number_format($totalTenderFeesPaid) }}</div>
                        <div class="text-[11px] text-stone-500">Paid from Bank / Cash</div>
                    </div>
                    <div class="w-12 h-12 rounded-full border-4 border-orange-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>

                <!-- 4. Active Bid Security (CDR Locked) -->
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-blue-700">Total Active Bid Security</div>
                        <div class="text-xl sm:text-2xl font-black text-blue-950 mt-0.5">PKR {{ number_format($totalBidSecuritySubmitted) }}</div>
                        <div class="text-[11px] text-stone-500">Locked in CDRs / Pay Orders</div>
                    </div>
                    <div class="w-12 h-12 rounded-full border-4 border-blue-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>

                <!-- 5. Released Bid Security / Docs Complete -->
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 shadow-xs flex items-center justify-between col-span-2 lg:col-span-1">
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-teal-700">Pending Award Documents Status</div>
                        <div class="text-xl sm:text-2xl font-black text-teal-950 mt-0.5">{{ $docsCompletedCount }} / {{ $wonTenders->count() }}</div>
                        <div class="text-[11px] text-stone-500">LOI & Work Order Verified</div>
                    </div>
                    <div class="w-12 h-12 rounded-full border-4 border-teal-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- FILTER BAR (Hidden on Print) -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-5 shadow-xs print:hidden">
                <form method="GET" action="{{ route('tenders.reports') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Search Keyword</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tender #, Work Order #, Ref #, CDR #..." 
                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <!-- Outcome Filter -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Outcome</label>
                        <select name="outcome" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Outcomes</option>
                            <option value="won" {{ request('outcome') === 'won' ? 'selected' : '' }}>🏆 Won / Awarded Only</option>
                            <option value="in_progress" {{ request('outcome') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="lost" {{ request('outcome') === 'lost' ? 'selected' : '' }}>Lost / Disqualified</option>
                        </select>
                    </div>

                    <!-- Tender Mode Filter -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Mode</label>
                        <select name="tender_type" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Modes</option>
                            <option value="tender" {{ request('tender_type') === 'tender' ? 'selected' : '' }}>Tender (Paper)</option>
                            <option value="epad" {{ request('tender_type') === 'epad' ? 'selected' : '' }}>EPAD 2.0</option>
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

                    <!-- Tender Fee Status -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-600 mb-1">Tender Fee</label>
                        <select name="tender_fee_status" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Fees Status</option>
                            <option value="paid" {{ request('tender_fee_status') === 'paid' ? 'selected' : '' }}>Paid Only</option>
                            <option value="unpaid" {{ request('tender_fee_status') === 'unpaid' ? 'selected' : '' }}>Unpaid Only</option>
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
                        <h1 class="text-xl font-black text-stone-900 uppercase">Tenders, Work Orders & Financial Securities Ledger</h1>
                        <p class="text-xs text-stone-600">Official Audit & Executive Summary Report — Generated on {{ now()->format('d/m/Y h:i A') }}</p>
                    </div>
                    <div class="text-right text-xs">
                        <div class="font-bold">Total Tenders: {{ $totalCount }}</div>
                        <div class="font-bold text-emerald-800">Won Value: PKR {{ number_format($totalWonValue) }}</div>
                    </div>
                </div>
            </div>

            <!-- COMPREHENSIVE REPORT TABLE -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] shadow-xs overflow-hidden print:border-none print:shadow-none">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs print:text-[10px]">
                        <thead>
                            <tr class="bg-stone-100/90 text-stone-900 border-b border-[#e5ded4] text-[11px] font-black uppercase tracking-wider print:bg-stone-200 text-center">
                                <th class="py-3 px-3 text-center w-12 border-r border-[#e5ded4]">S.#</th>
                                <th class="py-3 px-3 min-w-[140px] border-r border-[#e5ded4]">Tender No. / Code</th>
                                <th class="py-3 px-4 min-w-[240px] border-r border-[#e5ded4]">Description of Items</th>
                                <th class="py-3 px-4 min-w-[240px] border-r border-[#e5ded4]">Department Name</th>
                                <th class="py-3 px-3 min-w-[130px] border-r border-[#e5ded4]">Budget (PKR)</th>
                                <th class="py-3 px-3 min-w-[150px] border-r border-[#e5ded4]">Work Order / Award Value</th>
                                <th class="py-3 px-3 min-w-[160px] border-r border-[#e5ded4]">Tender Fee</th>
                                <th class="py-3 px-3 min-w-[170px] border-r border-[#e5ded4]">Bid Security (CDR / PO)</th>
                                <th class="py-3 px-3 min-w-[120px] border-r border-[#e5ded4]">Status</th>
                                <th class="py-3 px-3 min-w-[120px] border-r border-[#e5ded4]">Received Award Documents</th>
                                <th class="py-3 px-3 text-center min-w-[70px] print:hidden">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e5ded4] text-stone-800">
                            @forelse($tenders as $idx => $t)
                                <tr class="hover:bg-amber-50/20 transition-colors {{ $t->isWon() ? 'bg-emerald-50/15' : '' }}">
                                    <!-- S.# -->
                                    <td class="py-3 px-3 text-center font-bold text-stone-600 border-r border-[#e5ded4]">
                                        {{ str_pad($tenders->firstItem() + $idx, 2, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <!-- Tender No. -->
                                    <td class="py-3 px-3 font-mono font-bold text-stone-900 border-r border-[#e5ded4]">
                                        {{ $t->effective_tender_no }}
                                        <div class="mt-0.5">
                                            @if($t->isEpad())
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-900 border border-rose-200">
                                                    EPAD 2.0
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-stone-500 font-sans font-normal">{{ $t->procedure_label }}</div>
                                    </td>

                                    <!-- Item of Description -->
                                    <td class="py-3 px-4 border-r border-[#e5ded4]">
                                        <a href="{{ route('tenders.show', $t) }}" class="font-bold text-stone-900 hover:text-orange-700 transition leading-snug block">
                                            {{ $t->title }}
                                        </a>
                                    </td>

                                    <!-- Department -->
                                    <td class="py-3 px-4 border-r border-[#e5ded4]">
                                        <span class="font-bold text-stone-800 block">{{ $t->department?->name ?: 'General' }}</span>
                                        @if($t->company)
                                            <div class="text-[11px] text-orange-800 font-semibold mt-0.5">
                                                {{ $t->company->company_name ?: $t->company->name }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Estimated Budget -->
                                    <td class="py-3 px-3 text-right font-mono font-bold text-stone-900 border-r border-[#e5ded4]">
                                        {{ $t->budget ? 'PKR ' . number_format($t->budget) : '-' }}
                                    </td>

                                    <!-- Work Order / Awarded Value -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4]">
                                        @if($t->isWon())
                                            <div class="font-mono font-black text-emerald-800 text-xs">
                                                PKR {{ number_format($t->awarded_amount ?: $t->budget) }}
                                            </div>
                                            <div class="text-[10px] text-stone-600 mt-0.5">
                                                @if($t->effective_work_order_no)
                                                    <span class="font-bold text-stone-700">W.O:</span> {{ $t->effective_work_order_no }}
                                                @else
                                                    <span class="italic text-stone-400">Awarded</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-stone-400 font-mono">-</span>
                                        @endif
                                    </td>

                                    <!-- Tender Fee -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4]">
                                        @if($t->tender_fee_amount || $t->is_tender_fee_paid)
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-mono font-bold text-stone-900">PKR {{ number_format($t->tender_fee_amount ?: 0) }}</span>
                                                @if($t->is_tender_fee_paid)
                                                    <span class="text-[9px] font-extrabold text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200">Paid ✓</span>
                                                @else
                                                    <span class="text-[9px] font-extrabold text-rose-700 bg-rose-50 px-1.5 py-0.2 rounded border border-rose-200">Unpaid ✗</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-stone-500 mt-0.5">
                                                @if($t->tenderFeeBankAccount)
                                                    <span class="font-semibold text-stone-700">A/c:</span> {{ $t->tenderFeeBankAccount->bank_name }}
                                                @elseif($t->tender_fee_payment_mode)
                                                    <span>{{ ucfirst($t->tender_fee_payment_mode) }}</span>
                                                @endif
                                                @if($t->tender_fee_ref_no)
                                                    <span>({{ $t->tender_fee_ref_no }})</span>
                                                @endif
                                            </div>
                                        @elseif($t->tender_type === 'epad')
                                            <span class="text-rose-700 font-bold text-[10px] bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">EPAD (Free)</span>
                                        @else
                                            <span class="text-stone-400 font-mono text-[11px]">No Fee</span>
                                        @endif
                                    </td>

                                    <!-- Bid Security (CDR / Pay Order) -->
                                    <td class="py-3 px-3 border-r border-[#e5ded4]">
                                        @if($t->bid_security_amount)
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-mono font-bold text-blue-900">PKR {{ number_format($t->bid_security_amount) }}</span>
                                                @if($t->bid_security_status === 'released_returned')
                                                    <span class="text-[9px] font-extrabold text-teal-700 bg-teal-50 px-1.5 py-0.2 rounded border border-teal-200">Returned</span>
                                                @else
                                                    <span class="text-[9px] font-extrabold text-blue-700 bg-blue-50 px-1.5 py-0.2 rounded border border-blue-200">Active</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-stone-500 mt-0.5">
                                                <span class="font-bold text-stone-700">{{ strtoupper($t->bid_security_type ?: 'CDR') }}:</span>
                                                <span>{{ $t->bid_security_instrument_no ?: '-' }}</span>
                                                @if($t->bidSecurityBankAccount)
                                                    <span class="block text-[10px] text-stone-500">{{ $t->bidSecurityBankAccount->bank_name }}</span>
                                                @endif
                                            </div>
                                        @elseif($t->tender_type === 'epad')
                                            <span class="text-stone-400 font-mono text-[11px]">N/A</span>
                                        @else
                                            <span class="text-stone-400 font-mono">-</span>
                                        @endif
                                    </td>

                                    <!-- Outcome -->
                                    <td class="py-3 px-3 text-center border-r border-[#e5ded4]">
                                        @if($t->isWon())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                                🏆 WON
                                            </span>
                                        @elseif($t->isLost())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-900 border border-rose-300">
                                                ✗ LOST
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-900 border border-blue-200">
                                                Active
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Post-Award Docs Status -->
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

                                    <!-- Action -->
                                    <td class="py-3 px-3 text-center print:hidden">
                                        <a href="{{ route('tenders.show', $t) }}" class="p-1.5 text-stone-600 hover:text-orange-700 hover:bg-stone-100 rounded-lg transition inline-block" title="View Tender">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="py-12 text-center text-stone-500">
                                        No tenders match the selected criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
</x-app-layout>
