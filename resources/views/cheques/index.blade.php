<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('bills.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Bills Registry</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">AGPR / Treasury Reconciliation</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Cheque Details & Deductions Registry') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Manage incoming AGPR cheques, tokens, tax deductions, GST/SST differences, overall commissions breakdown, and net department disbursements.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="openCommissionReportModal()" class="px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Commission Report
                </button>
                <button type="button" onclick="printCustomSheet('dept')" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Department Sheet
                </button>
                <button type="button" onclick="printCustomSheet('office')" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Office Sheet
                </button>
                <a href="{{ route('cheques.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    + Enter New Cheque
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @php
                $totBill = (float)($totals['bill_amount'] ?? 0);
                $totCheque = (float)($totals['cheque_amount'] ?? 0);
                $totComm = (float)($totals['xyz_commission'] ?? 0);
                $totDept = (float)($totals['amount_to_paid_department'] ?? 0);
                $chequePct = $totBill > 0 ? min(100, round(($totCheque / $totBill) * 100)) : 82;
                $commPct = $totBill > 0 ? min(100, round(($totComm / $totBill) * 100)) : 12;
                $deptPct = $totBill > 0 ? min(100, round(($totDept / $totBill) * 100)) : 70;
            @endphp

            <!-- 1. TOP 4 VIBRANT 3D METRIC CARDS WITH CANDY ICONS & CIRCULAR RING METERS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: TOTAL BILL AMOUNT (Electric Blue 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-blue">
                        <div class="stat-candy-icon candy-icon-blue">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="stat-card-label">Total Bill Amount</span>
                            <div class="text-xl sm:text-2xl font-black stat-card-value my-0.5 truncate">
                                <span class="text-xs mr-1 font-extrabold text-blue-600">Rs.</span>{{ number_format($totBill, 0) }}
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">Processed Bills</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="Total Bill Volume">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#dbeafe" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#0056d2" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#0056d2]">100%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: TOTAL CHEQUE AMOUNT (Emerald Green 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-green">
                        <div class="stat-candy-icon candy-icon-green">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke-width="2.2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke-width="2.2"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="stat-card-label">Total Cheque Amount</span>
                            <div class="text-xl sm:text-2xl font-black stat-card-value my-0.5 truncate">
                                <span class="text-xs mr-1 font-extrabold text-emerald-600">Rs.</span>{{ number_format($totCheque, 0) }}
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">Processed Cheques</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="{{ $chequePct }}% Cheques Cleared">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#d1fae5" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#0d9467" stroke-width="3.5" 
                                        stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $chequePct / 100) }}" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#0d9467]">{{ $chequePct }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: TOTAL COMMISSION (Sunset Orange 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-orange">
                        <div class="stat-candy-icon candy-icon-orange">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="stat-card-label">AGPR Commission</span>
                            <div class="text-xl sm:text-2xl font-black stat-card-value my-0.5 truncate">
                                <span class="text-xs mr-1 font-extrabold text-orange-600">Rs.</span>{{ number_format($totComm, 0) }}
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">Official deduction</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="{{ $commPct }}% Commission">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#ffedd5" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#cd3805" stroke-width="3.5" 
                                        stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $commPct / 100) }}" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#cd3805]">{{ $commPct }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: PAID TO DEPARTMENT / PROFIT (Deep Maroon 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-maroon">
                        <div class="stat-candy-icon candy-icon-maroon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="stat-card-label">Paid to Department</span>
                            <div class="text-xl sm:text-2xl font-black stat-card-value my-0.5 truncate">
                                <span class="text-xs mr-1 font-extrabold text-rose-800">Rs.</span>{{ number_format($totDept, 0) }}
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">Net Dept Payout</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="{{ $deptPct }}% Dept Payout">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#ffe4e6" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#881337" stroke-width="3.5" 
                                        stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $deptPct / 100) }}" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#881337]">{{ $deptPct }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cheque Details Statement Table Card with 2-Way Sheet Switcher -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                
                <!-- 2-WAY FRACTION TAB SWITCHER BAR -->
                <div id="sheet-tabs-nav" class="px-6 py-3.5 bg-slate-900 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex items-center gap-2 p-1 bg-slate-800/90 rounded-xl">
                        <!-- Tab 1: Department Copy -->
                        <button type="button" onclick="switchActiveSheet('dept')" id="btn-tab-dept" class="px-4 py-2 rounded-lg text-xs font-black transition flex items-center gap-2 shadow-sm bg-emerald-600 text-white">
                            <span class="w-2 h-2 rounded-full bg-emerald-200"></span>
                            <span>🏢 Department Statement Sheet (Department Copy)</span>
                        </button>

                        <!-- Tab 2: Office Copy -->
                        <button type="button" onclick="switchActiveSheet('office')" id="btn-tab-office" class="px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 text-slate-300 hover:text-white">
                            <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                            <span>💼 Office / Internal Audit Sheet (Audit & Profit)</span>
                        </button>
                    </div>

                    <!-- Quick Print Actions for Active Sheet -->
                    <div class="flex items-center gap-2 no-print">
                        <button type="button" onclick="printCustomSheet('dept')" class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-400/40 text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 shadow-xs">
                            <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>🖨️ Print Department Sheet</span>
                        </button>
                        <button type="button" onclick="printCustomSheet('office')" class="px-3 py-1.5 bg-indigo-500/20 hover:bg-indigo-500/30 text-indigo-200 border border-indigo-400/40 text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 shadow-xs">
                            <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>🖨️ Print Office Sheet</span>
                        </button>
                    </div>
                </div>

                <!-- Filter Bar (In One Simple Row) -->
                <div id="cheque-filter-bar" class="px-6 py-3.5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
                    <div>
                        <div id="active-sheet-badge" class="inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-800 uppercase tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Currently Viewing: Department Statement & AGPR Reconciliation</span>
                        </div>
                        <p id="active-sheet-desc" class="text-[11px] text-slate-500 mt-0.5">
                            Official Treasury Statement formatted for submission to the department (Margins & vendor profits hidden).
                        </p>
                    </div>

                    <form method="GET" action="{{ route('cheques.index') }}" style="display: flex; flex-direction: row; align-items: center; flex-wrap: nowrap; gap: 8px;">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Token / Cheque #..." class="text-xs rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500 focus:border-indigo-500 w-44">
                        </div>
                        @if(!empty($costCenters) && count($costCenters) > 0)
                            <div>
                                <select name="cost_center" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-300 py-1.5 font-semibold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">All Cost Centers</option>
                                    @foreach($costCenters as $cc)
                                        <option value="{{ $cc }}" {{ request('cost_center') == $cc ? 'selected' : '' }}>Cost Center: {{ $cc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition whitespace-nowrap">
                            Filter
                        </button>
                        @if(request('search') || request('cost_center'))
                            <a href="{{ route('cheques.index') }}" class="text-xs text-rose-600 hover:underline px-2 py-1 font-semibold whitespace-nowrap">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- ========================================================================= -->
                <!-- SHEET 1: DEPARTMENT STATEMENT SHEET (DEPARTMENT COPY - IMAGES 1 & 2) -->
                <!-- ========================================================================= -->
                <div id="sheet-dept" class="sheet-container">
                    <!-- Formal Department Statement Header (Visible when Printing Department Copy) -->
                    <div class="p-6 border-b border-slate-200 bg-white">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                                    {{ Auth::user()->company_name ?: 'Al-Madina Enterprises' }}
                                </h2>
                                <h3 class="text-sm font-extrabold text-emerald-800 mt-0.5 uppercase tracking-wide">
                                    DEPARTMENT STATEMENT & AGPR DEDUCTION RECONCILIATION SHEET
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Verified token reconciliation, AGPR tax deductions (Purchased & Repaired), and net departmental disbursement.
                                </p>
                            </div>
                            <div class="text-right text-xs text-slate-600 font-mono">
                                <div>Statement Date: <strong class="text-slate-900">{{ now()->format('d M, Y') }}</strong></div>
                                <div>Total Records: <strong class="text-slate-900">{{ $cheques->total() }}</strong></div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Table Container (Image 1 & 2 Layout) -->
                    <div class="overflow-x-auto" style="max-height: 540px; overflow-y: auto;">
                        <table class="w-full text-left border-collapse text-xs" style="min-width: 1560px;">
                            <thead class="bg-slate-100 border-b-2 border-slate-300 text-slate-800 uppercase font-black sticky top-0 z-10 shadow-sm text-[10.5px]">
                                <!-- Header Row 1 -->
                                <tr>
                                    <th rowspan="2" class="p-2.5 text-center w-10 border-r border-slate-200 bg-slate-100">S.#</th>
                                    <th rowspan="2" class="p-2.5 border-r border-slate-200 bg-slate-100 whitespace-nowrap">Company Name</th>
                                    <th rowspan="2" class="p-2.5 border-r border-slate-200 bg-indigo-50 text-indigo-900 font-mono whitespace-nowrap">Token #</th>
                                    <th rowspan="2" class="p-2.5 border-r border-slate-200 bg-slate-100 font-mono whitespace-nowrap">Cheque # / Doc #</th>
                                    <th rowspan="2" class="p-2.5 border-r border-slate-200 bg-slate-100 whitespace-nowrap">Cheque Date</th>
                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-blue-100/70 text-blue-950 font-mono whitespace-nowrap">Cheque Amount</th>
                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-slate-100 font-mono whitespace-nowrap">Bill Amount</th>
                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-slate-100 font-mono whitespace-nowrap">18% GST Amount</th>
                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-slate-100 font-mono whitespace-nowrap">5.5% I.Tax Amount</th>
                                    
                                    <!-- AGPR Deducted for Purchased Items (Image 2) -->
                                    <th colspan="2" class="p-2 text-center border-r border-slate-200 bg-amber-100/80 text-amber-950 font-black whitespace-nowrap">
                                        AGPR Deducted for Purchased Items
                                    </th>

                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-amber-50 text-amber-900 font-mono whitespace-nowrap">GST Diff Amount</th>
                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-slate-100 font-mono whitespace-nowrap">15% SST Amount</th>
                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-slate-100 font-mono whitespace-nowrap">15% I.Tax Amount</th>

                                    <!-- AGPR Deducted for Repaired Items (Image 2) -->
                                    <th colspan="2" class="p-2 text-center border-r border-slate-200 bg-sky-100/80 text-sky-950 font-black whitespace-nowrap">
                                        AGPR Deducted for Repaired Items
                                    </th>

                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-sky-50 text-sky-900 font-mono whitespace-nowrap">SST Diff Amount</th>
                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-amber-50/80 text-amber-950 font-mono whitespace-nowrap">Contractor Commission</th>
                                    <th rowspan="2" class="p-2.5 text-right border-r border-slate-200 bg-rose-100/60 text-rose-950 font-mono whitespace-nowrap">Total Deduction Amount</th>
                                    <th rowspan="2" class="p-2.5 text-right bg-amber-200 text-amber-950 font-mono font-black whitespace-nowrap" style="background-color: #fef08a;">Amount to be Paid Dept</th>
                                </tr>
                                <!-- Header Row 2 (Sub-columns for AGPR Purchases & Repairs) -->
                                <tr>
                                    <th class="p-2 text-right border-r border-slate-200 bg-amber-50 text-amber-900 font-mono whitespace-nowrap">GST (20%)</th>
                                    <th class="p-2 text-right border-r border-slate-200 bg-amber-50 text-amber-900 font-mono whitespace-nowrap">I.Tax 5.5%</th>
                                    <th class="p-2 text-right border-r border-slate-200 bg-sky-50 text-sky-900 font-mono whitespace-nowrap">SST (20%)</th>
                                    <th class="p-2 text-right border-r border-slate-200 bg-sky-50 text-sky-900 font-mono whitespace-nowrap">I.Tax</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans">
                                @forelse($cheques as $index => $c)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="p-2.5 text-center font-bold text-slate-400 border-r border-slate-100">
                                            {{ $cheques->firstItem() + $index }}
                                        </td>
                                        <td class="p-2.5 font-bold text-slate-900 whitespace-nowrap border-r border-slate-100">
                                            {{ $c->company_name }}
                                        </td>
                                        <td class="p-2.5 font-mono font-bold text-indigo-700 bg-indigo-50/30 whitespace-nowrap border-r border-slate-100">
                                            {{ $c->token_number }}
                                        </td>
                                        <td class="p-2.5 font-mono font-bold text-slate-800 whitespace-nowrap border-r border-slate-100">
                                            {{ $c->document_number }}
                                        </td>
                                        <td class="p-2.5 text-slate-600 whitespace-nowrap border-r border-slate-100">
                                            {{ $c->cheque_date->format('d-M-y') }}
                                        </td>
                                        <td class="p-2.5 text-right font-black text-blue-800 font-mono bg-blue-50/30 border-r border-slate-100 whitespace-nowrap">
                                            {{ number_format($c->cheque_amount) }}
                                        </td>
                                        <td class="p-2.5 text-right font-bold text-slate-700 font-mono border-r border-slate-100 whitespace-nowrap">
                                            {{ number_format($c->bill_amount) }}
                                        </td>
                                        <td class="p-2.5 text-right font-mono text-slate-600 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->gst_amount > 0 ? number_format($c->gst_amount) : '-' }}
                                        </td>
                                        <td class="p-2.5 text-right font-mono text-slate-600 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->itax_5_5_amount > 0 ? number_format($c->itax_5_5_amount) : '-' }}
                                        </td>
                                        
                                        <!-- AGPR Purchased Sub-values -->
                                        <td class="p-2.5 text-right font-mono text-amber-900 bg-amber-50/20 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->agpr_purchased_gst > 0 ? number_format($c->agpr_purchased_gst) : '-' }}
                                        </td>
                                        <td class="p-2.5 text-right font-mono text-amber-900 bg-amber-50/20 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->agpr_purchased_itax > 0 ? number_format($c->agpr_purchased_itax) : '-' }}
                                        </td>
                                        <td class="p-2.5 text-right font-mono text-slate-600 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->vendor_gst_difference > 0 ? number_format($c->vendor_gst_difference) : '-' }}
                                        </td>
                                        <td class="p-2.5 text-right font-mono text-slate-600 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->sst_amount > 0 ? number_format($c->sst_amount) : '-' }}
                                        </td>
                                        <td class="p-2.5 text-right font-mono text-slate-600 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->itax_15_amount > 0 ? number_format($c->itax_15_amount) : '-' }}
                                        </td>

                                        <!-- AGPR Repaired Sub-values -->
                                        <td class="p-2.5 text-right font-mono text-sky-900 bg-sky-50/20 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->agpr_repaired_sst > 0 ? number_format($c->agpr_repaired_sst) : '-' }}
                                        </td>
                                        <td class="p-2.5 text-right font-mono text-sky-900 bg-sky-50/20 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->agpr_repaired_itax > 0 ? number_format($c->agpr_repaired_itax) : '-' }}
                                        </td>
                                        <td class="p-2.5 text-right font-mono text-slate-600 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->sst_difference > 0 ? number_format($c->sst_difference) : '-' }}
                                        </td>

                                        <!-- Contractor Commission (No internal split shown to Department) -->
                                        <td class="p-2.5 text-right font-mono font-bold text-amber-900 bg-amber-50/30 border-r border-slate-100 whitespace-nowrap">
                                            {{ $c->xyz_commission > 0 ? number_format($c->xyz_commission) : '-' }}
                                        </td>
                                        <td class="p-2.5 text-right font-bold text-rose-700 font-mono bg-rose-50/20 border-r border-slate-100 whitespace-nowrap">
                                            {{ number_format($c->total_deduction) }}
                                        </td>
                                        <td class="p-2.5 text-right font-black text-slate-950 font-mono whitespace-nowrap" style="background-color: #fef9c3;">
                                            {{ number_format($c->amount_to_paid_department) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="19" class="p-8 text-center text-slate-400">
                                            No cheque records found for Department Statement.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <!-- Department Grand Totals Row -->
                            <tfoot class="bg-slate-100 border-t-2 border-slate-300 font-bold text-xs text-slate-900 uppercase tracking-wider">
                                <tr>
                                    <td colspan="5" class="p-2.5 text-slate-900 text-right font-black border-r border-slate-200">
                                        Department Grand Total:
                                    </td>
                                    <td class="p-2.5 text-right font-mono font-black text-blue-900 text-sm bg-blue-100/60 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['cheque_amount']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono font-black text-slate-900 text-sm border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['bill_amount']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-slate-700 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['gst_amount']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-slate-700 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['itax_5_5_amount']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-amber-950 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['agpr_purchased_gst']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-amber-950 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['agpr_purchased_itax']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-slate-700 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['vendor_gst_difference']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-slate-700 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['sst_amount']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-slate-700 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['itax_15_amount']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-sky-950 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['agpr_repaired_sst']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-sky-950 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['agpr_repaired_itax']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono text-slate-700 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['sst_difference']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono font-black text-amber-950 text-sm bg-amber-100/60 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['xyz_commission']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono font-black text-rose-700 text-sm bg-rose-100/60 border-r border-slate-200 whitespace-nowrap">
                                        {{ number_format($totals['total_deduction']) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono font-black text-slate-950 text-sm whitespace-nowrap" style="background-color: #fde047;">
                                        {{ number_format($totals['amount_to_paid_department']) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Department Print Signature Blocks -->
                    <div class="p-6 bg-slate-50 border-t border-slate-200 grid grid-cols-3 gap-6 text-center text-xs text-slate-600">
                        <div class="border-t-2 border-slate-300 pt-3">
                            <span class="font-extrabold text-slate-800 uppercase tracking-wide">Prepared By</span>
                            <div class="text-[11px] text-slate-500 mt-0.5">Accountant / Treasury Data Operator</div>
                        </div>
                        <div class="border-t-2 border-slate-300 pt-3">
                            <span class="font-extrabold text-slate-800 uppercase tracking-wide">Checked & Verified By</span>
                            <div class="text-[11px] text-slate-500 mt-0.5">Accounts Officer / Section Head</div>
                        </div>
                        <div class="border-t-2 border-slate-300 pt-3">
                            <span class="font-extrabold text-slate-800 uppercase tracking-wide">Authorized Department Officer</span>
                            <div class="text-[11px] text-slate-500 mt-0.5">Director / Treasury Reconciliation Authority</div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- SHEET 2: OFFICE INTERNAL AUDIT SHEET (INTERNAL AUDIT COPY - IMAGE 3) -->
                <!-- ========================================================================= -->
                <div id="sheet-office" class="sheet-container hidden">
                    <!-- Office Confidential Audit Header (Visible when Printing Office Copy) -->
                    <div class="p-6 border-b border-slate-200 bg-white">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                                    {{ Auth::user()->company_name ?: 'ProWave Technologies Management' }}
                                </h2>
                                <h3 class="text-sm font-extrabold text-indigo-900 mt-0.5 uppercase tracking-wide flex items-center gap-2">
                                    <span>OFFICE INTERNAL AUDIT & RECONCILIATION SHEET</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">INTERNAL & CONFIDENTIAL</span>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Complete internal audit with Cost Center, Our vs Other Commission breakdown, and Vendor Total Profit.
                                </p>
                            </div>
                            <div class="text-right text-xs text-slate-600 font-mono">
                                <div>Audit Date: <strong class="text-slate-900">{{ now()->format('d M, Y') }}</strong></div>
                                <div>Total Records: <strong class="text-slate-900">{{ $cheques->total() }}</strong></div>
                            </div>
                        </div>
                    </div>

                    <!-- Office Table Container (Image 3 Layout) -->
                    <div class="overflow-x-auto" style="max-height: 540px; overflow-y: auto;">
                        <table class="w-full text-left border-collapse text-xs" style="min-width: 1480px;">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold sticky top-0 z-10 shadow-sm text-[11px]">
                                <tr>
                                    <th class="p-3 text-center w-12 bg-slate-50">S.#</th>
                                    <th class="p-3 bg-slate-50 whitespace-nowrap">Cost Center</th>
                                    <th class="p-3 bg-slate-50">Department Name</th>
                                    <th class="p-3 bg-slate-50 whitespace-nowrap">Company Name</th>
                                    <th class="p-3 bg-slate-50 font-mono text-indigo-700 whitespace-nowrap">Token #</th>
                                    <th class="p-3 bg-slate-50 font-mono whitespace-nowrap">Cheque # / Doc #</th>
                                    <th class="p-3 bg-slate-50 whitespace-nowrap">Cheque Date</th>
                                    <th class="p-3 text-right bg-blue-50 text-blue-900 font-mono whitespace-nowrap">Cheque Amount</th>
                                    <th class="p-3 text-right bg-slate-50 font-mono whitespace-nowrap">Bill Amount</th>
                                    <th class="p-3 text-right bg-slate-50 font-mono whitespace-nowrap">18% GST</th>
                                    <th class="p-3 text-right bg-slate-50 font-mono whitespace-nowrap">I.Tax Amount</th>
                                    <th class="p-3 text-right bg-slate-50 font-mono whitespace-nowrap">15% SST</th>
                                    <th class="p-3 text-right bg-slate-50 font-mono whitespace-nowrap">Diff Amount</th>
                                    <th class="p-3 text-right bg-amber-50/70 text-amber-900 font-mono whitespace-nowrap" title="Click to view recipient % and PKR breakdown">
                                        Overall Commission
                                    </th>
                                    <th class="p-3 text-right bg-rose-50 text-rose-900 font-mono whitespace-nowrap">Total Deduction</th>
                                    <th class="p-3 text-right bg-amber-100 text-amber-950 font-mono whitespace-nowrap" style="background-color: #fef08a;">Amount to be Paid Dept</th>
                                    <th class="p-3 text-right bg-emerald-50 text-emerald-900 font-mono whitespace-nowrap">Vendor Profit</th>
                                    <th class="p-3 text-center bg-slate-50 w-16 no-print">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans">
                                @forelse($cheques as $index => $c)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="p-3 text-center font-bold text-slate-400">
                                            {{ $cheques->firstItem() + $index }}
                                        </td>
                                        <td class="p-3 font-semibold text-slate-700 whitespace-nowrap">
                                            {{ $c->cost_center ?: 'KA XYZ' }}
                                        </td>
                                        <td class="p-3 font-medium text-slate-800">
                                            {{ $c->department_name ?: ($c->department ? $c->department->name : '-') }}
                                        </td>
                                        <td class="p-3 font-bold text-slate-900 whitespace-nowrap">
                                            {{ $c->company_name }}
                                        </td>
                                        <td class="p-3 font-mono font-bold text-indigo-700 bg-indigo-50/40">
                                            {{ $c->token_number }}
                                        </td>
                                        <td class="p-3 font-mono font-bold text-slate-800">
                                            {{ $c->document_number }}
                                        </td>
                                        <td class="p-3 text-slate-600 whitespace-nowrap">
                                            {{ $c->cheque_date->format('d-M-y') }}
                                        </td>
                                        <td class="p-3 text-right font-black text-blue-700 font-mono bg-blue-50/30 whitespace-nowrap">
                                            {{ number_format($c->cheque_amount) }}
                                        </td>
                                        <td class="p-3 text-right font-bold text-slate-700 font-mono whitespace-nowrap">
                                            {{ number_format($c->bill_amount) }}
                                        </td>
                                        <td class="p-3 text-right font-mono text-slate-600 whitespace-nowrap">
                                            {{ $c->gst_amount > 0 ? number_format($c->gst_amount) : '-' }}
                                        </td>
                                        <td class="p-3 text-right font-mono text-slate-600 whitespace-nowrap">
                                            {{ $c->itax_amount > 0 ? number_format($c->itax_amount) : '-' }}
                                        </td>
                                        <td class="p-3 text-right font-mono text-slate-600 whitespace-nowrap">
                                            {{ $c->sst_amount > 0 ? number_format($c->sst_amount) : '-' }}
                                        </td>
                                        <td class="p-3 text-right font-mono text-slate-600 whitespace-nowrap">
                                            @php
                                                $diff = $c->vendor_gst_difference + $c->sst_difference;
                                            @endphp
                                            {{ $diff > 0 ? number_format($diff) : '-' }}
                                        </td>

                                        <!-- Overall Commission with Breakdown Modal Trigger -->
                                        <td class="p-3 text-right font-mono bg-amber-50/30 whitespace-nowrap">
                                            @if($c->xyz_commission > 0)
                                                <button type="button" 
                                                        onclick="viewCommissionBreakdown('{{ $c->document_number }}', '{{ addslashes($c->company_name) }}', {{ json_encode($c->commission_breakdown ?: [['type' => ($c->our_commission > 0 && $c->other_commission == 0 ? 'our' : 'other'), 'title' => 'Commission', 'percentage' => round(($c->cheque_amount > 0 ? ($c->xyz_commission / $c->cheque_amount) * 100 : 0), 2), 'amount' => (float)$c->xyz_commission]]) }}, '{{ number_format($c->xyz_commission, 2) }}', '{{ number_format($c->cheque_amount, 2) }}', '{{ number_format($c->our_commission, 2) }}', '{{ number_format($c->other_commission, 2) }}')" 
                                                        class="font-black text-amber-800 hover:text-indigo-600 underline decoration-dotted inline-flex items-center gap-1 group transition"
                                                        title="Click to view Commission Breakdown (% with PKR)">
                                                    <span>{{ number_format($c->xyz_commission) }}</span>
                                                    <svg class="w-3.5 h-3.5 text-amber-600 opacity-60 group-hover:opacity-100 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </button>
                                                <div class="text-[9.5px] font-semibold text-slate-500 whitespace-nowrap mt-0.5">
                                                    @if($c->our_commission > 0)<span class="text-blue-700 font-bold">Our: {{ number_format($c->our_commission) }}</span>@endif
                                                    @if($c->our_commission > 0 && $c->other_commission > 0)<span class="text-slate-300"> | </span>@endif
                                                    @if($c->other_commission > 0)<span class="text-amber-800 font-bold">Oth: {{ number_format($c->other_commission) }}</span>@endif
                                                </div>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>

                                        <td class="p-3 text-right font-bold text-rose-600 font-mono bg-rose-50/20 whitespace-nowrap">
                                            {{ number_format($c->total_deduction) }}
                                        </td>
                                        <td class="p-3 text-right font-black text-slate-900 font-mono whitespace-nowrap" style="background-color: #fef9c3;">
                                            {{ number_format($c->amount_to_paid_department) }}
                                        </td>
                                        <td class="p-3 text-right font-black text-emerald-800 font-mono bg-emerald-50/50 whitespace-nowrap">
                                            {{ $c->vendor_profit > 0 ? number_format($c->vendor_profit) : '-' }}
                                        </td>
                                        <td class="p-3 text-center no-print whitespace-nowrap">
                                            @if(Auth::user()->isAdmin())
                                                <form method="POST" action="{{ route('cheques.destroy', $c) }}" onsubmit="return confirm('Remove cheque record #{{ $c->document_number }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold text-xs" title="Delete Cheque">
                                                        &times;
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="18" class="p-8 text-center text-slate-400">
                                            No cheque records found. Click "+ Enter New Cheque" to record incoming cheques.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <!-- Office Grand Consolidated Totals Row -->
                            <tfoot class="bg-slate-100 border-t-2 border-slate-300 font-bold text-xs text-slate-900 uppercase tracking-wider">
                                <tr>
                                    <td colspan="7" class="p-3 text-slate-900 text-right font-extrabold whitespace-nowrap">
                                        Office Grand Total:
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-blue-800 text-sm bg-blue-100/50 whitespace-nowrap">
                                        {{ number_format($totals['cheque_amount']) }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-slate-900 text-sm whitespace-nowrap">
                                        {{ number_format($totals['bill_amount']) }}
                                    </td>
                                    <td class="p-3 text-right font-mono text-slate-700 whitespace-nowrap">
                                        {{ number_format($totals['gst_amount']) }}
                                    </td>
                                    <td class="p-3 text-right font-mono text-slate-700 whitespace-nowrap">
                                        {{ number_format($totals['itax_amount']) }}
                                    </td>
                                    <td class="p-3 text-right font-mono text-slate-700 whitespace-nowrap">
                                        {{ number_format($totals['sst_amount']) }}
                                    </td>
                                    <td class="p-3 text-right font-mono text-slate-700 whitespace-nowrap">
                                        {{ number_format($totals['vendor_gst_difference'] + $totals['sst_difference']) }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-amber-800 text-sm bg-amber-100/50 whitespace-nowrap">
                                        <div>{{ number_format($totals['xyz_commission']) }}</div>
                                        <div class="text-[9.5px] font-bold text-slate-600 whitespace-nowrap mt-0.5">
                                            <span class="text-blue-800">Our: {{ number_format($totals['our_commission']) }}</span> | 
                                            <span class="text-amber-900">Oth: {{ number_format($totals['other_commission']) }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-rose-700 text-sm bg-rose-100/50 whitespace-nowrap">
                                        {{ number_format($totals['total_deduction']) }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-slate-950 text-sm whitespace-nowrap" style="background-color: #fde047;">
                                        {{ number_format($totals['amount_to_paid_department']) }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-emerald-800 text-sm bg-emerald-100/50 whitespace-nowrap">
                                        {{ number_format($totals['vendor_profit']) }}
                                    </td>
                                    <td class="p-3 no-print"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Internal Audit Signatures -->
                    <div class="p-6 bg-slate-50 border-t border-slate-200 grid grid-cols-3 gap-6 text-center text-xs text-slate-600">
                        <div class="border-t-2 border-slate-300 pt-3">
                            <span class="font-extrabold text-slate-800 uppercase tracking-wide">Prepared By</span>
                            <div class="text-[11px] text-slate-500 mt-0.5">Accounts Officer</div>
                        </div>
                        <div class="border-t-2 border-slate-300 pt-3">
                            <span class="font-extrabold text-slate-800 uppercase tracking-wide">Reviewed By</span>
                            <div class="text-[11px] text-slate-500 mt-0.5">Chief Financial Officer / Audit Lead</div>
                        </div>
                        <div class="border-t-2 border-slate-300 pt-3">
                            <span class="font-extrabold text-slate-800 uppercase tracking-wide">Approved By</span>
                            <div class="text-[11px] text-slate-500 mt-0.5">Managing Director / CEO</div>
                        </div>
                    </div>
                </div>

                @if($cheques->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 no-print">
                        {{ $cheques->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- LARGE MODAL: Enter Cheque (With Multiple Commission Titles & % / PKR) -->
    <div id="cheque-modal-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all max-h-[92vh] flex flex-col">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                <div>
                    <h4 class="font-extrabold text-base">Enter Cheque & Token Details</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Record received cheque, token number, commission titles (% / PKR), and deductions</p>
                </div>
                <button type="button" onclick="closeChequeModal()" class="text-slate-400 hover:text-white text-xl font-bold leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('cheques.store') }}" class="p-6 space-y-5 overflow-y-auto" id="cheque-form">
                @csrf

                <!-- Optional: Link to Existing Bill -->
                @if($pendingBills->isNotEmpty())
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Optional: Select Unpaid Bill to Autofill</label>
                        <select id="bill_selector" name="bill_id" onchange="autoFillFromBill(this)" class="w-full text-xs rounded-lg border-slate-300">
                            <option value="">-- Manual Entry (Or select a bill to auto-populate) --</option>
                            @foreach($pendingBills as $pb)
                                <option value="{{ $pb->id }}" 
                                        data-amount="{{ $pb->net_amount }}"
                                        data-company="{{ $pb->vendor ? ($pb->vendor->company_name ?: $pb->vendor->name) : 'Department Direct' }}"
                                        data-dept-id="{{ $pb->department_id }}"
                                        data-dept-name="{{ $pb->department ? $pb->department->name : '' }}"
                                        data-cost-center="{{ $pb->department ? ($pb->department->cost_center_description ?: $pb->department->code) : '' }}">
                                    [{{ $pb->bill_number }}] {{ $pb->department ? $pb->department->code : '' }} — PKR {{ number_format($pb->net_amount) }} ({{ $pb->milestone_description }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Section 1: Identifiers -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Token Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="token_number" placeholder="e.g. 023359" required class="w-full text-xs font-bold rounded-lg border-slate-300 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Cheque # / Document # <span class="text-rose-500">*</span></label>
                        <input type="text" name="document_number" placeholder="e.g. 1500157809" required class="w-full text-xs font-bold rounded-lg border-slate-300 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Cheque Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="cheque_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-lg border-slate-300">
                    </div>
                </div>

                <!-- Section 2: Department, Cost Center, Company -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Company Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="company_name" id="modal_company_name" placeholder="e.g. Al-Mehran Gasoline" required class="w-full text-xs rounded-lg border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Cost Center</label>
                        <input type="text" name="cost_center" id="modal_cost_center" value="KA XYZ" placeholder="e.g. KA XYZ or 1234" class="w-full text-xs rounded-lg border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Department</label>
                        <select name="department_id" id="modal_dept_id" class="w-full text-xs rounded-lg border-slate-300">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" data-cost-center="{{ $dept->cost_center_description ?: $dept->code }}">
                                    [{{ $dept->code }}] {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Section 3: Cheque Amount, Bill Amount -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                    <div>
                        <label class="block text-xs font-bold text-blue-900 mb-1">Cheque Amount (PKR) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="cheque_amount" id="modal_cheque_amt" placeholder="e.g. 61600" required oninput="calcChequeDeductions()" class="w-full text-sm font-black rounded-lg border-slate-300 text-blue-700 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Bill Amount (PKR) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="bill_amount" id="modal_bill_amt" placeholder="e.g. 61600" required oninput="calcChequeDeductions()" class="w-full text-sm font-bold rounded-lg border-slate-300 font-mono">
                    </div>
                </div>

                <!-- Section 4: AGPR Deductions Breakdown (Purchased & Repaired) -->
                <div class="space-y-3 border-t border-slate-100 pt-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-black text-slate-800 uppercase tracking-wider">AGPR Tax Deductions (Treasury Format)</span>
                        <span class="text-[10px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Purchased 20% GST / Repaired 20% SST</span>
                    </div>

                    <!-- AGPR Purchased Items -->
                    <div class="p-3 bg-amber-50/40 rounded-xl border border-amber-200 space-y-1.5">
                        <div class="text-[10.5px] font-bold text-amber-950 uppercase flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                            AGPR Deducted for Purchased Items
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2.5">
                            <div>
                                <label class="block text-[10.5px] font-semibold text-slate-600 mb-1">18% GST</label>
                                <input type="number" step="0.01" name="gst_amount" id="modal_gst" value="0" oninput="onModalAgprPurchasedChange()" class="w-full text-xs rounded-lg border-slate-300 py-1 font-mono">
                            </div>
                            <div>
                                <label class="block text-[10.5px] font-bold text-amber-900 mb-1">AGPR GST (20%)</label>
                                <input type="number" step="0.01" name="agpr_purchased_gst" id="modal_agpr_purchased_gst" value="0" oninput="onModalAgprPurchasedChange()" class="w-full text-xs font-bold rounded-lg border-amber-300 py-1 font-mono text-amber-900">
                            </div>
                            <div>
                                <label class="block text-[10.5px] font-semibold text-slate-600 mb-1">5.5% I.Tax</label>
                                <input type="number" step="0.01" name="itax_5_5_amount" id="modal_itax_5_5" value="0" oninput="onModalItaxChange()" class="w-full text-xs rounded-lg border-slate-300 py-1 font-mono">
                            </div>
                            <div>
                                <label class="block text-[10.5px] font-bold text-amber-900 mb-1">AGPR 5.5% I.Tax</label>
                                <input type="number" step="0.01" name="agpr_purchased_itax" id="modal_agpr_purchased_itax" value="0" oninput="calcChequeDeductions()" class="w-full text-xs font-bold rounded-lg border-amber-300 py-1 font-mono text-amber-900">
                            </div>
                            <div>
                                <label class="block text-[10.5px] font-black text-rose-800 mb-1">GST Diff</label>
                                <input type="number" step="0.01" name="vendor_gst_difference" id="modal_gst_diff" value="0" oninput="calcChequeDeductions()" class="w-full text-xs font-black rounded-lg border-rose-300 bg-white text-rose-800 py-1 font-mono">
                            </div>
                        </div>
                    </div>

                    <!-- AGPR Repaired Items -->
                    <div class="p-3 bg-sky-50/40 rounded-xl border border-sky-200 space-y-1.5">
                        <div class="text-[10.5px] font-bold text-sky-950 uppercase flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-600"></span>
                            AGPR Deducted for Repaired Items
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2.5">
                            <div>
                                <label class="block text-[10.5px] font-semibold text-slate-600 mb-1">15% SST</label>
                                <input type="number" step="0.01" name="sst_amount" id="modal_sst" value="0" oninput="onModalAgprRepairedChange()" class="w-full text-xs rounded-lg border-slate-300 py-1 font-mono">
                            </div>
                            <div>
                                <label class="block text-[10.5px] font-bold text-sky-900 mb-1">AGPR SST (20%)</label>
                                <input type="number" step="0.01" name="agpr_repaired_sst" id="modal_agpr_repaired_sst" value="0" oninput="onModalAgprRepairedChange()" class="w-full text-xs font-bold rounded-lg border-sky-300 py-1 font-mono text-sky-900">
                            </div>
                            <div>
                                <label class="block text-[10.5px] font-semibold text-slate-600 mb-1">15% I.Tax</label>
                                <input type="number" step="0.01" name="itax_15_amount" id="modal_itax_15" value="0" oninput="onModalItaxChange()" class="w-full text-xs rounded-lg border-slate-300 py-1 font-mono">
                            </div>
                            <div>
                                <label class="block text-[10.5px] font-bold text-sky-900 mb-1">AGPR Repaired I.Tax</label>
                                <input type="number" step="0.01" name="agpr_repaired_itax" id="modal_agpr_repaired_itax" value="0" oninput="calcChequeDeductions()" class="w-full text-xs font-bold rounded-lg border-sky-300 py-1 font-mono text-sky-900">
                            </div>
                            <div>
                                <label class="block text-[10.5px] font-black text-sky-900 mb-1">SST Diff</label>
                                <input type="number" step="0.01" name="sst_difference" id="modal_sst_diff" value="0" oninput="calcChequeDeductions()" class="w-full text-xs font-black rounded-lg border-sky-300 bg-white text-sky-900 py-1 font-mono">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden or synced I.Tax total -->
                <input type="hidden" name="itax_amount" id="modal_itax" value="0">

                <!-- Section 5: Multiple Commissions Breakdown (Our Commission vs Other Commission) -->
                <div class="space-y-3 border-t border-slate-100 pt-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Commissions Breakdown (Our vs Other)</div>
                            <p class="text-[11px] text-slate-400">Specify whether each commission belongs to our company or other beneficiaries</p>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button type="button" onclick="addCommissionRow('our')" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-900 text-[11px] font-bold rounded-lg border border-blue-200 transition flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                + Our Commission
                            </button>
                            <button type="button" onclick="addCommissionRow('other')" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-900 text-[11px] font-bold rounded-lg border border-amber-200 transition flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                + Other Commission
                            </button>
                        </div>
                    </div>

                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-amber-50/60 border-b border-amber-100 text-amber-950 font-bold uppercase text-[10px]">
                                <tr>
                                    <th class="p-2.5 w-8 text-center">#</th>
                                    <th class="p-2.5 w-40">Commission Option</th>
                                    <th class="p-2.5">Title / Beneficiary Name</th>
                                    <th class="p-2.5 w-24 text-center">Rate (%)</th>
                                    <th class="p-2.5 w-32 text-right">Commission (PKR)</th>
                                    <th class="p-2.5 w-8 text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="commission-rows-body" class="divide-y divide-slate-100">
                                <!-- Default Commission Row 1 -->
                                <tr class="commission-row hover:bg-slate-50/50 transition">
                                    <td class="p-2.5 text-center font-bold text-slate-400 comm-num">1</td>
                                    <td class="p-2.5">
                                        <select name="commissions[0][type]" onchange="onCommTypeChange(this)" class="comm-type w-full text-[11px] font-bold rounded-lg border-slate-200 py-1 focus:border-amber-500 focus:ring-amber-500 text-slate-800 bg-white">
                                            <option value="our" selected>🔵 Our Commission</option>
                                            <option value="other">🟠 Other Commission</option>
                                        </select>
                                    </td>
                                    <td class="p-2.5">
                                        <input type="text" name="commissions[0][title]" value="Our Commission" placeholder="e.g. Our Commission, Office Share..." class="comm-title w-full text-xs rounded-lg border-slate-200 py-1 focus:border-amber-500 focus:ring-amber-500 font-medium">
                                    </td>
                                    <td class="p-2.5">
                                        <div class="flex items-center gap-1">
                                            <input type="number" step="0.01" min="0" max="100" name="commissions[0][percentage]" value="" placeholder="e.g. 5" oninput="calcCommRowFromPercent(this)" class="w-full text-xs rounded-lg border-slate-200 py-1 text-center font-bold comm-pct font-mono">
                                            <span class="text-slate-400 font-bold">%</span>
                                        </div>
                                    </td>
                                    <td class="p-2.5 text-right">
                                        <input type="number" step="0.01" min="0" name="commissions[0][amount]" value="0" placeholder="0.00" oninput="calcCommRowFromAmount(this)" class="w-full text-xs rounded-lg border-slate-200 py-1 text-right font-black comm-amt font-mono text-amber-900">
                                    </td>
                                    <td class="p-2.5 text-center">
                                        <button type="button" onclick="removeCommissionRow(this)" class="text-slate-300 hover:text-rose-500 font-bold text-sm" title="Remove commission">&times;</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-amber-50/40 border-t border-amber-100 font-bold text-xs">
                                <tr>
                                    <td colspan="4" class="p-2.5 text-right">
                                        <div class="flex items-center justify-end gap-2 text-[11px]">
                                            <span class="text-blue-800 font-bold">Our: <strong id="modal_subtotal_our" class="font-mono">PKR 0.00</strong></span>
                                            <span class="text-slate-300">|</span>
                                            <span class="text-amber-900 font-bold">Other: <strong id="modal_subtotal_other" class="font-mono">PKR 0.00</strong></span>
                                            <span class="text-slate-700 uppercase ms-2">Total:</span>
                                        </div>
                                    </td>
                                    <td class="p-2.5 text-right font-mono font-black text-amber-900 text-xs" id="total_commission_display">
                                        PKR 0.00
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Hidden field holding sum for compatibility -->
                <input type="hidden" name="xyz_commission" id="modal_commission_total" value="0">
                <input type="hidden" name="our_commission" id="modal_our_commission" value="0">
                <input type="hidden" name="other_commission" id="modal_other_commission" value="0">

                <!-- Section 6: Deductions & Net Paid to Department -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-amber-50/50 rounded-xl border border-amber-200">
                    <div>
                        <label class="block text-xs font-bold text-rose-900 mb-1">Total Deduction (PKR)</label>
                        <input type="number" step="0.01" name="total_deduction" id="modal_total_deduction" value="0" readonly class="w-full text-sm font-black rounded-lg border-slate-200 bg-slate-100 font-mono text-rose-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-amber-950 mb-1">Amount to be Paid Department (PKR)</label>
                        <input type="number" step="0.01" name="amount_to_paid_department" id="modal_paid_dept" value="0" readonly class="w-full text-sm font-black rounded-lg border-amber-300 bg-amber-100/70 font-mono text-amber-950">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeChequeModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                        Confirm & Save Cheque Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- POPUP MODAL: View Commission Breakdown (% with PKR) -->
    <div id="commission-view-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="px-6 py-4 bg-amber-800 text-white flex items-center justify-between">
                <div>
                    <h4 class="font-extrabold text-base flex items-center gap-2">
                        <span>Commission Breakdown (Our vs Other)</span>
                    </h4>
                    <p class="text-xs text-amber-200 mt-0.5" id="view-comm-sub">
                        Recipient details, percentage rates, and PKR disbursements
                    </p>
                </div>
                <button type="button" onclick="closeCommissionViewModal()" class="text-amber-200 hover:text-white text-xl font-bold leading-none">&times;</button>
            </div>

            <div class="p-6 space-y-4" id="view-comm-print-area">
                <!-- Cheque summary badges -->
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-slate-500 font-medium">Cheque / Document #:</span>
                        <strong class="font-mono text-slate-800 ml-1" id="view-comm-doc">1500157809</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium">Cheque Amount:</span>
                        <strong class="font-mono text-indigo-700 ml-1" id="view-comm-cheque-amt">PKR 0.00</strong>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-bold text-[10px]">
                            <tr>
                                <th class="p-3 w-10 text-center">#</th>
                                <th class="p-3 w-32">Option</th>
                                <th class="p-3">Beneficiary / Title</th>
                                <th class="p-3 w-24 text-center">Rate (%)</th>
                                <th class="p-3 w-32 text-right">Amount (PKR)</th>
                            </tr>
                        </thead>
                        <tbody id="view-comm-tbody" class="divide-y divide-slate-100 font-sans">
                            <!-- Injected via JavaScript -->
                        </tbody>
                        <tfoot class="bg-amber-50/70 border-t-2 border-amber-200 font-bold text-xs">
                            <tr class="border-b border-amber-100/60">
                                <td colspan="4" class="p-2.5 text-right text-blue-900 font-bold">
                                    🔵 Total Our Commission:
                                </td>
                                <td class="p-2.5 text-right font-mono font-black text-blue-900" id="view-comm-our-subtotal">
                                    PKR 0.00
                                </td>
                            </tr>
                            <tr class="border-b border-amber-100/60">
                                <td colspan="4" class="p-2.5 text-right text-amber-900 font-bold">
                                    🟠 Total Other Commission:
                                </td>
                                <td class="p-2.5 text-right font-mono font-black text-amber-900" id="view-comm-other-subtotal">
                                    PKR 0.00
                                </td>
                            </tr>
                            <tr class="bg-amber-100/80">
                                <td colspan="4" class="p-3 text-amber-950 uppercase tracking-wider text-right font-black">
                                    Total Overall Commission:
                                </td>
                                <td class="p-3 text-right font-mono font-black text-amber-950 text-sm" id="view-comm-total">
                                    PKR 0.00
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="button" onclick="printCommissionVoucher()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition inline-flex items-center gap-1.5 shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print Voucher
                    </button>
                    <button type="button" onclick="closeCommissionViewModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- POPUP MODAL: Comprehensive Commission Distribution Report (Our vs Other) -->
    <div id="commission-report-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] shadow-2xl border border-slate-200 overflow-hidden flex flex-col transform transition-all">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-400/40 flex items-center justify-center text-amber-400 font-bold text-sm">
                        📊
                    </div>
                    <div>
                        <h4 class="font-extrabold text-base flex items-center gap-2">
                            <span>Commission Distribution Report (Our vs Other)</span>
                        </h4>
                        <p class="text-xs text-slate-300 mt-0.5">
                            Audit of company commission vs third-party/beneficiary shares across all cheques
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="printCommissionReport()" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-lg transition inline-flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print Report
                    </button>
                    <button type="button" onclick="closeCommissionReportModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none p-1">&times;</button>
                </div>
            </div>

            <!-- Report Body (Scrollable & Printable) -->
            <div class="p-6 overflow-y-auto space-y-5" id="printable-commission-report">
                <!-- Formal Report Header (Visible on screen and print) -->
                <div class="border-b border-slate-200 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                            {{ Auth::user()->company_name ?: 'ProWave Technologies Management' }}
                        </h2>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5">
                            Executive Commission Statement & Beneficiary Distribution Audit
                        </p>
                    </div>
                    <div class="text-right text-xs text-slate-500 font-mono">
                        <div>Report Date: <strong class="text-slate-800">{{ now()->format('d M, Y - h:i A') }}</strong></div>
                        <div>Generated By: <strong class="text-slate-800">{{ Auth::user()->name }}</strong></div>
                    </div>
                </div>

                <!-- 3 Consolidated KPI Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- 1. Our Commission -->
                    <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider">🔵 Our Commission</span>
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        </div>
                        <div class="text-xl font-black text-blue-950 font-mono mt-1">
                            PKR {{ number_format($totals['our_commission']) }}
                        </div>
                        <p class="text-[10px] text-blue-700/90 mt-1">Total company retained commission</p>
                    </div>

                    <!-- 2. Other Commission -->
                    <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">🟠 Other Commission</span>
                            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                        </div>
                        <div class="text-xl font-black text-amber-950 font-mono mt-1">
                            PKR {{ number_format($totals['other_commission']) }}
                        </div>
                        <p class="text-[10px] text-amber-800/90 mt-1">Total distributed to third-party recipients</p>
                    </div>

                    <!-- 3. Grand Total Commission -->
                    <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">💰 Grand Total Commission</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        </div>
                        <div class="text-xl font-black text-emerald-950 font-mono mt-1">
                            PKR {{ number_format($totals['xyz_commission']) }}
                        </div>
                        <p class="text-[10px] text-emerald-700/90 mt-1">Combined sum across all cheques</p>
                    </div>
                </div>

                <!-- Detailed Breakdown Table -->
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-100 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10px]">
                            <tr>
                                <th class="p-3 w-10 text-center">#</th>
                                <th class="p-3 w-32 font-mono">Cheque #</th>
                                <th class="p-3">Company / Vendor</th>
                                <th class="p-3 w-28 text-right font-mono">Cheque Amt</th>
                                <th class="p-3 w-28 text-right font-mono text-blue-900">Our Comm</th>
                                <th class="p-3 w-28 text-right font-mono text-amber-900">Other Comm</th>
                                <th class="p-3 w-32 text-right font-mono font-black text-slate-900">Total Comm</th>
                                <th class="p-3">Beneficiary Breakdown Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($allCommissionCheques as $idx => $ac)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="p-3 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="p-3 font-mono font-bold text-slate-800">{{ $ac->document_number }}</td>
                                    <td class="p-3 font-bold text-slate-800">
                                        {{ $ac->company_name }}
                                        <div class="text-[10px] font-normal text-slate-400">{{ $ac->cheque_date ? $ac->cheque_date->format('d M, Y') : '' }}</div>
                                    </td>
                                    <td class="p-3 text-right font-mono text-slate-700">
                                        {{ number_format($ac->cheque_amount) }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-bold text-blue-800 bg-blue-50/30">
                                        {{ $ac->our_commission > 0 ? number_format($ac->our_commission) : '-' }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-bold text-amber-900 bg-amber-50/30">
                                        {{ $ac->other_commission > 0 ? number_format($ac->other_commission) : '-' }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-slate-900 bg-slate-50/50">
                                        {{ number_format($ac->xyz_commission) }}
                                    </td>
                                    <td class="p-3">
                                        @if(!empty($ac->commission_breakdown))
                                            <div class="space-y-1">
                                                @foreach($ac->commission_breakdown as $b)
                                                    <div class="flex items-center justify-between text-[11px] gap-2">
                                                        <span class="inline-flex items-center gap-1 font-semibold {{ ($b['type'] ?? '') === 'other' ? 'text-amber-900' : 'text-blue-900' }}">
                                                            <span class="w-1.5 h-1.5 rounded-full {{ ($b['type'] ?? '') === 'other' ? 'bg-amber-500' : 'bg-blue-600' }}"></span>
                                                            {{ $b['title'] ?? 'Commission' }}:
                                                        </span>
                                                        <span class="font-mono text-slate-600">
                                                            @if(!empty($b['percentage']))
                                                                <span class="text-[10px] text-slate-400">({{ number_format($b['percentage'], 1) }}%)</span>
                                                            @endif
                                                            <strong>PKR {{ number_format($b['amount'] ?? 0) }}</strong>
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-6 text-center text-slate-400">
                                        No cheques with recorded commissions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-100 border-t-2 border-slate-300 font-bold text-xs">
                            <tr>
                                <td colspan="4" class="p-3 text-right uppercase tracking-wider text-slate-800 font-black">
                                    Grand Totals:
                                </td>
                                <td class="p-3 text-right font-mono font-black text-blue-900 bg-blue-100/60">
                                    PKR {{ number_format($totals['our_commission']) }}
                                </td>
                                <td class="p-3 text-right font-mono font-black text-amber-950 bg-amber-100/60">
                                    PKR {{ number_format($totals['other_commission']) }}
                                </td>
                                <td class="p-3 text-right font-mono font-black text-slate-950 bg-slate-200/70">
                                    PKR {{ number_format($totals['xyz_commission']) }}
                                </td>
                                <td class="p-3 text-slate-500 text-[11px]">
                                    Complete audit verified
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Signatures for audit print -->
                <div class="pt-8 pb-4 grid grid-cols-3 gap-6 text-center text-xs text-slate-500">
                    <div class="border-t border-slate-300 pt-2">
                        <span class="font-bold text-slate-800">Prepared By</span>
                        <div class="text-[11px] text-slate-400">Accountant / Operator</div>
                    </div>
                    <div class="border-t border-slate-300 pt-2">
                        <span class="font-bold text-slate-800">Verified By</span>
                        <div class="text-[11px] text-slate-400">Accounts Manager</div>
                    </div>
                    <div class="border-t border-slate-300 pt-2">
                        <span class="font-bold text-slate-800">Approved By</span>
                        <div class="text-[11px] text-slate-400">Director / CEO</div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
                <span class="text-xs text-slate-500">
                    💡 Click <strong>Print Report</strong> for official PDF export or physical paper printout.
                </span>
                <button type="button" onclick="closeCommissionReportModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Script for Live Auto-Calculation & Commission Breakdown -->
    <script>
        let commRowCount = 1;

        function openChequeModal() {
            document.getElementById('cheque-modal-overlay').classList.remove('hidden');
        }

        function closeChequeModal() {
            document.getElementById('cheque-modal-overlay').classList.add('hidden');
        }

        function autoFillFromBill(select) {
            const opt = select.options[select.selectedIndex];
            if (!opt.value) return;

            const amount = opt.getAttribute('data-amount');
            const company = opt.getAttribute('data-company');
            const deptId = opt.getAttribute('data-dept-id');
            const costCenter = opt.getAttribute('data-cost-center');

            if (amount) {
                document.getElementById('modal_cheque_amt').value = amount;
                document.getElementById('modal_bill_amt').value = amount;
            }
            if (company) document.getElementById('modal_company_name').value = company;
            if (deptId) document.getElementById('modal_dept_id').value = deptId;
            if (costCenter) document.getElementById('modal_cost_center').value = costCenter;

            recalcAllCommissionRows();
            calcChequeDeductions();
        }

        function addCommissionRow(defaultType = 'other') {
            const tbody = document.getElementById('commission-rows-body');
            const newIndex = commRowCount++;
            
            const tr = document.createElement('tr');
            tr.className = 'commission-row hover:bg-slate-50/50 transition';
            tr.innerHTML = `
                <td class="p-2.5 text-center font-bold text-slate-400 comm-num">` + (tbody.children.length + 1) + `</td>
                <td class="p-2.5">
                    <select name="commissions[` + newIndex + `][type]" onchange="onCommTypeChange(this)" class="comm-type w-full text-[11px] font-bold rounded-lg border-slate-200 py-1 focus:border-amber-500 focus:ring-amber-500 text-slate-800 bg-white">
                        <option value="our"` + (defaultType === 'our' ? ' selected' : '') + `>🔵 Our Commission</option>
                        <option value="other"` + (defaultType === 'other' ? ' selected' : '') + `>🟠 Other Commission</option>
                    </select>
                </td>
                <td class="p-2.5">
                    <input type="text" name="commissions[` + newIndex + `][title]" value="` + (defaultType === 'our' ? 'Our Commission' : '') + `" placeholder="` + (defaultType === 'our' ? 'e.g. Our Commission' : 'e.g. Beneficiary Name, Coordinator...') + `" class="comm-title w-full text-xs rounded-lg border-slate-200 py-1 focus:border-amber-500 focus:ring-amber-500 font-medium">
                </td>
                <td class="p-2.5">
                    <div class="flex items-center gap-1">
                        <input type="number" step="0.01" min="0" max="100" name="commissions[` + newIndex + `][percentage]" placeholder="e.g. 5" oninput="calcCommRowFromPercent(this)" class="w-full text-xs rounded-lg border-slate-200 py-1 text-center font-bold comm-pct font-mono">
                        <span class="text-slate-400 font-bold">%</span>
                    </div>
                </td>
                <td class="p-2.5 text-right">
                    <input type="number" step="0.01" min="0" name="commissions[` + newIndex + `][amount]" value="0" placeholder="0.00" oninput="calcCommRowFromAmount(this)" class="w-full text-xs rounded-lg border-slate-200 py-1 text-right font-black comm-amt font-mono text-amber-900">
                </td>
                <td class="p-2.5 text-center">
                    <button type="button" onclick="removeCommissionRow(this)" class="text-slate-300 hover:text-rose-500 font-bold text-sm" title="Remove commission">&times;</button>
                </td>
            `;
            tbody.appendChild(tr);
            updateCommRowNumbers();
            calcChequeDeductions();
        }

        function onCommTypeChange(selectElem) {
            const row = selectElem.closest('tr');
            const titleInput = row.querySelector('.comm-title');
            if (selectElem.value === 'our') {
                if (!titleInput.value || titleInput.value.toLowerCase().includes('other')) {
                    titleInput.value = 'Our Commission';
                }
                titleInput.placeholder = 'e.g. Our Commission, Office Share';
            } else {
                if (titleInput.value === 'Our Commission') {
                    titleInput.value = '';
                }
                titleInput.placeholder = 'e.g. Beneficiary Name, Coordinator, Partner...';
            }
            calcChequeDeductions();
        }

        function removeCommissionRow(btn) {
            const tbody = document.getElementById('commission-rows-body');
            if (tbody.children.length <= 1) {
                // Clear the values instead of removing the last row
                const row = tbody.children[0];
                row.querySelector('input[type="text"]').value = '';
                row.querySelector('.comm-pct').value = '';
                row.querySelector('.comm-amt').value = '0';
                calcChequeDeductions();
                return;
            }
            btn.closest('tr').remove();
            updateCommRowNumbers();
            calcChequeDeductions();
        }

        function updateCommRowNumbers() {
            const rows = document.querySelectorAll('#commission-rows-body .commission-row');
            rows.forEach((row, idx) => {
                const num = row.querySelector('.comm-num');
                if (num) num.textContent = idx + 1;
            });
        }

        function calcCommRowFromPercent(pctInput) {
            const chequeAmt = parseFloat(document.getElementById('modal_cheque_amt').value) || 0;
            const pct = parseFloat(pctInput.value) || 0;
            const row = pctInput.closest('tr');
            const amtInput = row.querySelector('.comm-amt');
            
            const calculatedAmt = (chequeAmt * pct) / 100;
            amtInput.value = calculatedAmt.toFixed(2);
            calcChequeDeductions();
        }

        function calcCommRowFromAmount(amtInput) {
            const chequeAmt = parseFloat(document.getElementById('modal_cheque_amt').value) || 0;
            const amt = parseFloat(amtInput.value) || 0;
            const row = amtInput.closest('tr');
            const pctInput = row.querySelector('.comm-pct');
            
            if (chequeAmt > 0) {
                const calculatedPct = (amt / chequeAmt) * 100;
                pctInput.value = calculatedPct.toFixed(2);
            }
            calcChequeDeductions();
        }

        function recalcAllCommissionRows() {
            const chequeAmt = parseFloat(document.getElementById('modal_cheque_amt').value) || 0;
            document.querySelectorAll('#commission-rows-body .commission-row').forEach(row => {
                const pctInput = row.querySelector('.comm-pct');
                const amtInput = row.querySelector('.comm-amt');
                const pct = parseFloat(pctInput.value);
                if (!isNaN(pct) && pct > 0) {
                    amtInput.value = ((chequeAmt * pct) / 100).toFixed(2);
                }
            });
        }

        function onModalAgprPurchasedChange() {
            const gst = parseFloat(document.getElementById('modal_gst').value) || 0;
            const agprGst = parseFloat(document.getElementById('modal_agpr_purchased_gst').value) || 0;
            if (agprGst > 0 && gst > 0) {
                document.getElementById('modal_gst_diff').value = Math.max(0, gst - agprGst).toFixed(2);
            }
            calcChequeDeductions();
        }

        function onModalAgprRepairedChange() {
            const sst = parseFloat(document.getElementById('modal_sst').value) || 0;
            const agprSst = parseFloat(document.getElementById('modal_agpr_repaired_sst').value) || 0;
            if (agprSst > 0 && sst > 0) {
                document.getElementById('modal_sst_diff').value = Math.max(0, sst - agprSst).toFixed(2);
            }
            calcChequeDeductions();
        }

        function onModalItaxChange() {
            const itax55 = parseFloat(document.getElementById('modal_itax_5_5').value) || 0;
            const itax15 = parseFloat(document.getElementById('modal_itax_15').value) || 0;
            const totalItax = itax55 + itax15;
            document.getElementById('modal_itax').value = totalItax.toFixed(2);
            calcChequeDeductions();
        }

        function calcChequeDeductions() {
            const chequeAmt = parseFloat(document.getElementById('modal_cheque_amt').value) || 0;
            const gst = parseFloat(document.getElementById('modal_gst').value) || 0;
            const itax55 = parseFloat(document.getElementById('modal_itax_5_5')?.value) || 0;
            const itax15 = parseFloat(document.getElementById('modal_itax_15')?.value) || 0;
            const itax = itax55 + itax15 > 0 ? (itax55 + itax15) : (parseFloat(document.getElementById('modal_itax').value) || 0);
            if (document.getElementById('modal_itax')) {
                document.getElementById('modal_itax').value = itax.toFixed(2);
            }
            const sst = parseFloat(document.getElementById('modal_sst').value) || 0;

            // Sum commissions by option
            let totalComm = 0;
            let ourComm = 0;
            let otherComm = 0;

            document.querySelectorAll('#commission-rows-body .commission-row').forEach(row => {
                const typeSelect = row.querySelector('.comm-type');
                const type = typeSelect ? typeSelect.value : 'our';
                const amtInput = row.querySelector('.comm-amt');
                const amt = amtInput ? (parseFloat(amtInput.value) || 0) : 0;

                if (type === 'other') {
                    otherComm += amt;
                } else {
                    ourComm += amt;
                }
                totalComm += amt;
            });

            const fmt = (v) => 'PKR ' + v.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const ourSub = document.getElementById('modal_subtotal_our');
            if (ourSub) ourSub.textContent = fmt(ourComm);
            const otherSub = document.getElementById('modal_subtotal_other');
            if (otherSub) otherSub.textContent = fmt(otherComm);

            document.getElementById('total_commission_display').textContent = fmt(totalComm);
            document.getElementById('modal_commission_total').value = totalComm.toFixed(2);
            const ourInput = document.getElementById('modal_our_commission');
            if (ourInput) ourInput.value = ourComm.toFixed(2);
            const otherInput = document.getElementById('modal_other_commission');
            if (otherInput) otherInput.value = otherComm.toFixed(2);

            const totalDeduction = gst + itax + sst + totalComm;
            document.getElementById('modal_total_deduction').value = totalDeduction.toFixed(2);

            const paidDept = Math.max(0, chequeAmt - totalComm);
            document.getElementById('modal_paid_dept').value = paidDept.toFixed(2);
        }

        // Popup: View Commission Breakdown
        function viewCommissionBreakdown(docNum, company, breakdown, totalFormatted, chequeAmtFormatted, ourFormatted, otherFormatted) {
            document.getElementById('view-comm-doc').textContent = docNum;
            document.getElementById('view-comm-sub').textContent = 'Company: ' + company;
            document.getElementById('view-comm-cheque-amt').textContent = 'PKR ' + chequeAmtFormatted;
            document.getElementById('view-comm-total').textContent = 'PKR ' + totalFormatted;

            const tbody = document.getElementById('view-comm-tbody');
            tbody.innerHTML = '';

            let ourSum = 0;
            let otherSum = 0;

            if (!breakdown || breakdown.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="p-4 text-center text-slate-400">No commission breakdown items recorded.</td></tr>';
            } else {
                breakdown.forEach((item, idx) => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50 transition';
                    const pctVal = item.percentage ? Number(item.percentage).toFixed(2) + '%' : '-';
                    const amtNum = parseFloat(item.amount) || 0;
                    const amtVal = amtNum.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    
                    const isOther = (item.type === 'other') || (item.title && item.title.toLowerCase().includes('other')) || (item.title && item.title.toLowerCase().includes('contractor'));
                    if (isOther) {
                        otherSum += amtNum;
                    } else {
                        ourSum += amtNum;
                    }

                    const badgeHtml = isOther 
                        ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10.5px] font-bold bg-amber-100 text-amber-900 border border-amber-300">🟠 Other Comm</span>`
                        : `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10.5px] font-bold bg-blue-100 text-blue-900 border border-blue-300">🔵 Our Comm</span>`;

                    tr.innerHTML = `
                        <td class="p-3 text-center font-bold text-slate-400">` + (idx + 1) + `</td>
                        <td class="p-3">` + badgeHtml + `</td>
                        <td class="p-3 font-bold text-slate-800">` + (item.title || (isOther ? 'Other Commission' : 'Our Commission')) + `</td>
                        <td class="p-3 text-center font-mono font-bold text-indigo-700 bg-indigo-50/50">` + pctVal + `</td>
                        <td class="p-3 text-right font-mono font-black ` + (isOther ? 'text-amber-900' : 'text-blue-900') + `">PKR ` + amtVal + `</td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            const fmt = (v) => 'PKR ' + v.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const ourElem = document.getElementById('view-comm-our-subtotal');
            if (ourElem) ourElem.textContent = fmt(ourSum);
            const otherElem = document.getElementById('view-comm-other-subtotal');
            if (otherElem) otherElem.textContent = fmt(otherSum);

            document.getElementById('commission-view-modal').classList.remove('hidden');
        }

        function closeCommissionViewModal() {
            document.getElementById('commission-view-modal').classList.add('hidden');
        }

        let currentActiveSheet = 'dept';

        function switchActiveSheet(sheet) {
            currentActiveSheet = sheet;
            const deptContainer = document.getElementById('sheet-dept');
            const officeContainer = document.getElementById('sheet-office');
            const btnDept = document.getElementById('btn-tab-dept');
            const btnOffice = document.getElementById('btn-tab-office');
            const badge = document.getElementById('active-sheet-badge');
            const desc = document.getElementById('active-sheet-desc');

            if (sheet === 'dept') {
                deptContainer.classList.remove('hidden');
                officeContainer.classList.add('hidden');
                
                btnDept.className = 'px-4 py-2 rounded-lg text-xs font-black transition flex items-center gap-2 shadow-sm bg-emerald-600 text-white';
                btnOffice.className = 'px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 text-slate-300 hover:text-white';

                if (badge) {
                    badge.className = 'inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-800 uppercase tracking-wide';
                    badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500"></span><span>Currently Viewing: Department Statement & AGPR Reconciliation</span>';
                }
                if (desc) {
                    desc.textContent = 'Official Treasury Statement formatted for submission to the department (Margins & vendor profits hidden).';
                }
            } else {
                deptContainer.classList.add('hidden');
                officeContainer.classList.remove('hidden');

                btnDept.className = 'px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 text-slate-300 hover:text-white';
                btnOffice.className = 'px-4 py-2 rounded-lg text-xs font-black transition flex items-center gap-2 shadow-sm bg-indigo-600 text-white';

                if (badge) {
                    badge.className = 'inline-flex items-center gap-1.5 text-xs font-extrabold text-indigo-900 uppercase tracking-wide';
                    badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-indigo-500"></span><span>Currently Viewing: Office Internal Audit & Profit Sheet</span>';
                }
                if (desc) {
                    desc.textContent = 'Confidential internal audit including Cost Center, department mapping, Our vs Other Commission, and Vendor Total Profit.';
                }
            }
        }

        function printCustomSheet(sheet) {
            switchActiveSheet(sheet);
            document.body.setAttribute('data-print-sheet', sheet);
            setTimeout(() => {
                window.print();
            }, 150);
        }
    </script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            nav, header, #sheet-tabs-nav, #cheque-filter-bar, .metrics-bar {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            /* When printing department copy */
            body[data-print-sheet="dept"] #sheet-office {
                display: none !important;
            }
            body[data-print-sheet="dept"] #sheet-dept {
                display: block !important;
            }
            /* When printing office copy */
            body[data-print-sheet="office"] #sheet-dept {
                display: none !important;
            }
            body[data-print-sheet="office"] #sheet-office {
                display: block !important;
            }
            @page {
                size: landscape;
                margin: 6mm;
            }
            table {
                font-size: 10px !important;
            }
            th, td {
                padding: 4px 6px !important;
            }
        }
    </style>
</x-app-layout>
