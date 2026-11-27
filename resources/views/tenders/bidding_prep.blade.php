<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('tenders.index') }}" class="text-xs font-bold text-orange-700 hover:text-orange-800 transition flex items-center gap-1">
                        &larr; Back to Tenders Directory
                    </a>
                    <span class="text-stone-300">•</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-900 border border-emerald-300">
                        Bidding Preparation Stage
                    </span>
                </div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight flex items-center gap-2">
                    <span>{{ __('Document Fee & Bid Security (CDR / Pay Order)') }}</span>
                    <span class="text-xs font-bold bg-stone-200 text-stone-700 px-2.5 py-0.5 rounded-full border border-stone-300">
                        {{ $tender->effective_tender_no }}
                    </span>
                </h2>
                <p class="text-xs text-stone-600 mt-0.5">
                    Complete tender document fee and call deposit receipt (CDR) / pay order details to officially enter the Tender Management workflow.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('tenders.reports') }}" class="px-3.5 py-2 text-xs font-bold bg-white hover:bg-stone-50 text-stone-700 border border-[#e5ded4] rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Tender & Contract Management</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- TENDER OVERVIEW BANNER WITH HIGH-CONTRAST EYE-CATCHING DEADLINE (Phase 9.1 & 9.2) -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-5 shadow-xs">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-900 border border-emerald-300">
                                🟢 Interested in Bidding
                            </span>
                            <span class="text-xs font-mono font-bold text-stone-500">
                                {{ $tender->effective_tender_no }}
                            </span>
                        </div>
                        <h3 class="text-lg font-extrabold text-stone-900 leading-snug">
                            {{ $tender->title }}
                        </h3>
                        <p class="text-xs text-stone-600">
                            <strong>Department:</strong> {{ $tender->department ? $tender->department->name : '-' }}
                            @if($tender->category) • <strong>Category:</strong> {{ $tender->category }} @endif
                        </p>
                    </div>

                    <!-- Highlighted Submission Deadline Badge / Card (Eye-catching, High-contrast, Theme-colored) -->
                    <div class="shrink-0">
                        @php 
                            $deadlineDate = $tender->submission_deadline ?: $tender->closing_date; 
                        @endphp
                        <div class="submission-deadline-badge p-4 rounded-2xl flex items-center gap-3.5"
                             style="background: linear-gradient(135deg, #ea580c 0%, #d97706 55%, #c2410c 100%) !important; border: 2.5px solid #fdba74 !important; box-shadow: 0 6px 18px -2px rgba(234, 88, 12, 0.45) !important; color: #ffffff !important;">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 shadow-inner"
                                 style="background: rgba(255, 255, 255, 0.25) !important; border: 1.5px solid rgba(255, 255, 255, 0.4) !important;">
                                <svg class="w-6 h-6" style="color: #ffffff !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider block" style="color: #ffedd5 !important; letter-spacing: 0.05em;">Submission Deadline</span>
                                <div class="text-sm sm:text-base font-black tracking-tight" style="color: #ffffff !important;">
                                    {{ $deadlineDate ? $deadlineDate->format('d/m/Y \a\t h:i A') : 'No deadline set' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (isset($errors) && $errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-semibold space-y-1 shadow-xs">
                    <div class="font-bold flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <span>Please correct the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside mt-1 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- MAIN STEP 2 FORM CARD -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-6 sm:p-8 shadow-xs">
                <form method="POST" action="{{ route('tenders.bidding-prep.store', $tender) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- SECTION A: TENDER DOCUMENT FEE -->
                    <div class="p-5 rounded-2xl bg-[#FCFAF7] border border-[#e5ded4] space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-600"></span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-stone-900">A. Tender Document Fee</h4>
                            </div>
                            <label class="inline-flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-stone-200 shadow-2xs">
                                <input type="checkbox" name="is_tender_fee_paid" id="checkbox_tender_fee_paid" value="1" 
                                       {{ old('is_tender_fee_paid', $tender->is_tender_fee_paid) ? 'checked' : '' }} onchange="toggleTenderFeeFields()"
                                       class="w-4 h-4 text-orange-600 rounded border-stone-300 focus:ring-orange-500">
                                <span class="text-xs font-bold text-stone-800">Fee Paid to Department?</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Fee Amount (PKR)</label>
                                <input type="number" step="0.01" name="tender_fee_amount" id="tender_fee_amount" value="{{ old('tender_fee_amount', $tender->tender_fee_amount) }}" 
                                       placeholder="e.g. 3000"
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1 gap-1">
                                    <label class="block text-[11px] font-bold text-stone-700 whitespace-nowrap">Issuing Bank Name</label>
                                    <a href="{{ route('banks.index') }}" target="_blank" class="text-[10px] text-blue-600 hover:underline font-bold whitespace-nowrap">+ Add / Manage</a>
                                </div>
                                <select name="tender_fee_bank_account_id" id="tender_fee_bank_account_id" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                    <option value="">-- Select Bank Account / Cash Vault --</option>
                                    @foreach($bankAccounts as $acc)
                                        <option value="{{ $acc->id }}" {{ old('tender_fee_bank_account_id', $tender->tender_fee_bank_account_id) == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->bank_name }} - {{ $acc->account_title }} (Bal: PKR {{ number_format($acc->current_balance) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Payment Mode</label>
                                <select name="tender_fee_payment_mode" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                    <option value="cash" {{ old('tender_fee_payment_mode', $tender->tender_fee_payment_mode) === 'cash' ? 'selected' : '' }}>Cash / Petty Cash</option>
                                    <option value="pay_order" {{ old('tender_fee_payment_mode', $tender->tender_fee_payment_mode) === 'pay_order' ? 'selected' : '' }}>Pay Order (P.O)</option>
                                    <option value="cheque" {{ old('tender_fee_payment_mode', $tender->tender_fee_payment_mode) === 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="online_transfer" {{ old('tender_fee_payment_mode', $tender->tender_fee_payment_mode) === 'online_transfer' ? 'selected' : '' }}>Online Bank Transfer</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Pay Order / Receipt / Slip #</label>
                                <input type="text" name="tender_fee_ref_no" value="{{ old('tender_fee_ref_no', $tender->tender_fee_ref_no) }}" 
                                       placeholder="e.g. PO-890214 or Receipt # 410"
                                       class="w-full text-xs rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Fee Payment Date</label>
                                <input type="date" name="tender_fee_date" value="{{ old('tender_fee_date', $tender->tender_fee_date?->format('Y-m-d')) }}" 
                                       class="w-full text-xs rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Upload Tender Fee Receipt</label>
                                <div class="relative">
                                    <input type="file" name="tender_fee_receipt" id="tender_fee_receipt_input" accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png" 
                                           onclick="this.value=null" onchange="handleFeeReceiptChange(this)" class="sr-only">
                                    
                                    <!-- Empty State Container -->
                                    <label for="tender_fee_receipt_input" id="feeReceiptEmptyBox" class="{{ $tender->tender_fee_receipt_file ? 'hidden' : 'flex' }} items-center justify-between border border-[#e5ded4] bg-white rounded-xl px-3 py-2 cursor-pointer hover:border-orange-400 transition">
                                        <span class="text-xs text-stone-400 truncate">Choose receipt (PDF, DOCX, ZIP)...</span>
                                        <span class="text-[10px] font-bold text-orange-700 bg-orange-50 px-2 py-0.5 rounded-md border border-orange-200 shrink-0">Browse</span>
                                    </label>

                                    <!-- Selected / Attached State Container (Filename inside field with Download icon) -->
                                    <div id="feeReceiptFileBox" class="{{ $tender->tender_fee_receipt_file ? 'flex' : 'hidden' }} items-center justify-between border border-orange-300 bg-orange-50/70 rounded-xl px-3 py-1.5 text-xs">
                                        <div class="flex items-center gap-1.5 min-w-0 pr-1">
                                            <svg class="w-4 h-4 text-orange-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                            <span id="feeReceiptFileName" class="font-bold text-stone-900 truncate text-[11px]" title="{{ $tender->tender_fee_receipt_file ? basename($tender->tender_fee_receipt_file) : '' }}">
                                                {{ $tender->tender_fee_receipt_file ? basename($tender->tender_fee_receipt_file) : 'Receipt Document' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1 shrink-0">
                                            <!-- Download icon button -->
                                            <button type="button" onclick="downloadFeeReceipt()" class="p-1 text-orange-700 hover:text-orange-950 hover:bg-orange-100 rounded-md transition" title="Download Document">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </button>
                                            <!-- Change button -->
                                            <label for="tender_fee_receipt_input" class="cursor-pointer text-[10px] font-bold text-stone-600 hover:text-stone-900 bg-white hover:bg-stone-50 px-1.5 py-0.5 rounded border border-stone-200 transition inline-flex items-center">
                                                Change
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION B: BID SECURITY / EARNEST MONEY (CDR / PAY ORDER) -->
                    <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-amber-950">B. Bid Security / Earnest Money Deposit (CDR / Pay Order)</h4>
                            </div>
                            <span class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full border border-amber-300">
                                Must be submitted with Tender Bid
                            </span>
                        </div>

                        <p class="text-xs text-stone-600">
                            As per SPPRA/PPRA rules, Bid Security (typically 1% - 5% of bid amount) must be deposited via Call Deposit Receipt (CDR) or Pay Order in favor of the Procuring Agency.
                            @if($tender->budget)
                                <span class="block mt-0.5 text-stone-500 font-semibold">Tender Estimated Budget: <strong class="text-stone-900">PKR {{ number_format($tender->budget) }}</strong></span>
                            @endif
                        </p>

                        <!-- Preserved Hidden Budget Input for Bid Security Calculation -->
                        <input type="hidden" name="budget" id="tender_budget" value="{{ $tender->budget ?? 0 }}">

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Rate %</label>
                                <input type="number" step="0.1" name="bid_security_percentage" id="bid_security_percentage" 
                                       value="{{ old('bid_security_percentage', $tender->bid_security_percentage ?? '2.0') }}" 
                                       placeholder="e.g. 2.0%" oninput="calculateBidSecurityFromBudget()"
                                       class="w-full text-xs font-semibold rounded-xl border-amber-200 bg-white focus:border-amber-500 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Security Amount (PKR)</label>
                                <input type="number" step="0.01" name="bid_security_amount" id="bid_security_amount" 
                                       value="{{ old('bid_security_amount', $tender->bid_security_amount) }}" 
                                       placeholder="e.g. 250000"
                                       class="w-full text-xs font-black text-emerald-800 rounded-xl border-amber-200 bg-white focus:border-amber-500 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Instrument Type</label>
                                <select name="bid_security_type" class="w-full text-xs font-semibold rounded-xl border-amber-200 bg-white focus:border-amber-500 focus:ring-amber-500">
                                    <option value="cdr" {{ old('bid_security_type', $tender->bid_security_type) === 'cdr' ? 'selected' : '' }}>Call Deposit Receipt (CDR)</option>
                                    <option value="pay_order" {{ old('bid_security_type', $tender->bid_security_type) === 'pay_order' ? 'selected' : '' }}>Pay Order (P.O)</option>
                                    <option value="bank_guarantee" {{ old('bid_security_type', $tender->bid_security_type) === 'bank_guarantee' ? 'selected' : '' }}>Bank Guarantee</option>
                                </select>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1 gap-1">
                                    <label class="block text-[11px] font-bold text-stone-700 whitespace-nowrap">Issuing Bank Name</label>
                                    <a href="{{ route('banks.index') }}" target="_blank" class="text-[10px] text-blue-600 hover:underline font-bold whitespace-nowrap">+ Add / Manage</a>
                                </div>
                                <select name="bid_security_bank_account_id" class="w-full text-xs font-semibold rounded-xl border-amber-200 bg-white focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">-- Select Bank Account --</option>
                                    @foreach($bankAccounts as $acc)
                                        <option value="{{ $acc->id }}" {{ old('bid_security_bank_account_id', $tender->bid_security_bank_account_id) == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->bank_name }} - {{ $acc->account_title }} (Bal: PKR {{ number_format($acc->current_balance) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">CDR / Pay Order #</label>
                                <input type="text" name="bid_security_instrument_no" value="{{ old('bid_security_instrument_no', $tender->bid_security_instrument_no) }}" 
                                       placeholder="e.g. CDR-998810"
                                       class="w-full text-xs rounded-xl border-amber-200 bg-white focus:border-amber-500 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Issuance Date</label>
                                <input type="date" name="bid_security_date" value="{{ old('bid_security_date', $tender->bid_security_date?->format('Y-m-d')) }}" 
                                       class="w-full text-xs rounded-xl border-amber-200 bg-white focus:border-amber-500 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Validity Expiry Date</label>
                                <input type="date" name="bid_security_expiry_date" value="{{ old('bid_security_expiry_date', $tender->bid_security_expiry_date?->format('Y-m-d')) }}" 
                                       class="w-full text-xs rounded-xl border-amber-200 bg-white focus:border-amber-500 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-[10.5px] font-bold text-stone-700 mb-1 whitespace-nowrap overflow-visible">Upload CDR / Bank Guarantee Copy</label>
                                <div class="relative">
                                    <input type="file" name="bid_security_scan" id="bid_security_scan_input" accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png" 
                                           onclick="this.value=null" onchange="handleBidSecurityChange(this)" class="sr-only">
                                    
                                    <!-- Empty State Container -->
                                    <label for="bid_security_scan_input" id="bidSecurityEmptyBox" class="{{ $tender->bid_security_file ? 'hidden' : 'flex' }} items-center justify-between border border-amber-200 bg-white rounded-xl px-3 py-2 cursor-pointer hover:border-amber-400 transition">
                                        <span class="text-xs text-stone-400 truncate">Choose file (PDF, DOCX, ZIP)...</span>
                                        <span class="text-[10px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 shrink-0">Browse</span>
                                    </label>

                                    <!-- Selected / Attached State Container (Filename inside field with Download icon) -->
                                    <div id="bidSecurityFileBox" class="{{ $tender->bid_security_file ? 'flex' : 'hidden' }} items-center justify-between border border-amber-300 bg-amber-50/70 rounded-xl px-3 py-1.5 text-xs">
                                        <div class="flex items-center gap-1.5 min-w-0 pr-1">
                                            <svg class="w-4 h-4 text-amber-700 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                            <span id="bidSecurityFileName" class="font-bold text-stone-900 truncate text-[11px]" title="{{ $tender->bid_security_file ? basename($tender->bid_security_file) : '' }}">
                                                {{ $tender->bid_security_file ? basename($tender->bid_security_file) : 'Bid Security Document' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1 shrink-0">
                                            <!-- Download icon button -->
                                            <button type="button" onclick="downloadBidSecurity()" class="p-1 text-amber-800 hover:text-amber-950 hover:bg-amber-100 rounded-md transition" title="Download Document">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </button>
                                            <!-- Change button -->
                                            <label for="bid_security_scan_input" class="cursor-pointer text-[10px] font-bold text-stone-600 hover:text-stone-900 bg-white hover:bg-stone-50 px-1.5 py-0.5 rounded border border-stone-200 transition inline-flex items-center">
                                                Change
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT ACTIONS -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-[#e5ded4]">
                        <a href="{{ route('tenders.index') }}" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:text-stone-900 transition flex items-center gap-1.5">
                            &larr; Return to Tenders Directory
                        </a>

                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save & Enter Tender Management</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // Track file states
        let feeReceiptFileUrl = "{{ $tender->tender_fee_receipt_file ? route('tenders.download-document', ['tender' => $tender, 'type' => 'fee_receipt']) : '' }}";
        let feeReceiptFileName = "{{ $tender->tender_fee_receipt_file ? basename($tender->tender_fee_receipt_file) : '' }}";
        let isFeeReceiptLocal = false;

        let bidSecurityFileUrl = "{{ $tender->bid_security_file ? route('tenders.download-document', ['tender' => $tender, 'type' => 'bid_security']) : '' }}";
        let bidSecurityFileName = "{{ $tender->bid_security_file ? basename($tender->bid_security_file) : '' }}";
        let isBidSecurityLocal = false;

        function calculateBidSecurityFromBudget() {
            const budgetInput = document.getElementById('tender_budget');
            const pctInput = document.getElementById('bid_security_percentage');
            const amtInput = document.getElementById('bid_security_amount');

            if (!budgetInput || !amtInput) return;

            const budget = parseFloat(budgetInput.value) || 0;
            const pct = parseFloat(pctInput ? pctInput.value : 2.0) || 2.0;

            if (budget > 0) {
                const computed = Math.round((budget * pct) / 100);
                amtInput.value = computed;
            }
        }

        function toggleTenderFeeFields() {
            const chk = document.getElementById('checkbox_tender_fee_paid');
            const amountInput = document.getElementById('tender_fee_amount');
            if (chk && amountInput) {
                if (chk.checked && (!amountInput.value || parseFloat(amountInput.value) <= 0)) {
                    amountInput.value = 3000;
                }
            }
        }

        function handleFeeReceiptChange(input) {
            const emptyBox = document.getElementById('feeReceiptEmptyBox');
            const fileBox = document.getElementById('feeReceiptFileBox');
            const nameEl = document.getElementById('feeReceiptFileName');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                feeReceiptFileName = file.name;
                feeReceiptFileUrl = URL.createObjectURL(file);
                isFeeReceiptLocal = true;

                nameEl.textContent = file.name;
                if (emptyBox) { emptyBox.classList.add('hidden'); emptyBox.classList.remove('flex'); }
                if (fileBox) { fileBox.classList.remove('hidden'); fileBox.classList.add('flex'); }
            }
        }

        function handleBidSecurityChange(input) {
            const emptyBox = document.getElementById('bidSecurityEmptyBox');
            const fileBox = document.getElementById('bidSecurityFileBox');
            const nameEl = document.getElementById('bidSecurityFileName');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                bidSecurityFileName = file.name;
                bidSecurityFileUrl = URL.createObjectURL(file);
                isBidSecurityLocal = true;

                nameEl.textContent = file.name;
                if (emptyBox) { emptyBox.classList.add('hidden'); emptyBox.classList.remove('flex'); }
                if (fileBox) { fileBox.classList.remove('hidden'); fileBox.classList.add('flex'); }
            }
        }

        function triggerDirectDownload(url, filename) {
            if (!url) return;
            const a = document.createElement('a');
            a.href = url;
            if (filename) {
                a.download = filename;
            }
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        function downloadFeeReceipt() {
            if (!feeReceiptFileUrl) return;
            triggerDirectDownload(feeReceiptFileUrl, isFeeReceiptLocal ? feeReceiptFileName : '');
        }

        function downloadBidSecurity() {
            if (!bidSecurityFileUrl) return;
            triggerDirectDownload(bidSecurityFileUrl, isBidSecurityLocal ? bidSecurityFileName : '');
        }

        // Initialize calculation on page load if amount is empty and budget exists
        window.addEventListener('DOMContentLoaded', () => {
            const amtInput = document.getElementById('bid_security_amount');
            if (amtInput && (!amtInput.value || parseFloat(amtInput.value) <= 0)) {
                calculateBidSecurityFromBudget();
            }
        });
    </script>
</x-app-layout>