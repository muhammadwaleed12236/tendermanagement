<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('bills.show', $bill) }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Back to Bill #{{ $bill->bill_number }}</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('bills.index') }}" class="text-xs font-semibold text-slate-500 hover:underline">All Bills</a>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    Edit Bill #{{ $bill->bill_number }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Modify invoice items, department head allocation, GST, or delivery dispatch details.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('bills.show', $bill) }}" class="px-3.5 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    View Bill
                </a>
                <a href="{{ route('bills.print', $bill) }}" target="_blank" class="px-3.5 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    Print Memo
                </a>
            </div>
        </div>
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

            <form method="POST" action="{{ route('bills.update', $bill) }}" enctype="multipart/form-data" id="bill-form" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- CARD 1: Department & Invoice Information (Clean 1-Line Layout) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">1</span>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Department & Invoice Details</h3>
                        </div>
                        <span class="text-xs text-slate-400">All fields marked with <span class="text-rose-500">*</span> are mandatory</span>
                    </div>

                    <div class="space-y-4">
                        <!-- FIRST ROW: 4 Equal Width Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                            <!-- 1. Cost Center -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Cost Center
                                </label>
                                <input type="text" name="cost_center" id="cost_center_input"
                                    value="{{ old('cost_center', $bill->cost_center) }}"
                                    placeholder="e.g. KA-8914"
                                    class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5">
                                <div class="mt-1.5 text-[11px] text-slate-400">
                                    Cost center code
                                </div>
                            </div>

                            <!-- 2. Department Name -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="text-xs font-bold text-slate-700 uppercase">
                                        Department Name <span class="text-rose-500">*</span>
                                    </label>
                                </div>
                                <select name="department_id" id="department_select" required
                                    class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $dept)
                                        @php
                                            $avail = $dept->availableReleasedBudget();
                                            $adjAvail = ($dept->id == $bill->department_id) ? ($avail + (float)$bill->net_amount) : $avail;
                                            $isSelected = (old('department_id', $bill->department_id) == $dept->id);
                                        @endphp
                                        <option value="{{ $dept->id }}" 
                                                data-available="{{ $adjAvail }}" 
                                                data-name="{{ $dept->name }}"
                                                data-code="{{ $dept->code }}"
                                                data-cost-center="{{ $dept->cost_center_description }}"
                                                {{ $isSelected ? 'selected' : '' }}>
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

                            <!-- 3. Head / Product -->
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

                            <!-- 4. Quarter -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Quarter</label>
                                <select name="quarter" id="quarter_select" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5 bg-white">
                                    <option value="1st Qtr" {{ old('quarter', $bill->quarter) == '1st Qtr' ? 'selected' : '' }}>1st Qtr</option>
                                    <option value="2nd Qtr" {{ old('quarter', $bill->quarter) == '2nd Qtr' ? 'selected' : '' }}>2nd Qtr</option>
                                    <option value="3rd Qtr" {{ old('quarter', $bill->quarter) == '3rd Qtr' ? 'selected' : '' }}>3rd Qtr</option>
                                    <option value="4th Qtr" {{ old('quarter', $bill->quarter) == '4th Qtr' ? 'selected' : '' }}>4th Qtr</option>
                                </select>
                                <div class="mt-1.5 text-[11px] text-slate-400">
                                    Financial quarter
                                </div>
                            </div>
                        </div>

                        <!-- SECOND ROW: 2 Equal Width Fields (Bill # and Bill Date) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                            <!-- Bill # -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Bill # <span class="text-rose-500">*</span></label>
                                <input type="text" name="bill_number" value="{{ old('bill_number', $bill->bill_number) }}" placeholder="e.g. 10885" required class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono py-2.5 @error('bill_number') border-rose-500 @enderror">
                                @error('bill_number') 
                                    <span class="text-[11px] text-rose-600 font-semibold block mt-1">{{ $message }}</span>
                                @else
                                    <div class="mt-1 text-[11px] text-slate-400">
                                        Voucher number
                                    </div>
                                @enderror
                            </div>

                            <!-- Bill Date -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Bill Date <span class="text-rose-500">*</span></label>
                                @php
                                    $formattedDate = $bill->invoice_date ? \Carbon\Carbon::parse($bill->invoice_date)->format('Y-m-d') : date('Y-m-d');
                                @endphp
                                <input type="date" name="invoice_date" value="{{ old('invoice_date', $formattedDate) }}" required class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5">
                                <div class="mt-1 text-[11px] text-slate-400">
                                    Invoice creation date
                                </div>
                            </div>
                        </div>

                        <!-- THIRD ROW: Bill Status -->
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-700 uppercase">Bill Status:</span>
                            @php
                                $isConfirmed = in_array(old('status', $bill->status), ['approved', 'confirmed']);
                            @endphp
                            <div class="inline-flex rounded-lg border border-slate-200 p-0.5 bg-slate-100 text-[11px] font-bold">
                                <label class="cursor-pointer px-2.5 py-1 rounded-md flex items-center gap-1 text-emerald-800 has-[:checked]:bg-emerald-600 has-[:checked]:text-white transition">
                                    <input type="radio" name="status" value="approved" class="sr-only" {{ $isConfirmed ? 'checked' : '' }}>
                                    <span>✓ Confirmed</span>
                                </label>
                                <label class="cursor-pointer px-2.5 py-1 rounded-md flex items-center gap-1 text-slate-600 has-[:checked]:bg-amber-500 has-[:checked]:text-white transition">
                                    <input type="radio" name="status" value="pending" class="sr-only" {{ !$isConfirmed ? 'checked' : '' }}>
                                    <span>Draft</span>
                                </label>
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
                                    <span class="text-emerald-700 font-bold">You can create/adjust bill up to this sanctioned amount</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-start md:self-auto">
                            <button type="button" onclick="autoFillBillFromHead()" class="px-4 py-2 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>⚡ 1-Click: Re-Fill Bill for this Head (<span id="btn-head-amount">PKR 20,000</span>)</span>
                            </button>
                        </div>
                    </div>

                    <!-- Head Details Inputs (Code & Description - Auto-Synced with dropdown, editable) -->
                    <div class="mt-3 pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Head of Account <span class="text-slate-400 font-normal">(e.g. A03806)</span></label>
                            <input type="text" name="head_code" id="head_code" value="{{ old('head_code', $bill->head_code ?: 'A03970') }}" placeholder="e.g. A03806" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2 bg-white">
                        </div>
                        <div class="sm:col-span-1 lg:col-span-2">
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Account Head Description <span class="text-slate-400 font-normal">(e.g. Transportation of Goods)</span></label>
                            <input type="text" name="head_description" id="head_description" value="{{ old('head_description', $bill->head_description ?: 'Others') }}" placeholder="e.g. Transportation of Goods" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2 bg-white">
                        </div>
                        <input type="hidden" name="head_of_account" id="head_of_account" value="{{ old('head_of_account', $bill->head_of_account) }}">
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
                                        Billing Company / Vendor Details <span class="text-rose-500">*</span>
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
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3.5 items-end bg-slate-50/70 p-3.5 rounded-xl border border-slate-200">
                            <!-- Company Selector (Vendor Name) -->
                            <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Vendor Name</label>
                                <select name="company_id" id="company_select" onchange="updateCompanyDetails(true)" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-2 text-slate-800 bg-white shadow-2xs">
                                    @foreach($companies as $comp)
                                        <option value="{{ $comp->id }}" 
                                                data-name="{{ $comp->company_name }}"
                                                data-vendor-no="{{ $comp->vendor_no }}"
                                                data-ntn="{{ $comp->ntn }}"
                                                data-gst="{{ $comp->gst }}"
                                                data-sst="{{ $comp->sst }}"
                                                data-address="{{ $comp->address }}"
                                                data-phone="{{ $comp->contact_number }}"
                                                {{ old('company_id', $bill->company_id) == $comp->id ? 'selected' : '' }}>
                                            {{ $comp->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Vendor Type Dropdown (AGPR or AG Sindh) -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Vendor Type</label>
                                <select name="vendor_type" id="company_vendor_type" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-2 text-slate-800 bg-white shadow-2xs">
                                    <option value="AGPR" {{ old('vendor_type', $bill->vendor_type ?? 'AGPR') == 'AGPR' ? 'selected' : '' }}>AGPR</option>
                                    <option value="AG Sindh" {{ old('vendor_type', $bill->vendor_type) == 'AG Sindh' ? 'selected' : '' }}>AG Sindh</option>
                                </select>
                            </div>

                            <!-- Vendor Number (separate field) -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Vendor Number</label>
                                <input type="text" name="vendor_number" id="company_vendor_no" value="{{ old('vendor_number', $bill->vendor_number ?? $bill->agpr_vendor_no) }}" oninput="if(document.getElementById('company_agpr_vendor_no_hidden')) document.getElementById('company_agpr_vendor_no_hidden').value = this.value" placeholder="30368984" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 bg-white">
                                <input type="hidden" name="agpr_vendor_no" id="company_agpr_vendor_no_hidden" value="{{ old('vendor_number', $bill->vendor_number ?? $bill->agpr_vendor_no) }}">
                            </div>

                            <!-- NTN # -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">NTN #</label>
                                <input type="text" name="ntn_number" id="company_ntn" value="{{ old('ntn_number', $bill->ntn_number) }}" placeholder="3437101-0" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 bg-white">
                            </div>

                            <!-- GST / STRN # -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">GST / STRN #</label>
                                <input type="text" name="strn_number" id="company_gst" value="{{ old('strn_number', $bill->strn_number) }}" placeholder="3277876289057" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 bg-white">
                            </div>

                            <!-- SST # -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">SST #</label>
                                <input type="text" name="sst_number" id="company_sst" value="{{ old('sst_number', $bill->sst_number) }}" placeholder="S3437101-2" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-blue-500 py-2 bg-white">
                            </div>
                        </div>

                        <!-- Live Details Bar -->
                        <div class="flex flex-wrap items-center justify-between text-[11px] text-slate-500 px-1 pt-0.5">
                            <div class="flex items-center gap-3">
                                <span><strong>Address:</strong> <span id="company_address_text" class="text-slate-700 font-medium">-</span></span>
                                <span>•</span>
                                <span><strong>Phone:</strong> <span id="company_phone_text" class="text-slate-700 font-medium">-</span></span>
                            </div>
                            <span class="text-[10px] text-slate-400">Values above will print on official Cash Memo, Sales Tax Invoice & Quotations</span>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Products & Line Items Grid -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">2</span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Invoice Items & Details</h3>
                                <p class="text-[11px] text-slate-400">Edit items, optional units, quantities, and rates</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span id="items-counter" class="text-xs font-semibold text-slate-500 px-2.5 py-1 bg-slate-100 rounded-lg">
                                {{ count($bill->items) }} Item{{ count($bill->items) > 1 ? 's' : '' }}
                            </span>
                            <button type="button" onclick="showUnitColumn()" id="show-unit-btn" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-lg border border-slate-300 shadow-2xs transition flex items-center gap-1.5" title="Show unit column for line items" style="display: none;">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span>+ Add Unit Column</span>
                            </button>
                            <button type="button" onclick="openCatalogModal()" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-200 transition flex items-center gap-1.5 shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span>Pick from Catalog</span>
                            </button>
                            <button type="button" onclick="togglePastePanel()" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-200 transition flex items-center gap-1.5 shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span>Paste from Excel</span>
                            </button>
                            <button type="button" onclick="addProductRow()" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span>+ Add Item</span>
                            </button>
                        </div>
                    </div>

                    <!-- Quick Excel / Multi-Line Text Paste Panel -->
                    <div id="quick-paste-panel" class="hidden p-4 bg-gradient-to-r from-emerald-50/90 to-teal-50/70 border-2 border-dashed border-emerald-300 rounded-2xl space-y-3 transition-all duration-200">
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
                                    <p class="text-[11px] text-emerald-700">Paste Excel cells here to populate or append invoice items.</p>
                                </div>
                            </div>

                            <button type="button" onclick="togglePastePanel()" class="text-xs text-slate-400 hover:text-slate-700 font-bold px-2 py-1 rounded hover:bg-slate-100 transition">✕ Hide</button>
                        </div>

                        <div class="space-y-2">
                            <textarea id="excel-paste-input" rows="3" 
                                      placeholder="Paste Excel cells here... Example:&#10;Cat6 Shielded Ethernet Cable 305m Roll&#9;12&#9;18500&#10;A4 Photocopy Paper 80gsm 500 Sheets&#9;&#9;1450"
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

                    <!-- Products Table -->
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs" id="items-table">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider">
                                    <th class="p-3 w-12 text-center">#</th>
                                    <th class="p-3 min-w-[260px]">Item Description <span class="text-rose-500">*</span></th>
                                    <th class="p-3 w-28 unit-col">Unit</th>
                                    <th class="p-3 w-28">Qty <span class="text-rose-500">*</span></th>
                                    <th class="p-3 w-36">Rate (PKR) <span class="text-rose-500">*</span></th>
                                    <th class="p-3 w-36 text-right">Total (PKR)</th>
                                    <th class="p-3 w-12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body" class="divide-y divide-slate-100">
                                @forelse($bill->items as $idx => $item)
                                    <tr class="item-row hover:bg-slate-50/50 transition">
                                        <td class="p-3 text-center font-bold text-slate-400 row-num">{{ $idx + 1 }}</td>
                                        <td class="p-3">
                                            <input type="text" name="items[{{ $idx }}][item_name]" value="{{ $item->item_name }}" list="products-catalog-list" oninput="handleProductSelect(this)" required placeholder="Type description or pick from catalog..." class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                        </td>
                                        <td class="p-3 unit-col">
                                            <select name="items[{{ $idx }}][unit]" class="w-full text-xs rounded-lg border-slate-200 unit-select" onchange="unitColumnForced = false; checkUnitColumnVisibility()">
                                                <option value="">-- No Unit --</option>
                                                @foreach(['Nos', 'Units', 'Pcs', 'Kg', 'Box', 'Pack', 'Meters', 'Set', 'LumpSum', 'Job'] as $u)
                                                    <option value="{{ $u }}" {{ strtolower($item->unit ?? '') == strtolower($u) ? 'selected' : '' }}>{{ $u }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-3">
                                            <input type="number" step="0.01" min="0.01" name="items[{{ $idx }}][quantity]" value="{{ (float)$item->quantity }}" required class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 item-qty font-bold" oninput="calcRow(this)">
                                        </td>
                                        <td class="p-3">
                                            <input type="number" step="0.01" min="0" name="items[{{ $idx }}][unit_price]" value="{{ (float)$item->unit_price }}" required class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 item-price font-bold" oninput="calcRow(this)">
                                        </td>
                                        <td class="p-3 text-right">
                                            <span class="font-extrabold text-slate-800 row-total font-mono text-xs">{{ number_format($item->total_price, 2) }}</span>
                                        </td>
                                        <td class="p-3 text-center">
                                            <button type="button" onclick="removeRow(this)" class="text-slate-300 hover:text-rose-500 transition remove-btn" title="Remove item">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="item-row hover:bg-slate-50/50 transition">
                                        <td class="p-3 text-center font-bold text-slate-400 row-num">1</td>
                                        <td class="p-3">
                                            <input type="text" name="items[0][item_name]" list="products-catalog-list" oninput="handleProductSelect(this)" required placeholder="Type description or pick from catalog..." class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                        </td>
                                        <td class="p-3 unit-col">
                                            <select name="items[0][unit]" class="w-full text-xs rounded-lg border-slate-200 unit-select" onchange="unitColumnForced = false; checkUnitColumnVisibility()">
                                                <option value="">-- No Unit --</option>
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
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                        <span class="text-slate-400">Manage line items or paste rows directly from Excel</span>
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
                                    <label class="catalog-item flex items-center justify-between p-3 rounded-xl hover:bg-blue-50/50 cursor-pointer transition select-none" data-search="{{ strtolower($p->description) }}">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" class="catalog-check rounded text-blue-600 focus:ring-blue-500" 
                                                   data-description="{{ $p->description }}" 
                                                   data-unit="{{ $p->unit ?: '' }}" 
                                                   data-price="{{ $p->default_price ?: 0 }}">
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800">{{ $p->description }}</p>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    @if($p->unit)
                                                        <span class="text-[10px] text-slate-400">Unit: {{ $p->unit }}</span>
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
                                </div>
                            @endif
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                            <span id="catalog-selected-count" class="text-xs font-semibold text-slate-600">0 items selected</span>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="closeCatalogModal()" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-200 rounded-lg transition">Cancel</button>
                                <button type="button" onclick="insertSelectedProducts()" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                    Insert Selected Products
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: Financial Calculations & Live Budget Compliance -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    <!-- Left: Taxes & Deductions (7 cols) -->
                    <div class="lg:col-span-7 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2.5">
                            <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">3</span>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Taxes & Deductions</h3>
                        </div>

                        <!-- Linked Dual-Input: GST -->
                        <div class="p-4 bg-slate-50/70 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-800 uppercase flex items-center gap-1.5">
                                    <span>Sales Tax (GST / SST)</span>
                                    <span class="text-[10px] text-slate-400 font-normal">Dual-linked % & PKR</span>
                                </label>
                                <span id="gst_badge_val" class="text-xs font-mono font-extrabold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">+ PKR {{ number_format($bill->gst_amount, 2) }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <div class="text-[11px] font-semibold text-slate-500 mb-1">GST Rate (%)</div>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0" name="gst_rate" id="gst_rate" value="{{ old('gst_rate', $bill->gst_rate) }}" oninput="handleGstRateChange(this.value)" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 pr-7 py-2">
                                        <span class="absolute right-2.5 top-2 text-xs font-bold text-slate-400">%</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[11px] font-semibold text-slate-500 mb-1">GST Amount (PKR)</div>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0" name="gst_amount" id="gst_amount" value="{{ old('gst_amount', $bill->gst_amount) }}" oninput="handleGstAmountChange(this.value)" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 pr-12 py-2">
                                        <span class="absolute right-2 top-2 text-[10px] font-bold text-slate-400">PKR</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400">Set to 0 if this product head has 0% GST (e.g. non-GST items).</p>
                        </div>

                        <!-- Linked Dual-Input: Withholding Tax Deduction -->
                        <div class="p-4 bg-slate-50/70 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-800 uppercase flex items-center gap-1.5">
                                    <span>Withholding / Income Tax Deduction</span>
                                    <span class="text-[10px] text-slate-400 font-normal">Dual-linked % & PKR</span>
                                </label>
                                <span id="deduction_badge_val" class="text-xs font-mono font-extrabold text-rose-700 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">- PKR {{ number_format($bill->tax_deduction, 2) }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <div class="text-[11px] font-semibold text-slate-500 mb-1">Deduction Rate (%)</div>
                                    <div class="relative">
                                        @php
                                            $dedRate = $bill->gross_amount > 0 ? round(($bill->tax_deduction / $bill->gross_amount) * 100, 2) : 0;
                                        @endphp
                                        <input type="number" step="0.01" min="0" id="deduction_rate" value="{{ $dedRate }}" oninput="handleDeductionRateChange(this.value)" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-rose-500 pr-7 py-2">
                                        <span class="absolute right-2.5 top-2 text-xs font-bold text-slate-400">%</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[11px] font-semibold text-slate-500 mb-1">Deduction Amount (PKR)</div>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0" name="tax_deduction" id="tax_deduction" value="{{ old('tax_deduction', $bill->tax_deduction) }}" oninput="handleDeductionAmountChange(this.value)" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-rose-500 pr-12 py-2">
                                        <span class="absolute right-2 top-2 text-[10px] font-bold text-slate-400">PKR</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Milestone Description -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Milestone / Scope of Work Description</label>
                            <textarea name="milestone_description" rows="2" placeholder="e.g. Execution of services under head as per sanctioned grant..." class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 py-2">{{ old('milestone_description', $bill->milestone_description) }}</textarea>
                        </div>
                    </div>

                    <!-- Right: Live Totals & Real-Time Budget Guard (5 cols) -->
                    <div class="lg:col-span-5 space-y-4">
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider pb-2 border-b border-slate-100">
                                Invoice Financial Summary
                            </h4>

                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between items-center text-slate-600">
                                    <span>Subtotal (Gross Items):</span>
                                    <span id="subtotal_display" class="font-mono font-extrabold text-slate-900 text-sm">PKR 0.00</span>
                                </div>
                                <div class="flex justify-between items-center text-indigo-700">
                                    <span>Sales Tax (GST):</span>
                                    <span id="gst_display" class="font-mono font-extrabold text-sm">+ PKR 0.00</span>
                                </div>
                                <div class="flex justify-between items-center text-rose-600">
                                    <span>Withholding Deduction:</span>
                                    <span id="deduction_display" class="font-mono font-extrabold text-sm">- PKR 0.00</span>
                                </div>
                                <div class="pt-3 border-t-2 border-slate-900 flex justify-between items-center">
                                    <span class="text-sm font-black text-slate-900 uppercase">Net Payable:</span>
                                    <span id="net_display" class="font-mono font-black text-emerald-600 text-lg">PKR 0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Live Budget Compliance Guard -->
                        <div id="budget-alert-box" class="bg-emerald-50/60 p-5 rounded-2xl border-2 border-emerald-300 shadow-sm space-y-3 transition-colors">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                                    <span>🛡️ Budget Compliance</span>
                                </h4>
                                <span id="budget-status-pill" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                    Verified
                                </span>
                            </div>

                            <p id="budget-alert-text" class="text-xs text-slate-700 leading-relaxed font-medium">
                                Verifying authorized budget allocation for this department...
                            </p>

                            <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-200/60 text-center font-mono">
                                <div class="bg-white/80 p-2 rounded-lg border border-slate-200/60">
                                    <div class="text-[10px] text-slate-500 uppercase font-sans font-bold">Dept Avail</div>
                                    <div id="comp-avail" class="text-xs font-black text-slate-800">PKR 0.00</div>
                                </div>
                                <div class="bg-white/80 p-2 rounded-lg border border-slate-200/60">
                                    <div class="text-[10px] text-slate-500 uppercase font-sans font-bold">This Bill</div>
                                    <div id="comp-bill" class="text-xs font-black text-indigo-700">PKR 0.00</div>
                                </div>
                                <div class="bg-white/80 p-2 rounded-lg border border-slate-200/60">
                                    <div class="text-[10px] text-slate-500 uppercase font-sans font-bold">Remaining</div>
                                    <div id="comp-rem" class="text-xs font-black text-emerald-700">PKR 0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 4: Supporting Documents & Daily Report Fields -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-2.5">
                        <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">4</span>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Dispatch & Delivery Tracking</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Supporting Document / Invoice Scan (PDF / Image)</label>
                            <input type="file" name="attachment" accept=".pdf,.docx,.zip,.jpg,.png" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-lg p-1">
                            @if($bill->attachment_path)
                                <p class="text-[11px] text-emerald-600 mt-1">✓ Current file uploaded: <a href="{{ asset('storage/' . $bill->attachment_path) }}" target="_blank" class="underline font-bold">View Document</a></p>
                            @else
                                <p class="text-[11px] text-slate-400 mt-1">Upload verified delivery challan, vendor bill, or inspection memo.</p>
                            @endif
                        </div>

                        <div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bill Dispatch Date</label>
                                    @php
                                        $formattedDispatch = $bill->dispatch_date ? \Carbon\Carbon::parse($bill->dispatch_date)->format('Y-m-d') : '';
                                    @endphp
                                    <input type="date" name="dispatch_date" value="{{ old('dispatch_date', $formattedDispatch) }}" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Dispatch Remarks / Mode</label>
                                    <input type="text" name="remarks" value="{{ old('remarks', $bill->remarks ?: 'Rider By Hand') }}" placeholder="e.g. Rider By Hand, TCS" class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 py-2.5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Systematic Submit Bar -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <a href="{{ route('bills.show', $bill) }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                        &larr; Cancel & Return
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="submit" id="submit-btn" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-2">
                            <span>Update & Save Bill</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Quick Add New Budget Head / Product -->
    <div id="new-head-modal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-indigo-600 from-indigo-500 via-indigo-600 to-blue-700 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 ring-1 ring-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round"
                             stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="15" rx="2"/>
                            <path d="M3 9h18"/>
                            <path d="M16 14h.01"/>
                            <path d="M7 5V3h10v2"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold tracking-tight">Account Head & Budget Management</h3>
                    </div>
                </div>
                <button type="button" onclick="closeNewHeadModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">✕</button>
            </div>

            <form id="quick-head-form" onsubmit="handleNewHeadSubmit(event)" class="p-5 space-y-3.5">
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Head of Account</label>
                        <input type="text" id="quick_head_code" placeholder="e.g. A03806" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Head Description <span class="text-rose-500">*</span></label>
                        <input type="text" id="quick_head_name" required placeholder="e.g. Transportation of Goods" class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <!-- 1. Total Original Budget (PKR) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                            Total Original Budget (PKR)
                        </label>
                        <input type="number" step="0.01" min="0"
                            id="quick_head_original"
                            placeholder="e.g. 50000"
                            class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2">
                        <p class="text-[10px] text-slate-400 mt-1">
                            Full financial year original budget allocation
                        </p>
                    </div>

                    <!-- 2. Released Budget -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                            Released Budget <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0"
                            id="quick_head_released"
                            required
                            placeholder="e.g. 20000"
                            class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 focus:border-indigo-500 py-2">
                        <p class="text-[10px] text-slate-400 mt-1">
                            Released / sanctioned budget amount
                        </p>
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

    <!-- Modal: Quick Add New Company -->
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

    <!-- Datalist for autocomplete -->
    <datalist id="products-catalog-list">
        @foreach($products as $prod)
            <option value="{{ $prod->description }}">{{ $prod->unit ? "({$prod->unit})" : '' }}</option>
        @endforeach
    </datalist>

    <!-- JavaScript for Interactive Edit Form -->
    <script>
        let rowCount = {{ count($bill->items) > 0 ? count($bill->items) : 1 }};
        const catalogProducts = @json(isset($products) ? $products : []);
        const initialSelectedHeadId = "{{ old('budget_head_id', $bill->budget_head_id) }}";

        // Department Budget Heads mapped by Department ID
        const departmentBudgetHeads = {
            @foreach($departments as $dept)
                "{{ $dept->id }}": [
                    @foreach($dept->budgetHeads as $head)
                    @php
                        // When editing, if this head was selected on the bill, its balance should reflect adjustment
                        $headBal = ($head->id == $bill->budget_head_id) ? ($head->released_balance + (float)$bill->net_amount) : $head->released_balance;
                    @endphp
                    {
                        id: {{ $head->id }},
                        code: @json($head->head_code ?? ''),
                        name: @json($head->head_name ?? ''),
                        original: {{ (float) $head->original_budget }},
                        released: {{ (float) $head->released_budget }},
                        expenditure: {{ (float) $head->expenditure }},
                        balance: {{ (float) $headBal }}
                    },
                    @endforeach
                ],
            @endforeach
        };

        let unitColumnForced = false;

        function showUnitColumn() {
            unitColumnForced = true;
            checkUnitColumnVisibility();
            const firstSelect = document.querySelector('#items-table .unit-select');
            if (firstSelect) {
                firstSelect.focus();
            }
        }

        function checkUnitColumnVisibility() {
            const unitSelects = document.querySelectorAll('#items-table .unit-select');
            let hasAnyUnit = false;
            unitSelects.forEach(select => {
                if (select.value && select.value.trim() !== '') {
                    hasAnyUnit = true;
                }
            });

            const shouldShow = hasAnyUnit || unitColumnForced;
            const unitCols = document.querySelectorAll('#items-table .unit-col');
            unitCols.forEach(col => {
                col.style.display = shouldShow ? '' : 'none';
            });

            const btn = document.getElementById('show-unit-btn');
            if (btn) {
                btn.style.display = shouldShow ? 'none' : 'inline-flex';
            }
        }

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
                    checkUnitColumnVisibility();
                }
                const priceInput = tr.querySelector('.item-price');
                if (priceInput && match.default_price && parseFloat(match.default_price) > 0) {
                    if (!priceInput.value || parseFloat(priceInput.value) === 0) {
                        priceInput.value = match.default_price;
                        calcRow(priceInput);
                    }
                }
            }
        }

        function addProductRow(desc = '', unit = '', price = 0, qty = 1) {
            const tbody = document.getElementById('items-body');
            const newIndex = rowCount++;
            
            const tr = document.createElement('tr');
            tr.className = 'item-row hover:bg-slate-50/50 transition';

            const units = ['Nos', 'Units', 'Pcs', 'Kg', 'Box', 'Pack', 'Meters', 'Set', 'LumpSum', 'Job'];
            let unitOptions = '<option value="">-- No Unit --</option>';
            units.forEach(u => {
                const sel = (unit && u.toLowerCase() === unit.toLowerCase()) ? 'selected' : '';
                unitOptions += `<option value="${u}" ${sel}>${u}</option>`;
            });

            tr.innerHTML = `
                <td class="p-3 text-center font-bold text-slate-400 row-num">` + (tbody.children.length + 1) + `</td>
                <td class="p-3">
                    <input type="text" name="items[` + newIndex + `][item_name]" value="${escapeHtml(desc)}" list="products-catalog-list" oninput="handleProductSelect(this)" required placeholder="Type description or pick from catalog..." class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </td>
                <td class="p-3 unit-col">
                    <select name="items[` + newIndex + `][unit]" class="w-full text-xs rounded-lg border-slate-200 unit-select" onchange="unitColumnForced = false; checkUnitColumnVisibility()">
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
            checkUnitColumnVisibility();
        }

        function escapeHtml(text) {
            if (!text) return '';
            return String(text).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function removeRow(btn) {
            const tbody = document.getElementById('items-body');
            if (tbody.children.length <= 1) {
                alert('At least one item is required for an invoice.');
                return;
            }
            btn.closest('tr').remove();
            updateCounters();
            calcTotals();
            checkUnitColumnVisibility();
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
                counter.textContent = rows.length + ' Item' + (rows.length > 1 ? 's' : '');
            }
        }

        /* Excel Paste Functions */
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

        function parseExcelLines(rawText) {
            const lines = (rawText || '').split(/\r?\n/);
            const items = [];

            lines.forEach((rawLine, idx) => {
                const line = rawLine.trim();
                if (!line) return;

                const lower = line.toLowerCase();
                if (idx === 0 && lower.includes('description') && (lower.includes('qty') || lower.includes('price'))) {
                    return;
                }

                let delimiter = null;
                if (rawLine.includes('\t')) delimiter = '\t';
                else if (rawLine.includes('|')) delimiter = '|';
                else if (rawLine.includes(',')) delimiter = ',';

                let desc = '';
                let unit = '';
                let qty = 1;
                let price = 0;

                if (delimiter) {
                    const parts = rawLine.split(delimiter).map(p => p.trim());
                    desc = parts[0] || '';
                    if (!desc) return;

                    if (parts.length >= 4) {
                        const p1Num = parseFloat(parts[1]);
                        if (isNaN(p1Num)) {
                            unit = parts[1];
                            const qVal = parseFloat(parts[2]);
                            qty = (!isNaN(qVal) && qVal > 0) ? qVal : 1;
                            const pStr = (parts[3] || '').replace(/[^\d.]/g, '');
                            const pVal = parseFloat(pStr);
                            price = (!isNaN(pVal) && pVal >= 0) ? pVal : 0;
                        } else {
                            qty = p1Num > 0 ? p1Num : 1;
                            unit = parts[2];
                            const pStr = (parts[3] || '').replace(/[^\d.]/g, '');
                            const pVal = parseFloat(pStr);
                            price = (!isNaN(pVal) && pVal >= 0) ? pVal : 0;
                        }
                    } else if (parts.length === 3) {
                        const qVal = parseFloat(parts[1]);
                        qty = (!isNaN(qVal) && qVal > 0) ? qVal : 1;
                        const pStr = (parts[2] || '').replace(/[^\d.]/g, '');
                        const pVal = parseFloat(pStr);
                        price = (!isNaN(pVal) && pVal >= 0) ? pVal : 0;
                        unit = '';
                    } else if (parts.length === 2) {
                        const valStr = (parts[1] || '').replace(/[^\d.]/g, '');
                        const val = parseFloat(valStr) || 0;
                        price = val;
                        qty = 1;
                        unit = '';
                    }
                } else {
                    desc = line;
                    qty = 1;
                    price = 0;
                    unit = '';
                }

                if (desc) {
                    items.push({
                        description: desc,
                        quantity: qty,
                        unit_price: price,
                        unit: unit
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

            let startIndex = 0;
            if (mode === 'append') {
                const rows = tbody.querySelectorAll('.item-row');
                if (rows.length === 1) {
                    const firstInput = rows[0].querySelector('input[name*="[item_name]"]');
                    if (firstInput && (!firstInput.value || firstInput.value.trim() === '')) {
                        const firstItem = items[0];
                        firstInput.value = firstItem.description;
                        if (firstItem.unit) {
                            const unitSelect = rows[0].querySelector('.unit-select');
                            if (unitSelect) unitSelect.value = firstItem.unit;
                        }
                        const qtyInput = rows[0].querySelector('.item-qty');
                        if (qtyInput) qtyInput.value = firstItem.quantity;
                        const priceInput = rows[0].querySelector('.item-price');
                        if (priceInput) priceInput.value = firstItem.unit_price;
                        calcRow(priceInput || firstInput);
                        startIndex = 1;
                    }
                }
            }

            for (let i = startIndex; i < items.length; i++) {
                const it = items[i];
                addProductRow(it.description, it.unit, it.unit_price, it.quantity);
            }

            const status = document.getElementById('paste-live-status');
            if (status) {
                status.textContent = `✓ ${items.length} items added to invoice!`;
                status.className = 'text-xs font-bold text-emerald-900 font-mono';
            }

            document.getElementById('excel-paste-input').value = '';
            calcTotals();
            checkUnitColumnVisibility();
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
            if (modal) modal.style.display = 'none';
        }

        function filterCatalogModal() {
            const q = (document.getElementById('catalog-search').value || '').toLowerCase().trim();
            const items = document.querySelectorAll('#catalog-list .catalog-item');
            items.forEach(el => {
                const text = el.getAttribute('data-search') || '';
                el.style.display = (!q || text.includes(q)) ? 'flex' : 'none';
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

        function insertSelectedProducts() {
            const checkedBoxes = Array.from(document.querySelectorAll('#catalog-list .catalog-check:checked'));
            if (checkedBoxes.length === 0) {
                alert('Please select at least one product from the list.');
                return;
            }

            const tbody = document.getElementById('items-body');
            const rows = tbody.querySelectorAll('.item-row');

            let firstRowHandled = false;
            if (rows.length === 1) {
                const firstInput = rows[0].querySelector('input[name*="[item_name]"]');
                if (firstInput && (!firstInput.value || firstInput.value.trim() === '')) {
                    const firstCb = checkedBoxes[0];
                    const desc = firstCb.getAttribute('data-description');
                    const unit = firstCb.getAttribute('data-unit') || '';
                    const price = parseFloat(firstCb.getAttribute('data-price')) || 0;

                    firstInput.value = desc;
                    const unitSelect = rows[0].querySelector('select[name*="[unit]"]');
                    if (unitSelect && unit) {
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

                    checkedBoxes.shift();
                    firstRowHandled = true;
                }
            }

            checkedBoxes.forEach(cb => {
                const desc = cb.getAttribute('data-description');
                const unit = cb.getAttribute('data-unit') || '';
                const price = parseFloat(cb.getAttribute('data-price')) || 0;
                addProductRow(desc, unit, price, 1);
            });

            document.querySelectorAll('#catalog-list .catalog-check').forEach(cb => cb.checked = false);
            updateCatalogSelectedCount();
            closeCatalogModal();
            calcTotals();
            checkUnitColumnVisibility();
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
        let lastGstSource = 'amount';
        let lastDeductionSource = 'amount';

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

            // 1. GST calculation
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

            // 2. Deduction calculation
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
            document.getElementById('gst_display').textContent = '+ PKR ' + gstAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('deduction_display').textContent = '- PKR ' + deduction.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
                alertText.innerHTML = 'Please select a target department to verify available Released Budget Balance.';
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
                alertBox.className = 'bg-rose-50/70 p-4 rounded-2xl border-2 border-rose-300 shadow-sm space-y-3';
                statusPill.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800';
                statusPill.textContent = 'Account Head Budget Exceeded';
                alertText.innerHTML = `<strong class="text-rose-700">Account Head Budget Warning:</strong> Bill payable (PKR ${netPayable.toLocaleString('en-US', { minimumFractionDigits: 2 })}) exceeds available Released Budget Balance in head <strong class="text-indigo-800 font-bold">${escapeHtml(headName)}</strong> (PKR ${headBalance.toLocaleString('en-US', { minimumFractionDigits: 2 })}) by <strong class="text-rose-700 font-mono">PKR ${(netPayable - headBalance).toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong>.`;
            } else if (netPayable > avail) {
                alertBox.className = 'bg-rose-50/70 p-4 rounded-2xl border-2 border-rose-300 shadow-sm space-y-3';
                statusPill.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800';
                statusPill.textContent = 'Account Head Budget Exceeded';
                alertText.innerHTML = `<strong class="text-rose-700">Account Head Budget Warning:</strong> Bill payable exceeds current department Released Budget Balance by <strong class="text-rose-700 font-mono">PKR ${(netPayable - avail).toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong>.`;
            } else {
                alertBox.className = 'bg-emerald-50/60 p-4 rounded-2xl border-2 border-emerald-300 shadow-sm space-y-3';
                statusPill.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800';
                statusPill.textContent = '✓ Budget Compliant';
                const headNote = headSelect && headSelect.value ? ` and head <strong class="text-indigo-800">${escapeHtml(headName)}</strong> (PKR ${(headBalance - netPayable).toLocaleString('en-US', { minimumFractionDigits: 2 })} remaining)` : '';
                alertText.innerHTML = `<strong class="text-emerald-700">Budget Verified!</strong> Invoice amount is fully covered by authorized released funds${headNote}. PKR ` + remaining.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' will remain available in this department.';
            }
        }

        function updateDepartmentDetails() {
            const deptSelect = document.getElementById('department_select');
            const selectedOpt = deptSelect.options[deptSelect.selectedIndex];
            const badgeVal = document.getElementById('budget-badge-val');
            const headSelect = document.getElementById('budget_head_select');

            const deptId = deptSelect.value;

            if (deptId && selectedOpt) {
                const avail = parseFloat(selectedOpt.getAttribute('data-available')) || 0;
                if (badgeVal) {
                    badgeVal.textContent = 'Avail: PKR ' + avail.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                }

                // Populate budget heads dropdown for this department
                const heads = departmentBudgetHeads[deptId] || [];
                const currentHeadId = headSelect.value || initialSelectedHeadId;
                
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
                    if (String(h.id) === String(currentHeadId)) {
                        opt.selected = true;
                    }
                    headSelect.appendChild(opt);
                });

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
                if (headCodeInput && !headCodeInput.value) headCodeInput.value = code;

                const headDescInput = document.getElementById('head_description');
                if (headDescInput && !headDescInput.value) headDescInput.value = name;

                const headOfAccountInput = document.getElementById('head_of_account');
                if (headOfAccountInput && !headOfAccountInput.value) {
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

            const factor = 1 + (gstRate / 100) - (deductionRate / 100);
            const unitPrice = factor > 0 ? (balance / factor) : balance;

            // Populate Item Row 0
            const tbody = document.getElementById('items-body');
            tbody.innerHTML = '';
            rowCount = 0;

            let cleanDesc = headName.replace(/^[A-Z0-9]+\s*[-:]\s*/i, '').trim();
            if (!cleanDesc) cleanDesc = headName;

            addProductRow(cleanDesc, 'LumpSum', unitPrice.toFixed(2), 1);

            const milestoneInput = document.querySelector('textarea[name="milestone_description"]');
            if (milestoneInput && (!milestoneInput.value || milestoneInput.value.includes('Procurement of goods'))) {
                milestoneInput.value = `Execution of services / delivery under head [${headCode}] ${headName} as per sanctioned grant.`;
            }

            calcTotals();

            const banner = document.getElementById('head-active-banner');
            if (banner) {
                banner.classList.add('ring-4', 'ring-emerald-400');
                setTimeout(() => banner.classList.remove('ring-4', 'ring-emerald-400'), 1500);
            }
        }

        /* Company / Supplier Interactive Functions */
        function updateCompanyDetails(force = false) {
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
            if (vendorInput && (force || !vendorInput.value)) vendorInput.value = vendorNo;
            const hiddenVendor = document.getElementById('company_agpr_vendor_no_hidden');
            if (hiddenVendor && (force || !hiddenVendor.value)) hiddenVendor.value = vendorNo;

            const ntnInput = document.getElementById('company_ntn');
            if (ntnInput && (force || !ntnInput.value)) ntnInput.value = ntn;

            const gstInput = document.getElementById('company_gst');
            if (gstInput && (force || !gstInput.value)) gstInput.value = gst;

            const sstInput = document.getElementById('company_sst');
            if (sstInput && (force || !sstInput.value)) sstInput.value = sst;

            const addrEl = document.getElementById('company_address_text');
            if (addrEl) addrEl.textContent = address || '-';
            const phoneEl = document.getElementById('company_phone_text');
            if (phoneEl) phoneEl.textContent = phone || '-';
        }

        function openNewCompanyModal() {
            const modal = document.getElementById('new-company-modal');
            if (modal) {
                modal.style.display = 'flex';
                document.getElementById('quick_comp_name').focus();
            }
        }

        function closeNewCompanyModal() {
            const modal = document.getElementById('new-company-modal');
            if (modal) modal.style.display = 'none';
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
        }

        async function handleNewHeadSubmit(e) {
            e.preventDefault();
            const deptSelect = document.getElementById('department_select');
            const deptId = deptSelect.value;
            if (!deptId) return;

            const btn = document.getElementById('quick-head-btn');
            const errEl = document.getElementById('quick-head-error');
            errEl.classList.add('hidden');
            btn.disabled = true;

            const original = parseFloat(document.getElementById('quick_head_original').value) || 0;
            const released = parseFloat(document.getElementById('quick_head_released').value) || 0;

            const payload = {
                head_code: document.getElementById('quick_head_code').value.trim(),
                head_name: document.getElementById('quick_head_name').value.trim(),
                original_budget: original,
                released_budget: released,
                year: document.getElementById('quick_head_year').value.trim() || '2026',
                notes: document.getElementById('quick_head_notes').value.trim(),
            };

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || document.querySelector('input[name="_token"]')?.value;

                const res = await fetch(`/departments/${deptId}/budget-heads`, {
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

                const headSelect = document.getElementById('budget_head_select');
                const opt = document.createElement('option');
                opt.value = head.id;
                opt.setAttribute('data-code', newHeadObj.code);
                opt.setAttribute('data-name', newHeadObj.name);
                opt.setAttribute('data-released', newHeadObj.released);
                opt.setAttribute('data-spent', newHeadObj.expenditure);
                opt.setAttribute('data-balance', newHeadObj.balance);
                const codeLabel = newHeadObj.code ? `[${newHeadObj.code}] ` : '';
                opt.textContent = `${codeLabel}${newHeadObj.name} — Avail: PKR ${newHeadObj.balance.toLocaleString('en-US')}`;
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
            }
        }

        document.getElementById('department_select').addEventListener('change', function() {
            updateDepartmentDetails();
            calcTotals();
        });

        // Form Validation Guard
        document.getElementById('bill-form').addEventListener('submit', function(e) {
            const deptSelect = document.getElementById('department_select');
            if (!deptSelect || !deptSelect.value) {
                e.preventDefault();
                alert('Please select a Target Department.');
                return false;
            }

            const itemRows = document.querySelectorAll('.item-row');
            let hasValid = false;
            itemRows.forEach(row => {
                const name = row.querySelector('input[name*="[item_name]"]')?.value.trim();
                const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
                if (name && qty > 0) hasValid = true;
            });

            if (!hasValid) {
                e.preventDefault();
                alert('Please enter at least one product line item with a valid description and quantity.');
                return false;
            }

            const submitBtn = document.getElementById('submit-btn');
            if (submitBtn) {
                submitBtn.innerHTML = '<span>Updating Bill...</span>';
            }
        });

        // Initialize on load
        window.addEventListener('DOMContentLoaded', function() {
            updateCompanyDetails();
            updateDepartmentDetails();
            calcTotals();
            checkUnitColumnVisibility();
        });
    </script>
</x-app-layout>
