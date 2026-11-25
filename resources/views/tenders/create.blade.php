<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('tenders.index') }}" class="text-xs font-bold text-orange-700 hover:text-orange-800 transition flex items-center gap-1">
                        &larr; Back to Tenders Directory
                    </a>
                    <span class="text-stone-300">•</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-orange-100/80 text-orange-800 border border-orange-200">
                        New Tender Publishing Wizard
                    </span>
                </div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight flex items-center gap-2">
                    <span>{{ __('Create New Procurement Tender') }}</span>
                    <span class="text-xs font-bold bg-stone-200 text-stone-700 px-2.5 py-0.5 rounded-full border border-stone-300">SPPRA / PPRA</span>
                </h2>
                <p class="text-xs text-stone-600 mt-0.5">
                    Step-by-step wizard to setup bidding procedure, schedule, tender document fee, and bid security (CDR / Pay Order).
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
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 04 SUMMARY KPI METRIC BOXES -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-4">
                <!-- 1. Total Tender Created -->
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[11px] font-black uppercase tracking-wider text-stone-500">Total Tender Created</span>
                        <div class="text-xl sm:text-2xl font-black text-stone-900 mt-0.5 leading-none">
                            {{ str_pad($totalTenderCreated ?? 0, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <span class="text-[10px] text-stone-400 font-semibold truncate block mt-0.5">All tenders logged</span>
                    </div>
                </div>

                <!-- 2. Total Active Tender -->
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-sky-100 text-[#0056d2] flex items-center justify-center shrink-0 border border-sky-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[11px] font-black uppercase tracking-wider text-stone-500">Total Active Tender</span>
                        <div class="text-xl sm:text-2xl font-black text-stone-900 mt-0.5 leading-none">
                            {{ str_pad($totalActiveTender ?? 0, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <span class="text-[10px] text-sky-600 font-semibold truncate block mt-0.5">Bidding open</span>
                    </div>
                </div>

                <!-- 3. Total Tender Interested -->
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[11px] font-black uppercase tracking-wider text-stone-500">Total Tender Interested</span>
                        <div class="text-xl sm:text-2xl font-black text-emerald-800 mt-0.5 leading-none">
                            {{ str_pad($totalInterested ?? 0, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <span class="text-[10px] text-emerald-600 font-semibold truncate block mt-0.5">Ready to bid</span>
                    </div>
                </div>

                <!-- 4. Total Tender Not Interested -->
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-stone-100 text-stone-600 flex items-center justify-center shrink-0 border border-stone-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[11px] font-black uppercase tracking-wider text-stone-500">Total Not Interested</span>
                        <div class="text-xl sm:text-2xl font-black text-stone-700 mt-0.5 leading-none">
                            {{ str_pad($totalNotInterested ?? 0, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <span class="text-[10px] text-stone-400 font-semibold truncate block mt-0.5">Skipped / Archived</span>
                    </div>
                </div>
            </div>

            <!-- WIZARD STEPPERS HEADER (3 Steps) -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 sm:p-5 shadow-xs">
                <div id="tender_stepper_row" class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">
                    <!-- Step 1 Indicator -->
                    <button type="button" onclick="goToStep(1)" id="step_indicator_1" 
                            class="flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-orange-500 bg-orange-50/50">
                        <span id="step_num_1" class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-orange-600 text-white shrink-0">1</span>
                        <div class="min-w-0">
                            <div id="step_tag_1" class="text-[10px] font-extrabold uppercase tracking-wider text-orange-900">Step 1</div>
                            <div id="step_title_1" class="text-xs font-black text-stone-900 truncate">Create Tender (Initial Entry)</div>
                        </div>
                    </button>

                    <!-- Step 2 Indicator -->
                    <button type="button" onclick="goToStep(2)" id="step_indicator_2" 
                            class="flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-[#e5ded4] bg-stone-50/40">
                        <span id="step_num_2" class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-stone-200 text-stone-700 shrink-0">2</span>
                        <div class="min-w-0">
                            <div id="step_tag_2" class="text-[10px] font-extrabold uppercase tracking-wider text-stone-500">Step 2</div>
                            <div id="step_title_2" class="text-xs font-black text-stone-700 truncate">Document Fee & Bid Security</div>
                        </div>
                    </button>

                    <!-- Step 3 Indicator -->
                    <button type="button" onclick="goToStep(3)" id="step_indicator_3" 
                            class="flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-[#e5ded4] bg-stone-50/40">
                        <span id="step_num_3" class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-stone-200 text-stone-700 shrink-0">3</span>
                        <div class="min-w-0">
                            <div id="step_tag_3" class="text-[10px] font-extrabold uppercase tracking-wider text-stone-500">Step 3</div>
                            <div id="step_title_3" class="text-xs font-black text-stone-700 truncate">Scope & Requirements</div>
                        </div>
                    </button>
                </div>
            </div>

            @if (isset($errors) && $errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-semibold space-y-1">
                    <div class="font-bold">Please correct the following errors:</div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- MAIN WIZARD FORM CARD -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-6 sm:p-8 shadow-xs">
                <form id="tender_wizard_form" method="POST" action="{{ route('tenders.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- ============================================================= -->
                    <!-- STEP 1: CREATE TENDER (INITIAL ENTRY & DATES MERGED)          -->
                    <!-- ============================================================= -->
                    <div id="wizard_step_1" class="space-y-5">
                        <div class="flex items-center justify-between border-b border-[#e5ded4] pb-2.5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                                <h3 class="font-black text-sm uppercase tracking-wider text-stone-900">Step 1: Create Tender (Initial Entry & Schedule)</h3>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500">Page 1 of 3</span>
                        </div>

                        <!-- 1. Toolbar: Mode & Procedure -->
                        <div class="p-2 sm:p-2.5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] shadow-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 items-center">
                                <!-- Mode Selector (Tender Paper Only) -->
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-black uppercase text-stone-700 whitespace-nowrap min-w-[50px]">Mode:</span>
                                    <div class="flex items-center">
                                        <label id="card_type_tender"
                                               class="flex items-center justify-center gap-2 py-1.5 px-3 rounded-xl border-2 font-bold text-xs border-orange-500 bg-orange-50 text-orange-950 ring-2 ring-orange-200 cursor-default">
                                            <input type="radio" name="tender_type" id="type_tender" value="tender" checked
                                                   class="w-3.5 h-3.5 text-orange-600 focus:ring-orange-500">
                                            <span class="font-extrabold text-stone-900 truncate">Tender (Paper)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Procedure Selector -->
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-black uppercase text-stone-700 whitespace-nowrap min-w-[70px]">Procedure:</span>
                                    <div class="grid grid-cols-2 gap-1.5 flex-1">
                                        <label id="card_one_envelope" onclick="selectProcedure('single_stage_one_envelope')"
                                               class="flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-orange-500 bg-orange-50 text-stone-900 ring-2 ring-orange-200">
                                            <input type="radio" name="bidding_procedure" id="proc_one" value="single_stage_one_envelope" 
                                                   {{ old('bidding_procedure', 'single_stage_one_envelope') === 'single_stage_one_envelope' ? 'checked' : '' }}
                                                   class="w-3.5 h-3.5 text-orange-600 focus:ring-orange-500">
                                            <span class="truncate">Single Stage 1E</span>
                                        </label>

                                        <label id="card_two_envelope" onclick="selectProcedure('single_stage_two_envelope')"
                                               class="flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50">
                                            <input type="radio" name="bidding_procedure" id="proc_two" value="single_stage_two_envelope" 
                                                   {{ old('bidding_procedure') === 'single_stage_two_envelope' ? 'checked' : '' }}
                                                   class="w-3.5 h-3.5 text-orange-600 focus:ring-orange-500">
                                            <span class="truncate">Single Stage 2E</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Tender # & Department Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">
                                    Procuring Department / Agency <span class="text-rose-500">*</span>
                                </label>
                                <select name="department_id" id="input_department_id" required class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                                    <option value="">-- Choose Department / Client --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }} @if($dept->code) [{{ $dept->code }}] @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">
                                    Tender # / Official Reference Code
                                </label>
                                <input type="text" name="tender_no" id="input_tender_no" value="{{ old('tender_no') }}" 
                                       placeholder="e.g. P&DD/GOS/TD/001/2025-26 (or auto-generated)" 
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            </div>
                        </div>

                        <!-- 3. Tender Description -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">
                                Tender Description / Scope of Work <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="title" id="input_title" rows="2" required placeholder="e.g. Supply, Installation, Testing & Commissioning of Audio-Video System for Committee Room..." 
                                      class="w-full text-sm font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">{{ old('title') }}</textarea>
                        </div>

                        <!-- 4. Estimated Budget (PKR) & Contact Person (Optional) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">
                                    Estimated Budget (PKR)
                                </label>
                                <input type="number" step="0.01" name="budget" id="input_budget" value="{{ old('budget') }}" 
                                       placeholder="e.g. 12500000" oninput="calculateBidSecurityFromBudget()"
                                       class="w-full text-xs font-bold text-emerald-800 rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">
                                    Contact Person (Name, Designation or Phone Number) <span class="text-stone-400 font-normal">(Optional)</span>
                                </label>
                                <input type="text" name="contact_person" id="input_contact_person" value="{{ old('contact_person') }}" 
                                       placeholder="e.g. Engr. Tariq (XEN) - 0300-1234567" 
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            </div>
                        </div>

                        <!-- 5. Merged Schedule Dates: Submission Date & Bid Opening Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4]">
                            <div>
                                <label class="block text-xs font-black text-stone-800 mb-1">
                                    Tender Submission Date & Time <span class="text-rose-500">*</span>
                                </label>
                                <input type="datetime-local" name="submission_deadline" id="input_submission_deadline" required value="{{ old('submission_deadline') }}"
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                <p class="text-[10px] text-stone-500 mt-1">e.g. 27/08/2026 up to 11:30 AM</p>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-stone-800 mb-1">
                                    Tender / Bid Opening Date (Technical & Financial)
                                </label>
                                <input type="datetime-local" name="opening_date" id="input_opening_date" value="{{ old('opening_date') }}"
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                <p class="text-[10px] text-stone-500 mt-1">e.g. 27/08/2026 at 12:00 PM</p>
                            </div>
                        </div>

                        <!-- 6. Interest Status: Interested vs Not Interested -->
                        <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-200">
                            <span class="block text-xs font-black text-amber-950 uppercase tracking-wider mb-2">
                                Bidding Intent / Interest Status:
                            </span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-emerald-300 bg-white cursor-pointer hover:bg-emerald-50/50 transition">
                                    <input type="radio" name="interest_status" value="interested" checked class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                    <div>
                                        <div class="font-extrabold text-xs text-emerald-950 flex items-center gap-1.5">
                                            <span>🟢 Interested (Plan to Bid)</span>
                                        </div>
                                        <div class="text-[10px] text-emerald-800">Tender marked as qualified for bidding; can be processed anytime.</div>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-stone-200 bg-white cursor-pointer hover:bg-stone-50 transition">
                                    <input type="radio" name="interest_status" value="not_interested" class="w-4 h-4 text-stone-600 focus:ring-stone-500">
                                    <div>
                                        <div class="font-extrabold text-xs text-stone-800 flex items-center gap-1.5">
                                            <span>⚪ Not Interested (Do Not Bid)</span>
                                        </div>
                                        <div class="text-[10px] text-stone-500">Log reference only; will not participate in bidding.</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- 7. Dynamic Fields: Bidding Firm & Category -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">Our Bidding Firm</label>
                                <select name="company_id" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                                    <option value="">-- Choose Company (Optional) --</option>
                                    @foreach($companies as $comp)
                                        <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                            {{ $comp->company_name ?: $comp->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sm:col-span-1 md:col-span-2">
                                <label class="block text-xs font-bold text-stone-700 mb-1">Category</label>
                                <input type="text" name="category" id="input_category" value="{{ old('category', 'Audio-Video & IT Systems') }}" list="cat_list" 
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                                <datalist id="cat_list">
                                    <option value="Audio-Video & IT Systems">
                                    <option value="Civil Works & Construction">
                                    <option value="Medical & Healthcare Logistics">
                                    <option value="Electrical & Power Systems">
                                </datalist>
                            </div>
                        </div>

                        <!-- Step 1 Actions: Save Initial Entry OR Proceed to Step 2 -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-[#e5ded4]">
                            <div>
                                <button type="submit" name="initial_entry_only" value="1" 
                                        class="px-5 py-2.5 bg-white border-2 border-stone-300 hover:bg-stone-50 hover:border-stone-400 text-stone-800 text-xs font-black rounded-xl shadow-xs transition flex items-center gap-2">
                                    <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                    <span>💾 Save Initial Entry (Draft / Log)</span>
                                </button>
                            </div>

                            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                                <button type="button" onclick="goToStep(2)" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                                    <span>Proceed to Fees & Bid Security</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================= -->
                    <!-- STEP 2: TENDER FEES & BID SECURITY / EARNEST MONEY            -->
                    <!-- ============================================================= -->
                    <div id="wizard_step_2" class="hidden space-y-6">
                        <div class="flex items-center justify-between border-b border-[#e5ded4] pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <h3 class="font-black text-sm uppercase tracking-wider text-stone-900">Step 2: Tender Document Fee & Bid Security (CDR / Pay Order)</h3>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500">Page 2 of 3</span>
                        </div>

                        <!-- SECTION A: TENDER DOCUMENT FEE -->
                        <div class="p-5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-orange-600"></span>
                                    <h4 class="font-black text-xs uppercase tracking-wider text-stone-900">A. Tender Document Fee</h4>
                                </div>
                                <label class="inline-flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-stone-200">
                                    <input type="checkbox" name="is_tender_fee_paid" id="checkbox_tender_fee_paid" value="1" 
                                           {{ old('is_tender_fee_paid') ? 'checked' : '' }} onchange="toggleTenderFeeFields()"
                                           class="w-4 h-4 text-orange-600 rounded border-stone-300 focus:ring-orange-500">
                                    <span class="text-xs font-bold text-stone-800">Fee Paid to Department?</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Fee Amount (PKR)</label>
                                    <input type="number" step="0.01" name="tender_fee_amount" id="tender_fee_amount" value="{{ old('tender_fee_amount') }}" 
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
                                            <option value="{{ $acc->id }}" {{ old('tender_fee_bank_account_id') == $acc->id ? 'selected' : '' }}>
                                                {{ $acc->bank_name }} - {{ $acc->account_title }} (Bal: PKR {{ number_format($acc->current_balance) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Payment Mode</label>
                                    <select name="tender_fee_payment_mode" class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                        <option value="cash">Cash / Petty Cash</option>
                                        <option value="pay_order">Pay Order (P.O)</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="online_transfer">Online Bank Transfer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Pay Order / Receipt / Slip #</label>
                                    <input type="text" name="tender_fee_ref_no" value="{{ old('tender_fee_ref_no') }}" 
                                           placeholder="e.g. PO-890214 or Receipt # 410"
                                           class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Fee Payment Date</label>
                                    <input type="date" name="tender_fee_date" value="{{ old('tender_fee_date') }}" 
                                           class="w-full text-xs rounded-xl border-[#e5ded4] bg-white">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Upload Receipt Scan / Slip</label>
                                    <input type="file" name="tender_fee_receipt" accept=".pdf,.docx,.jpg,.png" 
                                           class="w-full text-xs text-stone-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-orange-50 file:text-orange-800">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION B: BID SECURITY / EARNEST MONEY (CDR / PAY ORDER) -->
                        <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                    <h4 class="font-black text-xs uppercase tracking-wider text-amber-950">B. Bid Security / Earnest Money Deposit (CDR / Pay Order)</h4>
                                </div>
                                <span class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full border border-amber-300">
                                    Must be submitted with Tender Bid
                                </span>
                            </div>

                            <p class="text-xs text-stone-600">
                                As per SPPRA/PPRA rules, Bid Security (typically 1% - 5% of bid amount) must be deposited via Call Deposit Receipt (CDR) or Pay Order in favor of the Procuring Agency.
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Rate %</label>
                                    <input type="number" step="0.1" name="bid_security_percentage" id="bid_security_percentage" value="{{ old('bid_security_percentage', '2.0') }}" 
                                           placeholder="e.g. 2.0%" oninput="calculateBidSecurityFromBudget()"
                                           class="w-full text-xs font-semibold rounded-xl border-amber-200 bg-white">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Security Amount (PKR)</label>
                                    <input type="number" step="0.01" name="bid_security_amount" id="bid_security_amount" value="{{ old('bid_security_amount') }}" 
                                           placeholder="e.g. 250000"
                                           class="w-full text-xs font-black text-emerald-800 rounded-xl border-amber-200 bg-white">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Instrument Type</label>
                                    <select name="bid_security_type" class="w-full text-xs font-semibold rounded-xl border-amber-200 bg-white">
                                        <option value="cdr">Call Deposit Receipt (CDR)</option>
                                        <option value="pay_order">Pay Order (P.O)</option>
                                        <option value="bank_guarantee">Bank Guarantee</option>
                                    </select>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-[11px] font-bold text-stone-700">Issued From Bank Account</label>
                                        <a href="{{ route('banks.index') }}" target="_blank" class="text-[10px] text-blue-600 hover:underline font-bold">+ Add / Manage</a>
                                    </div>
                                    <select name="bid_security_bank_account_id" class="w-full text-xs font-semibold rounded-xl border-amber-200 bg-white">
                                        <option value="">-- Select Bank Account --</option>
                                        @foreach($bankAccounts as $acc)
                                            <option value="{{ $acc->id }}" {{ old('bid_security_bank_account_id') == $acc->id ? 'selected' : '' }}>
                                                {{ $acc->bank_name }} - {{ $acc->account_title }} (Bal: PKR {{ number_format($acc->current_balance) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">CDR / Pay Order #</label>
                                    <input type="text" name="bid_security_instrument_no" value="{{ old('bid_security_instrument_no') }}" 
                                           placeholder="e.g. CDR-998810"
                                           class="w-full text-xs rounded-xl border-amber-200 bg-white">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Issuance Date</label>
                                    <input type="date" name="bid_security_date" value="{{ old('bid_security_date') }}" 
                                           class="w-full text-xs rounded-xl border-amber-200 bg-white">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Validity Expiry Date</label>
                                    <input type="date" name="bid_security_expiry_date" value="{{ old('bid_security_expiry_date') }}" 
                                           class="w-full text-xs rounded-xl border-amber-200 bg-white">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Upload CDR / Pay Order Scan</label>
                                    <input type="file" name="bid_security_scan" accept=".pdf,.docx,.jpg,.png" 
                                           class="w-full text-xs text-stone-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-amber-100 file:text-amber-900">
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 Navigation Buttons -->
                        <div class="flex items-center justify-between pt-4 border-t border-[#e5ded4]">
                            <button type="button" onclick="goToStep(1)" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:text-stone-900 transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span>Back to Step 1</span>
                            </button>
                            <button type="button" onclick="goToStep(3)" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                                <span>Proceed to Scope & Technical Requirements</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- ============================================================= -->
                    <!-- STEP 3: SCOPE OF WORK & TECHNICAL REQUIREMENTS                -->
                    <!-- ============================================================= -->
                    <div id="wizard_step_3" class="hidden space-y-6">
                        <div class="flex items-center justify-between border-b border-[#e5ded4] pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <h3 class="font-black text-sm uppercase tracking-wider text-stone-900">Step 3: Scope of Work, Technical Requirements & Dossier</h3>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500">Page 3 of 3</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">
                                Scope of Work & Project Specifications
                            </label>
                            <textarea name="description" rows="3" placeholder="Detailed technical specifications, milestones, deliverables..." 
                                      class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">
                                Vendor Eligibility Criteria & Compliance Requirements
                            </label>
                            <textarea name="requirements" rows="3" placeholder="e.g. 1. Valid PEC registration, 2. Minimum 3 years experience, 3. Turn over > PKR 50M..." 
                                      class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">{{ old('requirements') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Official RFP / Tender Document Dossier (PDF / DOCX / ZIP)</label>
                            <input type="file" name="document" accept=".pdf,.docx,.zip" 
                                   class="w-full text-xs text-stone-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-800 hover:file:bg-orange-100">
                        </div>

                        <!-- Step 3 Navigation Buttons -->
                        <div class="flex items-center justify-between pt-4 border-t border-[#e5ded4]">
                            <button type="button" onclick="goToStep(2)" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:text-stone-900 transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span>Back to Step 2</span>
                            </button>
                            <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black rounded-xl shadow-xs transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Save & Publish Full Tender</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;

        function goToStep(step) {
            // Validate required fields on Step 1 before proceeding
            if (step > 1) {
                const title = document.getElementById('input_title').value.trim();
                const dept = document.getElementById('input_department_id').value;
                const deadline = document.getElementById('input_submission_deadline').value;

                if (!title || !dept || !deadline) {
                    alert('Please enter Tender Description, select Procuring Department, and provide Submission Date on Step 1 first.');
                    goToStep(1);
                    return;
                }
            }

            // Hide all steps
            for (let i = 1; i <= 3; i++) {
                const el = document.getElementById('wizard_step_' + i);
                if (el) el.classList.add('hidden');

                const ind = document.getElementById('step_indicator_' + i);
                const num = document.getElementById('step_num_' + i);
                const tag = document.getElementById('step_tag_' + i);
                const title = document.getElementById('step_title_' + i);

                if (ind && num && tag && title) {
                    if (i === step) {
                        ind.className = "flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-orange-500 bg-orange-50/50";
                        num.className = "w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-orange-600 text-white shrink-0";
                        tag.className = "text-[10px] font-extrabold uppercase tracking-wider text-orange-900";
                        title.className = "text-xs font-black text-stone-900 truncate";
                    } else if (i < step) {
                        ind.className = "flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-emerald-300 bg-emerald-50/30";
                        num.className = "w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-emerald-600 text-white shrink-0";
                        tag.className = "text-[10px] font-extrabold uppercase tracking-wider text-emerald-800";
                        title.className = "text-xs font-black text-stone-800 truncate";
                    } else {
                        ind.className = "flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-[#e5ded4] bg-stone-50/40";
                        num.className = "w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-stone-200 text-stone-700 shrink-0";
                        tag.className = "text-[10px] font-extrabold uppercase tracking-wider text-stone-500";
                        title.className = "text-xs font-black text-stone-700 truncate";
                    }
                }
            }

            // Show current step
            const target = document.getElementById('wizard_step_' + step);
            if (target) {
                target.classList.remove('hidden');
            }
            currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function selectProcedure(proc) {
            const card1 = document.getElementById('card_one_envelope');
            const card2 = document.getElementById('card_two_envelope');
            const rad1 = document.getElementById('proc_one');
            const rad2 = document.getElementById('proc_two');

            if (proc === 'single_stage_one_envelope') {
                rad1.checked = true;
                card1.className = "flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-orange-500 bg-orange-50 text-stone-900 ring-2 ring-orange-200";
                card2.className = "flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50";
            } else {
                rad2.checked = true;
                card2.className = "flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-orange-500 bg-orange-50 text-stone-900 ring-2 ring-orange-200";
                card1.className = "flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50";
            }
        }

        function calculateBidSecurityFromBudget() {
            const budgetInput = document.getElementById('input_budget');
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
    </script>
</x-app-layout>