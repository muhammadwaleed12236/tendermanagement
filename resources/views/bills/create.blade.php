<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('bills.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Back to Bills Registry</a>
        </div>
        <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
            {{ __('Generate Departmental Product Invoice / Bill') }}
        </h2>
        <p class="text-xs text-slate-500 mt-1">Record and verify invoice products against the department's authorized quarterly budget ceiling.</p>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Error -->
            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm space-y-1.5 shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-rose-900">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Please correct the following fields before saving:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs font-semibold text-rose-700 pl-2 space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('bills.store') }}" enctype="multipart/form-data" id="bill-form" class="space-y-6">
                @csrf

                <!-- CARD 1: Department & Invoice Information (Clean 1-Line Layout) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">1</span>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Department & Invoice Details</h3>
                        </div>
                        <span class="text-xs text-slate-400">All fields marked with <span class="text-rose-500">*</span> are mandatory</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                        <!-- 1. Target Department -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase">Target Department <span class="text-rose-500">*</span></label>
                            </div>
                            <select name="department_id" id="department_select" required class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dept)
                                    @php
                                        $avail = $dept->availableReleasedBudget();
                                    @endphp
                                    <option value="{{ $dept->id }}" 
                                            data-available="{{ $avail }}"
                                            data-code="{{ $dept->code }}"
                                            data-name="{{ $dept->name }}"
                                            data-cost-center="{{ $dept->cost_center_description ?: ('Office of the ' . $dept->name) }}"
                                            data-city="{{ $dept->city ?: 'Karachi.' }}"
                                            {{ (old('department_id', $selectedDeptId) == $dept->id) ? 'selected' : '' }}>
                                        [{{ $dept->code }}] {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-1.5 flex items-center justify-between text-[11px]">
                                <span class="text-slate-500 font-medium">Released Funds:</span>
                                <span id="budget-badge-val" class="font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Avail: PKR 0.00</span>
                            </div>
                            @error('department_id') <span class="text-[11px] text-rose-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- 2. Department Budget Head (Product) -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase flex items-center gap-1">
                                    <span>Head / Product</span>
                                    <span class="text-indigo-600 font-extrabold text-[10px] bg-indigo-50 px-1.5 py-0.2 rounded border border-indigo-200">Dept Budget</span>
                                </label>
                                <button type="button" onclick="openNewHeadModal()" class="text-[11px] font-bold text-blue-600 hover:text-blue-800 flex items-center gap-0.5">
                                    <span>+ New Head</span>
                                </button>
                            </div>
                            <select name="budget_head_id" id="budget_head_select" onchange="handleBudgetHeadChange()" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5 text-slate-800 bg-white">
                                <option value="">-- Select Product / Head --</option>
                            </select>
                            <div class="mt-1.5 flex items-center justify-between text-[11px]">
                                <span class="text-slate-500 font-medium">Head Balance:</span>
                                <span id="head-badge-val" class="font-mono font-bold text-slate-600 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">Select Dept</span>
                            </div>
                        </div>

                        <!-- 3. Quarter & Cost Center -->
                        <div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Quarter</label>
                                    <select name="quarter" id="quarter_select" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5 bg-white">
                                        <option value="1st Qtr" {{ old('quarter', '1st Qtr') == '1st Qtr' ? 'selected' : '' }}>1st Qtr</option>
                                        <option value="2nd Qtr" {{ old('quarter') == '2nd Qtr' ? 'selected' : '' }}>2nd Qtr</option>
                                        <option value="3rd Qtr" {{ old('quarter') == '3rd Qtr' ? 'selected' : '' }}>3rd Qtr</option>
                                        <option value="4th Qtr" {{ old('quarter') == '4th Qtr' ? 'selected' : '' }}>4th Qtr</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Cost Center</label>
                                    <input type="text" name="cost_center" id="cost_center_input" value="{{ old('cost_center') }}" placeholder="e.g. KA-8914" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5">
                                </div>
                            </div>
                            <div class="mt-1.5 text-[11px] text-slate-400">
                                Financial quarter & cost center code
                            </div>
                        </div>

                        <!-- 4. Bill / Cash Memo # & Date -->
                        <div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Bill # <span class="text-rose-500">*</span></label>
                                    <input type="text" name="bill_number" value="{{ old('bill_number', $suggestedBillNumber ?? '11710') }}" placeholder="e.g. 10885" required class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono py-2.5 @error('bill_number') border-rose-500 @enderror">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Bill Date <span class="text-rose-500">*</span></label>
                                    <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5">
                                </div>
                            </div>
                            @error('bill_number') 
                                <span class="text-[11px] text-rose-600 font-semibold block mt-1">{{ $message }}</span>
                            @else
                                <div class="mt-1 text-[11px] text-slate-400">
                                    Voucher number & invoice creation date
                                </div>
                            @enderror
                            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] font-bold text-slate-700 uppercase">Bill Status:</span>
                                <div class="inline-flex rounded-lg border border-slate-200 p-0.5 bg-slate-100 text-[11px] font-bold">
                                    <label class="cursor-pointer px-2.5 py-1 rounded-md flex items-center gap-1 text-emerald-800 has-[:checked]:bg-emerald-600 has-[:checked]:text-white transition">
                                        <input type="radio" name="status" value="approved" class="sr-only" checked>
                                        <span>✓ Confirmed</span>
                                    </label>
                                    <label class="cursor-pointer px-2.5 py-1 rounded-md flex items-center gap-1 text-slate-600 has-[:checked]:bg-amber-500 has-[:checked]:text-white transition">
                                        <input type="radio" name="status" value="pending" class="sr-only">
                                        <span>Draft</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Head of Account & 1-Click Auto-Fill Bar -->
                    <div id="head-active-banner" class="hidden p-3.5 bg-gradient-to-r from-blue-50/90 via-indigo-50/70 to-emerald-50/80 rounded-xl border-2 border-blue-200 mt-4 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-[#0056d2] text-white flex items-center justify-center font-black text-base shrink-0 shadow-xs">
                                🎯
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-black text-slate-900" id="banner-head-name">Transport</span>
                                    <span class="font-mono text-[11px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-900 border border-blue-200" id="banner-head-code">A03806</span>
                                    <span class="text-[11px] font-black px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300" id="banner-head-balance-pill">Available: PKR 20,000</span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-2.5 flex-wrap">
                                    <span>Sanctioned / Released: <strong class="text-slate-800 font-mono" id="banner-head-released">PKR 20,000</strong></span>
                                    <span>•</span>
                                    <span>Already Spent: <strong class="text-slate-800 font-mono" id="banner-head-spent">PKR 0.00</strong></span>
                                    <span>•</span>
                                    <span class="text-emerald-700 font-bold">You can create bill up to this sanctioned amount</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-start md:self-auto">
                            <button type="button" onclick="autoFillBillFromHead()" class="px-4 py-2 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>⚡ 1-Click: Create Bill for this Head (<span id="btn-head-amount">PKR 20,000</span>)</span>
                            </button>
                        </div>
                    </div>

                    <!-- Head Details Inputs (Code & Description - Auto-Synced with dropdown, editable) -->
                    <div class="mt-3 pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Head of Account Code <span class="text-slate-400 font-normal">(e.g. A03806)</span></label>
                            <input type="text" name="head_code" id="head_code" value="{{ old('head_code', 'A03970') }}" placeholder="e.g. A03806" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2 bg-white">
                        </div>
                        <div class="sm:col-span-1 lg:col-span-2">
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Head Description / Product Title <span class="text-slate-400 font-normal">(e.g. Transportation of Goods)</span></label>
                            <input type="text" name="head_description" id="head_description" value="{{ old('head_description', 'Others') }}" placeholder="e.g. Transportation of Goods" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2 bg-white">
                        </div>
                        <input type="hidden" name="head_of_account" id="head_of_account" value="{{ old('head_of_account') }}">
                    </div>

                    <!-- Company / Supplier Setup Selection -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </span>
                                <div>
                                    <label class="text-xs font-bold text-slate-800 uppercase tracking-wider block">
                                        Billing Company / Supplier Setup <span class="text-rose-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-slate-400 font-medium">Select company to auto-fill Vendor #, NTN, GST, & SST</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" onclick="openNewCompanyModal()" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-lg border border-blue-200 transition shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span>+ New Company</span>
                                </button>
                                <a href="{{ route('companies.index') }}" target="_blank" class="text-[11px] text-slate-500 hover:text-blue-600 font-semibold underline">
                                    Manage Companies &rarr;
                                </a>
                            </div>
                        </div>

                        <!-- Dropdown & Direct Fields Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end bg-slate-50/70 p-3.5 rounded-xl border border-slate-200">
                            <!-- Company Selector (4 cols) -->
                            <div class="md:col-span-4">
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Company / Supplier Name</label>
                                <select name="company_id" id="company_select" onchange="updateCompanyDetails()" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-2 text-slate-800 bg-white shadow-2xs">
                                    @foreach($companies as $comp)
                                        <option value="{{ $comp->id }}" 
                                                data-name="{{ $comp->company_name }}"
                                                data-vendor-no="{{ $comp->vendor_no }}"
                                                data-ntn="{{ $comp->ntn }}"
                                                data-gst="{{ $comp->gst }}"
                                                data-sst="{{ $comp->sst }}"
                                                data-address="{{ $comp->address }}"
                                                data-phone="{{ $comp->contact_number }}"
                                                data-is-default="{{ $comp->is_default ? '1' : '0' }}"
                                                {{ (old('company_id', $defaultCompany?->id) == $comp->id) ? 'selected' : '' }}>
                                            {{ $comp->company_name }} {{ $comp->is_default ? '(Default)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Vendor No (AGPR) (2 cols) -->
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Vendor # (AGPR)</label>
                                <input type="text" name="agpr_vendor_no" id="company_vendor_no" value="{{ old('agpr_vendor_no', $defaultCompany?->vendor_no ?? '30368984') }}" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 bg-white" placeholder="e.g. 30368984">
                            </div>

                            <!-- NTN # (2 cols) -->
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">NTN #</label>
                                <input type="text" name="ntn_number" id="company_ntn" value="{{ old('ntn_number', $defaultCompany?->ntn ?? '3437101-0') }}" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 bg-white" placeholder="e.g. 3437101-0">
                            </div>

                            <!-- GST / STRN # (2 cols) -->
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">GST / STRN #</label>
                                <input type="text" name="strn_number" id="company_gst" value="{{ old('strn_number', $defaultCompany?->gst ?? '3277876289057') }}" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 bg-white" placeholder="e.g. 3277876289057">
                            </div>

                            <!-- SST / SRB # (2 cols) -->
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">SST #</label>
                                <input type="text" name="sst_number" id="company_sst" value="{{ old('sst_number', $defaultCompany?->sst ?? 'S3437101-2') }}" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 bg-white" placeholder="e.g. S3437101-2">
                            </div>
                        </div>

                        <!-- Company Meta Quick Details Strip -->
                        <div id="company_meta_strip" class="text-[11px] text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-1 px-1">
                            <span><strong class="text-slate-700">Address:</strong> <span id="company_address_text">{{ $defaultCompany?->address ?: 'Office # 1/2 Block # 6, PECHS Nursery Karachi' }}</span></span>
                            <span>•</span>
                            <span><strong class="text-slate-700">Phone:</strong> <span id="company_phone_text">{{ $defaultCompany?->contact_number ?: '0331-2449892' }}</span></span>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Products & Line Items (Scrollable Systematic Table) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">2</span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Products & Invoice Line Items</h3>
                                <p class="text-[11px] text-slate-400">Add multiple items, units, quantities, and rates for this bill</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <span id="items-counter" class="text-xs font-semibold text-slate-500 px-2.5 py-1 bg-slate-100 rounded-lg">
                                1 Product Item
                            </span>
                            <button type="button" onclick="togglePastePanel()" id="toggle-paste-btn" class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-300 shadow-2xs transition flex items-center gap-1.5" title="Copy Excel rows (description, qty, price) and paste directly into invoice">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span>Paste from Excel / Text</span>
                            </button>
                            <button type="button" onclick="openCatalogModal()" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-lg border border-blue-200 shadow-2xs transition flex items-center gap-1.5" title="Browse and multi-select from imported products">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                <span>Pick from Catalog</span>
                                @if(isset($products) && $products->count() > 0)
                                    <span class="bg-blue-600 text-white rounded-full text-[10px] px-1.5 py-0.2">{{ $products->count() }}</span>
                                @endif
                            </button>
                            <button type="button" onclick="addProductRow()" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                + Add Product Item
                            </button>
                        </div>
                    </div>

                    <!-- Quick Excel / Multi-Line Text Paste Panel -->
                    <div id="quick-paste-panel" class="p-4 bg-gradient-to-r from-emerald-50/90 to-teal-50/70 border-2 border-dashed border-emerald-300 rounded-2xl space-y-3 transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </span>
                                <div>
                                    <h4 class="text-xs font-bold text-emerald-950 flex items-center gap-2">
                                        <span>Quick Paste from Excel (Description, Qty, Price)</span>
                                        <span class="text-[10px] bg-emerald-200/70 text-emerald-800 font-semibold px-2 py-0.5 rounded-full">Live Table Auto-Populate</span>
                                    </h4>
                                    <p class="text-[11px] text-emerald-700">Excel sheet se cells copy karke yahan paste kar dyn (Ctrl+V), ye foran invoice table me populate kar dega.</p>
                                </div>
                            </div>

                            <button type="button" onclick="togglePastePanel()" class="text-xs text-slate-400 hover:text-slate-700 font-bold px-2 py-1 rounded hover:bg-slate-100 transition">✕ Hide</button>
                        </div>

                        <div class="space-y-2">
                            <textarea id="excel-paste-input" rows="3" 
                                      placeholder="Paste Excel cells here... Example:&#10;Cat6 Shielded Ethernet Cable 305m Roll&#9;12&#9;18500&#10;A4 Photocopy Paper 80gsm 500 Sheets&#9;&#9;1450&#10;Heavy Duty Office Executive Chair&#9;&#9;24000&#10;Ballpoint Pens Blue Box of 10&#9;&#9;450"
                                      class="w-full text-xs font-mono rounded-xl border-emerald-300 focus:border-emerald-600 focus:ring-emerald-600 placeholder-slate-400 bg-white shadow-inner"
                                      oninput="handleExcelPasteInput()"></textarea>

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                                <div class="flex flex-wrap items-center gap-4 text-xs">
                                    <label class="flex items-center gap-1.5 cursor-pointer text-slate-700 font-semibold text-[11px]">
                                        <input type="radio" name="paste_mode" value="append" checked class="text-emerald-600 focus:ring-emerald-500">
                                        <span>Append to Table</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-slate-700 font-semibold text-[11px]">
                                        <input type="radio" name="paste_mode" value="replace" class="text-emerald-600 focus:ring-emerald-500">
                                        <span>Replace Existing Rows</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-emerald-800 font-semibold text-[11px]">
                                        <input type="checkbox" id="save-to-catalog-check" checked class="rounded text-emerald-600 focus:ring-emerald-500">
                                        <span>Save to Products Catalog too</span>
                                    </label>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span id="paste-live-status" class="text-xs font-bold text-emerald-800 font-mono"></span>
                                    <button type="button" onclick="clearPasteInput()" class="px-2.5 py-1 text-xs text-slate-500 hover:text-slate-800 font-bold">Clear</button>
                                    <button type="button" onclick="applyExcelPasteToInvoice()" id="apply-paste-btn" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span>Insert into Invoice</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datalist for Instant Autocomplete from Products Catalog -->
                    <datalist id="products-catalog-list">
                        @if(isset($products))
                            @foreach($products as $p)
                                <option value="{{ $p->description }}"></option>
                            @endforeach
                        @endif
                    </datalist>

                    <!-- Scrollable Table Container (Both Horizontal & Vertical Scroll) -->
                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm" style="overflow-x: auto; max-height: 400px; overflow-y: auto;">
                        <table class="w-full text-left border-collapse text-xs" id="items-table" style="min-width: 780px;">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="p-3 w-10 text-center bg-slate-50">#</th>
                                    <th class="p-3 bg-slate-50" style="min-width: 280px;">Product / Item Description <span class="text-rose-500">*</span></th>
                                    <th class="p-3 w-28 bg-slate-50">Unit</th>
                                    <th class="p-3 w-24 bg-slate-50">Qty <span class="text-rose-500">*</span></th>
                                    <th class="p-3 w-36 bg-slate-50">Unit Price (PKR) <span class="text-rose-500">*</span></th>
                                    <th class="p-3 w-36 text-right bg-slate-50">Total (PKR)</th>
                                    <th class="p-3 w-12 text-center bg-slate-50"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="items-body">
                                <!-- Row 1 -->
                                <tr class="item-row hover:bg-slate-50/50 transition">
                                    <td class="p-3 text-center font-bold text-slate-400 row-num">1</td>
                                    <td class="p-3">
                                        <input type="text" name="items[0][item_name]" list="products-catalog-list" oninput="handleProductSelect(this)" required placeholder="Type description or pick from catalog..." class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="p-3">
                                        <select name="items[0][unit]" class="w-full text-xs rounded-lg border-slate-200">
                                            <option value="Nos">Nos</option>
                                            <option value="Units">Units</option>
                                            <option value="Pcs">Pcs</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Box">Box</option>
                                            <option value="Pack">Pack</option>
                                            <option value="Meters">Meters</option>
                                            <option value="Set">Set</option>
                                            <option value="LumpSum">LumpSum</option>
                                        </select>
                                    </td>
                                    <td class="p-3">
                                        <input type="number" step="0.01" min="0.01" name="items[0][quantity]" value="1" required class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 item-qty font-bold" oninput="calcRow(this)">
                                    </td>
                                    <td class="p-3">
                                        <input type="number" step="0.01" min="0" name="items[0][unit_price]" value="0" required class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 item-price font-bold" oninput="calcRow(this)">
                                    </td>
                                    <td class="p-3 text-right">
                                        <span class="font-extrabold text-slate-800 row-total font-mono text-xs">0.00</span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <button type="button" onclick="removeRow(this)" class="text-slate-300 hover:text-rose-500 transition remove-btn" title="Remove item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between text-[11px] text-slate-400 gap-2 pt-1">
                        <div class="flex items-center gap-3">
                            <span>💡 Start typing to see suggestions from catalog, or click <strong>Pick from Catalog</strong>.</span>
                            <a href="{{ route('products.index') }}" target="_blank" class="text-blue-600 font-bold hover:underline flex items-center gap-1" title="Create or import more products">
                                <span>Manage / Import Catalog</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                        <button type="button" onclick="addProductRow()" class="text-indigo-600 font-bold hover:underline">+ Add Another Line Item</button>
                    </div>
                </div>

                <!-- Catalog Multi-Select Modal -->
                <div id="catalog-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden animate-in fade-in zoom-in duration-150">
                        <!-- Modal Header -->
                        <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                                    <svg width="20" height="20" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">Select Products from Catalog</h4>
                                    <p class="text-[11px] text-slate-500">Pick one or more products to insert into this bill</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeCatalogModal()" class="w-8 h-8 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Modal Search & Selection Controls -->
                        <div class="p-3 bg-white border-b border-slate-100 flex items-center justify-between gap-3">
                            <div class="relative flex-1">
                                <input type="text" id="catalog-search" oninput="filterCatalogModal()" placeholder="Search product description..." class="w-full text-xs rounded-xl border-slate-300 pl-8 py-2">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" onclick="toggleSelectAllCatalog(true)" class="text-[11px] font-bold text-blue-600 hover:underline">Select All</button>
                                <span class="text-slate-300">|</span>
                                <button type="button" onclick="toggleSelectAllCatalog(false)" class="text-[11px] font-bold text-slate-500 hover:underline">Clear</button>
                            </div>
                        </div>

                        <!-- Modal Products List -->
                        <div class="p-2 overflow-y-auto flex-1 divide-y divide-slate-100" id="catalog-list">
                            @if(isset($products) && $products->isNotEmpty())
                                @foreach($products as $p)
                                    <label class="catalog-item flex items-center justify-between p-3 rounded-xl hover:bg-blue-50/50 cursor-pointer transition select-none" data-search="{{ strtolower($p->description . ' ' . $p->head_of_account) }}">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" class="catalog-check rounded text-blue-600 focus:ring-blue-500" 
                                                   data-description="{{ $p->description }}" 
                                                   data-head="{{ $p->head_of_account }}"
                                                   data-unit="{{ $p->unit ?: 'Nos' }}" 
                                                   data-price="{{ $p->default_price ?: 0 }}">
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800">{{ $p->description }}</p>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <span class="text-[10px] text-slate-400">Unit: {{ $p->unit ?: 'Nos' }}</span>
                                                    @if($p->head_of_account)
                                                        <span class="text-[9px] font-mono font-semibold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">
                                                            {{ $p->head_of_account }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if($p->default_price > 0)
                                            <span class="text-xs font-mono font-bold text-slate-600">PKR {{ number_format($p->default_price, 2) }}</span>
                                        @endif
                                    </label>
                                @endforeach
                            @else
                                <div class="p-8 text-center text-slate-400">
                                    <p class="text-xs font-semibold">No products saved in catalog yet.</p>
                                    <a href="{{ route('products.index') }}" target="_blank" class="text-blue-600 text-xs font-bold hover:underline mt-1 inline-block">+ Create or import products now</a>
                                </div>
                            @endif
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                            <span class="text-xs text-slate-500" id="catalog-selected-count">0 items selected</span>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="closeCatalogModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-xl transition">Cancel</button>
                                <button type="button" onclick="insertSelectedProducts()" class="px-4 py-2 text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm transition flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span>Insert Selected Items</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: Calculations & Live Budget Compliance Guard -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <!-- Left: Budget Compliance Guard Alert Box -->
                    <div id="budget-alert-box" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Department Budget Compliance</span>
                            <span id="budget-status-pill" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                Pending Selection
                            </span>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Released Funds Ceiling:</span>
                                <span id="comp-avail" class="font-bold text-slate-800 font-mono">PKR 0.00</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Total Payable for this Bill:</span>
                                <span id="comp-bill" class="font-bold text-slate-900 font-mono">PKR 0.00</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                                <span class="font-semibold text-slate-700">Estimated Balance After Bill:</span>
                                <span id="comp-rem" class="font-black text-indigo-700 font-mono">PKR 0.00</span>
                            </div>

                            <!-- Head of Account Budget Compliance Details -->
                            <div id="comp-head-box" class="hidden pt-2 border-t border-blue-100 space-y-1.5 bg-blue-50/50 p-2.5 rounded-lg border border-blue-100">
                                <div class="flex justify-between items-center text-blue-900 font-bold">
                                    <span>Head (<span id="comp-head-name">Transport</span>) Ceiling:</span>
                                    <span id="comp-head-avail" class="font-mono">PKR 0.00</span>
                                </div>
                                <div class="flex justify-between items-center text-slate-700">
                                    <span>Head Balance After Bill:</span>
                                    <span id="comp-head-rem" class="font-bold font-mono">PKR 0.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-500 leading-relaxed pt-2 border-t border-slate-100" id="budget-alert-text">
                            Please select a target department to verify that this bill amount stays strictly within the authorized released budget.
                        </div>
                    </div>

                    <!-- Right: Pricing Totals Card (Dual % & PKR Inputs) -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3.5">
                        <div class="text-xs font-bold text-slate-800 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center justify-between">
                            <span>Invoice Summary & Deductions</span>
                            <span class="text-[10px] text-slate-400 font-medium">Editable in % or PKR</span>
                        </div>

                        <!-- 1. Gross Subtotal -->
                        <div class="flex justify-between items-center text-xs py-1">
                            <span class="font-bold text-slate-700">Items Gross Subtotal:</span>
                            <span class="font-black text-slate-900 text-sm font-mono" id="subtotal_display">PKR 0.00</span>
                        </div>

                        <!-- 2. Sales Tax GST (Dual % and PKR) -->
                        <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-800">Sales Tax GST</span>
                                    <span class="text-[10px] font-semibold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100">Default 18%</span>
                                </div>
                                <span class="text-xs font-extrabold text-indigo-700 font-mono" id="gst_badge_val">+ PKR 0.00</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 items-center">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rate (%):</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" name="gst_rate" id="gst_rate" value="18" 
                                               class="w-full text-xs font-bold rounded-lg border-slate-300 pr-8 py-2 text-right font-mono focus:border-indigo-500 focus:ring-indigo-500" 
                                               placeholder="18"
                                               oninput="handleGstRateChange(this.value)">
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Or Amount (PKR):</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400 pointer-events-none">PKR</span>
                                        <input type="number" step="0.01" name="gst_amount" id="gst_amount" value="0.00" 
                                               class="w-full text-xs font-black rounded-lg border-slate-300 pl-11 py-2 text-right font-mono text-indigo-700 focus:border-indigo-500 focus:ring-indigo-500" 
                                               placeholder="0.00"
                                               oninput="handleGstAmountChange(this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Withholding Deductions (Dual % and PKR) -->
                        <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-800">Less: Withholding Deductions</span>
                                    <span class="text-[10px] font-semibold text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">Optional</span>
                                </div>
                                <span class="text-xs font-extrabold text-rose-600 font-mono" id="deduction_badge_val">- PKR 0.00</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 items-center">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Deduction Rate (%):</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" id="deduction_rate" value="0" 
                                               class="w-full text-xs font-bold rounded-lg border-slate-300 pr-8 py-2 text-right font-mono text-rose-600 focus:border-rose-500 focus:ring-rose-500" 
                                               placeholder="0"
                                               oninput="handleDeductionRateChange(this.value)">
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Or Deduction Amount (PKR):</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400 pointer-events-none">PKR</span>
                                        <input type="number" step="0.01" name="tax_deduction" id="tax_deduction" value="0" 
                                               class="w-full text-xs font-black rounded-lg border-slate-300 pl-11 py-2 text-right font-mono text-rose-600 focus:border-rose-500 focus:ring-rose-500" 
                                               placeholder="0.00"
                                               oninput="handleDeductionAmountChange(this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Total Payable Amount -->
                        <div class="p-3.5 bg-emerald-50/70 rounded-xl border border-emerald-200 flex justify-between items-center">
                            <div>
                                <span class="font-black text-slate-900 uppercase tracking-wider text-xs block">Total Bill Amount Payable:</span>
                                <span class="text-[10px] text-emerald-800 font-medium">(Gross + GST - Deductions)</span>
                            </div>
                            <span class="text-xl font-black text-emerald-700 font-mono" id="net_display">PKR 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- CARD 4: Purpose & Attachments -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">3</span>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Expenditure Purpose & Supporting Document</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expenditure Purpose / Justification Notes <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <textarea name="milestone_description" rows="3" placeholder="Procurement of goods / store items as per bill..." class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500">{{ old('milestone_description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Supporting Document / Invoice Scan (PDF / Image)</label>
                            <input type="file" name="attachment" accept=".pdf,.docx,.zip,.jpg,.png" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-lg p-1">
                            <p class="text-[11px] text-slate-400 mt-1">Upload verified delivery challan, vendor bill, or inspection memo.</p>
                        </div>
                    </div>

                    <!-- Daily Billing Report Fields: Dispatch Date & Remarks -->
                    <div class="pt-4 border-t border-slate-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bill Dispatch Date <span class="text-slate-400 font-normal">(Daily Report)</span></label>
                                <input type="date" name="dispatch_date" value="{{ old('dispatch_date') }}" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5">
                                <p class="text-[11px] text-slate-400 mt-1">Date when bill voucher was dispatched to the department.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Dispatch Remarks / Mode <span class="text-slate-400 font-normal">(e.g. Rider By Hand)</span></label>
                                <input type="text" name="remarks" value="{{ old('remarks', 'Rider By Hand') }}" placeholder="e.g. Rider By Hand, TCS, Hand Delivery" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5">
                                <p class="text-[11px] text-slate-400 mt-1">Carrier method or special delivery remarks.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Systematic Submit Bar -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <a href="{{ route('bills.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                        &larr; Cancel & Return
                    </a>
                    <button type="submit" id="submit-btn" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-2">
                        <span>Confirm & Submit Bill</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Quick Add New Budget Head / Product (Placed cleanly outside main bill-form) -->
    <div id="new-head-modal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                        🎯
                    </span>
                    <div>
                        <h3 class="text-sm font-bold tracking-tight">Add Budget Head / Product</h3>
                        <p class="text-[10px] text-slate-300" id="quick-head-dept-name">Department Head Allocation</p>
                    </div>
                </div>
                <button type="button" onclick="closeNewHeadModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">✕</button>
            </div>

            <form id="quick-head-form" onsubmit="handleNewHeadSubmit(event)" class="p-5 space-y-3.5">
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Head Code</label>
                        <input type="text" id="quick_head_code" placeholder="e.g. A03806" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Head Name / Product <span class="text-rose-500">*</span></label>
                        <input type="text" id="quick_head_name" required placeholder="e.g. Transportation of Goods" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Released / Sanctioned (PKR) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" id="quick_head_released" required placeholder="e.g. 20000" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2" oninput="if(!document.getElementById('quick_head_original').dataset.manual) document.getElementById('quick_head_original').value = this.value">
                        <p class="text-[10px] text-slate-400 mt-1">Available funds allocated for this product/head</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Total / Original Grant (PKR)</label>
                        <input type="number" step="0.01" min="0" id="quick_head_original" placeholder="e.g. 20000" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2" oninput="this.dataset.manual = 'true'">
                        <p class="text-[10px] text-slate-400 mt-1">Full financial year allocation (defaults to released)</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Financial Year</label>
                        <input type="text" id="quick_head_year" value="2026" placeholder="2026" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Notes / Remarks</label>
                        <input type="text" id="quick_head_notes" placeholder="e.g. Sanctioned grant for transport" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2">
                    </div>
                </div>

                <div id="quick-head-error" class="text-xs text-rose-600 font-semibold hidden"></div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeNewHeadModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition">Cancel</button>
                    <button type="submit" id="quick-head-btn" class="px-5 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow-sm flex items-center gap-1.5">
                        <span>Save & Use Head</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Quick Add New Company (Placed cleanly outside main bill-form) -->
    <div id="new-company-modal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold tracking-tight">Register New Company / Supplier</h3>
                        <p class="text-[10px] text-slate-300">Add company details for instant bill selection</p>
                    </div>
                </div>
                <button type="button" onclick="closeNewCompanyModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">✕</button>
            </div>

            <form id="quick-company-form" onsubmit="handleQuickCompanySubmit(event)" class="p-5 space-y-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Company Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="quick_comp_name" required placeholder="e.g. M/S. Apex Tech Solutions" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Vendor No (AGPR)</label>
                        <input type="text" id="quick_comp_vendor_no" placeholder="e.g. 30368984" class="w-full text-xs font-mono font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NTN Number</label>
                        <input type="text" id="quick_comp_ntn" placeholder="e.g. 3437101-0" class="w-full text-xs font-mono font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">GST / STRN Number</label>
                        <input type="text" id="quick_comp_gst" placeholder="e.g. 3277876289057" class="w-full text-xs font-mono font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">SST / SRB Number</label>
                        <input type="text" id="quick_comp_sst" placeholder="e.g. S3437101-2" class="w-full text-xs font-mono font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Contact Phone</label>
                        <input type="text" id="quick_comp_phone" placeholder="e.g. 0331-2449892" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email</label>
                        <input type="email" id="quick_comp_email" placeholder="e.g. info@company.com" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Company Address</label>
                    <input type="text" id="quick_comp_address" placeholder="e.g. Office # 1/2 Block # 6, PECHS Karachi" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-blue-500 py-2">
                </div>

                <div id="quick-comp-error" class="text-xs text-rose-600 font-semibold hidden"></div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeNewCompanyModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition">Cancel</button>
                    <button type="submit" id="quick-comp-btn" class="px-5 py-2 text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm flex items-center gap-1.5">
                        <span>Save & Use Company</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Interactive JavaScript for Dynamic Rows, Multi-Products & Budget Guard -->
    <script>
        let rowCount = 1;
        const catalogProducts = @json(isset($products) ? $products : []);

        // Department Budget Heads mapped by Department ID
        const departmentBudgetHeads = {
            @foreach($departments as $dept)
                "{{ $dept->id }}": [
                    @foreach($dept->budgetHeads as $head)
                    {
                        id: {{ $head->id }},
                        code: @json($head->head_code ?? ''),
                        name: @json($head->head_name ?? ''),
                        original: {{ (float) $head->original_budget }},
                        released: {{ (float) $head->released_budget }},
                        expenditure: {{ (float) $head->expenditure }},
                        balance: {{ (float) $head->released_balance }}
                    },
                    @endforeach
                ],
            @endforeach
        };

        function handleProductSelect(input) {
            const val = (input.value || '').trim().toLowerCase();
            if (!val) return;
            const match = catalogProducts.find(p => (p.description || '').toLowerCase() === val);
            if (match) {
                const tr = input.closest('tr');
                if (match.unit) {
                    const unitSelect = tr.querySelector('select[name*="[unit]"]');
                    if (unitSelect) {
                        for (let i = 0; i < unitSelect.options.length; i++) {
                            if (unitSelect.options[i].value.toLowerCase() === match.unit.toLowerCase()) {
                                unitSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }
                }
                const priceInput = tr.querySelector('.item-price');
                if (priceInput && match.default_price && parseFloat(match.default_price) > 0) {
                    if (!priceInput.value || parseFloat(priceInput.value) === 0) {
                        priceInput.value = match.default_price;
                        calcRow(priceInput);
                    }
                }
                if (match.head_of_account) {
                    const headInput = document.getElementById('head_of_account');
                    if (headInput && !headInput.dataset.manualEdit) {
                        headInput.value = match.head_of_account;
                    }
                }
                if (match.head_description) {
                    const headDescInput = document.getElementById('head_description');
                    if (headDescInput && (!headDescInput.value || headDescInput.value === 'Others')) {
                        headDescInput.value = match.head_description;
                    }
                } else if (match.head_of_account) {
                    const headDescInput = document.getElementById('head_description');
                    if (headDescInput && (!headDescInput.value || headDescInput.value === 'Others')) {
                        headDescInput.value = match.head_of_account;
                    }
                }
            }
        }

        function addProductRow(desc = '', unit = 'Nos', price = 0, qty = 1) {
            const tbody = document.getElementById('items-body');
            const newIndex = rowCount++;
            
            const tr = document.createElement('tr');
            tr.className = 'item-row hover:bg-slate-50/50 transition';

            const units = ['Nos', 'Units', 'Pcs', 'Kg', 'Box', 'Pack', 'Meters', 'Set', 'LumpSum'];
            let unitOptions = '';
            units.forEach(u => {
                const sel = (u.toLowerCase() === (unit || 'Nos').toLowerCase()) ? 'selected' : '';
                unitOptions += `<option value="${u}" ${sel}>${u}</option>`;
            });

            tr.innerHTML = `
                <td class="p-3 text-center font-bold text-slate-400 row-num">` + (tbody.children.length + 1) + `</td>
                <td class="p-3">
                    <input type="text" name="items[` + newIndex + `][item_name]" value="${escapeHtml(desc)}" list="products-catalog-list" oninput="handleProductSelect(this)" required placeholder="Type description or pick from catalog..." class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </td>
                <td class="p-3">
                    <select name="items[` + newIndex + `][unit]" class="w-full text-xs rounded-lg border-slate-200">
                        ${unitOptions}
                    </select>
                </td>
                <td class="p-3">
                    <input type="number" step="0.01" min="0.01" name="items[` + newIndex + `][quantity]" value="${qty}" required class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 item-qty font-bold" oninput="calcRow(this)">
                </td>
                <td class="p-3">
                    <input type="number" step="0.01" min="0" name="items[` + newIndex + `][unit_price]" value="${price}" required class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 item-price font-bold" oninput="calcRow(this)">
                </td>
                <td class="p-3 text-right">
                    <span class="font-extrabold text-slate-800 row-total font-mono text-xs">` + (parseFloat(qty) * parseFloat(price)).toFixed(2) + `</span>
                </td>
                <td class="p-3 text-center">
                    <button type="button" onclick="removeRow(this)" class="text-slate-300 hover:text-rose-500 transition remove-btn" title="Remove item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            updateCounters();
            calcTotals();
        }

        function escapeHtml(text) {
            if (!text) return '';
            return String(text).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        /* Catalog Modal Functions */
        function openCatalogModal() {
            const modal = document.getElementById('catalog-modal');
            if (modal) {
                modal.style.display = 'flex';
                document.getElementById('catalog-search').value = '';
                filterCatalogModal();
                updateCatalogSelectedCount();
            }
        }

        function closeCatalogModal() {
            const modal = document.getElementById('catalog-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function filterCatalogModal() {
            const q = (document.getElementById('catalog-search').value || '').toLowerCase().trim();
            const items = document.querySelectorAll('#catalog-list .catalog-item');
            items.forEach(el => {
                const text = el.getAttribute('data-search') || '';
                if (!q || text.includes(q)) {
                    el.style.display = 'flex';
                } else {
                    el.style.display = 'none';
                }
            });
        }

        function toggleSelectAllCatalog(checked) {
            const visibleItems = document.querySelectorAll('#catalog-list .catalog-item');
            visibleItems.forEach(item => {
                if (item.style.display !== 'none') {
                    const cb = item.querySelector('.catalog-check');
                    if (cb) cb.checked = checked;
                }
            });
            updateCatalogSelectedCount();
        }

        function updateCatalogSelectedCount() {
            const checked = document.querySelectorAll('#catalog-list .catalog-check:checked');
            const countEl = document.getElementById('catalog-selected-count');
            if (countEl) {
                countEl.textContent = checked.length + ' item' + (checked.length === 1 ? '' : 's') + ' selected';
            }
        }

        // Attach change listener to checkboxes
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('catalog-check')) {
                updateCatalogSelectedCount();
            }
        });

        function insertSelectedProducts() {
            const checkedBoxes = Array.from(document.querySelectorAll('#catalog-list .catalog-check:checked'));
            if (checkedBoxes.length === 0) {
                alert('Please select at least one product from the list.');
                return;
            }

            const tbody = document.getElementById('items-body');
            const rows = tbody.querySelectorAll('.item-row');

            // Check if first row is completely empty
            let firstRowHandled = false;
            if (rows.length === 1) {
                const firstInput = rows[0].querySelector('input[name*="[item_name]"]');
                if (firstInput && (!firstInput.value || firstInput.value.trim() === '')) {
                    const firstCb = checkedBoxes[0];
                    const desc = firstCb.getAttribute('data-description');
                    const unit = firstCb.getAttribute('data-unit') || 'Nos';
                    const price = parseFloat(firstCb.getAttribute('data-price')) || 0;

                    firstInput.value = desc;
                    const unitSelect = rows[0].querySelector('select[name*="[unit]"]');
                    if (unitSelect) {
                        for (let i = 0; i < unitSelect.options.length; i++) {
                            if (unitSelect.options[i].value.toLowerCase() === unit.toLowerCase()) {
                                unitSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }
                    const priceInput = rows[0].querySelector('.item-price');
                    if (priceInput) priceInput.value = price;
                    calcRow(priceInput || firstInput);

                    checkedBoxes.shift(); // Remove first since it filled the existing row
                    firstRowHandled = true;
                }
            }

            // Append additional rows for the remaining checked items
            checkedBoxes.forEach(cb => {
                const desc = cb.getAttribute('data-description');
                const unit = cb.getAttribute('data-unit') || 'Nos';
                const price = parseFloat(cb.getAttribute('data-price')) || 0;
                addProductRow(desc, unit, price, 1);
            });

            // Uncheck all checkboxes in modal for next time
            document.querySelectorAll('#catalog-list .catalog-check').forEach(cb => cb.checked = false);
            updateCatalogSelectedCount();
            closeCatalogModal();
            calcTotals();
        }

        /* Quick Paste from Excel / Multi-Line Text Functions */
        function togglePastePanel() {
            const panel = document.getElementById('quick-paste-panel');
            if (!panel) return;
            if (panel.style.display === 'none' || panel.classList.contains('hidden')) {
                panel.style.display = 'block';
                panel.classList.remove('hidden');
                document.getElementById('excel-paste-input').focus();
            } else {
                panel.style.display = 'none';
                panel.classList.add('hidden');
            }
        }

        function clearPasteInput() {
            const input = document.getElementById('excel-paste-input');
            if (input) input.value = '';
            const status = document.getElementById('paste-live-status');
            if (status) status.textContent = '';
        }

        function parseExcelLines(text) {
            if (!text) return [];
            const rawLines = text.split(/\r\n|\r|\n/);
            const items = [];

            rawLines.forEach((rawLine, idx) => {
                const line = rawLine.trim();
                if (!line) return;

                // Check header
                const lower = line.toLowerCase();
                if (idx === 0 && lower.includes('description') && (lower.includes('qty') || lower.includes('price'))) {
                    return; // Skip header row
                }

                // Delimiter: tab (\t) or comma (,) or pipe (|)
                let delimiter = null;
                if (rawLine.includes('\t')) delimiter = '\t';
                else if (rawLine.includes('|')) delimiter = '|';
                else if (rawLine.includes(',')) delimiter = ',';

                let desc = '';
                let qty = 1;
                let price = 0;

                if (delimiter) {
                    const parts = rawLine.split(delimiter).map(p => p.trim());
                    desc = parts[0] || '';
                    if (!desc) return;

                    if (parts.length >= 3) {
                        // Col 0: description | Col 1: qty | Col 2: default_price
                        const qVal = parseFloat(parts[1]);
                        qty = (!isNaN(qVal) && qVal > 0) ? qVal : 1;

                        const pStr = (parts[2] || '').replace(/[^\d.]/g, '');
                        const pVal = parseFloat(pStr);
                        price = (!isNaN(pVal) && pVal >= 0) ? pVal : 0;
                    } else if (parts.length === 2) {
                        // Col 0: description | Col 1: qty or price
                        const valStr = (parts[1] || '').replace(/[^\d.]/g, '');
                        const val = parseFloat(valStr) || 0;
                        price = val;
                        qty = 1;
                    }
                } else {
                    desc = line;
                    qty = 1;
                    price = 0;
                }

                if (desc) {
                    items.push({
                        description: desc,
                        quantity: qty,
                        unit_price: price,
                        unit: 'Nos'
                    });
                }
            });

            return items;
        }

        function handleExcelPasteInput() {
            const text = document.getElementById('excel-paste-input').value;
            const items = parseExcelLines(text);
            const status = document.getElementById('paste-live-status');
            if (status) {
                if (items.length > 0) {
                    const totalEst = items.reduce((sum, it) => sum + (it.quantity * it.unit_price), 0);
                    status.textContent = `${items.length} items parsed (Total: PKR ${totalEst.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })})`;
                    status.className = 'text-xs font-bold text-emerald-800 font-mono';
                } else {
                    status.textContent = '';
                }
            }
        }

        function applyExcelPasteToInvoice() {
            const text = (document.getElementById('excel-paste-input').value || '').trim();
            const items = parseExcelLines(text);

            if (items.length === 0) {
                alert('Please paste rows copied from Excel first (e.g. description, qty, price).');
                return;
            }

            const tbody = document.getElementById('items-body');
            const mode = document.querySelector('input[name="paste_mode"]:checked')?.value || 'append';

            if (mode === 'replace') {
                tbody.innerHTML = '';
                rowCount = 0;
            }

            // If append mode and first row is completely empty, populate it
            let startIndex = 0;
            if (mode === 'append') {
                const rows = tbody.querySelectorAll('.item-row');
                if (rows.length === 1) {
                    const firstInput = rows[0].querySelector('input[name*="[item_name]"]');
                    if (firstInput && (!firstInput.value || firstInput.value.trim() === '')) {
                        const firstItem = items[0];
                        firstInput.value = firstItem.description;
                        const qtyInput = rows[0].querySelector('.item-qty');
                        if (qtyInput) qtyInput.value = firstItem.quantity;
                        const priceInput = rows[0].querySelector('.item-price');
                        if (priceInput) priceInput.value = firstItem.unit_price;
                        calcRow(priceInput || firstInput);
                        startIndex = 1;
                    }
                }
            }

            // Add all remaining items
            for (let i = startIndex; i < items.length; i++) {
                const it = items[i];
                addProductRow(it.description, it.unit, it.unit_price, it.quantity);
            }

            // Also save to products catalog if checkbox checked
            const saveToCatalog = document.getElementById('save-to-catalog-check')?.checked;
            if (saveToCatalog) {
                fetch("{{ route('products.import') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ paste_text: text })
                }).then(res => res.json()).then(data => {
                    console.log('Saved to catalog:', data);
                }).catch(err => {
                    console.error('Catalog save error:', err);
                });
            }

            // Feedback
            const status = document.getElementById('paste-live-status');
            if (status) {
                status.textContent = `✓ ${items.length} items added to invoice!`;
                status.className = 'text-xs font-bold text-emerald-900 font-mono';
            }

            document.getElementById('excel-paste-input').value = '';
            calcTotals();
        }

        function removeRow(btn) {
            const tbody = document.getElementById('items-body');
            if (tbody.children.length <= 1) {
                alert('At least one product item is required for an invoice.');
                return;
            }
            btn.closest('tr').remove();
            updateCounters();
            calcTotals();
        }

        function updateCounters() {
            const tbody = document.getElementById('items-body');
            const rows = tbody.querySelectorAll('.item-row');
            rows.forEach((row, idx) => {
                const numEl = row.querySelector('.row-num');
                if (numEl) numEl.textContent = idx + 1;
            });
            const counter = document.getElementById('items-counter');
            if (counter) {
                counter.textContent = rows.length + ' Product Item' + (rows.length > 1 ? 's' : '');
            }
        }

        function calcRow(input) {
            const tr = input.closest('tr');
            const qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
            const price = parseFloat(tr.querySelector('.item-price').value) || 0;
            const total = qty * price;
            tr.querySelector('.row-total').textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            calcTotals();
        }

        let currentGross = 0;
        let lastGstSource = 'rate'; // 'rate' or 'amount'
        let lastDeductionSource = 'amount'; // 'rate' or 'amount'

        function handleGstRateChange(val) {
            lastGstSource = 'rate';
            const rate = parseFloat(val) || 0;
            const amount = (currentGross * rate) / 100;
            document.getElementById('gst_amount').value = amount.toFixed(2);
            calcTotals();
        }

        function handleGstAmountChange(val) {
            lastGstSource = 'amount';
            const amount = parseFloat(val) || 0;
            const rate = currentGross > 0 ? ((amount / currentGross) * 100) : 0;
            document.getElementById('gst_rate').value = rate.toFixed(2);
            calcTotals();
        }

        function handleDeductionRateChange(val) {
            lastDeductionSource = 'rate';
            const rate = parseFloat(val) || 0;
            const amount = (currentGross * rate) / 100;
            document.getElementById('tax_deduction').value = amount.toFixed(2);
            calcTotals();
        }

        function handleDeductionAmountChange(val) {
            lastDeductionSource = 'amount';
            const amount = parseFloat(val) || 0;
            const rate = currentGross > 0 ? ((amount / currentGross) * 100) : 0;
            document.getElementById('deduction_rate').value = rate.toFixed(2);
            calcTotals();
        }

        function calcTotals() {
            let gross = 0;
            document.querySelectorAll('.item-row').forEach(tr => {
                const qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
                const price = parseFloat(tr.querySelector('.item-price').value) || 0;
                gross += (qty * price);
            });
            currentGross = gross;

            // 1. GST calculation (linked dual % and PKR)
            let gstAmount = 0;
            const gstRateInput = document.getElementById('gst_rate');
            const gstAmountInput = document.getElementById('gst_amount');

            if (lastGstSource === 'amount') {
                gstAmount = parseFloat(gstAmountInput.value) || 0;
                if (gross > 0 && document.activeElement === gstAmountInput) {
                    gstRateInput.value = ((gstAmount / gross) * 100).toFixed(2);
                }
            } else {
                const gstRate = parseFloat(gstRateInput.value) || 0;
                gstAmount = (gross * gstRate) / 100;
                if (document.activeElement !== gstAmountInput) {
                    gstAmountInput.value = gstAmount.toFixed(2);
                }
            }

            // 2. Deduction calculation (linked dual % and PKR)
            let deduction = 0;
            const dedRateInput = document.getElementById('deduction_rate');
            const dedAmountInput = document.getElementById('tax_deduction');

            if (lastDeductionSource === 'rate') {
                const dedRate = parseFloat(dedRateInput.value) || 0;
                deduction = (gross * dedRate) / 100;
                if (document.activeElement !== dedAmountInput) {
                    dedAmountInput.value = deduction.toFixed(2);
                }
            } else {
                deduction = parseFloat(dedAmountInput.value) || 0;
                if (gross > 0 && document.activeElement === dedAmountInput) {
                    dedRateInput.value = ((deduction / gross) * 100).toFixed(2);
                }
            }

            const net = Math.max(0, gross + gstAmount - deduction);

            const gstBadge = document.getElementById('gst_badge_val');
            if (gstBadge) {
                gstBadge.textContent = '+ PKR ' + gstAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
            const dedBadge = document.getElementById('deduction_badge_val');
            if (dedBadge) {
                dedBadge.textContent = '- PKR ' + deduction.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            document.getElementById('subtotal_display').textContent = 'PKR ' + gross.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('net_display').textContent = 'PKR ' + net.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            checkBudgetCompliance(net);
        }

        function checkBudgetCompliance(netPayable) {
            const deptSelect = document.getElementById('department_select');
            const selectedOpt = deptSelect.options[deptSelect.selectedIndex];
            const headSelect = document.getElementById('budget_head_select');
            const selectedHeadOpt = headSelect ? headSelect.options[headSelect.selectedIndex] : null;

            const alertBox = document.getElementById('budget-alert-box');
            const alertText = document.getElementById('budget-alert-text');
            const statusPill = document.getElementById('budget-status-pill');
            const submitBtn = document.getElementById('submit-btn');

            const compAvail = document.getElementById('comp-avail');
            const compBill = document.getElementById('comp-bill');
            const compRem = document.getElementById('comp-rem');

            compBill.textContent = 'PKR ' + netPayable.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            if (!deptSelect.value) {
                compAvail.textContent = 'PKR 0.00';
                compRem.textContent = 'PKR 0.00';
                statusPill.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600';
                statusPill.textContent = 'Pending Selection';
                alertText.innerHTML = 'Please select a target department to verify available released quarterly funds.';
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            const avail = parseFloat(selectedOpt.getAttribute('data-available')) || 0;
            const remaining = avail - netPayable;

            // Head budget compliance check
            let isHeadExceeded = false;
            let headBalance = 0;
            let headName = '';
            if (headSelect && headSelect.value && selectedHeadOpt && selectedHeadOpt.value) {
                headBalance = parseFloat(selectedHeadOpt.getAttribute('data-balance')) || 0;
                headName = selectedHeadOpt.getAttribute('data-name') || 'Selected Head';
                if (netPayable > headBalance) {
                    isHeadExceeded = true;
                }
            }

            compAvail.textContent = 'PKR ' + avail.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            compRem.textContent = 'PKR ' + Math.max(0, remaining).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            if (isHeadExceeded) {
                alertBox.className = 'bg-amber-50/70 p-5 rounded-2xl border-2 border-amber-300 shadow-sm space-y-3';
                statusPill.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900';
                statusPill.textContent = 'Head Budget Exceeded';
                alertText.innerHTML = `<strong class="text-amber-800">Head Budget Warning:</strong> Bill payable (PKR ${netPayable.toLocaleString('en-US', { minimumFractionDigits: 2 })}) exceeds available balance in head <strong class="text-indigo-800 font-bold">${escapeHtml(headName)}</strong> (PKR ${headBalance.toLocaleString('en-US', { minimumFractionDigits: 2 })}) by <strong class="text-rose-700 font-mono">PKR ${(netPayable - headBalance).toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong>. Bill can still be submitted for tranche clearance.`;
                submitBtn.disabled = false;
            } else if (netPayable > avail) {
                alertBox.className = 'bg-amber-50/70 p-5 rounded-2xl border-2 border-amber-300 shadow-sm space-y-3';
                statusPill.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900';
                statusPill.textContent = 'Tranche Release Required';
                alertText.innerHTML = '<strong class="text-amber-800">Budget Notice:</strong> Bill payable exceeds current released funds by <strong class="text-rose-700 font-mono">PKR ' + (netPayable - avail).toLocaleString('en-US', { minimumFractionDigits: 2 }) + '</strong>. Bill can still be submitted & recorded in Pending status.';
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                alertBox.className = 'bg-emerald-50/60 p-5 rounded-2xl border-2 border-emerald-300 shadow-sm space-y-3';
                statusPill.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800';
                statusPill.textContent = '✓ Budget Compliant';
                const headNote = headSelect && headSelect.value ? ` and head <strong class="text-indigo-800">${escapeHtml(headName)}</strong> (PKR ${(headBalance - netPayable).toLocaleString('en-US', { minimumFractionDigits: 2 })} remaining)` : '';
                alertText.innerHTML = `<strong class="text-emerald-700">Budget Verified!</strong> Invoice amount is fully covered by authorized released funds${headNote}. PKR ` + remaining.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' will remain available in this department.';
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function updateDepartmentDetails() {
            const deptSelect = document.getElementById('department_select');
            const selectedOpt = deptSelect.options[deptSelect.selectedIndex];
            const badgeVal = document.getElementById('budget-badge-val');
            const headInput = document.getElementById('head_of_account');
            const headSelect = document.getElementById('budget_head_select');

            const deptId = deptSelect.value;

            if (deptId && selectedOpt) {
                const avail = parseFloat(selectedOpt.getAttribute('data-available')) || 0;
                const costCenter = selectedOpt.getAttribute('data-cost-center') || selectedOpt.getAttribute('data-name') || '';

                if (badgeVal) {
                    badgeVal.textContent = 'Avail: PKR ' + avail.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                }

                // Auto-fill Bill Head / Cost Center Title if not manually typed by user
                if (!headInput.dataset.manualEdit || headInput.value.trim() === '') {
                    headInput.value = costCenter;
                }

                const costCenterInput = document.getElementById('cost_center_input');
                const deptCode = selectedOpt.getAttribute('data-code') || '';
                if (costCenterInput && (!costCenterInput.value || costCenterInput.dataset.autoFilled)) {
                    costCenterInput.value = deptCode;
                    costCenterInput.dataset.autoFilled = "true";
                }

                const headDescInput = document.getElementById('head_description');
                if (headDescInput && !headDescInput.value) {
                    headDescInput.value = costCenter;
                }

                // Populate budget heads dropdown for this department
                const heads = departmentBudgetHeads[deptId] || [];
                const previousSelectedHeadId = headSelect.value || "{{ old('budget_head_id', '') }}";
                
                headSelect.innerHTML = '';
                const defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.textContent = heads.length > 0 ? `-- Select Product / Head (${heads.length} Available) --` : '-- No heads defined (+ Click "+ New Head") --';
                headSelect.appendChild(defaultOpt);

                heads.forEach(h => {
                    const opt = document.createElement('option');
                    opt.value = h.id;
                    opt.setAttribute('data-code', h.code || '');
                    opt.setAttribute('data-name', h.name || '');
                    opt.setAttribute('data-released', h.released || 0);
                    opt.setAttribute('data-spent', h.expenditure || 0);
                    opt.setAttribute('data-balance', h.balance || 0);
                    const codeLabel = h.code ? `[${h.code}] ` : '';
                    opt.textContent = `${codeLabel}${h.name} — Avail: PKR ${parseFloat(h.balance).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
                    if (String(h.id) === String(previousSelectedHeadId)) {
                        opt.selected = true;
                    }
                    headSelect.appendChild(opt);
                });

                // Trigger head change to update banner
                handleBudgetHeadChange();
            } else {
                if (badgeVal) {
                    badgeVal.textContent = 'Avail: PKR 0.00';
                }
                headSelect.innerHTML = '<option value="">-- Select Product / Head --</option>';
                handleBudgetHeadChange();
            }
        }

        function handleBudgetHeadChange() {
            const headSelect = document.getElementById('budget_head_select');
            if (!headSelect) return;
            const selectedOpt = headSelect.options[headSelect.selectedIndex];
            const banner = document.getElementById('head-active-banner');
            const headBadgeVal = document.getElementById('head-badge-val');

            if (headSelect.value && selectedOpt && selectedOpt.value) {
                const code = selectedOpt.getAttribute('data-code') || '';
                const name = selectedOpt.getAttribute('data-name') || '';
                const released = parseFloat(selectedOpt.getAttribute('data-released')) || 0;
                const spent = parseFloat(selectedOpt.getAttribute('data-spent')) || 0;
                const balance = parseFloat(selectedOpt.getAttribute('data-balance')) || 0;

                // Update banner
                document.getElementById('banner-head-name').textContent = name || 'Selected Head';
                document.getElementById('banner-head-code').textContent = code || 'HEAD';
                document.getElementById('banner-head-released').textContent = 'PKR ' + released.toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('banner-head-spent').textContent = 'PKR ' + spent.toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('banner-head-balance-pill').textContent = 'Available: PKR ' + balance.toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('btn-head-amount').textContent = 'PKR ' + balance.toLocaleString('en-US', { minimumFractionDigits: 2 });

                if (headBadgeVal) {
                    headBadgeVal.textContent = 'Avail: PKR ' + balance.toLocaleString('en-US', { minimumFractionDigits: 0 });
                    headBadgeVal.className = 'font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200';
                }

                // Auto-fill head code & description
                const headCodeInput = document.getElementById('head_code');
                if (headCodeInput && code) headCodeInput.value = code;

                const headDescInput = document.getElementById('head_description');
                if (headDescInput && name) headDescInput.value = name;

                const headOfAccountInput = document.getElementById('head_of_account');
                if (headOfAccountInput) {
                    headOfAccountInput.value = code ? (code + ' - ' + name) : name;
                }

                if (banner) banner.classList.remove('hidden');
            } else {
                if (banner) banner.classList.add('hidden');
                if (headBadgeVal) {
                    headBadgeVal.textContent = 'None selected';
                    headBadgeVal.className = 'font-mono font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200';
                }
            }
            calcTotals();
        }

        function autoFillBillFromHead() {
            const headSelect = document.getElementById('budget_head_select');
            const selectedOpt = headSelect ? headSelect.options[headSelect.selectedIndex] : null;
            if (!headSelect || !headSelect.value || !selectedOpt) {
                alert('Please select a Head / Product first.');
                return;
            }

            const balance = parseFloat(selectedOpt.getAttribute('data-balance')) || parseFloat(selectedOpt.getAttribute('data-released')) || 0;
            const headName = selectedOpt.getAttribute('data-name') || 'Sanctioned Budget Services';
            const headCode = selectedOpt.getAttribute('data-code') || '';

            if (balance <= 0) {
                alert('This head currently has PKR 0.00 available balance.');
                return;
            }

            // Current GST rate and Deduction rate
            const gstRate = parseFloat(document.getElementById('gst_rate').value) || 0;
            const deductionRate = parseFloat(document.getElementById('deduction_rate').value) || 0;

            // Target Net Amount = balance (e.g. 20,000)
            // Net = Gross + (Gross * gstRate / 100) - (Gross * deductionRate / 100)
            // Net = Gross * (1 + gstRate/100 - deductionRate/100)
            const factor = 1 + (gstRate / 100) - (deductionRate / 100);
            const unitPrice = factor > 0 ? (balance / factor) : balance;

            // Populate Item Row 0
            const tbody = document.getElementById('items-body');
            tbody.innerHTML = ''; // reset so ONLY this head's bill is generated
            rowCount = 0;

            // Clean item description
            let cleanDesc = headName.replace(/^[A-Z0-9]+\s*[-:]\s*/i, '').trim();
            if (!cleanDesc) cleanDesc = headName;

            addProductRow(cleanDesc, 'LumpSum', unitPrice.toFixed(2), 1);

            // Also set milestone description
            const milestoneInput = document.querySelector('textarea[name="milestone_description"]');
            if (milestoneInput && (!milestoneInput.value || milestoneInput.value.includes('Procurement of goods'))) {
                milestoneInput.value = `Execution of services / delivery under head [${headCode}] ${headName} as per sanctioned grant.`;
            }

            calcTotals();

            // Flash visual confirmation
            const banner = document.getElementById('head-active-banner');
            if (banner) {
                banner.classList.add('ring-4', 'ring-emerald-400');
                setTimeout(() => banner.classList.remove('ring-4', 'ring-emerald-400'), 1500);
            }
        }

        /* Quick Add Budget Head Modal Functions */
        function openNewHeadModal() {
            const deptSelect = document.getElementById('department_select');
            if (!deptSelect || !deptSelect.value) {
                alert('Please select a Target Department first.');
                deptSelect.focus();
                return;
            }
            const deptName = deptSelect.options[deptSelect.selectedIndex].text.split('(')[0].trim();
            const deptNameEl = document.getElementById('quick-head-dept-name');
            if (deptNameEl) deptNameEl.textContent = 'Department: ' + deptName;

            const modal = document.getElementById('new-head-modal');
            if (modal) {
                modal.style.display = 'flex';
                document.getElementById('quick_head_name').focus();
            }
        }

        function closeNewHeadModal() {
            const modal = document.getElementById('new-head-modal');
            if (modal) modal.style.display = 'none';
            const err = document.getElementById('quick-head-error');
            if (err) {
                err.textContent = '';
                err.classList.add('hidden');
            }
        }

        async function handleNewHeadSubmit(e) {
            e.preventDefault();
            const deptSelect = document.getElementById('department_select');
            const deptId = deptSelect.value;
            if (!deptId) {
                alert('Please select a Target Department.');
                return;
            }

            const btn = document.getElementById('quick-head-btn');
            const errEl = document.getElementById('quick-head-error');
            errEl.classList.add('hidden');
            btn.disabled = true;
            btn.innerHTML = '<span>Saving...</span>';

            const released = parseFloat(document.getElementById('quick_head_released').value) || 0;
            const originalVal = document.getElementById('quick_head_original').value;
            const original = originalVal ? (parseFloat(originalVal) || 0) : released;

            const payload = {
                head_code: document.getElementById('quick_head_code').value.trim(),
                head_name: document.getElementById('quick_head_name').value.trim(),
                released_budget: released,
                original_budget: original,
                year: document.getElementById('quick_head_year').value.trim() || '2026',
                notes: document.getElementById('quick_head_notes').value.trim(),
            };

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || document.querySelector('input[name="_token"]')?.value;

                const url = `/departments/${deptId}/budget-heads`;
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (!res.ok || !data.success) {
                    throw new Error(data.message || 'Failed to save budget head.');
                }

                const head = data.head;
                // Add to client-side data
                if (!departmentBudgetHeads[deptId]) {
                    departmentBudgetHeads[deptId] = [];
                }
                const newHeadObj = {
                    id: head.id,
                    code: head.head_code || '',
                    name: head.head_name || '',
                    original: parseFloat(head.original_budget) || 0,
                    released: parseFloat(head.released_budget) || 0,
                    expenditure: parseFloat(head.expenditure) || 0,
                    balance: Math.max(0, (parseFloat(head.released_budget) || 0) - (parseFloat(head.expenditure) || 0)),
                };
                departmentBudgetHeads[deptId].push(newHeadObj);

                // Add to dropdown and select it
                const headSelect = document.getElementById('budget_head_select');
                const opt = document.createElement('option');
                opt.value = head.id;
                opt.setAttribute('data-code', newHeadObj.code);
                opt.setAttribute('data-name', newHeadObj.name);
                opt.setAttribute('data-released', newHeadObj.released);
                opt.setAttribute('data-spent', newHeadObj.expenditure);
                opt.setAttribute('data-balance', newHeadObj.balance);
                const codeLabel = newHeadObj.code ? `[${newHeadObj.code}] ` : '';
                opt.textContent = `${codeLabel}${newHeadObj.name} — Avail: PKR ${newHeadObj.balance.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
                opt.selected = true;
                headSelect.appendChild(opt);

                handleBudgetHeadChange();
                closeNewHeadModal();
                document.getElementById('quick-head-form').reset();
            } catch (err) {
                errEl.textContent = err.message || 'An error occurred while saving the budget head.';
                errEl.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>Save & Use Head</span>';
            }
        }

        document.getElementById('head_of_account').addEventListener('input', function() {
            this.dataset.manualEdit = "true";
        });

        document.getElementById('department_select').addEventListener('change', function() {
            delete document.getElementById('head_of_account').dataset.manualEdit;
            updateDepartmentDetails();
            calcTotals();
        });

        /* Company / Supplier Interactive Functions */
        function updateCompanyDetails() {
            const select = document.getElementById('company_select');
            if (!select) return;
            const opt = select.options[select.selectedIndex];
            if (!opt) return;

            const vendorNo = opt.getAttribute('data-vendor-no') || '';
            const ntn = opt.getAttribute('data-ntn') || '';
            const gst = opt.getAttribute('data-gst') || '';
            const sst = opt.getAttribute('data-sst') || '';
            const address = opt.getAttribute('data-address') || '';
            const phone = opt.getAttribute('data-phone') || '';

            const vendorInput = document.getElementById('company_vendor_no');
            if (vendorInput) vendorInput.value = vendorNo;

            const ntnInput = document.getElementById('company_ntn');
            if (ntnInput) ntnInput.value = ntn;

            const gstInput = document.getElementById('company_gst');
            if (gstInput) gstInput.value = gst;

            const sstInput = document.getElementById('company_sst');
            if (sstInput) sstInput.value = sst;

            const addrEl = document.getElementById('company_address_text');
            if (addrEl) addrEl.textContent = address || '-';
            const phoneEl = document.getElementById('company_phone_text');
            if (phoneEl) phoneEl.textContent = phone || '-';
        }

        function openNewCompanyModal() {
            const modal = document.getElementById('new-company-modal');
            if (modal) {
                modal.style.display = 'flex';
                const nameInput = document.getElementById('quick_comp_name');
                if (nameInput) nameInput.focus();
            }
        }

        function closeNewCompanyModal() {
            const modal = document.getElementById('new-company-modal');
            if (modal) {
                modal.style.display = 'none';
            }
            const err = document.getElementById('quick-comp-error');
            if (err) {
                err.textContent = '';
                err.classList.add('hidden');
            }
        }

        async function handleQuickCompanySubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('quick-comp-btn');
            const errEl = document.getElementById('quick-comp-error');
            errEl.classList.add('hidden');
            btn.disabled = true;
            btn.innerHTML = '<span>Saving...</span>';

            const payload = {
                company_name: document.getElementById('quick_comp_name').value.trim(),
                vendor_no: document.getElementById('quick_comp_vendor_no').value.trim(),
                ntn: document.getElementById('quick_comp_ntn').value.trim(),
                gst: document.getElementById('quick_comp_gst').value.trim(),
                sst: document.getElementById('quick_comp_sst').value.trim(),
                contact_number: document.getElementById('quick_comp_phone').value.trim(),
                email: document.getElementById('quick_comp_email').value.trim(),
                address: document.getElementById('quick_comp_address').value.trim(),
            };

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || document.querySelector('input[name="_token"]')?.value;

                const res = await fetch("{{ route('companies.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (!res.ok || !data.success) {
                    throw new Error(data.message || 'Failed to save company.');
                }

                const comp = data.company;
                const select = document.getElementById('company_select');
                const newOpt = document.createElement('option');
                newOpt.value = comp.id;
                newOpt.setAttribute('data-name', comp.company_name);
                newOpt.setAttribute('data-vendor-no', comp.vendor_no || '');
                newOpt.setAttribute('data-ntn', comp.ntn || '');
                newOpt.setAttribute('data-gst', comp.gst || '');
                newOpt.setAttribute('data-sst', comp.sst || '');
                newOpt.setAttribute('data-address', comp.address || '');
                newOpt.setAttribute('data-phone', comp.contact_number || '');
                newOpt.textContent = comp.company_name;
                newOpt.selected = true;

                select.appendChild(newOpt);
                updateCompanyDetails();
                closeNewCompanyModal();
                document.getElementById('quick-company-form').reset();
            } catch (err) {
                errEl.textContent = err.message || 'An error occurred while saving the company.';
                errEl.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>Save & Use Company</span>';
            }
        }

        // Form Submission Validation Guard
        document.getElementById('bill-form').addEventListener('submit', function(e) {
            const deptSelect = document.getElementById('department_select');
            if (!deptSelect || !deptSelect.value) {
                e.preventDefault();
                alert('Please select a Target Department before submitting.');
                deptSelect.focus();
                return false;
            }

            const itemRows = document.querySelectorAll('.item-row');
            let hasValidItem = false;
            itemRows.forEach(row => {
                const name = row.querySelector('input[name*="[item_name]"]')?.value.trim();
                const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
                if (name && qty > 0) hasValidItem = true;
            });

            if (!hasValidItem) {
                e.preventDefault();
                alert('Please enter at least one product item with a valid description and quantity.');
                return false;
            }

            const submitBtn = document.getElementById('submit-btn');
            if (submitBtn) {
                submitBtn.innerHTML = '<span>Saving Bill...</span>';
            }
        });

        // Initialize on load
        window.addEventListener('DOMContentLoaded', function() {
            updateCompanyDetails();
            updateDepartmentDetails();
            calcTotals();
        });
    </script>
</x-app-layout>
