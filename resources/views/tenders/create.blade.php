<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1 text-xs text-stone-500 font-semibold">
                    <a href="{{ route('tenders.index') }}" class="hover:text-orange-700 transition">Tenders</a>
                    <span>/</span>
                    <span class="text-stone-800 font-bold">New Tender</span>
                </div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight">
                    {{ __('Create New Tender') }}
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">
                    Enter official procurement details, schedule, requirements, and dossier files.
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

            <!-- MAIN CREATE TENDER FORM CONTAINER -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-6 sm:p-8 shadow-xs">
                <form method="POST" action="{{ route('tenders.store') }}" enctype="multipart/form-data" id="tenderCreateForm" class="space-y-6">
                    @csrf

                    <!-- TOP TOOLBAR: MODE & PROCEDURE SELECTOR -->
                    <div class="p-3.5 rounded-2xl bg-stone-50/70 border border-[#e5ded4] shadow-xs">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <!-- MODE SELECTION (Tender Paper Only) -->
                            <div class="flex items-center gap-3">
                                <span class="text-[11px] font-black uppercase text-stone-500 tracking-wider">MODE:</span>
                                <div class="inline-flex items-center gap-1.5 bg-white p-1 rounded-xl border border-[#e5ded4] shadow-2xs">
                                    <label class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold border bg-orange-50/80 text-orange-950 border-orange-300 ring-1 ring-orange-200 cursor-default">
                                        <input type="hidden" name="tender_type" id="mode_tender_radio" value="tender">
                                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Tender (Paper)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- PROCEDURE SELECTION -->
                            <div class="flex items-center gap-3">
                                <span class="text-[11px] font-black uppercase text-stone-500 tracking-wider">PROCEDURE:</span>
                                <div class="inline-flex items-center gap-1.5 bg-white p-1 rounded-xl border border-[#e5ded4] shadow-2xs">
                                    <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg cursor-pointer text-xs font-bold transition border"
                                           id="proc_1e_label" onclick="selectProcedure('single_stage_one_envelope')">
                                        <input type="radio" name="bidding_procedure" id="proc_1e_radio" value="single_stage_one_envelope" 
                                               {{ old('bidding_procedure', 'single_stage_one_envelope') === 'single_stage_one_envelope' ? 'checked' : '' }} class="hidden">
                                        <span>Single Stage 1E</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg cursor-pointer text-xs font-bold transition border"
                                           id="proc_2e_label" onclick="selectProcedure('single_stage_two_envelope')">
                                        <input type="radio" name="bidding_procedure" id="proc_2e_radio" value="single_stage_two_envelope" 
                                               {{ old('bidding_procedure') === 'single_stage_two_envelope' ? 'checked' : '' }} class="hidden">
                                        <span>Single Stage 2E</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- SECTION 1: GENERAL INFORMATION                            -->
                    <!-- ======================================================== -->
                    <div class="p-6 rounded-2xl bg-white border border-[#e5ded4] shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-[#e5ded4]">
                            <div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="font-black text-sm text-stone-900 tracking-tight">General Information</h3>
                        </div>

                        <!-- Row 1: Procuring Department & Official Reference Code -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    Procuring Department / Agency <span class="text-rose-500">*</span>
                                </label>
                                <select name="department_id" id="input_department_id" required 
                                        class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition py-2.5">
                                    <option value="">-- Choose Department / Client --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-xs font-bold text-stone-700">
                                        Tender # / Official Reference Code
                                    </label>
                                    <span class="text-[10px] text-stone-400 font-semibold" title="Auto-generated if left blank">Auto or Official #</span>
                                </div>
                                <input type="text" name="tender_no" id="input_tender_no" value="{{ old('tender_no') }}" 
                                       placeholder="e.g. P&DD/GOS/TD/001/2025-26 (or auto-generated)" 
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition py-2.5">
                            </div>
                        </div>

                        <!-- Row 2: Tender Description / Scope of Work -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                Tender Description / Scope of Work <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="title" id="input_title" rows="2" required 
                                      placeholder="e.g. Supply, Installation, Testing & Commissioning of Audio-Video System for Committee Room..." 
                                      class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition p-3">{{ old('title') }}</textarea>
                        </div>

                        <!-- Row 3: Estimated Budget (PKR) & Contact Person (Optional) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    Estimated Budget (PKR)
                                </label>
                                <div class="relative rounded-xl shadow-2xs">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-black text-stone-500">
                                        PKR
                                    </div>
                                    <input type="number" step="0.01" name="budget" id="input_budget" value="{{ old('budget') }}" 
                                           placeholder="e.g. 12,500,000" 
                                           class="w-full text-xs font-bold text-emerald-900 rounded-xl border-[#e5ded4] bg-[#FCFAF7] pl-12 pr-3.5 py-2.5 focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition">
                                </div>
                                <span class="text-[10px] text-stone-400 mt-1 block">Enter total estimated value</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    Contact Person (Name, Designation or Phone Number) <span class="text-stone-400 font-normal">(Optional)</span>
                                </label>
                                <div class="relative rounded-xl shadow-2xs">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <input type="text" name="contact_person" id="input_contact_person" value="{{ old('contact_person') }}" 
                                           placeholder="e.g. Engr. Tariq (XEN) - 0300-1234567" 
                                           class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] pl-10 pr-3.5 py-2.5 focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- SECTION 2: SCHEDULE & TIMELINE                            -->
                    <!-- ======================================================== -->
                    <div class="p-6 rounded-2xl bg-white border border-[#e5ded4] shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-[#e5ded4]">
                            <div class="w-6 h-6 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center font-black text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="font-black text-sm text-stone-900 tracking-tight">Schedule & Timeline</h3>
                        </div>

                        <!-- 1E Single Opening Date View -->
                        <div id="schedule_1e_container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    Tender Submission Date & Time <span class="text-rose-500">*</span>
                                </label>
                                <input type="datetime-local" name="submission_deadline" id="input_submission_deadline" required 
                                       value="{{ old('submission_deadline') }}"
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition py-2.5">
                                <p class="text-[10px] text-stone-400 mt-1">e.g. 27/08/2026 up to 11:30 AM</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    Tender / Bid Opening Date (Technical & Financial)
                                </label>
                                <input type="datetime-local" name="opening_date" id="input_opening_date" 
                                       value="{{ old('opening_date') }}"
                                       class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition py-2.5">
                                <p class="text-[10px] text-stone-400 mt-1">e.g. 27/08/2026 at 12:00 PM</p>
                            </div>
                        </div>

                        <!-- 2E Two Envelopes Opening Dates View (Hidden by default unless 2E is active) -->
                        <div id="schedule_2e_container" class="hidden space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                        Tender Submission Date & Time <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="submission_deadline_2e" id="input_submission_deadline_2e" 
                                           value="{{ old('submission_deadline') }}"
                                           class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition py-2.5">
                                    <p class="text-[10px] text-stone-400 mt-1">Submission cutoff time</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                        Technical Opening Date & Time <span class="text-orange-600 font-extrabold">(Stage 1)</span>
                                    </label>
                                    <input type="datetime-local" name="technical_opening_date" id="input_technical_opening_date" 
                                           value="{{ old('technical_opening_date') }}"
                                           class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition py-2.5">
                                    <p class="text-[10px] text-stone-400 mt-1">e.g. 27/08/2026 at 11:30 AM</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                        Financial Opening Date & Time <span class="text-emerald-700 font-extrabold">(Stage 2)</span>
                                    </label>
                                    <input type="datetime-local" name="financial_opening_date" id="input_financial_opening_date" 
                                           value="{{ old('financial_opening_date') }}"
                                           class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition py-2.5">
                                    <p class="text-[10px] text-stone-400 mt-1">e.g. 28/08/2026 at 12:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- SECTION 3: ELIGIBILITY & DOCUMENTATION                    -->
                    <!-- ======================================================== -->
                    <div class="p-6 rounded-2xl bg-white border border-[#e5ded4] shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-[#e5ded4]">
                            <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="font-black text-sm text-stone-900 tracking-tight">Eligibility & Documentation</h3>
                        </div>

                        <!-- Vendor Eligibility -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                Vendor Eligibility Criteria & Compliance Requirements
                            </label>
                            <textarea name="requirements" rows="3" 
                                      placeholder="e.g. 1. Valid PEC registration, 2. Minimum 3 years experience, 3. Turn over > PKR 50M..." 
                                      class="w-full text-xs font-medium rounded-xl border-[#e5ded4] bg-[#FCFAF7] focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition p-3">{{ old('requirements') }}</textarea>
                        </div>

                        <!-- Official Tender Document Upload Area with Live Preview -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                Tender Document <span class="text-stone-400 font-normal">(PDF / DOCX / ZIP)</span>
                            </label>

                            <div class="relative">
                                <input type="file" name="document" id="tender_document_file" accept=".pdf,.doc,.docx,.zip" class="hidden" onchange="handleFileSelect(this)">
                                
                                <!-- Empty State Container (Click to browse/upload) -->
                                <div id="tender_file_empty_box" 
                                     class="border-2 border-dashed border-[#d8d0c5] hover:border-orange-400 rounded-2xl p-6 text-center bg-[#FCFAF7] hover:bg-orange-50/20 transition group cursor-pointer flex flex-col items-center justify-center space-y-2"
                                     onclick="document.getElementById('tender_document_file').click()">
                                    <div class="w-10 h-10 rounded-full bg-orange-100/70 text-orange-600 flex items-center justify-center pointer-events-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    </div>
                                    <div class="text-xs font-bold text-stone-800 pointer-events-none">
                                        Drag & drop files here or <span class="text-orange-600 underline">click to upload</span>
                                    </div>
                                    <p class="text-[11px] text-stone-400 pointer-events-none">
                                        Supports PDF, DOCX, ZIP • Max size 50MB
                                    </p>
                                    <button type="button" class="mt-1 px-4 py-1.5 bg-white border border-[#e5ded4] hover:bg-stone-50 rounded-lg text-xs font-bold text-stone-700 shadow-2xs pointer-events-none">
                                        Choose File
                                    </button>
                                </div>

                                <!-- Selected File Container (Filename inside field with Eye icon preview) -->
                                <div id="tender_file_selected_box" class="hidden p-3.5 bg-emerald-50/80 rounded-2xl border border-emerald-300 shadow-2xs items-center justify-between">
                                    <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <div id="preview_filename" class="text-xs font-black text-stone-900 truncate">document.pdf</div>
                                            <div id="preview_filesize" class="text-[10px] text-stone-500 font-medium">0 KB</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <!-- Eye icon button for preview -->
                                        <button type="button" onclick="previewUploadedFile()" class="p-2 text-emerald-800 hover:text-emerald-950 bg-white hover:bg-emerald-100 rounded-xl border border-emerald-300 transition flex items-center gap-1.5 text-xs font-bold shadow-2xs" title="Preview Document">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Preview</span>
                                        </button>
                                        <!-- Change File Button -->
                                        <button type="button" onclick="document.getElementById('tender_document_file').click()" class="px-3 py-2 text-stone-600 hover:text-stone-900 bg-white hover:bg-stone-50 rounded-xl border border-stone-200 transition text-xs font-bold shadow-2xs">
                                            Change
                                        </button>
                                        <!-- Remove Button -->
                                        <button type="button" onclick="removeSelectedFile()" class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Remove file">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- SECTION 4: BIDDING DETAILS                                -->
                    <!-- ======================================================== -->
                    <div class="p-6 rounded-2xl bg-white border border-[#e5ded4] shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-[#e5ded4]">
                            <div class="w-6 h-6 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-black text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <h3 class="font-black text-sm text-stone-900 tracking-tight">Bidding Details</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">Our Bidding Firm</label>
                                <div class="relative rounded-xl shadow-2xs">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <select name="company_id" 
                                            class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] pl-10 pr-3.5 py-2.5 focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition">
                                        <option value="">-- Choose Company (Optional) --</option>
                                        @foreach($companies as $comp)
                                            <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                                {{ $comp->company_name ?: $comp->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">Category</label>
                                <div class="relative rounded-xl shadow-2xs">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </div>
                                    <input type="text" name="category" id="input_category" value="{{ old('category', 'Audio-Video & IT Systems') }}" list="cat_list" 
                                           class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-[#FCFAF7] pl-10 pr-3.5 py-2.5 focus:bg-white focus:border-orange-500 focus:ring-orange-500 transition">
                                    <datalist id="cat_list">
                                        <option value="Audio-Video & IT Systems">
                                        <option value="Civil Works & Construction">
                                        <option value="Medical & Healthcare Logistics">
                                        <option value="Electrical & Power Systems">
                                    </datalist>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======================================================== -->
                    <!-- FOOTER ACTIONS: CANCEL, SAVE DRAFT, CREATE TENDER         -->
                    <!-- ======================================================== -->
                    <div class="mt-8 pt-6 pb-2 border-t border-[#e5ded4] flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="{{ route('tenders.index') }}" class="px-5 py-2.5 text-xs font-bold text-stone-600 hover:text-stone-900 transition flex items-center gap-1.5">
                            &larr; Cancel & Return to Directory
                        </a>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            <!-- SAVE AS DRAFT BUTTON -->
                            <button type="submit" name="is_draft" value="1" 
                                    class="px-6 py-2.5 bg-white hover:bg-stone-50 text-stone-700 text-xs font-bold rounded-xl border border-[#d8d0c5] shadow-2xs transition flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                <span>Save as Draft</span>
                            </button>

                            <!-- CREATE TENDER BUTTON -->
                            <button type="submit" name="is_draft" value="0"
                                    class="px-6 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-black rounded-xl shadow-xs transition flex items-center gap-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>Create Tender</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- DOCUMENT PREVIEW MODAL (Enlarged for clear document & PDF reading) -->
    <div id="docPreviewModal" class="fixed inset-0 z-50 bg-stone-900/60 backdrop-blur-xs hidden items-center justify-center p-3 sm:p-6">
        <div class="bg-white rounded-3xl border border-stone-200 shadow-2xl max-w-5xl w-full overflow-hidden flex flex-col h-[90vh] max-h-[92vh]">
            <div class="p-4 px-6 bg-stone-100/90 border-b border-stone-200 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2 min-w-0">
                    <svg class="w-5 h-5 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span id="modalDocTitle" class="font-black text-sm text-stone-900 truncate">Document Preview</span>
                </div>
                <button type="button" onclick="closeDocPreviewModal()" class="text-stone-400 hover:text-stone-800 p-1 font-bold text-xl leading-none">&times;</button>
            </div>
            <div id="modalDocBody" class="p-4 flex-1 overflow-auto flex items-center justify-center min-h-[500px] h-full bg-stone-50">
                <!-- Preview Content Injected Dynamically -->
            </div>
            <div class="p-3 px-6 bg-white border-t border-stone-200 flex items-center justify-between shrink-0">
                <span class="text-[11px] text-stone-500 font-medium">Use mouse wheel or scrollbar to view all pages</span>
                <button type="button" onclick="closeDocPreviewModal()" class="px-6 py-2 bg-stone-800 hover:bg-stone-900 text-white rounded-xl text-xs font-bold transition">Close</button>
            </div>
        </div>
    </div>

    <script>
        let selectedFileBlob = null;
        let selectedFileType = '';

        function selectProcedure(proc) {
            const rad1 = document.getElementById('proc_1e_radio');
            const rad2 = document.getElementById('proc_2e_radio');
            const lbl1 = document.getElementById('proc_1e_label');
            const lbl2 = document.getElementById('proc_2e_label');
            const box1 = document.getElementById('schedule_1e_container');
            const box2 = document.getElementById('schedule_2e_container');
            const subInput1 = document.getElementById('input_submission_deadline');
            const subInput2 = document.getElementById('input_submission_deadline_2e');

            if (proc === 'single_stage_one_envelope') {
                rad1.checked = true;
                lbl1.className = "flex items-center gap-1.5 px-3 py-1.5 rounded-lg cursor-pointer text-xs font-bold transition border bg-orange-50/80 text-orange-950 border-orange-300 ring-1 ring-orange-200";
                lbl2.className = "flex items-center gap-1.5 px-3 py-1.5 rounded-lg cursor-pointer text-xs font-bold transition border bg-transparent text-stone-600 border-transparent hover:text-stone-900";
                box1.classList.remove('hidden');
                box2.classList.add('hidden');
                subInput1.required = true;
                subInput2.required = false;
                if (subInput2.value && !subInput1.value) subInput1.value = subInput2.value;
            } else {
                rad2.checked = true;
                lbl2.className = "flex items-center gap-1.5 px-3 py-1.5 rounded-lg cursor-pointer text-xs font-bold transition border bg-orange-50/80 text-orange-950 border-orange-300 ring-1 ring-orange-200";
                lbl1.className = "flex items-center gap-1.5 px-3 py-1.5 rounded-lg cursor-pointer text-xs font-bold transition border bg-transparent text-stone-600 border-transparent hover:text-stone-900";
                box1.classList.add('hidden');
                box2.classList.remove('hidden');
                subInput1.required = false;
                subInput2.required = true;
                if (subInput1.value && !subInput2.value) subInput2.value = subInput1.value;
            }
        }

        // Synchronize 1E and 2E submission deadlines before form submit
        document.getElementById('tenderCreateForm').addEventListener('submit', function(e) {
            const rad2 = document.getElementById('proc_2e_radio');
            const subInput1 = document.getElementById('input_submission_deadline');
            const subInput2 = document.getElementById('input_submission_deadline_2e');
            if (rad2 && rad2.checked) {
                if (subInput2.value) subInput1.value = subInput2.value;
            }
        });

        // Initialize procedure state on page load
        window.addEventListener('DOMContentLoaded', () => {
            const rad2 = document.getElementById('proc_2e_radio');
            if (rad2 && rad2.checked) {
                selectProcedure('single_stage_two_envelope');
            } else {
                selectProcedure('single_stage_one_envelope');
            }
        });

        // File Selection & Preview Handling
        function handleFileSelect(input) {
            const emptyBox = document.getElementById('tender_file_empty_box');
            const selectedBox = document.getElementById('tender_file_selected_box');
            const nameEl = document.getElementById('preview_filename');
            const sizeEl = document.getElementById('preview_filesize');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                selectedFileBlob = file;
                selectedFileType = file.type || file.name.split('.').pop().toLowerCase();

                nameEl.textContent = file.name;
                const sizeKb = (file.size / 1024).toFixed(1);
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                sizeEl.textContent = file.size > 1048576 ? `${sizeMb} MB` : `${sizeKb} KB`;

                if (emptyBox) { emptyBox.classList.add('hidden'); emptyBox.classList.remove('flex'); }
                if (selectedBox) { selectedBox.classList.remove('hidden'); selectedBox.classList.add('flex'); }
            } else {
                if (emptyBox) { emptyBox.classList.remove('hidden'); emptyBox.classList.add('flex'); }
                if (selectedBox) { selectedBox.classList.add('hidden'); selectedBox.classList.remove('flex'); }
                selectedFileBlob = null;
            }
        }

        function removeSelectedFile() {
            const input = document.getElementById('tender_document_file');
            input.value = '';
            const emptyBox = document.getElementById('tender_file_empty_box');
            const selectedBox = document.getElementById('tender_file_selected_box');
            if (emptyBox) { emptyBox.classList.remove('hidden'); emptyBox.classList.add('flex'); }
            if (selectedBox) { selectedBox.classList.add('hidden'); selectedBox.classList.remove('flex'); }
            selectedFileBlob = null;
        }

        function previewUploadedFile() {
            if (!selectedFileBlob) return;
            const modal = document.getElementById('docPreviewModal');
            const body = document.getElementById('modalDocBody');
            const title = document.getElementById('modalDocTitle');
            title.textContent = selectedFileBlob.name;

            const fileUrl = URL.createObjectURL(selectedFileBlob);

            if (selectedFileBlob.type === 'application/pdf' || selectedFileBlob.name.toLowerCase().endsWith('.pdf')) {
                body.innerHTML = `<iframe src="${fileUrl}" class="w-full h-full min-h-[650px] rounded-2xl border border-stone-200 shadow-inner" style="min-height: 650px; height: 75vh;"></iframe>`;
            } else if (selectedFileBlob.type.startsWith('image/')) {
                body.innerHTML = `<img src="${fileUrl}" class="max-h-[75vh] max-w-full rounded-2xl object-contain shadow-xs">`;
            } else {
                body.innerHTML = `
                    <div class="text-center py-10 space-y-3">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h4 class="font-black text-stone-900 text-sm">${selectedFileBlob.name}</h4>
                        <p class="text-xs text-stone-500 max-w-sm mx-auto">This document (${selectedFileType}) will be attached upon saving.</p>
                        <a href="${fileUrl}" download="${selectedFileBlob.name}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white rounded-xl text-xs font-bold shadow-xs">
                            Download / Open File
                        </a>
                    </div>
                `;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDocPreviewModal() {
            const modal = document.getElementById('docPreviewModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('modalDocBody').innerHTML = '';
        }
    </script>
</x-app-layout>
