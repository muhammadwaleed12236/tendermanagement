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
                    <span>{{ __('Publish Procurement Tender') }}</span>
                    <span class="text-xs font-bold bg-stone-200 text-stone-700 px-2.5 py-0.5 rounded-full border border-stone-300">SPPRA / PPRA</span>
                </h2>
                <p class="text-xs text-stone-600 mt-0.5">
                    Step-by-step wizard to setup bidding procedure, collection schedule, tender form fees, and bid security (CDR / Pay Order).
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('tenders.reports') }}" class="px-3.5 py-2 text-xs font-bold bg-white hover:bg-stone-50 text-stone-700 border border-[#e5ded4] rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Tenders & Work Orders Report</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- WIZARD STEPPERS HEADER -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-4 sm:p-5 shadow-xs">
                <!-- 1. Traditional Tender Stepper (4 Steps: Basic -> Dates -> Fees & CDR -> Scope & Publish) -->
                <div id="tender_stepper_row" class="{{ old('tender_type', 'tender') === 'epad' ? 'hidden' : '' }} grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4">
                    <!-- Step 1 Indicator -->
                    <button type="button" onclick="goToStep(1)" id="step_indicator_1" 
                            class="flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-orange-500 bg-orange-50/50">
                        <span id="step_num_1" class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-orange-600 text-white shrink-0">1</span>
                        <div class="min-w-0">
                            <div class="text-[10px] font-extrabold uppercase tracking-wider text-orange-900">Step 1</div>
                            <div class="text-xs font-black text-stone-900 truncate">Basic & Procedure</div>
                        </div>
                    </button>

                    <!-- Step 2 Indicator -->
                    <button type="button" onclick="goToStep(2)" id="step_indicator_2" 
                            class="flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-[#e5ded4] bg-stone-50/40">
                        <span id="step_num_2" class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-stone-200 text-stone-700 shrink-0">2</span>
                        <div class="min-w-0">
                            <div id="step_tag_2" class="text-[10px] font-extrabold uppercase tracking-wider text-stone-500">Step 2</div>
                            <div id="step_title_2" class="text-xs font-black text-stone-700 truncate">Dates & Schedule</div>
                        </div>
                    </button>

                    <!-- Step 3 Indicator -->
                    <button type="button" onclick="goToStep(3)" id="step_indicator_3" 
                            class="flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-[#e5ded4] bg-stone-50/40">
                        <span id="step_num_3" class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-stone-200 text-stone-700 shrink-0">3</span>
                        <div class="min-w-0">
                            <div class="text-[10px] font-extrabold uppercase tracking-wider text-stone-500">Step 3</div>
                            <div class="text-xs font-black text-stone-700 truncate">Fees & Bid Security</div>
                        </div>
                    </button>

                    <!-- Step 4 Indicator -->
                    <button type="button" onclick="goToStep(4)" id="step_indicator_4" 
                            class="flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-[#e5ded4] bg-stone-50/40">
                        <span id="step_num_4" class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-stone-200 text-stone-700 shrink-0">4</span>
                        <div class="min-w-0">
                            <div class="text-[10px] font-extrabold uppercase tracking-wider text-stone-500">Step 4</div>
                            <div class="text-xs font-black text-stone-700 truncate">Scope & Publish</div>
                        </div>
                    </button>
                </div>

                <!-- 2. EPAD 2.0 Stepper (2 Steps Only: Dates & Fees/Bid Security are eliminated!) -->
                <div id="epad_stepper_row" class="{{ old('tender_type', 'tender') === 'epad' ? '' : 'hidden' }} grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <!-- EPAD Step 1 Indicator -->
                    <button type="button" onclick="goToStep(1)" id="epad_step_indicator_1" 
                            class="flex items-center gap-3 p-3 rounded-2xl border-2 transition-all text-left border-rose-600 bg-rose-50/60 ring-2 ring-rose-200">
                        <span id="epad_step_num_1" class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black bg-rose-600 text-white shrink-0">1</span>
                        <div class="min-w-0">
                            <div class="text-[10px] font-black uppercase tracking-wider text-rose-900 flex items-center gap-1.5">
                                <span>EPAD 2.0 Step 1</span>
                                <span class="px-1.5 py-0.2 bg-rose-200 text-rose-900 rounded font-black text-[9px]">E-Procurement</span>
                            </div>
                            <div class="text-xs font-black text-rose-950 truncate">Basic Details, Closing Date & Budget</div>
                        </div>
                    </button>

                    <!-- EPAD Step 2 Indicator (Loads Scope & Publish) -->
                    <button type="button" onclick="goToStep(4)" id="epad_step_indicator_2" 
                            class="flex items-center gap-3 p-3 rounded-2xl border-2 transition-all text-left border-[#e5ded4] bg-stone-50/40">
                        <span id="epad_step_num_2" class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black bg-stone-200 text-stone-700 shrink-0">2</span>
                        <div class="min-w-0">
                            <div class="text-[10px] font-black uppercase tracking-wider text-stone-500">EPAD 2.0 Step 2</div>
                            <div class="text-xs font-black text-stone-700 truncate">Scope, Technical Dossier & Publish</div>
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
                    <!-- STEP 1: BASIC INFORMATION & BIDDING PROCEDURE                 -->
                    <!-- ============================================================= -->
                    <div id="wizard_step_1" class="space-y-4">
                        <div class="flex items-center justify-between border-b border-[#e5ded4] pb-2.5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                                <h3 class="font-black text-sm uppercase tracking-wider text-stone-900">Step 1: Bidding Procedure & Basic Information</h3>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500">Page 1 of 4</span>
                        </div>

                        <!-- 1. Ultra-Compact Single Row Toolbar: Mode & Procedure -->
                        <div class="p-2 sm:p-2.5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] shadow-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 items-center">
                                <!-- Mode Selector -->
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-black uppercase text-stone-700 whitespace-nowrap min-w-[50px]">Mode:</span>
                                    <div class="grid grid-cols-2 gap-1.5 flex-1">
                                        <label id="card_type_tender" onclick="selectTenderType('tender')"
                                               class="flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-orange-500 bg-orange-50 text-orange-950 ring-2 ring-orange-200">
                                            <input type="radio" name="tender_type" id="type_tender" value="tender" 
                                                   {{ old('tender_type', 'tender') === 'tender' ? 'checked' : '' }}
                                                   class="w-3.5 h-3.5 text-orange-600 focus:ring-orange-500">
                                            <span class="truncate">Tender (Paper)</span>
                                        </label>

                                        <label id="card_type_epad" onclick="selectTenderType('epad')"
                                               class="flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50">
                                            <input type="radio" name="tender_type" id="type_epad" value="epad" 
                                                   {{ old('tender_type') === 'epad' ? 'checked' : '' }}
                                                   class="w-3.5 h-3.5 text-rose-600 focus:ring-rose-500">
                                            <span class="text-rose-950 font-black truncate">EPAD 2.0</span>
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

                        <!-- 2. Tender No & Department -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">
                                    Tender No. / Official Reference Code
                                </label>
                                <input type="text" name="tender_no" id="input_tender_no" value="{{ old('tender_no') }}" 
                                       placeholder="e.g. P&DD/GOS/TD/001/2025-26 (or auto-generated)" 
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">
                            </div>

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
                        </div>

                        <!-- 3. Title / Description -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">
                                Tender Description / Scope of Work <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="title" id="input_title" rows="2" required placeholder="e.g. Supply, Installation, Testing & Commissioning of Audio-Video System for Committee Room..." 
                                      class="w-full text-sm font-semibold rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">{{ old('title') }}</textarea>
                        </div>

                        <!-- 4. Dynamic Fields: Company, Category / (Closing Date & Budget in EPAD) -->
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

                            <!-- Traditional Tender: Category Input -->
                            <div id="tender_category_wrapper" class="sm:col-span-1 md:col-span-2">
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

                            <!-- EPAD 2.0: Closing Date & Time (Replaces Category) -->
                            <div id="epad_closing_wrapper" class="hidden">
                                <label class="block text-xs font-black text-rose-950 mb-1 flex items-center justify-between">
                                    <span>Closing Date & Time <span class="text-rose-500">*</span></span>
                                    <span class="text-[9px] font-bold text-rose-700 bg-rose-50 px-1 py-0.2 rounded border border-rose-200">EPAD</span>
                                </label>
                                <input type="datetime-local" name="closing_date" id="input_closing_date" value="{{ old('closing_date') }}"
                                       oninput="syncEpadDates()"
                                       class="w-full text-xs font-black rounded-xl border-rose-300 bg-rose-50/40 text-rose-950 focus:border-rose-600 focus:ring-rose-500">
                            </div>

                            <!-- EPAD 2.0: Estimated Budget (Directly in Row 4) -->
                            <div id="epad_budget_wrapper" class="hidden">
                                <label class="block text-xs font-black text-emerald-950 mb-1 flex items-center justify-between">
                                    <span>Total Estimated Budget (PKR) <span class="text-rose-500">*</span></span>
                                    <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 px-1 py-0.2 rounded border border-emerald-200">Budget</span>
                                </label>
                                <input type="number" step="0.01" id="input_budget_epad" value="{{ old('budget') }}" 
                                       placeholder="e.g. 12500000" oninput="syncEpadBudget()"
                                       class="w-full text-xs font-black text-emerald-900 rounded-xl border-emerald-300 bg-emerald-50/30 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>

                        <!-- 5. Step 1 Actions: Next / Quick Direct Publish -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-[#e5ded4]">
                            <div class="text-xs text-stone-500 font-semibold text-center sm:text-left">
                                <span id="step1_hint_text">Fill required information before proceeding.</span>
                            </div>
                            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                                <button type="submit" id="btn_epad_quick_publish" class="hidden px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black rounded-xl shadow-xs transition flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Publish Directly</span>
                                </button>
                                <button type="button" id="btn_step1_next" onclick="step1NextAction()" class="px-6 py-2 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                                    <span id="btn_step1_text">Proceed to Dates & Schedule</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================= -->
                    <!-- STEP 2: KEY DATES & SUBMISSION SCHEDULE                       -->
                    <!-- ============================================================= -->
                    <div id="wizard_step_2" class="hidden space-y-6">
                        <div class="flex items-center justify-between border-b border-[#e5ded4] pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <h3 class="font-black text-sm uppercase tracking-wider text-stone-900">Step 2: Tender Collection & Submission Schedule</h3>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500">Page 2 of 4</span>
                        </div>

                        <!-- EPAD Notice in Step 2 -->
                        <div id="epad_step2_banner" class="hidden p-4 rounded-2xl bg-rose-50 border border-rose-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-rose-200 text-rose-900">EPAD 2.0 Mode Active</span>
                                    <span class="text-xs text-rose-900 font-bold">Physical collection dates eliminated. Unified under Closing Date.</span>
                                </div>
                                <button type="button" onclick="goToStep(3)" class="px-4 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition shrink-0">
                                    Proceed to Fees & Security &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- Collection Start & End Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-2">
                                <label class="block text-xs font-black text-stone-800">
                                    Tender Collection (Start Date)
                                </label>
                                <input type="date" name="collection_start_date" value="{{ old('collection_start_date') }}"
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                <input type="text" name="collection_start_notes" value="{{ old('collection_start_notes', 'During working hours') }}" 
                                       placeholder="Timing notes e.g. During working hours"
                                       class="w-full text-[11px] rounded-lg border-[#e5ded4] bg-white px-2.5 py-1.5 text-stone-700">
                            </div>

                            <div class="p-4 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-2">
                                <label class="block text-xs font-black text-stone-800">
                                    Tender Collection (End Date)
                                </label>
                                <input type="date" name="collection_end_date" value="{{ old('collection_end_date') }}"
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                <input type="text" name="collection_end_notes" value="{{ old('collection_end_notes', 'During working hours') }}" 
                                       placeholder="Timing notes e.g. During working hours"
                                       class="w-full text-[11px] rounded-lg border-[#e5ded4] bg-white px-2.5 py-1.5 text-stone-700">
                            </div>
                        </div>

                        <!-- Submission & Opening Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-black text-stone-800">
                                        Tender Submission Deadline <span class="text-rose-500">*</span>
                                    </label>
                                    <span class="text-[10px] font-bold text-stone-500">Date & Time</span>
                                </div>
                                <input type="datetime-local" name="submission_deadline" id="input_submission_deadline" required value="{{ old('submission_deadline') }}"
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                <p class="text-[11px] text-stone-500">e.g. 27/08/2025 up to 11:30 AM</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-2" id="bid_opening_box">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-black text-stone-800" id="bid_opening_label">
                                        Tender / Bid Opening Date (Technical & Financial)
                                    </label>
                                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200" id="bid_opening_badge">
                                        Joint Opening
                                    </span>
                                </div>
                                <input type="datetime-local" name="opening_date" value="{{ old('opening_date') }}"
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                <p class="text-[11px] text-stone-500" id="bid_opening_hint">e.g. 27/08/2025 at 12:00 PM</p>
                            </div>
                        </div>

                        <!-- Step 2 Navigation Buttons -->
                        <div class="flex items-center justify-between pt-4 border-t border-[#e5ded4]">
                            <button type="button" onclick="nextStep(1)" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:text-stone-900 transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span>Back to Step 1</span>
                            </button>
                            <button type="button" onclick="nextStep(3)" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                                <span>Proceed to Fees & Bid Security</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- ============================================================= -->
                    <!-- STEP 3: TENDER FEES & BID SECURITY / EARNEST MONEY            -->
                    <!-- ============================================================= -->
                    <div id="wizard_step_3" class="hidden space-y-6">
                        <div class="flex items-center justify-between border-b border-[#e5ded4] pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <h3 class="font-black text-sm uppercase tracking-wider text-stone-900">Step 3: Tender Form Fees & Bid Security (CDR / Pay Order)</h3>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500">Page 3 of 4</span>
                        </div>

                        <!-- Overall Estimated Budget -->
                        <div class="p-4 rounded-2xl bg-stone-50/70 border border-[#e5ded4] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <label class="block text-xs font-black text-stone-900">Total Estimated Tender Budget (PKR)</label>
                                <p class="text-[11px] text-stone-500">Bid Security % can automatically compute from this amount.</p>
                            </div>
                            <div class="w-full sm:w-64">
                                <input type="number" step="0.01" name="budget" id="input_budget" value="{{ old('budget') }}" 
                                       placeholder="e.g. 12500000" oninput="calculateBidSecurityFromBudget()"
                                       class="w-full text-sm font-black text-emerald-800 rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500 text-right">
                            </div>
                        </div>

                        <!-- SECTION A: TENDER FORM / DOCUMENT FEE -->
                        <div class="p-5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-orange-600"></span>
                                    <h4 class="font-black text-xs uppercase tracking-wider text-stone-900">A. Tender Document / Form Fee</h4>
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
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Paid From Which Bank Account?</label>
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
                                        <option value="cheque">Cheque</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Issued From Bank Account</label>
                                    <select name="bid_security_bank_account_id" class="w-full text-xs font-semibold rounded-xl border-amber-200 bg-white">
                                        <option value="">-- Select Bank Account --</option>
                                        @foreach($bankAccounts as $acc)
                                            <option value="{{ $acc->id }}" {{ old('bid_security_bank_account_id') == $acc->id ? 'selected' : '' }}>
                                                {{ $acc->bank_name }} - {{ $acc->account_title }}
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

                        <!-- Step 3 Navigation Buttons -->
                        <div class="flex items-center justify-between pt-4 border-t border-[#e5ded4]">
                            <button type="button" onclick="step3BackAction()" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:text-stone-900 transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span id="btn_step3_back_text">Back to Step 2</span>
                            </button>
                            <button type="button" onclick="nextStep(4)" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                                <span>Proceed to Scope & Publish</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- ============================================================= -->
                    <!-- STEP 4: SCOPE, TECHNICAL REQUIREMENTS & PUBLISH              -->
                    <!-- ============================================================= -->
                    <div id="wizard_step_4" class="hidden space-y-6">
                        <div class="flex items-center justify-between border-b border-[#e5ded4] pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <h3 id="step4_header_title" class="font-black text-sm uppercase tracking-wider text-stone-900">Step 4: Scope of Work, Technical Requirements & Official RFP Dossier</h3>
                            </div>
                            <span id="step4_page_tag" class="text-[11px] font-bold text-stone-500">Page 4 of 4</span>
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

                        <!-- Review Callout Summary -->
                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-emerald-950 text-xs space-y-1">
                            <div class="font-black flex items-center gap-1.5 text-emerald-900">
                                <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span>Ready to Publish Tender to Database:</span>
                            </div>
                            <p class="text-stone-700" id="step4_callout_text">
                                Upon clicking "Publish Tender Now", the tender will be registered in the official Tender Directory. If Single Stage Two Envelope is selected, Stage 1 Technical evaluations will be tracked first.
                            </p>
                        </div>

                        <!-- Step 4 Navigation Buttons -->
                        <div class="flex items-center justify-between pt-4 border-t border-[#e5ded4]">
                            <button type="button" id="btn_step4_back" onclick="step4BackAction()" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:text-stone-900 transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span id="btn_step4_back_text">Back to Step 3</span>
                            </button>
                            <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black rounded-xl shadow-xs transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span id="btn_publish_label">Publish Tender Now</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let currentTenderType = '{{ old('tender_type', 'tender') }}';

        function selectTenderType(type) {
            currentTenderType = type;
            const radioTender = document.getElementById('type_tender');
            const radioEpad = document.getElementById('type_epad');
            const cardTender = document.getElementById('card_type_tender');
            const cardEpad = document.getElementById('card_type_epad');
            const badgeLabel = document.getElementById('type_badge_label');
            const catWrapper = document.getElementById('tender_category_wrapper');
            const closingWrapper = document.getElementById('epad_closing_wrapper');
            const epadBudgetWrapper = document.getElementById('epad_budget_wrapper');
            const btnStep1Text = document.getElementById('btn_step1_text');
            const btnEpadQuickPublish = document.getElementById('btn_epad_quick_publish');
            const step1HintText = document.getElementById('step1_hint_text');
            const step4HeaderTitle = document.getElementById('step4_header_title');
            const step4PageTag = document.getElementById('step4_page_tag');
            const btnStep4BackText = document.getElementById('btn_step4_back_text');
            const btnPublishLabel = document.getElementById('btn_publish_label');
            const tenderStepperRow = document.getElementById('tender_stepper_row');
            const epadStepperRow = document.getElementById('epad_stepper_row');

            if (type === 'epad') {
                if (radioEpad) radioEpad.checked = true;
                if (cardEpad) {
                    cardEpad.className = 'flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-rose-600 bg-rose-50 text-rose-950 ring-2 ring-rose-200';
                }
                if (cardTender) {
                    cardTender.className = 'flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50';
                }
                if (badgeLabel) {
                    badgeLabel.innerText = 'EPAD 2.0 (E-Procurement)';
                    badgeLabel.className = 'text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full bg-rose-100 text-rose-900 border border-rose-300';
                }

                // Show EPAD Stepper (2 Steps), hide Traditional Stepper (4 Steps)
                if (tenderStepperRow) tenderStepperRow.classList.add('hidden');
                if (epadStepperRow) epadStepperRow.classList.remove('hidden');

                // Step 1: Category replaced by Closing Date & Estimated Budget directly in Step 1
                if (catWrapper) catWrapper.classList.add('hidden');
                if (closingWrapper) closingWrapper.classList.remove('hidden');
                if (epadBudgetWrapper) epadBudgetWrapper.classList.remove('hidden');

                // Step 1 CTA buttons
                if (btnStep1Text) btnStep1Text.innerText = 'Proceed to Scope & Publish (Step 2)';
                if (btnEpadQuickPublish) btnEpadQuickPublish.classList.remove('hidden');
                if (step1HintText) step1HintText.innerText = 'EPAD 2.0: Schedule & Fees/CDR modules are bypassed.';

                // Step 4 (EPAD Step 2) labels
                if (step4HeaderTitle) step4HeaderTitle.innerText = 'Step 2: Scope of Work, Technical Requirements & RFP Dossier (EPAD 2.0)';
                if (step4PageTag) step4PageTag.innerText = 'Page 2 of 2 (EPAD 2.0)';
                if (btnStep4BackText) btnStep4BackText.innerText = 'Back to EPAD Basic Info (Step 1)';
                if (btnPublishLabel) btnPublishLabel.innerText = 'Publish EPAD 2.0 Tender Now';

                // Disable all inputs in Step 3 so no fee/bid security is submitted
                toggleStep3Inputs(true);

                // If currently on step 2 or 3, redirect to 1 or 4
                if (currentStep === 2 || currentStep === 3) {
                    goToStep(1);
                }

                syncEpadBudget();
                syncEpadDates();
            } else {
                if (radioTender) radioTender.checked = true;
                if (cardTender) {
                    cardTender.className = 'flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-orange-500 bg-orange-50 text-orange-950 ring-2 ring-orange-200';
                }
                if (cardEpad) {
                    cardEpad.className = 'flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50';
                }
                if (badgeLabel) {
                    badgeLabel.innerText = 'Traditional Tender (Default)';
                    badgeLabel.className = 'text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full bg-stone-200 text-stone-800 border border-stone-300';
                }

                // Show Traditional Stepper (4 Steps), hide EPAD Stepper
                if (tenderStepperRow) tenderStepperRow.classList.remove('hidden');
                if (epadStepperRow) epadStepperRow.classList.add('hidden');

                // Show Category, hide EPAD closing & budget
                if (catWrapper) catWrapper.classList.remove('hidden');
                if (closingWrapper) closingWrapper.classList.add('hidden');
                if (epadBudgetWrapper) epadBudgetWrapper.classList.add('hidden');

                // Step 1 CTA buttons
                if (btnStep1Text) btnStep1Text.innerText = 'Proceed to Dates & Schedule (Step 2)';
                if (btnEpadQuickPublish) btnEpadQuickPublish.classList.add('hidden');
                if (step1HintText) step1HintText.innerText = 'Fill required information before proceeding.';

                // Step 4 labels
                if (step4HeaderTitle) step4HeaderTitle.innerText = 'Step 4: Scope of Work, Technical Requirements & Official RFP Dossier';
                if (step4PageTag) step4PageTag.innerText = 'Page 4 of 4';
                if (btnStep4BackText) btnStep4BackText.innerText = 'Back to Step 3';
                if (btnPublishLabel) btnPublishLabel.innerText = 'Publish Tender Now';

                // Re-enable inputs in Step 3
                toggleStep3Inputs(false);
            }
        }

        function toggleStep3Inputs(disabled) {
            const step3 = document.getElementById('wizard_step_3');
            if (step3) {
                const inputs = step3.querySelectorAll('input, select, textarea');
                inputs.forEach(el => el.disabled = disabled);
            }
        }

        function step1NextAction() {
            const title = document.getElementById('input_title').value.trim();
            const dept = document.getElementById('input_department_id').value;
            if (!title || !dept) {
                alert('Please enter Tender Title and select a Procuring Department first.');
                return;
            }

            if (currentTenderType === 'epad') {
                const closingDate = document.getElementById('input_closing_date').value;
                if (!closingDate) {
                    alert('Please specify the Closing Date & Time for EPAD 2.0.');
                    document.getElementById('input_closing_date').focus();
                    return;
                }
                syncEpadDates();
                syncEpadBudget();
                // In EPAD mode, skip Step 2 and Step 3, go straight to Step 4 (Scope & Publish)!
                goToStep(4);
            } else {
                goToStep(2);
            }
        }

        function step3BackAction() {
            goToStep(2);
        }

        function step4BackAction() {
            if (currentTenderType === 'epad') {
                goToStep(1);
            } else {
                goToStep(3);
            }
        }

        function syncEpadDates() {
            const closing = document.getElementById('input_closing_date').value;
            if (closing) {
                const deadlineInput = document.getElementById('input_submission_deadline');
                if (deadlineInput) deadlineInput.value = closing;
            }
        }

        function syncEpadBudget() {
            const epadBudget = document.getElementById('input_budget_epad').value;
            const mainBudget = document.getElementById('input_budget');
            if (epadBudget && mainBudget) {
                mainBudget.value = epadBudget;
                calculateBidSecurityFromBudget();
            }
        }

        function goToStep(step) {
            // Validate required fields on earlier steps
            if (step > 1) {
                const title = document.getElementById('input_title').value.trim();
                const dept = document.getElementById('input_department_id').value;
                if (!title || !dept) {
                    alert('Please enter Tender Title and select a Department on Step 1 first.');
                    return;
                }
            }

            if (currentTenderType === 'epad') {
                const closing = document.getElementById('input_closing_date').value;
                if (step > 1 && !closing) {
                    alert('Please enter EPAD 2.0 Closing Date & Time on Step 1 first.');
                    return;
                }
                syncEpadDates();
                syncEpadBudget();
                // In EPAD mode, only Step 1 and Step 4 (Scope & Publish) exist
                if (step === 2 || step === 3) {
                    step = 4;
                }
            } else {
                if (step > 2) {
                    const deadline = document.getElementById('input_submission_deadline').value;
                    if (!deadline) {
                        alert('Please specify the Submission Deadline on Step 2 first.');
                        return;
                    }
                }
            }

            // Hide all steps
            for (let i = 1; i <= 4; i++) {
                const stepEl = document.getElementById('wizard_step_' + i);
                if (stepEl) stepEl.classList.add('hidden');

                // Traditional step indicators
                const ind = document.getElementById('step_indicator_' + i);
                const num = document.getElementById('step_num_' + i);
                if (ind && num) {
                    if (i < step) {
                        ind.className = 'flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-emerald-400 bg-emerald-50/50';
                        num.className = 'w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-emerald-600 text-white shrink-0';
                    } else if (i === step) {
                        ind.className = 'flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-orange-500 bg-orange-50/50 ring-2 ring-orange-200';
                        num.className = 'w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-orange-600 text-white shrink-0';
                    } else {
                        ind.className = 'flex items-center gap-2.5 p-2.5 rounded-2xl border-2 transition-all text-left border-[#e5ded4] bg-stone-50/40';
                        num.className = 'w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black bg-stone-200 text-stone-700 shrink-0';
                    }
                }
            }

            // EPAD 2-step indicators
            const epadInd1 = document.getElementById('epad_step_indicator_1');
            const epadNum1 = document.getElementById('epad_step_num_1');
            const epadInd2 = document.getElementById('epad_step_indicator_2');
            const epadNum2 = document.getElementById('epad_step_num_2');

            if (epadInd1 && epadNum1 && epadInd2 && epadNum2) {
                if (step === 1) {
                    epadInd1.className = 'flex items-center gap-3 p-3 rounded-2xl border-2 transition-all text-left border-rose-600 bg-rose-50/60 ring-2 ring-rose-200';
                    epadNum1.className = 'w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black bg-rose-600 text-white shrink-0';
                    epadInd2.className = 'flex items-center gap-3 p-3 rounded-2xl border-2 transition-all text-left border-[#e5ded4] bg-stone-50/40';
                    epadNum2.className = 'w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black bg-stone-200 text-stone-700 shrink-0';
                } else if (step === 4) {
                    epadInd1.className = 'flex items-center gap-3 p-3 rounded-2xl border-2 transition-all text-left border-emerald-400 bg-emerald-50/50';
                    epadNum1.className = 'w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black bg-emerald-600 text-white shrink-0';
                    epadInd2.className = 'flex items-center gap-3 p-3 rounded-2xl border-2 transition-all text-left border-rose-600 bg-rose-50/60 ring-2 ring-rose-200';
                    epadNum2.className = 'w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black bg-rose-600 text-white shrink-0';
                }
            }

            // Show active step
            document.getElementById('wizard_step_' + step).classList.remove('hidden');
            currentStep = step;
            window.scrollTo({ top: 100, behavior: 'smooth' });
        }

        function nextStep(step) {
            goToStep(step);
        }

        function selectProcedure(type) {
            const radioOne = document.getElementById('proc_one');
            const radioTwo = document.getElementById('proc_two');
            const cardOne = document.getElementById('card_one_envelope');
            const cardTwo = document.getElementById('card_two_envelope');
            const label = document.getElementById('bid_opening_label');
            const badge = document.getElementById('bid_opening_badge');
            const hint = document.getElementById('bid_opening_hint');

            if (type === 'single_stage_two_envelope') {
                radioTwo.checked = true;
                cardTwo.className = 'flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-orange-500 bg-orange-50 text-stone-900 ring-2 ring-orange-200';
                cardOne.className = 'flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50';
                
                if (label) label.innerHTML = 'Stage 1: Technical Bid Opening Date & Time';
                if (badge) {
                    badge.innerText = 'Stage 1: Technical Only';
                    badge.className = 'text-[10px] font-bold text-orange-800 bg-orange-100 px-2 py-0.5 rounded border border-orange-200';
                }
                if (hint) hint.innerText = 'Date and time when Envelope 1 (Technical) will be opened.';
            } else {
                radioOne.checked = true;
                cardOne.className = 'flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-orange-500 bg-orange-50 text-stone-900 ring-2 ring-orange-200';
                cardTwo.className = 'flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50';

                if (label) label.innerHTML = 'Tender / Bid Opening Date (Technical & Financial Bids)';
                if (badge) {
                    badge.innerText = 'Joint Opening';
                    badge.className = 'text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200';
                }
                if (hint) hint.innerText = 'Both Technical & Financial bids are opened together.';
            }
        }

        function calculateBidSecurityFromBudget() {
            const budgetVal = parseFloat(document.getElementById('input_budget').value) || 0;
            const pctVal = parseFloat(document.getElementById('bid_security_percentage').value) || 0;
            const securityInput = document.getElementById('bid_security_amount');
            
            if (budgetVal > 0 && pctVal > 0) {
                const computed = (budgetVal * (pctVal / 100));
                securityInput.value = computed.toFixed(2);
            }
        }

        function toggleTenderFeeFields() {
            const checkbox = document.getElementById('checkbox_tender_fee_paid');
            // Can be used to highlight paid state
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize procedure
            const radioTwo = document.getElementById('proc_two');
            if (radioTwo && radioTwo.checked) {
                selectProcedure('single_stage_two_envelope');
            } else {
                selectProcedure('single_stage_one_envelope');
            }

            // Initialize procurement type (Tender vs EPAD 2.0)
            const radioEpad = document.getElementById('type_epad');
            if (radioEpad && radioEpad.checked) {
                selectTenderType('epad');
            } else {
                selectTenderType('tender');
            }
        });
    </script>
</x-app-layout>
