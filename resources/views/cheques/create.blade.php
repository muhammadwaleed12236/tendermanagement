<x-app-layout>
    <x-slot name="header">
        <div class="hidden"></div>
    </x-slot>

    <div class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-5" style="background-color: #f4efe6;">
        <div class="max-w-7xl mx-auto space-y-5">

            <!-- 1. TOP HEADER FLOATING CARD: POST OFFICE PORTAL -->
            <div class="bg-white px-6 py-4 rounded-3xl border border-[#e5ded4] shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-stone-900 border border-stone-800 flex items-center justify-center text-orange-500 shadow-sm shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg sm:text-xl font-black tracking-tight text-stone-900 uppercase">
                                TENDER MANAGEMENT <span class="text-orange-500">SYSTEM</span>
                            </h1>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                Cheque Details
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 font-medium mt-0.5">
                            Complete Cheque Details & AGPR Reconciliation Statement
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-end sm:self-center">
                    <button type="button" onclick="window.location.reload()" class="px-4 py-2 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Refresh</span>
                    </button>
                </div>
            </div>

            <!-- 2. TOP 4 METRIC CARDS WITH CIRCULAR RINGS (MATCHING THE UPLOADED DESIGN) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: TOTAL BILL AMOUNT (Crimson Red Ring) -->
                <div class="bg-white p-5 rounded-3xl border border-[#e5ded4] shadow-xs flex items-center justify-between hover:border-stone-400 transition">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-rose-700">TOTAL BILL AMOUNT</span>
                        <div class="text-xl sm:text-2xl font-black font-mono text-stone-900 mt-1">
                            Rs. {{ number_format($totals['bill_amount'] ?? 0, 2) }}
                        </div>
                        <span class="inline-block px-2.5 py-0.5 mt-2 text-[10px] font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                            Overall Processed Bills
                        </span>
                    </div>
                    <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                        <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-stone-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-rose-600" stroke-width="4" stroke-dasharray="75, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                    </div>
                </div>

                <!-- Card 2: TOTAL CHEQUE AMOUNT (Royal Blue Ring) -->
                <div class="bg-white p-5 rounded-3xl border border-[#e5ded4] shadow-xs flex items-center justify-between hover:border-stone-400 transition">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">TOTAL CHEQUE AMOUNT</span>
                        <div class="text-xl sm:text-2xl font-black font-mono text-blue-700 mt-1">
                            Rs. {{ number_format($totals['cheque_amount'] ?? 0, 2) }}
                        </div>
                        <span class="inline-block px-2.5 py-0.5 mt-2 text-[10px] font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                            Overall Processed Cheques
                        </span>
                    </div>
                    <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                        <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-stone-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-blue-600" stroke-width="4" stroke-dasharray="60, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                    </div>
                </div>

                <!-- Card 3: TOTAL AGPR COMMISSION (Orange Ring) -->
                <div class="bg-white p-5 rounded-3xl border border-[#e5ded4] shadow-xs flex items-center justify-between hover:border-stone-400 transition">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-orange-700">TOTAL AGPR COMMISSION</span>
                        <div class="text-xl sm:text-2xl font-black font-mono text-orange-600 mt-1">
                            Rs. {{ number_format($totals['xyz_commission'] ?? 0, 2) }}
                        </div>
                        <span class="inline-block px-2.5 py-0.5 mt-2 text-[10px] font-bold rounded-full bg-orange-50 text-orange-700 border border-orange-200">
                            Overall AGPR Commission
                        </span>
                    </div>
                    <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                        <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-stone-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-orange-500" stroke-width="4" stroke-dasharray="45, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                    </div>
                </div>

                <!-- Card 4: TOTAL VENDOR PROFIT (Forest Green Ring) -->
                <div class="bg-white p-5 rounded-3xl border border-[#e5ded4] shadow-xs flex items-center justify-between hover:border-stone-400 transition">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">TOTAL VENDOR PROFIT</span>
                        <div class="text-xl sm:text-2xl font-black font-mono text-emerald-700 mt-1">
                            Rs. {{ number_format($totals['vendor_profit'] ?? 0, 2) }}
                        </div>
                        <span class="inline-block px-2.5 py-0.5 mt-2 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Net Vendor Earnings
                        </span>
                    </div>
                    <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                        <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-stone-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-emerald-600" stroke-width="4" stroke-dasharray="55, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 3. ORANGE DASHED BANNER CARD: Payment Records History & Professional Report View -->
            <a href="{{ route('cheques.index') }}" class="group block p-4 sm:p-5 rounded-3xl border-2 border-dashed border-orange-300 hover:border-orange-500 bg-[#fefbf7] hover:bg-[#fdf8f0] shadow-2xs transition flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-orange-100 border border-orange-200 text-orange-600 flex items-center justify-center shadow-2xs shrink-0 group-hover:scale-105 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-black text-sm text-stone-900 group-hover:text-orange-700 transition">
                            Payment Records History & Professional Report View
                        </h4>
                        <p class="text-xs text-stone-500 font-medium mt-0.5">
                            Click here to open complete database ledger list & printable report ({{ $chequesCount ?? 0 }} Total Entries)
                        </p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-orange-500 group-hover:bg-orange-600 text-white flex items-center justify-center shadow-sm shrink-0 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>
            </a>

            <!-- 4. GREEN BANNER: Bulk Excel Database Import -->
            <div class="p-5 sm:p-6 rounded-3xl bg-white border border-[#e5ded4] shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-sm text-stone-900">Bulk Excel Database Import (.xlsx, .csv)</h4>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100/70 text-emerald-800 border border-emerald-200">
                        Database Sync
                    </span>
                </div>
                <p class="text-xs text-stone-500">
                    Upload your multi-row Excel or CSV file to automatically parse, compute, and save all rows directly into MySQL database.
                </p>
                <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3 pt-1">
                    @csrf
                    <input type="file" name="csv_file" accept=".csv,.xlsx" class="text-xs rounded-xl border border-stone-300 p-1.5 bg-stone-50 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white file:text-stone-700 hover:file:bg-stone-100 cursor-pointer">
                    <button type="submit" class="px-4 py-2 bg-[#0d5440] hover:bg-[#093e2f] text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Save to Database</span>
                    </button>
                </form>
            </div>

            <!-- 5. MAIN FORM CARD: New Payment & Cheque Entry -->
            <div class="p-6 rounded-3xl bg-white border border-[#e5ded4] shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-stone-200/80 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-orange-500 font-black text-lg">+</span>
                        <h3 class="font-black text-sm text-stone-900 uppercase tracking-wider">New Payment & Cheque Entry</h3>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-500 text-white shadow-2xs">
                        Secure Form
                    </span>
                </div>

                <!-- Flash Error -->
                @if(session('error'))
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('cheques.store') }}" id="cheque-form" class="space-y-6">
                    @csrf

                    <!-- SECTION A: BASIC INFORMATION (Matching the uploaded image) -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-[#faf7f2] border border-[#e8e2d8] space-y-3">
                        <div class="flex items-center gap-2 text-xs font-black text-orange-800 uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            <span>Basic Information</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Department Name -->
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 uppercase mb-1">Department Name <span class="text-rose-500">*</span></label>
                                <select name="department_id" id="form_dept_id" onchange="onDeptSelect(this)" class="w-full text-xs font-semibold rounded-xl border-stone-300 py-2 bg-white focus:border-orange-500 focus:ring-orange-500">
                                    <option value="">-- Choose Department --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" 
                                                data-name="{{ $dept->name }}"
                                                data-cost-center="{{ $dept->cost_center_description ?: $dept->code }}">
                                            {{ $dept->name }} ({{ $dept->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="department_name" id="form_dept_name" value="">
                            </div>

                            <!-- Department Cost Centre -->
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 uppercase mb-1">Department Cost Centre</label>
                                <input type="text" name="cost_center" id="form_cost_center" value="KA XYZ" placeholder="e.g. CC-1042 or KA XYZ" class="w-full text-xs font-semibold rounded-xl border-stone-300 py-2 bg-white focus:border-orange-500 focus:ring-orange-500">
                            </div>

                            <!-- Company Name -->
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 uppercase mb-1">Company Name <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="company_name" id="form_company_name" list="company_list" placeholder="-- Choose Company --" required class="w-full text-xs font-semibold rounded-xl border-stone-300 py-2 bg-white focus:border-orange-500 focus:ring-orange-500">
                                    <datalist id="company_list">
                                        @foreach($companies as $cmp)
                                            <option value="{{ $cmp->name }}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>

                        <!-- Optional Autofill from Bill -->
                        @if($pendingBills->isNotEmpty())
                            <div class="pt-2 border-t border-[#e2dad0] flex items-center gap-2">
                                <span class="text-[11px] font-semibold text-stone-600 whitespace-nowrap">Autofill from Pending Bill:</span>
                                <select id="bill_selector" name="bill_id" onchange="autoFillFromBill(this)" class="text-xs rounded-lg border-stone-300 py-1 font-medium bg-white focus:border-orange-500 focus:ring-orange-500 max-w-sm">
                                    <option value="">-- Manual Entry / None --</option>
                                    @foreach($pendingBills as $pb)
                                        <option value="{{ $pb->id }}" 
                                                data-amount="{{ $pb->net_amount }}"
                                                data-company="{{ $pb->vendor ? ($pb->vendor->company_name ?: $pb->vendor->name) : 'Department Direct' }}"
                                                data-dept-id="{{ $pb->department_id }}"
                                                data-dept-name="{{ $pb->department ? $pb->department->name : '' }}"
                                                data-cost-center="{{ $pb->department ? ($pb->department->cost_center_description ?: $pb->department->code) : '' }}"
                                                {{ ($selectedBill && $selectedBill->id == $pb->id) ? 'selected' : '' }}>
                                            [{{ $pb->bill_number }}] {{ $pb->department ? $pb->department->code : '' }} — PKR {{ number_format($pb->net_amount) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <!-- SECTION B: CHEQUE IDENTIFIERS & AMOUNTS -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-[#e8e2d8] space-y-4">
                        <div class="flex items-center gap-2 text-xs font-black text-stone-900 uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            <span>Cheque Identifiers & Invoicing Values</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                            <!-- Token Number -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 uppercase mb-1">Token Number <span class="text-rose-500">*</span></label>
                                <input type="text" name="token_number" placeholder="e.g. 023359" required class="w-full text-xs font-bold rounded-xl border-stone-300 focus:border-orange-500 focus:ring-orange-500 font-mono py-2">
                            </div>

                            <!-- Cheque / Doc # -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 uppercase mb-1">Cheque / Doc # <span class="text-rose-500">*</span></label>
                                <input type="text" name="document_number" placeholder="e.g. 1500157809" required class="w-full text-xs font-bold rounded-xl border-stone-300 focus:border-orange-500 focus:ring-orange-500 font-mono py-2">
                            </div>

                            <!-- Cheque Date -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 uppercase mb-1">Cheque Date <span class="text-rose-500">*</span></label>
                                <input type="date" name="cheque_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-stone-300 focus:border-orange-500 focus:ring-orange-500 font-medium py-2">
                            </div>

                            <!-- Cheque Amount -->
                            <div>
                                <label class="block text-xs font-black text-blue-900 uppercase mb-1">Cheque Amount (PKR) <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" name="cheque_amount" id="form_cheque_amt" placeholder="e.g. 61600" required oninput="calcChequeDeductions()" class="w-full text-sm font-black rounded-xl border-blue-300 bg-blue-50/40 text-blue-800 font-mono py-2 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                            <!-- Bill Amount -->
                            <div>
                                <label class="block text-xs font-bold text-stone-800 uppercase mb-1">Bill Amount (PKR) <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" name="bill_amount" id="form_bill_amt" placeholder="e.g. 61600" required oninput="calcChequeDeductions()" class="w-full text-sm font-black rounded-xl border-stone-300 font-mono py-2 focus:border-orange-500 focus:ring-orange-500">
                            </div>

                            <!-- Consolidated I.Tax -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-stone-800 uppercase">Total I.Tax (Auto Sum 5.5% + 15%)</label>
                                </div>
                                <input type="number" step="0.01" name="itax_amount" id="form_itax" value="0" readonly class="w-full text-sm font-black rounded-xl border-stone-300 bg-stone-50 font-mono py-2">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION C: AGPR DEDUCTIONS FOR PURCHASED & REPAIRED ITEMS -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-[#e8e2d8] space-y-4">
                        <div class="flex items-center justify-between border-b border-stone-200/80 pb-2">
                            <div class="flex items-center gap-2 text-xs font-black text-stone-900 uppercase tracking-wider">
                                <span class="w-2 h-2 rounded-full bg-orange-600"></span>
                                <span>AGPR Deductions Breakdown</span>
                            </div>
                            <span class="text-[11px] font-bold text-orange-800 bg-orange-100/70 px-2.5 py-0.5 rounded-full">
                                Treasury Standard
                            </span>
                        </div>

                        <!-- AGPR Deducted for Purchased Items -->
                        <div class="p-3.5 rounded-xl border border-amber-200/90 bg-amber-50/30 space-y-2">
                            <span class="text-xs font-black text-amber-950 uppercase tracking-wide block">
                                1. AGPR Deducted for Purchased Items
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">18% GST Amount</label>
                                    <input type="number" step="0.01" name="gst_amount" id="form_gst" value="0" oninput="onAgprPurchasedChange()" class="w-full text-xs font-bold rounded-lg border-stone-300 py-1.5 font-mono">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-amber-900 mb-1">AGPR GST (20%)</label>
                                    <input type="number" step="0.01" name="agpr_purchased_gst" id="form_agpr_purchased_gst" value="0" oninput="onAgprPurchasedChange()" class="w-full text-xs font-bold rounded-lg border-amber-300 py-1.5 font-mono text-amber-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">5.5% I.Tax Amount</label>
                                    <input type="number" step="0.01" name="itax_5_5_amount" id="form_itax_5_5" value="0" oninput="onItaxChange()" class="w-full text-xs font-bold rounded-lg border-stone-300 py-1.5 font-mono">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-amber-900 mb-1">AGPR 5.5% I.Tax</label>
                                    <input type="number" step="0.01" name="agpr_purchased_itax" id="form_agpr_purchased_itax" value="0" oninput="calcChequeDeductions()" class="w-full text-xs font-bold rounded-lg border-amber-300 py-1.5 font-mono text-amber-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">GST Difference</label>
                                    <input type="number" step="0.01" name="vendor_gst_difference" id="form_gst_diff" value="0" oninput="calcChequeDeductions()" class="w-full text-xs font-bold rounded-lg border-stone-300 py-1.5 font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- AGPR Deducted for Repaired Items -->
                        <div class="p-3.5 rounded-xl border border-sky-200/90 bg-sky-50/30 space-y-2">
                            <span class="text-xs font-black text-sky-950 uppercase tracking-wide block">
                                2. AGPR Deducted for Repaired Items
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">15% SST Amount</label>
                                    <input type="number" step="0.01" name="sst_amount" id="form_sst" value="0" oninput="onAgprRepairedChange()" class="w-full text-xs font-bold rounded-lg border-stone-300 py-1.5 font-mono">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-sky-900 mb-1">AGPR SST (20%)</label>
                                    <input type="number" step="0.01" name="agpr_repaired_sst" id="form_agpr_repaired_sst" value="0" oninput="onAgprRepairedChange()" class="w-full text-xs font-bold rounded-lg border-sky-300 py-1.5 font-mono text-sky-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">15% I.Tax Amount</label>
                                    <input type="number" step="0.01" name="itax_15_amount" id="form_itax_15" value="0" oninput="onItaxChange()" class="w-full text-xs font-bold rounded-lg border-stone-300 py-1.5 font-mono">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-sky-900 mb-1">AGPR 15% I.Tax</label>
                                    <input type="number" step="0.01" name="agpr_repaired_itax" id="form_agpr_repaired_itax" value="0" oninput="calcChequeDeductions()" class="w-full text-xs font-bold rounded-lg border-sky-300 py-1.5 font-mono text-sky-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">SST Difference</label>
                                    <input type="number" step="0.01" name="sst_difference" id="form_sst_diff" value="0" oninput="calcChequeDeductions()" class="w-full text-xs font-bold rounded-lg border-stone-300 py-1.5 font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION D: MULTI-RECIPIENT COMMISSION BREAKDOWN -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-[#e8e2d8] space-y-4">
                        <div class="flex items-center justify-between border-b border-stone-200/80 pb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                <div>
                                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Commission Breakdown (Our vs Other)</h3>
                                    <p class="text-[11px] text-stone-400">Add dynamic commission recipients, rates and types</p>
                                </div>
                            </div>

                            <button type="button" onclick="addCommissionRow()" class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl transition shadow-2xs flex items-center gap-1">
                                + Add Commission Row
                            </button>
                        </div>

                        <!-- Subtotal Badges -->
                        <div class="flex flex-wrap items-center gap-3 p-3 bg-stone-50 rounded-xl border border-stone-200/70 text-xs">
                            <div class="font-bold text-stone-700">Subtotals:</div>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 font-bold font-mono">
                                <span>Our Commission:</span>
                                <span id="subtotal_our_comm">PKR 0.00</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-300 text-amber-900 font-bold font-mono">
                                <span>Other Commission:</span>
                                <span id="subtotal_other_comm">PKR 0.00</span>
                            </div>
                            <div class="ml-auto font-mono font-black text-stone-900">
                                Overall Total: <span id="total_commission_display" class="text-orange-600">PKR 0.00</span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-stone-50 text-stone-500 font-bold uppercase text-[10px] border-b border-stone-200">
                                        <th class="p-2.5 text-center w-12">#</th>
                                        <th class="p-2.5 w-36">Type</th>
                                        <th class="p-2.5">Beneficiary / Title</th>
                                        <th class="p-2.5 w-28">Rate (%)</th>
                                        <th class="p-2.5 w-36 text-right">Commission (PKR)</th>
                                        <th class="p-2.5 text-center w-12">Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="commission-rows-body" class="divide-y divide-stone-100">
                                    <tr class="commission-row hover:bg-stone-50/70 transition">
                                        <td class="p-2 text-center font-bold text-stone-400 row-idx">1</td>
                                        <td class="p-2">
                                            <select name="commissions[0][type]" class="comm-type w-full text-xs font-bold rounded-lg border-stone-300 py-1.5 focus:border-orange-500 focus:ring-orange-500" onchange="calcChequeDeductions()">
                                                <option value="our" selected>Our Commission</option>
                                                <option value="other">Other Commission</option>
                                            </select>
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="commissions[0][title]" placeholder="e.g. Partner Commission, Coordinator" class="comm-title w-full text-xs font-semibold rounded-lg border-stone-300 py-1.5 focus:border-orange-500 focus:ring-orange-500">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" step="0.01" name="commissions[0][rate]" placeholder="e.g. 5" oninput="calcCommissionRow(this)" class="comm-rate w-full text-xs font-mono rounded-lg border-stone-300 py-1.5 focus:border-orange-500 focus:ring-orange-500">
                                        </td>
                                        <td class="p-2 text-right">
                                            <input type="number" step="0.01" name="commissions[0][amount]" value="0" oninput="calcChequeDeductions()" class="comm-amt w-full text-xs font-black font-mono text-right rounded-lg border-stone-300 py-1.5 text-stone-900 focus:border-orange-500 focus:ring-orange-500">
                                        </td>
                                        <td class="p-2 text-center">
                                            <button type="button" onclick="removeCommissionRow(this)" class="text-stone-400 hover:text-rose-600 font-bold text-base">&times;</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Hidden Total Inputs for Controller Storage -->
                        <input type="hidden" name="xyz_commission" id="form_commission_total" value="0">
                        <input type="hidden" name="our_commission" id="form_our_commission" value="0">
                        <input type="hidden" name="other_commission" id="form_other_commission" value="0">
                        <input type="hidden" name="total_deduction" id="form_total_deduction" value="0">
                        <input type="hidden" name="amount_to_paid_department" id="form_paid_dept" value="0">
                        <input type="hidden" name="vendor_profit" id="form_vendor_profit" value="0">
                    </div>

                    <!-- SUBMIT BUTTON BAR -->
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('cheques.index') }}" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:bg-stone-100 rounded-xl transition">
                            Cancel
                        </a>

                        <button type="submit" class="px-6 py-2.5 text-xs font-black bg-orange-600 hover:bg-orange-500 text-white rounded-xl shadow-xs transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Save Cheque Record & Sync</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Calculations Script -->
    <script>
        function onDeptSelect(select) {
            const opt = select.options[select.selectedIndex];
            if (opt && opt.dataset.costCenter) {
                document.getElementById('form_cost_center').value = opt.dataset.costCenter;
            }
            if (opt && opt.dataset.name) {
                document.getElementById('form_dept_name').value = opt.dataset.name;
            }
        }

        function autoFillFromBill(select) {
            const opt = select.options[select.selectedIndex];
            if (!opt || !opt.value) return;

            if (opt.dataset.amount) {
                document.getElementById('form_bill_amt').value = opt.dataset.amount;
                document.getElementById('form_cheque_amt').value = opt.dataset.amount;
            }
            if (opt.dataset.company) {
                document.getElementById('form_company_name').value = opt.dataset.company;
            }
            if (opt.dataset.deptId) {
                document.getElementById('form_dept_id').value = opt.dataset.deptId;
            }
            if (opt.dataset.costCenter) {
                document.getElementById('form_cost_center').value = opt.dataset.costCenter;
            }
            if (opt.dataset.deptName) {
                document.getElementById('form_dept_name').value = opt.dataset.deptName;
            }
            calcChequeDeductions();
        }

        function addCommissionRow() {
            const tbody = document.getElementById('commission-rows-body');
            const idx = tbody.querySelectorAll('.commission-row').length;
            const tr = document.createElement('tr');
            tr.className = 'commission-row hover:bg-stone-50/70 transition';
            tr.innerHTML = `
                <td class="p-2 text-center font-bold text-stone-400 row-idx">${idx + 1}</td>
                <td class="p-2">
                    <select name="commissions[${idx}][type]" class="comm-type w-full text-xs font-bold rounded-lg border-stone-300 py-1.5 focus:border-orange-500 focus:ring-orange-500" onchange="calcChequeDeductions()">
                        <option value="our">Our Commission</option>
                        <option value="other" selected>Other Commission</option>
                    </select>
                </td>
                <td class="p-2">
                    <input type="text" name="commissions[${idx}][title]" placeholder="e.g. Partner Commission, Coordinator" class="comm-title w-full text-xs font-semibold rounded-lg border-stone-300 py-1.5 focus:border-orange-500 focus:ring-orange-500">
                </td>
                <td class="p-2">
                    <input type="number" step="0.01" name="commissions[${idx}][rate]" placeholder="e.g. 5" oninput="calcCommissionRow(this)" class="comm-rate w-full text-xs font-mono rounded-lg border-stone-300 py-1.5 focus:border-orange-500 focus:ring-orange-500">
                </td>
                <td class="p-2 text-right">
                    <input type="number" step="0.01" name="commissions[${idx}][amount]" value="0" oninput="calcChequeDeductions()" class="comm-amt w-full text-xs font-black font-mono text-right rounded-lg border-stone-300 py-1.5 text-stone-900 focus:border-orange-500 focus:ring-orange-500">
                </td>
                <td class="p-2 text-center">
                    <button type="button" onclick="removeCommissionRow(this)" class="text-stone-400 hover:text-rose-600 font-bold text-base">&times;</button>
                </td>
            `;
            tbody.appendChild(tr);
        }

        function removeCommissionRow(btn) {
            const tbody = document.getElementById('commission-rows-body');
            if (tbody.querySelectorAll('.commission-row').length <= 1) {
                alert('At least one commission row must remain.');
                return;
            }
            btn.closest('tr').remove();
            tbody.querySelectorAll('.commission-row').forEach((row, i) => {
                row.querySelector('.row-idx').textContent = i + 1;
            });
            calcChequeDeductions();
        }

        function calcCommissionRow(rateInput) {
            const row = rateInput.closest('.commission-row');
            const rate = parseFloat(rateInput.value) || 0;
            const chequeAmt = parseFloat(document.getElementById('form_cheque_amt').value) || 0;
            const amtInput = row.querySelector('.comm-amt');
            if (chequeAmt > 0 && rate > 0) {
                amtInput.value = ((chequeAmt * rate) / 100).toFixed(2);
            }
            calcChequeDeductions();
        }

        function onAgprPurchasedChange() {
            const gst = parseFloat(document.getElementById('form_gst').value) || 0;
            const agprGst = parseFloat(document.getElementById('form_agpr_purchased_gst').value) || 0;
            if (agprGst > 0 && gst > 0) {
                document.getElementById('form_gst_diff').value = Math.max(0, gst - agprGst).toFixed(2);
            }
            calcChequeDeductions();
        }

        function onAgprRepairedChange() {
            const sst = parseFloat(document.getElementById('form_sst').value) || 0;
            const agprSst = parseFloat(document.getElementById('form_agpr_repaired_sst').value) || 0;
            if (agprSst > 0 && sst > 0) {
                document.getElementById('form_sst_diff').value = Math.max(0, sst - agprSst).toFixed(2);
            }
            calcChequeDeductions();
        }

        function onItaxChange() {
            const itax55 = parseFloat(document.getElementById('form_itax_5_5').value) || 0;
            const itax15 = parseFloat(document.getElementById('form_itax_15').value) || 0;
            const totalItax = itax55 + itax15;
            document.getElementById('form_itax').value = totalItax.toFixed(2);
            calcChequeDeductions();
        }

        function calcChequeDeductions() {
            const chequeAmt = parseFloat(document.getElementById('form_cheque_amt').value) || 0;
            const gst = parseFloat(document.getElementById('form_gst').value) || 0;
            const itax55 = parseFloat(document.getElementById('form_itax_5_5')?.value) || 0;
            const itax15 = parseFloat(document.getElementById('form_itax_15')?.value) || 0;
            const itax = itax55 + itax15 > 0 ? (itax55 + itax15) : (parseFloat(document.getElementById('form_itax').value) || 0);
            if (document.getElementById('form_itax')) {
                document.getElementById('form_itax').value = itax.toFixed(2);
            }
            const sst = parseFloat(document.getElementById('form_sst').value) || 0;

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
            const subOur = document.getElementById('subtotal_our_comm');
            if (subOur) subOur.textContent = fmt(ourComm);
            const subOther = document.getElementById('subtotal_other_comm');
            if (subOther) subOther.textContent = fmt(otherComm);

            document.getElementById('total_commission_display').textContent = fmt(totalComm);
            document.getElementById('form_commission_total').value = totalComm.toFixed(2);
            const ourInput = document.getElementById('form_our_commission');
            if (ourInput) ourInput.value = ourComm.toFixed(2);
            const otherInput = document.getElementById('form_other_commission');
            if (otherInput) otherInput.value = otherComm.toFixed(2);

            const totalDeduction = gst + itax + sst + totalComm;
            document.getElementById('form_total_deduction').value = totalDeduction.toFixed(2);

            const paidDept = Math.max(0, chequeAmt - totalComm);
            document.getElementById('form_paid_dept').value = paidDept.toFixed(2);

            const vendorProfitInput = document.getElementById('form_vendor_profit');
            if (vendorProfitInput) {
                vendorProfitInput.value = totalComm.toFixed(2);
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
            const billSelect = document.getElementById('bill_selector');
            if (billSelect && billSelect.value) {
                autoFillFromBill(billSelect);
            }
        });
    </script>
</x-app-layout>
