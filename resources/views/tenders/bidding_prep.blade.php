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
                    <span>Tenders Management</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- TENDER OVERVIEW BANNER -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-5 shadow-xs">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-900 border border-emerald-300">
                                🟢 Interested in Bidding
                            </span>
                            <span class="text-xs font-mono font-bold text-stone-500">
                                {{ $tender->effective_tender_no }}
                            </span>
                        </div>
                        <h3 class="text-base font-extrabold text-stone-900 leading-snug">
                            {{ $tender->title }}
                        </h3>
                        <p class="text-xs text-stone-600">
                            <strong>Department:</strong> {{ $tender->department ? $tender->department->name : '-' }}
                            @if($tender->category) • <strong>Category:</strong> {{ $tender->category }} @endif
                            @if($tender->budget) • <strong>Estimated Budget:</strong> PKR {{ number_format($tender->budget) }} @endif
                        </p>
                    </div>

                    <div class="shrink-0 text-left md:text-right border-t md:border-t-0 md:border-l border-[#e5ded4] pt-3 md:pt-0 md:pl-5">
                        <span class="text-[10px] font-black uppercase tracking-wider text-stone-400 block">Submission Deadline</span>
                        <div class="text-xs font-bold text-stone-900 mt-0.5">
                            {{ $tender->submission_deadline ? $tender->submission_deadline->format('d/m/Y \a\t h:i A') : 'No deadline set' }}
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
                    <div class="p-5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-600"></span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-stone-900">A. Tender Document Fee</h4>
                            </div>
                            <label class="inline-flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-stone-200">
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
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[11px] font-bold text-stone-700">Paid From Which Bank Account?</label>
                                    <a href="{{ route('banks.index') }}" target="_blank" class="text-[10px] text-blue-600 hover:underline font-bold">+ Add / Manage</a>
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
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Upload Receipt Scan / Slip</label>
                                <input type="file" name="tender_fee_receipt" accept=".pdf,.docx,.jpg,.png" 
                                       class="w-full text-xs text-stone-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-orange-50 file:text-orange-800">
                                @if($tender->tender_fee_receipt_file)
                                    <span class="text-[10px] text-emerald-700 font-bold mt-1 block">Current file attached</span>
                                @endif
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
                        </p>

                        <!-- Hidden Budget for Calculation -->
                        <input type="hidden" id="tender_budget" value="{{ $tender->budget ?? 0 }}">

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
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[11px] font-bold text-stone-700">Issued From Bank Account</label>
                                    <a href="{{ route('banks.index') }}" target="_blank" class="text-[10px] text-blue-600 hover:underline font-bold">+ Add / Manage</a>
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
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Upload CDR / Pay Order Scan</label>
                                <input type="file" name="bid_security_scan" accept=".pdf,.docx,.jpg,.png" 
                                       class="w-full text-xs text-stone-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-amber-100 file:text-amber-900">
                                @if($tender->bid_security_file)
                                    <span class="text-[10px] text-emerald-700 font-bold mt-1 block">Current file attached</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT ACTIONS -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-5 border-t border-[#e5ded4]">
                        <a href="{{ route('tenders.index') }}" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:text-stone-900 transition flex items-center gap-1.5">
                            &larr; Return to Tender Details
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

        // Initialize calculation on page load if amount is empty and budget exists
        window.addEventListener('DOMContentLoaded', () => {
            const amtInput = document.getElementById('bid_security_amount');
            if (amtInput && (!amtInput.value || parseFloat(amtInput.value) <= 0)) {
                calculateBidSecurityFromBudget();
            }
        });
    </script>
</x-app-layout>
