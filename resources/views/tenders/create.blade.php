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
                        Procurement Tender Entry
                    </span>
                </div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight flex items-center gap-2">
                    <span>{{ __('Create New Procurement Tender') }}</span>
                    <span class="text-xs font-bold bg-stone-200 text-stone-700 px-2.5 py-0.5 rounded-full border border-stone-300">SPPRA / PPRA</span>
                </h2>
                <p class="text-xs text-stone-600 mt-0.5">
                    Direct entry form to setup tender reference, procuring agency, schedule dates, vendor eligibility, and upload RFP dossier.
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

            <!-- MAIN CREATE TENDER FORM CARD -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-6 sm:p-8 shadow-xs">
                <form method="POST" action="{{ route('tenders.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- 1. Toolbar: Mode & Procedure Selector -->
                    <div class="p-3 sm:p-3.5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] shadow-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
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
                                <div class="grid grid-cols-2 gap-2 flex-1">
                                    <label id="card_one_envelope" onclick="selectProcedure('single_stage_one_envelope')"
                                           class="flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition {{ old('bidding_procedure', 'single_stage_one_envelope') === 'single_stage_one_envelope' ? 'border-orange-500 bg-orange-50 text-stone-900 ring-2 ring-orange-200' : 'border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50' }}">
                                        <input type="radio" name="bidding_procedure" id="proc_one" value="single_stage_one_envelope" 
                                               {{ old('bidding_procedure', 'single_stage_one_envelope') === 'single_stage_one_envelope' ? 'checked' : '' }}
                                               class="w-3.5 h-3.5 text-orange-600 focus:ring-orange-500">
                                        <span class="truncate">Single Stage 1E</span>
                                    </label>

                                    <label id="card_two_envelope" onclick="selectProcedure('single_stage_two_envelope')"
                                           class="flex items-center justify-center gap-1.5 py-1.5 px-2.5 rounded-xl border-2 cursor-pointer font-bold text-xs transition {{ old('bidding_procedure') === 'single_stage_two_envelope' ? 'border-orange-500 bg-orange-50 text-stone-900 ring-2 ring-orange-200' : 'border-[#e5ded4] bg-white text-stone-700 hover:bg-stone-50' }}">
                                        <input type="radio" name="bidding_procedure" id="proc_two" value="single_stage_two_envelope" 
                                               {{ old('bidding_procedure') === 'single_stage_two_envelope' ? 'checked' : '' }}
                                               class="w-3.5 h-3.5 text-orange-600 focus:ring-orange-500">
                                        <span class="truncate">Single Stage 2E</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Procuring Department & Tender # / Official Reference Code -->
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

                    <!-- 3. Tender Description / Scope of Work -->
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
                                   placeholder="e.g. 12500000"
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

                    <!-- 5. Schedule Dates: Submission Date & Bid Opening Date -->
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

                    <!-- 6. Relocated Field: Vendor Eligibility Criteria & Compliance Requirements -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">
                            Vendor Eligibility Criteria & Compliance Requirements
                        </label>
                        <textarea name="requirements" rows="3" placeholder="e.g. 1. Valid PEC registration, 2. Minimum 3 years experience, 3. Turn over > PKR 50M..." 
                                  class="w-full text-xs rounded-xl border-[#e5ded4] bg-stone-50/40 focus:bg-white focus:border-orange-500 focus:ring-orange-500">{{ old('requirements') }}</textarea>
                    </div>

                    <!-- 7. Relocated Field: Official RFP / Tender Document Dossier Upload -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">
                            Official RFP / Tender Document Dossier (PDF / DOCX / ZIP)
                        </label>
                        <input type="file" name="document" accept=".pdf,.docx,.zip" 
                               class="w-full text-xs text-stone-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-800 hover:file:bg-orange-100 border border-[#e5ded4] rounded-xl p-1 bg-stone-50/40">
                    </div>

                    <!-- 8. Dynamic Fields: Bidding Firm & Category -->
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

                    <!-- 9. Submit Actions -->
                    <div class="flex items-center justify-between pt-5 border-t border-[#e5ded4]">
                        <a href="{{ route('tenders.index') }}" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:text-stone-900 transition flex items-center gap-1.5">
                            &larr; Cancel & Return to Directory
                        </a>

                        <button type="submit" class="px-8 py-3 bg-orange-600 hover:bg-orange-500 text-white text-xs font-black rounded-xl shadow-xs transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Create Tender</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</x-app-layout>
