<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('reports.index') }}" class="hover:underline">Reports & Analytics</a>
                    <span>/</span>
                    <span class="text-slate-400">Master Data Management</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-200 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </span>
                    <span>Master Data Management Report</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Item-level detailed procurement, detail object heads, budget ceilings, and vendor records.
                </p>
            </div>

            <!-- Export & Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-2xs transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Print Report</span>
                </button>
                <a href="{{ route('reports.master-data.export', array_merge(request()->query(), ['format' => 'xls'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Export Excel (.xls)</span>
                </a>
                <a href="{{ route('reports.master-data.export', array_merge(request()->query(), ['format' => 'csv'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <span>CSV</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-[98%] mx-auto px-3 sm:px-4 space-y-4">

            <!-- 3D Summary KPI Cards with Candy Icons & Circular Meters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Line Items (Electric Blue 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-blue">
                        <div class="stat-candy-icon candy-icon-blue">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="stat-card-label">Total Line Items</span>
                            <div class="text-xl sm:text-2xl font-black stat-card-value my-0.5">
                                {{ number_format($totalRecords) }}
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">Billed item rows</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="Total Records">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#dbeafe" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#0056d2" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#0056d2]">100%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total Quantity (Purple 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-purple">
                        <div class="stat-candy-icon candy-icon-purple">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="stat-card-label">Total Quantity</span>
                            <div class="text-xl sm:text-2xl font-black stat-card-value my-0.5 truncate">
                                {{ number_format($totalQty, 0) }} <span class="text-xs font-bold text-indigo-400 ml-1">Units</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">Procured volumes</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="Total Quantity Units">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#e0e7ff" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="15" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#6366f1]">QTY</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Procurement Value (Emerald Green 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-green">
                        <div class="stat-candy-icon candy-icon-green">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="stat-card-label">Procurement Value</span>
                            <div class="text-xl sm:text-2xl font-black stat-card-value my-0.5 truncate">
                                <span class="text-xs mr-1 font-extrabold text-emerald-600">PKR</span>{{ number_format($totalAmount, 0) }}
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">Net disbursed</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="Total Amount">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#d1fae5" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#0d9467" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="0" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#0d9467]">100%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Active Departments (Sunset Orange 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-orange">
                        <div class="stat-candy-icon candy-icon-orange">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="stat-card-label">Active Departments</span>
                            <div class="text-xl sm:text-2xl font-black stat-card-value my-0.5">
                                {{ $departments->count() }} <span class="text-xs font-bold text-orange-400 ml-1">Depts</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">Cost centers active</div>
                        </div>
                        <div class="relative shrink-0 flex items-center justify-center self-center" style="width: 58px; height: 58px;" title="{{ $departments->count() }} Departments">
                            <svg class="w-[58px] h-[58px] -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#ffedd5" stroke-width="3.5" />
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#cd3805" stroke-width="3.5" stroke-dasharray="88" stroke-dashoffset="20" stroke-linecap="round" />
                            </svg>
                            <span class="absolute text-xs font-black font-mono text-[#cd3805]">{{ $departments->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Controls Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <form method="GET" action="{{ route('reports.master-data') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                    
                    <!-- Search Input (4 cols) -->
                    <div class="lg:col-span-4">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Search Keyword</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Item desc, head code, vendor, dept..." 
                                   class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 pl-8 py-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Year (2 cols) -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Fiscal Year</label>
                        <select name="year" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 py-1.5">
                            <option value="">All Years</option>
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quarter (2 cols) -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Quarter</label>
                        <select name="quarter" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 py-1.5">
                            <option value="">All Quarters</option>
                            <option value="1st Qtr" {{ request('quarter') == '1st Qtr' || request('quarter') == 'Q1' ? 'selected' : '' }}>1st Quarter (Q1)</option>
                            <option value="2nd Qtr" {{ request('quarter') == '2nd Qtr' || request('quarter') == 'Q2' ? 'selected' : '' }}>2nd Quarter (Q2)</option>
                            <option value="3rd Qtr" {{ request('quarter') == '3rd Qtr' || request('quarter') == 'Q3' ? 'selected' : '' }}>3rd Quarter (Q3)</option>
                            <option value="4th Qtr" {{ request('quarter') == '4th Qtr' || request('quarter') == 'Q4' ? 'selected' : '' }}>4th Quarter (Q4)</option>
                        </select>
                    </div>

                    <!-- Department (2 cols) -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Department</label>
                        <select name="department_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 py-1.5">
                            <option value="">All Departments</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" {{ request('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons (2 cols) -->
                    <div class="lg:col-span-2 flex items-center gap-2">
                        <button type="submit" class="flex-1 py-1.5 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span>Filter</span>
                        </button>
                        @if(request()->hasAny(['search', 'year', 'quarter', 'department_id']))
                            <a href="{{ route('reports.master-data') }}" class="py-1.5 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition" title="Clear all filters">
                                ✕
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Master Data Management Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Master Data Records</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-900 border border-amber-200">
                            Showing {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} of {{ $items->total() }}
                        </span>
                    </div>
                </div>

                @if($items->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700">No Master Data Records Found</h4>
                        <p class="text-xs text-slate-400 mt-1">Try adjusting your filters or date range.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <!-- Custom Peach Styled Header per User Attachment -->
                        <table class="w-full text-left border-collapse text-xs">
                            <thead style="background: linear-gradient(to bottom, #fed7aa, #fdba74); color: #431407;" class="border-b-2 border-amber-300 font-extrabold text-[11px] uppercase tracking-tight sticky top-0 z-10 select-none">
                                <tr>
                                    <th class="py-2.5 px-2.5 text-center w-12 border-r border-amber-300/80">Sr #</th>
                                    <th class="py-2.5 px-2.5 text-center w-16 border-r border-amber-300/80 whitespace-nowrap">Years</th>
                                    <th class="py-2.5 px-2.5 text-center w-20 border-r border-amber-300/80 whitespace-nowrap">Quarter</th>
                                    <th class="py-2.5 px-3 border-r border-amber-300/80 whitespace-nowrap">Head of Account</th>
                                    <th class="py-2.5 px-3 border-r border-amber-300/80 whitespace-nowrap">Head Description</th>
                                    <th class="py-2.5 px-3 border-r border-amber-300/80 whitespace-nowrap">Cost Center</th>
                                    <th class="py-2.5 px-3 border-r border-amber-300/80 whitespace-nowrap">Department Name</th>
                                    <th class="py-2.5 px-3 border-r border-amber-300/80">Item Description</th>
                                    <th class="py-2.5 px-2.5 text-right w-16 border-r border-amber-300/80">Qty</th>
                                    <th class="py-2.5 px-3 text-right w-24 border-r border-amber-300/80">Rate</th>
                                    <th class="py-2.5 px-3 border-r border-amber-300/80 whitespace-nowrap">Vendor Name</th>
                                    <th class="py-2.5 px-3 text-right w-28 whitespace-nowrap">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans">
                                @foreach($items as $idx => $it)
                                    @php
                                        $bill = $it->bill;
                                        $year = $bill->invoice_date ? $bill->invoice_date->format('Y') : ($bill->created_at ? $bill->created_at->format('Y') : '2026');
                                        $quarter = $bill->quarter ?: '1st Qtr';
                                        $headOfAccount = $bill->head_of_account ?: ($bill->budgetHead ? $bill->budgetHead->head_code : ($bill->head_code ?: 'A03901'));
                                        $headDesc = $bill->head_description ?: ($bill->budgetHead ? $bill->budgetHead->head_name : $bill->head_description_display);
                                        $costCenter = $bill->cost_center ?: ($bill->department ? ($bill->department->cost_center_description ?: $bill->department->code) : 'HQ-01');
                                        $deptName = $bill->department ? $bill->department->name : '-';
                                        $vendorName = $bill->vendor ? $bill->vendor->name : ($bill->company ? $bill->company->name : 'N/A');
                                        $lineTotal = (float) ($it->total_price ?: ($it->quantity * $it->unit_price));
                                    @endphp
                                    <tr class="hover:bg-amber-50/40 transition">
                                        <!-- 1. Sr # -->
                                        <td class="py-2 px-2.5 text-center font-bold text-slate-400 font-mono text-[11px] border-r border-slate-100">
                                            {{ $items->firstItem() + $idx }}
                                        </td>

                                        <!-- 2. Years -->
                                        <td class="py-2 px-2.5 text-center font-bold text-slate-700 font-mono border-r border-slate-100 whitespace-nowrap">
                                            {{ $year }}
                                        </td>

                                        <!-- 3. Quarter -->
                                        <td class="py-2 px-2.5 text-center border-r border-slate-100 whitespace-nowrap">
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200">
                                                {{ $quarter }}
                                            </span>
                                        </td>

                                        <!-- 4. Head of Account -->
                                        <td class="py-2 px-3 border-r border-slate-100 whitespace-nowrap">
                                            <span class="font-mono font-bold text-amber-800 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200 text-[11px]">
                                                {{ $headOfAccount }}
                                            </span>
                                        </td>

                                        <!-- 5. Head Description -->
                                        <td class="py-2 px-3 border-r border-slate-100 font-semibold text-slate-700 max-w-xs truncate" title="{{ $headDesc }}">
                                            {{ $headDesc }}
                                        </td>

                                        <!-- 6. Cost Center -->
                                        <td class="py-2 px-3 border-r border-slate-100 font-mono text-slate-600 text-[11px] whitespace-nowrap">
                                            {{ $costCenter }}
                                        </td>

                                        <!-- 7. Department Name -->
                                        <td class="py-2 px-3 border-r border-slate-100 font-bold text-slate-800 whitespace-nowrap">
                                            {{ $deptName }}
                                        </td>

                                        <!-- 8. Item Description -->
                                        <td class="py-2 px-3 border-r border-slate-100 font-medium text-slate-900">
                                            <a href="{{ route('bills.show', $bill) }}" class="hover:text-blue-600 hover:underline">
                                                {{ $it->item_name }}
                                            </a>
                                            @if($it->unit)
                                                <span class="text-[10px] text-slate-400 font-normal">({{ $it->unit }})</span>
                                            @endif
                                        </td>

                                        <!-- 9. Qty -->
                                        <td class="py-2 px-2.5 text-right font-mono font-bold text-slate-800 border-r border-slate-100">
                                            {{ number_format($it->quantity, 2) }}
                                        </td>

                                        <!-- 10. Rate -->
                                        <td class="py-2 px-3 text-right font-mono font-bold text-slate-700 border-r border-slate-100 whitespace-nowrap">
                                            {{ number_format($it->unit_price, 2) }}
                                        </td>

                                        <!-- 11. Vendor Name -->
                                        <td class="py-2 px-3 border-r border-slate-100 font-semibold text-slate-800 whitespace-nowrap">
                                            {{ $vendorName }}
                                        </td>

                                        <!-- Total Amount -->
                                        <td class="py-2 px-3 text-right font-mono font-black text-emerald-700 bg-emerald-50/20 whitespace-nowrap">
                                            {{ number_format($lineTotal, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-slate-100 border-t-2 border-slate-300 font-bold text-xs sticky bottom-0 z-10">
                                <tr>
                                    <td colspan="8" class="py-2.5 px-3 text-right text-slate-700 uppercase tracking-wider font-extrabold">
                                        Page Subtotal:
                                    </td>
                                    <td class="py-2.5 px-2.5 text-right font-mono font-bold text-slate-900">
                                        {{ number_format($items->sum('quantity'), 2) }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-800 text-sm">
                                        PKR {{ number_format($items->sum('total_price'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
                        <div class="text-xs text-slate-500">
                            Showing <span class="font-bold text-slate-800">{{ $items->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-800">{{ $items->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800">{{ $items->total() }}</span> records
                        </div>
                        <div>
                            {{ $items->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
