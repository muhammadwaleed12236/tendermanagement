<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1 print:hidden">
                    <a href="{{ route('bills.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 transition flex items-center gap-1">
                        &larr; Back to Bills Ledger
                    </a>
                    <span class="text-slate-300">•</span>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-orange-100 text-orange-900 border border-orange-300">
                        Official Daily Billing Registry
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>{{ __('Daily Billing Report') }}</span>
                    <span class="text-xs font-mono font-bold bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full border border-slate-300">
                        {{ $stats['total_count'] }} Records
                    </span>
                </h1>
                <p class="text-xs text-slate-600 mt-0.5">
                    Daily invoice generation ledger, cost center heads, tax deduction breakdown, and bill dispatch tracking.
                </p>
            </div>

            <!-- Action Buttons (Print / Excel / New Bill) -->
            <div class="flex items-center gap-2 self-start md:self-auto flex-wrap print:hidden">
                <!-- Print Button -->
                <button type="button" onclick="window.print()" class="px-3.5 py-2 text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Report</span>
                </button>

                <!-- Export to Excel (.xls) matching exact image pattern -->
                <a href="{{ route('bills.daily-report.export', array_merge(request()->all(), ['format' => 'xls'])) }}" class="px-3.5 py-2 text-xs font-bold bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/></svg>
                    <span>Export Excel (.xls)</span>
                </a>

                <!-- Download CSV -->
                <a href="{{ route('bills.daily-report.export', array_merge(request()->all(), ['format' => 'csv'])) }}" class="px-3 py-2 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl border border-slate-300 shadow-2xs transition flex items-center gap-1.5">
                    <span>CSV</span>
                </a>

                <!-- Create Bill -->
                <a href="{{ route('bills.create') }}" class="px-3.5 py-2 text-xs font-bold bg-[#0056d2] hover:bg-[#07255e] text-white rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ New Bill</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Main Container with Alpine.js state for Quick Dispatch update modal -->
    <div class="py-6 font-sans" x-data="{
        editModalOpen: false,
        modalBillId: null,
        modalBillNo: '',
        modalDispatchDate: '',
        modalRemarks: '',
        modalQuarter: '1st Qtr',
        modalHeadCode: '',
        modalHeadDesc: '',
        openDispatchModal(bill) {
            this.modalBillId = bill.id;
            this.modalBillNo = bill.bill_number;
            this.modalDispatchDate = bill.dispatch_date || '';
            this.modalRemarks = bill.remarks || '';
            this.modalQuarter = bill.quarter || '1st Qtr';
            this.modalHeadCode = bill.head_code || '';
            this.modalHeadDesc = bill.head_description || '';
            this.editModalOpen = true;
        }
    }">
        <div class="max-w-[100rem] mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <!-- Print Header Banner (Only visible on physical print/PDF) -->
            <div class="hidden print:block pb-4 mb-4 border-b-2 border-slate-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">DAILY BILLING REPORT</h1>
                        <p class="text-xs font-bold text-slate-600 mt-0.5">Government Departmental Invoices, Taxes & Dispatch Register</p>
                    </div>
                    <div class="text-right text-xs">
                        <div><strong>Report Date:</strong> {{ request('date') ? \Carbon\Carbon::parse(request('date'))->format('d-M-Y') : date('d-M-Y') }}</div>
                        <div><strong>Printed By:</strong> {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</div>
                    </div>
                </div>
            </div>

            <!-- Top KPI Summary Cards (Printable & Screen) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 print:hidden">
                <!-- 1. Total Bills -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Total Invoices</span>
                        <div class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ number_format($stats['total_count']) }}</div>
                        <span class="text-[11px] font-medium text-slate-400">Bills Generated</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0056d2] border border-blue-200 flex items-center justify-center font-black text-sm shrink-0">
                        📋
                    </div>
                </div>

                <!-- 2. Total Invoiced Amount -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Total Billing Volume</span>
                        <div class="text-xl sm:text-2xl font-black text-emerald-900 mt-0.5">PKR {{ number_format($stats['total_amount']) }}</div>
                        <span class="text-[11px] font-medium text-emerald-600">Net Payable Amount</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-black text-sm shrink-0">
                        Rs.
                    </div>
                </div>

                <!-- 3. Dispatched Bills -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-sky-700">Dispatched Bills</span>
                        <div class="text-xl sm:text-2xl font-black text-sky-900 mt-0.5">{{ number_format($stats['dispatched_count']) }}</div>
                        <span class="text-[11px] font-medium text-sky-600">Delivered to Dept</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-700 border border-sky-200 flex items-center justify-center font-black text-sm shrink-0">
                        🚚
                    </div>
                </div>

                <!-- 4. Pending Dispatch -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-amber-700">Pending Dispatch</span>
                        <div class="text-xl sm:text-2xl font-black text-amber-900 mt-0.5">{{ number_format($stats['pending_dispatch_count']) }}</div>
                        <span class="text-[11px] font-medium text-amber-600">In Office / In Transit</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-black text-sm shrink-0">
                        ⏳
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs print:hidden">
                <form method="GET" action="{{ route('bills.daily-report') }}" id="reportFilterForm" class="space-y-4">
                    
                    <!-- Quick Date Presets -->
                    <div class="flex items-center gap-2 flex-wrap pb-3 border-b border-slate-100">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1">Quick Filter:</span>
                        
                        <!-- Today -->
                        <button type="button" onclick="setFilterDate('{{ date('Y-m-d') }}')" class="px-2.5 py-1 rounded-lg text-xs font-bold {{ request('date') === date('Y-m-d') ? 'bg-[#0056d2] text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Today ({{ date('d-M') }})
                        </button>

                        <!-- Yesterday -->
                        <button type="button" onclick="setFilterDate('{{ date('Y-m-d', strtotime('-1 day')) }}')" class="px-2.5 py-1 rounded-lg text-xs font-bold {{ request('date') === date('Y-m-d', strtotime('-1 day')) ? 'bg-[#0056d2] text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Yesterday
                        </button>

                        <!-- All Dates -->
                        <button type="button" onclick="clearFilterDate()" class="px-2.5 py-1 rounded-lg text-xs font-bold {{ !request('date') && !request('date_from') ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            All Dates
                        </button>

                        <!-- Active Filter Indicator -->
                        @if(request('date'))
                            <span class="ml-auto inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-blue-50 text-[#0056d2] border border-blue-200">
                                <span>Filter Date: {{ \Carbon\Carbon::parse(request('date'))->format('d-M-Y') }}</span>
                                <a href="{{ route('bills.daily-report') }}" class="text-blue-500 hover:text-blue-900 ml-1 font-bold">&times;</a>
                            </span>
                        @endif
                    </div>

                    <!-- Filter Fields Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                        
                        <!-- Single Date (Daily Report Picker) -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Specific Date</label>
                            <input type="date" name="date" id="input_filter_date" value="{{ request('date') }}" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                        </div>

                        <!-- Date From (Range Option) -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Date From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                        </div>

                        <!-- Date To (Range Option) -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Date To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                        </div>

                        <!-- Quarter Dropdown -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Quarter</label>
                            <select name="quarter" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 text-slate-800 bg-white">
                                <option value="">All Quarters</option>
                                @foreach($quarters as $qtr)
                                    <option value="{{ $qtr }}" {{ request('quarter') == $qtr ? 'selected' : '' }}>{{ $qtr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Department -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Department</label>
                            <select name="department_id" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2 text-slate-800 bg-white">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} ({{ $dept->code ?: 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Company / Vendor -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Vendor / Company</label>
                            <select name="company_id" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2 text-slate-800 bg-white">
                                <option value="">All Vendors</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- Search and Action Row -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                        <div class="w-full sm:max-w-md">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Bill #, Head Code, Remarks, Vendor, Cost Center..." class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 py-2">
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <button type="submit" class="px-5 py-2 text-xs font-bold bg-[#0056d2] hover:bg-[#07255e] text-white rounded-lg shadow-xs transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Filter Report</span>
                            </button>

                            <a href="{{ route('bills.daily-report') }}" class="px-3.5 py-2 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg border border-slate-300 transition">
                                Reset
                            </a>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Report Table Container (EXACT MATCH TO USER'S EXCEL IMAGE) -->
            <div class="bg-white rounded-2xl border-2 border-slate-300 overflow-hidden shadow-sm">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse report-table text-[12px] sm:text-[13px]">
                        
                        <!-- Table Header: Exact Peach / Salmon Background #f7cbb0 from Image -->
                        <thead>
                            <tr class="bg-[#f7cbb0] text-slate-900 border-b-2 border-slate-800">
                                <th class="py-2.5 px-2.5 text-center font-black border-r border-slate-400/60 whitespace-nowrap w-12">Sr #</th>
                                <th class="py-2.5 px-3 text-center font-black border-r border-slate-400/60 whitespace-nowrap">Bill CreateDate</th>
                                <th class="py-2.5 px-3 text-center font-black border-r border-slate-400/60 whitespace-nowrap">Bill No</th>
                                <th class="py-2.5 px-3 text-center font-black border-r border-slate-400/60 whitespace-nowrap">Quarter</th>
                                <th class="py-2.5 px-2 text-center font-black border-r border-slate-400/60 whitespace-nowrap w-24">Head of Account</th>
                                <th class="py-2.5 px-4 text-left font-black border-r border-slate-400/60 min-w-[280px]">Head Description</th>
                                <th class="py-2.5 px-3 text-center font-black border-r border-slate-400/60 whitespace-nowrap">Cost Center</th>
                                <th class="py-2.5 px-4 text-left font-black border-r border-slate-400/60 min-w-[220px]">Department Name</th>
                                <th class="py-2.5 px-3 text-center font-black border-r border-slate-400/60 whitespace-nowrap">GST/SST</th>
                                <th class="py-2.5 px-3.5 text-right font-black border-r border-slate-400/60 whitespace-nowrap min-w-[100px]">Amount</th>
                                <th class="py-2.5 px-4 text-left font-black border-r border-slate-400/60 min-w-[180px]">Vendor Name</th>
                                <th class="py-2.5 px-3 text-center font-black border-r border-slate-400/60 whitespace-nowrap">Bill Dispatch Date</th>
                                <th class="py-2.5 px-3.5 text-left font-black border-r border-slate-400/60 min-w-[140px]">Remarks</th>
                                <th class="py-2.5 px-2.5 text-center font-black print:hidden whitespace-nowrap w-20">Actions</th>
                            </tr>
                        </thead>

                        <!-- Table Body with Soft Blue-Gray Tint Rows from Image -->
                        <tbody class="divide-y divide-slate-300/80">
                            @php
                                $sr = 1;
                                $runningTotal = 0;
                            @endphp
                            @forelse($allBills as $index => $bill)
                                @php
                                    $amount = (float)($bill->net_amount ?: $bill->gross_amount);
                                    $runningTotal += $amount;
                                    // Alternating background: soft blue-gray tint #eef3f8 vs white
                                    $rowBg = ($index % 2 == 0) ? 'bg-white' : 'bg-[#eef3f8]';
                                @endphp
                                <tr class="{{ $rowBg }} hover:bg-amber-50/60 transition-colors">
                                    
                                    <!-- Sr # -->
                                    <td class="py-2 px-2.5 text-center font-mono font-bold text-slate-700 border-r border-slate-300">
                                        {{ $sr++ }}
                                    </td>

                                    <!-- Bill CreateDate -->
                                    <td class="py-2 px-3 text-center font-mono font-medium text-slate-800 border-r border-slate-300 whitespace-nowrap">
                                        {{ $bill->formatted_create_date }}
                                    </td>

                                    <!-- Bill No -->
                                    <td class="py-2 px-3 text-center font-mono font-black text-slate-900 border-r border-slate-300 whitespace-nowrap">
                                        <a href="{{ route('bills.show', $bill) }}" class="text-[#0056d2] hover:underline" title="View Bill Details">
                                            {{ $bill->bill_number }}
                                        </a>
                                    </td>

                                    <!-- Quarter -->
                                    <td class="py-2 px-3 text-center font-semibold text-slate-800 border-r border-slate-300 whitespace-nowrap">
                                        {{ $bill->quarter_display }}
                                    </td>

                                    <!-- Head of Account (Compact width) -->
                                    <td class="py-2 px-2 text-center font-mono font-bold text-slate-900 border-r border-slate-300 whitespace-nowrap w-24">
                                        {{ $bill->head_code_display }}
                                    </td>

                                    <!-- Head Description (Expanded space) -->
                                    <td class="py-2 px-4 text-left font-semibold text-slate-800 border-r border-slate-300 min-w-[280px]">
                                        {{ $bill->head_description_display }}
                                    </td>

                                    <!-- Cost Center -->
                                    <td class="py-2 px-3 text-center font-mono font-bold text-slate-800 border-r border-slate-300 whitespace-nowrap">
                                        {{ $bill->cost_center_code_display }}
                                    </td>

                                    <!-- Department Name -->
                                    <td class="py-2 px-4 text-left font-semibold text-slate-900 border-r border-slate-300">
                                        {{ $bill->department_name_display }}
                                    </td>

                                    <!-- GST/SST -->
                                    <td class="py-2 px-3 text-center font-mono font-bold text-slate-800 border-r border-slate-300 whitespace-nowrap">
                                        {{ $bill->gst_display }}
                                    </td>

                                    <!-- Amount -->
                                    <td class="py-2 px-3.5 text-right font-mono font-black text-slate-900 border-r border-slate-300 whitespace-nowrap">
                                        {{ number_format($amount) }}
                                    </td>

                                    <!-- Vendor Name -->
                                    <td class="py-2 px-4 text-left font-semibold text-slate-800 border-r border-slate-300">
                                        {{ $bill->vendor_name_display }}
                                    </td>

                                    <!-- Bill Dispatch Date -->
                                    <td class="py-2 px-3 text-center font-mono font-medium border-r border-slate-300 whitespace-nowrap">
                                        @if($bill->dispatch_date)
                                            <span class="text-emerald-800 font-bold">{{ $bill->formatted_dispatch_date }}</span>
                                        @else
                                            <span class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 print:hidden">Pending</span>
                                            <span class="hidden print:inline text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <!-- Remarks -->
                                    <td class="py-2 px-3.5 text-left font-medium text-slate-700 border-r border-slate-300">
                                        {{ $bill->remarks ?: '-' }}
                                    </td>

                                    <!-- Row Actions (Screen Only) -->
                                    <td class="py-1.5 px-2 text-center print:hidden whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1">
                                            <!-- Quick Dispatch Edit Button -->
                                            <button type="button" 
                                                    @click="openDispatchModal({
                                                        id: {{ $bill->id }},
                                                        bill_number: '{{ $bill->bill_number }}',
                                                        dispatch_date: '{{ $bill->dispatch_date ? $bill->dispatch_date->format('Y-m-d') : '' }}',
                                                        remarks: '{{ addslashes($bill->remarks ?? '') }}',
                                                        quarter: '{{ addslashes($bill->quarter_display) }}',
                                                        head_code: '{{ addslashes($bill->head_code_display) }}',
                                                        head_description: '{{ addslashes($bill->head_description_display) }}'
                                                    })"
                                                    class="p-1 rounded bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-700 transition" 
                                                    title="Quick Edit Dispatch & Remarks">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>

                                            <!-- Print Invoice Icon -->
                                            <a href="{{ route('bills.print', $bill) }}" target="_blank" class="p-1 rounded bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-700 transition" title="Print Invoice">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="py-12 text-center text-slate-500">
                                        <div class="max-w-sm mx-auto space-y-2">
                                            <div class="text-3xl">📭</div>
                                            <div class="text-sm font-bold text-slate-800">No Billing Records Found</div>
                                            <div class="text-xs text-slate-500">There are no bills matching your selected date or filters. Try choosing "All Dates" or generate a new bill.</div>
                                            <div class="pt-2">
                                                <a href="{{ route('bills.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0056d2] hover:bg-[#07255e] text-white text-xs font-bold rounded-lg shadow-xs transition">
                                                    + Create First Bill
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Grand Total Row -->
                            @if(count($allBills) > 0)
                                <tr class="bg-[#fed7aa] font-black text-slate-900 border-t-2 border-slate-900 border-b-2">
                                    <td colspan="9" class="py-3 px-4 text-right uppercase tracking-wider text-xs border-r border-slate-400">
                                        Total Billing Volume ({{ count($allBills) }} Bills):
                                    </td>
                                    <td class="py-3 px-3.5 text-right font-mono text-sm sm:text-base font-black text-slate-950 border-r border-slate-400 whitespace-nowrap">
                                        PKR {{ number_format($runningTotal) }}
                                    </td>
                                    <td colspan="4" class="py-3 px-4 text-xs font-medium text-slate-700">
                                        {{ $stats['dispatched_count'] }} Dispatched • {{ $stats['pending_dispatch_count'] }} Pending Dispatch
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary info -->
                <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2 print:hidden">
                    <div>
                        Showing <strong>{{ count($allBills) }}</strong> billing line entries.
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Dispatched ({{ $stats['dispatched_count'] }})</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Pending Dispatch ({{ $stats['pending_dispatch_count'] }})</span>
                    </div>
                </div>

            </div>

        </div>

        <!-- Alpine Quick Update Dispatch Modal -->
        <div x-show="editModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             @keydown.escape.window="editModalOpen = false">
            <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full p-6 space-y-4" @click.away="editModalOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Update Bill Dispatch & Remarks</h3>
                        <p class="text-xs text-slate-500">Bill #: <span class="font-mono font-bold text-[#0056d2]" x-text="modalBillNo"></span></p>
                    </div>
                    <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <form :action="'/bills/' + modalBillId + '/quick-update-dispatch'" method="POST" class="space-y-4">
                    @csrf

                    <!-- Dispatch Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bill Dispatch Date</label>
                        <input type="date" name="dispatch_date" x-model="modalDispatchDate" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2.5">
                        <p class="text-[11px] text-slate-400 mt-1">Date when bill voucher was dispatched to the department.</p>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Remarks / Dispatch Mode</label>
                        <input type="text" name="remarks" x-model="modalRemarks" placeholder="e.g. Rider By Hand, TCS, Courier, Urgent" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2.5">
                    </div>

                    <!-- Quarter -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Quarter</label>
                            <select name="quarter" x-model="modalQuarter" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 bg-white">
                                <option value="1st Qtr">1st Qtr</option>
                                <option value="2nd Qtr">2nd Qtr</option>
                                <option value="3rd Qtr">3rd Qtr</option>
                                <option value="4th Qtr">4th Qtr</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Head of Account (Code)</label>
                            <input type="text" name="head_code" x-model="modalHeadCode" placeholder="e.g. A13801" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                        </div>
                    </div>

                    <!-- Head Description -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Head Description</label>
                        <input type="text" name="head_description" x-model="modalHeadDesc" placeholder="e.g. Maintenance of Parks & Gardens" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 text-xs font-bold bg-[#0056d2] hover:bg-[#07255e] text-white rounded-lg shadow-xs">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Print Specific CSS Styling -->
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 8mm;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .report-table th {
                background-color: #f7cbb0 !important;
                color: #000000 !important;
                border: 1px solid #333333 !important;
                padding: 4px 6px !important;
                font-size: 10px !important;
            }
            .report-table td {
                border: 1px solid #777777 !important;
                padding: 4px 6px !important;
                font-size: 9.5px !important;
            }
            .report-table tr:nth-child(even) td {
                background-color: #ffffff !important;
            }
            .report-table tr:nth-child(odd) td {
                background-color: #eef3f8 !important;
            }
        }
    </style>

    <script>
        function setFilterDate(dateStr) {
            document.getElementById('input_filter_date').value = dateStr;
            document.getElementById('reportFilterForm').submit();
        }
        function clearFilterDate() {
            document.getElementById('input_filter_date').value = '';
            document.getElementById('reportFilterForm').submit();
        }
    </script>
</x-app-layout>
