<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('tenders.show', $tender) }}" class="text-xs font-bold text-orange-700 hover:text-orange-800 transition flex items-center gap-1">
                &larr; Back to Tender
            </a>
            <span class="text-stone-300">•</span>
            <span class="font-mono text-xs font-bold bg-stone-200 text-stone-800 px-2 py-0.5 rounded border border-stone-300">
                {{ $tender->effective_tender_no }}
            </span>
        </div>
        <h2 class="font-black text-2xl text-stone-900 leading-tight">
            {{ __('Edit Tender Parameters') }}: {{ $tender->title }}
        </h2>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-6 sm:p-8 shadow-xs">
                <form method="POST" action="{{ route('tenders.update', $tender) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Tender Type Selector (Traditional vs EPAD 2.0) -->
                    <div class="p-4 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-black uppercase tracking-wider text-stone-900">
                                Procurement System / Mode <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full {{ $tender->isEpad() ? 'bg-purple-100 text-purple-900 border border-purple-300' : 'bg-stone-200 text-stone-800' }}">
                                {{ $tender->type_label }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition {{ old('tender_type', $tender->tender_type ?? 'tender') === 'tender' ? 'border-orange-500 bg-orange-50/50' : 'border-[#e5ded4] bg-white' }}"
                                   onclick="toggleEditTenderType('tender')">
                                <input type="radio" name="tender_type" id="edit_type_tender" value="tender" 
                                       {{ old('tender_type', $tender->tender_type ?? 'tender') === 'tender' ? 'checked' : '' }}
                                       class="text-orange-600 focus:ring-orange-500 border-stone-300">
                                <div>
                                    <div class="font-black text-xs text-stone-900">Tender (Traditional Paper)</div>
                                    <div class="text-[11px] text-stone-500">Physical collection and submission dates</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition {{ old('tender_type', $tender->tender_type) === 'epad' ? 'border-purple-600 bg-purple-50/50' : 'border-[#e5ded4] bg-white' }}"
                                   onclick="toggleEditTenderType('epad')">
                                <input type="radio" name="tender_type" id="edit_type_epad" value="epad" 
                                       {{ old('tender_type', $tender->tender_type) === 'epad' ? 'checked' : '' }}
                                       class="text-purple-600 focus:ring-purple-500 border-stone-300">
                                <div>
                                    <div class="font-black text-xs text-purple-950">EPAD 2.0 (E-Procurement)</div>
                                    <div class="text-[11px] text-stone-500">Only Closing Date & Time needed</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Procedure & Identifiers -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Bidding Procedure <span class="text-rose-500">*</span></label>
                            <select name="bidding_procedure" required class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                                <option value="single_stage_one_envelope" {{ old('bidding_procedure', $tender->bidding_procedure) === 'single_stage_one_envelope' ? 'selected' : '' }}>
                                    Single Stage — One Envelope
                                </option>
                                <option value="single_stage_two_envelope" {{ old('bidding_procedure', $tender->bidding_procedure) === 'single_stage_two_envelope' ? 'selected' : '' }}>
                                    Single Stage — Two Envelope
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Tender No. / Official Reference</label>
                            <input type="text" name="tender_no" value="{{ old('tender_no', $tender->tender_no) }}" 
                                   class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Department / Agency <span class="text-rose-500">*</span></label>
                            <select name="department_id" required class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $tender->department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Title / Description -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Tender Description / Title <span class="text-rose-500">*</span></label>
                        <textarea name="title" rows="2" required class="w-full text-sm font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">{{ old('title', $tender->title) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Our Bidding Firm</label>
                            <select name="company_id" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                                <option value="">-- None Selected --</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id', $tender->company_id) == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->company_name ?: $comp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category or Closing Date -->
                        <div id="edit_cat_wrapper" class="{{ $tender->isEpad() ? 'hidden' : '' }}">
                            <label class="block text-xs font-bold text-stone-700 mb-1">Category</label>
                            <input type="text" name="category" value="{{ old('category', $tender->category) }}" 
                                   class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                        </div>

                        <div id="edit_closing_wrapper" class="{{ $tender->isEpad() ? '' : 'hidden' }}">
                            <label class="block text-xs font-black text-purple-950 mb-1">Closing Date & Time <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="closing_date" value="{{ old('closing_date', $tender->closing_date ? $tender->closing_date->format('Y-m-d\TH:i') : ($tender->submission_deadline ? $tender->submission_deadline->format('Y-m-d\TH:i') : '')) }}" 
                                   class="w-full text-xs font-black rounded-xl border-purple-300 bg-purple-50/30 text-purple-950 focus:border-purple-600 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Estimated Budget (PKR)</label>
                            <input type="number" step="0.01" name="budget" value="{{ old('budget', $tender->budget) }}" 
                                   class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                        </div>
                    </div>

                    <!-- Collection Dates (Hidden if EPAD) -->
                    <div id="edit_collection_box" class="{{ $tender->isEpad() ? 'hidden' : '' }} grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4]">
                        <div>
                            <label class="block text-xs font-black text-stone-800 mb-1">Collection (Start Date)</label>
                            <input type="date" name="collection_start_date" value="{{ old('collection_start_date', $tender->collection_start_date ? $tender->collection_start_date->format('Y-m-d') : '') }}"
                                   class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                            <input type="text" name="collection_start_notes" value="{{ old('collection_start_notes', $tender->collection_start_notes) }}" placeholder="Timing notes..."
                                   class="w-full text-[11px] rounded-lg border-[#e5ded4] bg-white mt-1">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-stone-800 mb-1">Collection (End Date)</label>
                            <input type="date" name="collection_end_date" value="{{ old('collection_end_date', $tender->collection_end_date ? $tender->collection_end_date->format('Y-m-d') : '') }}"
                                   class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                            <input type="text" name="collection_end_notes" value="{{ old('collection_end_notes', $tender->collection_end_notes) }}" placeholder="Timing notes..."
                                   class="w-full text-[11px] rounded-lg border-[#e5ded4] bg-white mt-1">
                        </div>
                    </div>

                    <!-- Submission & Opening Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Submission Deadline <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="submission_deadline" required value="{{ old('submission_deadline', $tender->submission_deadline ? $tender->submission_deadline->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Opening Date (General/Technical)</label>
                            <input type="datetime-local" name="opening_date" value="{{ old('opening_date', $tender->opening_date ? $tender->opening_date->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Financial Opening Date (Stage 2)</label>
                            <input type="datetime-local" name="financial_opening_date" value="{{ old('financial_opening_date', $tender->financial_opening_date ? $tender->financial_opening_date->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40">
                        </div>
                    </div>

                    <!-- Tender Fee & Bid Security Parameters (Traditional Tender Only, Bypassed in EPAD) -->
                    <div id="edit_fees_security_wrapper" class="{{ old('tender_type', $tender->tender_type) === 'epad' ? 'hidden' : '' }} p-5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-4">
                        <div class="font-black text-xs uppercase tracking-wider text-stone-800 flex items-center justify-between">
                            <span>Tender Fee & Bid Security (CDR) Parameters</span>
                            <span class="text-[10px] text-stone-500 font-bold bg-stone-100 px-2 py-0.5 rounded border border-stone-200">Traditional Tender Only</span>
                        </div>

                        <!-- Tender Fee -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Tender Fee Amount (PKR)</label>
                                <input type="number" step="0.01" name="tender_fee_amount" value="{{ old('tender_fee_amount', $tender->tender_fee_amount) }}" class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Fee Paid From Bank Account</label>
                                <select name="tender_fee_bank_account_id" class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                                    <option value="">-- None / Cash --</option>
                                    @foreach($bankAccounts as $acc)
                                        <option value="{{ $acc->id }}" {{ old('tender_fee_bank_account_id', $tender->tender_fee_bank_account_id) == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->bank_name }} - {{ $acc->account_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-2 rounded-xl border border-[#e5ded4] w-full">
                                    <input type="checkbox" name="is_tender_fee_paid" value="1" {{ old('is_tender_fee_paid', $tender->is_tender_fee_paid) ? 'checked' : '' }} class="w-4 h-4 text-orange-600 rounded">
                                    <span class="text-xs font-bold text-stone-800">Fee is Paid ✓</span>
                                </label>
                            </div>
                        </div>

                        <!-- Bid Security -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-2 border-t border-stone-200">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Bid Security Amount (PKR)</label>
                                <input type="number" step="0.01" name="bid_security_amount" value="{{ old('bid_security_amount', $tender->bid_security_amount) }}" class="w-full text-xs rounded-xl border-[#e5ded4] bg-white font-bold text-blue-900">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Instrument Type</label>
                                <select name="bid_security_type" class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                                    <option value="cdr" {{ old('bid_security_type', $tender->bid_security_type) === 'cdr' ? 'selected' : '' }}>Call Deposit Receipt (CDR)</option>
                                    <option value="pay_order" {{ old('bid_security_type', $tender->bid_security_type) === 'pay_order' ? 'selected' : '' }}>Pay Order (P.O)</option>
                                    <option value="bank_guarantee" {{ old('bid_security_type', $tender->bid_security_type) === 'bank_guarantee' ? 'selected' : '' }}>Bank Guarantee</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">CDR Issued From Bank</label>
                                <select name="bid_security_bank_account_id" class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                                    <option value="">-- Select Bank Account --</option>
                                    @foreach($bankAccounts as $acc)
                                        <option value="{{ $acc->id }}" {{ old('bid_security_bank_account_id', $tender->bid_security_bank_account_id) == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->bank_name }} - {{ $acc->account_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">CDR / Pay Order #</label>
                                <input type="text" name="bid_security_instrument_no" value="{{ old('bid_security_instrument_no', $tender->bid_security_instrument_no) }}" class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                            </div>
                        </div>
                    </div>

                    <!-- Work Order Fields (Post-Award) -->
                    <div class="p-5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-4">
                        <div class="font-black text-xs uppercase tracking-wider text-emerald-950 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            <span>Awarded Contract Value & Work Order Details (Post-Award)</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Awarded Contract Value (PKR)</label>
                                <input type="number" step="0.01" name="awarded_amount" value="{{ old('awarded_amount', $tender->awarded_amount) }}" placeholder="Awarded Work Order Value" class="w-full text-xs rounded-xl border-[#e5ded4] bg-white font-bold text-emerald-800">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Work Order #</label>
                                <input type="text" name="work_order_no" value="{{ old('work_order_no', $tender->work_order_no) }}" placeholder="e.g. WO-88190" class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                            </div>
                        </div>
                    </div>

                    <!-- Status & Outcome -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Status</label>
                            <select name="status" class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40">
                                <option value="draft" {{ old('status', $tender->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $tender->status) === 'published' ? 'selected' : '' }}>Published (Open)</option>
                                <option value="under_evaluation" {{ old('status', $tender->status) === 'under_evaluation' ? 'selected' : '' }}>Under Evaluation</option>
                                <option value="awarded" {{ old('status', $tender->status) === 'awarded' ? 'selected' : '' }}>Awarded</option>
                                <option value="closed" {{ old('status', $tender->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="cancelled" {{ old('status', $tender->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Overall Outcome</label>
                            <select name="tender_outcome" class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40">
                                <option value="in_progress" {{ old('tender_outcome', $tender->tender_outcome) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="won" {{ old('tender_outcome', $tender->tender_outcome) === 'won' ? 'selected' : '' }}>Won / Awarded</option>
                                <option value="lost" {{ old('tender_outcome', $tender->tender_outcome) === 'lost' ? 'selected' : '' }}>Lost / Disqualified</option>
                                <option value="cancelled" {{ old('tender_outcome', $tender->tender_outcome) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Scope of Work & Notes</label>
                        <textarea name="description" rows="3" class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40">{{ old('description', $tender->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Requirements</label>
                        <textarea name="requirements" rows="2" class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40">{{ old('requirements', $tender->requirements) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-[#e5ded4]">
                        <button type="button" onclick="if(confirm('Are you sure you want to delete this tender?')) document.getElementById('delete-tender-form').submit();" 
                                class="text-xs font-bold text-rose-600 hover:text-rose-800 transition">
                            Delete Tender
                        </button>

                        <div class="flex gap-3">
                            <a href="{{ route('tenders.show', $tender) }}" class="px-4 py-2 text-xs font-bold text-stone-600 hover:text-stone-800">Cancel</a>
                            <button type="submit" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-tender-form" method="POST" action="{{ route('tenders.destroy', $tender) }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleEditTenderType(type) {
            const catWrap = document.getElementById('edit_cat_wrapper');
            const closingWrap = document.getElementById('edit_closing_wrapper');
            const colBox = document.getElementById('edit_collection_box');
            const feesSec = document.getElementById('edit_fees_security_wrapper');

            if (type === 'epad') {
                if (catWrap) catWrap.classList.add('hidden');
                if (closingWrap) closingWrap.classList.remove('hidden');
                if (colBox) colBox.classList.add('hidden');
                if (feesSec) feesSec.classList.add('hidden');
            } else {
                if (catWrap) catWrap.classList.remove('hidden');
                if (closingWrap) closingWrap.classList.add('hidden');
                if (colBox) colBox.classList.remove('hidden');
                if (feesSec) feesSec.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
