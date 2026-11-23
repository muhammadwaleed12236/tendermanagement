<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('tenders.index') }}" class="text-xs font-bold text-orange-700 hover:text-orange-800 transition flex items-center gap-1">
                        &larr; Back to Directory
                    </a>
                    <span class="text-stone-300">•</span>
                    <span class="font-mono text-xs font-bold bg-stone-200 text-stone-800 px-2 py-0.5 rounded border border-stone-300">
                        {{ $tender->effective_tender_no }}
                    </span>
                    <span class="text-stone-300">•</span>
                    @if($tender->isEpad())
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-900 border border-rose-300">
                            EPAD 2.0
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-stone-100 text-stone-700 border border-stone-200">
                            Tender
                        </span>
                    @endif
                    <span class="text-stone-300">•</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider 
                        {{ $tender->isTwoEnvelope() ? 'bg-orange-100 text-orange-900 border border-orange-200' : 'bg-stone-100 text-stone-800 border border-stone-200' }}">
                        {{ $tender->procedure_label }}
                    </span>
                </div>
                <h2 class="font-black text-2xl text-stone-900 leading-tight">
                    {{ $tender->title }}
                </h2>
            </div>
            @if(Auth::check() && Auth::user()->isAdmin())
                <div class="flex items-center gap-2">
                    <a href="{{ route('tenders.edit', $tender) }}" class="px-4 py-2 bg-white hover:bg-stone-50 text-stone-700 border border-[#e5ded4] text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit Tender</span>
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            @if(session('info'))
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-2xl text-blue-800 text-xs font-bold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-700 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-bold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                </div>
            @endif

            <!-- ------------------------------------------------------------- -->
            <!-- STAGE TRACKER & INTERACTIVE STATUS CONTROLLER                 -->
            <!-- ------------------------------------------------------------- -->
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-6 sm:p-7 shadow-xs space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#e5ded4] pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $tender->isWon() ? 'bg-emerald-500' : ($tender->isLost() ? 'bg-rose-500' : 'bg-blue-500 animate-pulse') }}"></span>
                            <h3 class="font-black text-lg text-stone-900">
                                Bidding Procedure & Stage Progression
                            </h3>
                        </div>
                        <p class="text-xs text-stone-600 mt-0.5">
                            Procedure: <strong>{{ $tender->procedure_label }}</strong>
                        </p>
                    </div>

                    <!-- Current Overall Outcome Badge -->
                    <div>
                        @if($tender->isWon())
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-900 border border-emerald-300 shadow-xs">
                                <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                🏆 FINAL RESULT: WON / AWARDED
                            </span>
                        @elseif($tender->isLost())
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-rose-100 text-rose-900 border border-rose-300 shadow-xs">
                                <svg class="w-4 h-4 text-rose-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                ❌ FINAL RESULT: LOST / DISQUALIFIED
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-blue-100 text-blue-900 border border-blue-200 shadow-xs">
                                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                                IN PROGRESS
                            </span>
                        @endif
                    </div>
                </div>

                @if($tender->isTwoEnvelope())
                    <!-- ============================================== -->
                    <!-- 2-STAGE WORKFLOW (SINGLE STAGE TWO ENVELOPE)   -->
                    <!-- ============================================== -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- STAGE 1: TECHNICAL ENVELOPE -->
                        <div class="p-5 rounded-2xl border-2 transition-all 
                            @if($tender->stage_1_status === 'qualified') border-emerald-300 bg-emerald-50/40 
                            @elseif($tender->stage_1_status === 'disqualified') border-rose-300 bg-rose-50/40 
                            @else border-orange-400 bg-orange-50/20 ring-2 ring-orange-100 @endif space-y-3">
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black
                                        @if($tender->stage_1_status === 'qualified') bg-emerald-600 text-white
                                        @elseif($tender->stage_1_status === 'disqualified') bg-rose-600 text-white
                                        @else bg-orange-500 text-white @endif">
                                        1
                                    </span>
                                    <h4 class="font-black text-sm text-stone-900">Stage 1: Technical Bid</h4>
                                </div>

                                @if($tender->stage_1_status === 'qualified')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        ✓ Qualified / Win
                                    </span>
                                @elseif($tender->stage_1_status === 'disqualified')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">
                                        ✗ Disqualified / Lose
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">
                                        ● Under Technical Evaluation
                                    </span>
                                @endif
                            </div>

                            <div class="text-xs text-stone-600 space-y-1">
                                <div class="flex justify-between">
                                    <span class="font-medium text-stone-500">Opening Date & Time:</span>
                                    <span class="font-bold text-stone-900">
                                        {{ ($tender->technical_opening_date ?: $tender->opening_date) ? ($tender->technical_opening_date ?: $tender->opening_date)->format('d/m/Y \a\t h:i A') : 'Not specified' }}
                                    </span>
                                </div>
                                @if($tender->stage_1_completed_at)
                                    <div class="flex justify-between">
                                        <span class="font-medium text-stone-500">Evaluated On:</span>
                                        <span class="font-bold text-stone-800">{{ $tender->stage_1_completed_at->format('d/m/Y h:i A') }}</span>
                                    </div>
                                @endif
                                @if($tender->stage_1_notes)
                                    <div class="mt-2 p-2 bg-white rounded-xl border border-stone-200 text-stone-700 italic">
                                        "{{ $tender->stage_1_notes }}"
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- STAGE 2: FINANCIAL ENVELOPE -->
                        <div class="p-5 rounded-2xl border-2 transition-all 
                            @if($tender->stage_2_status === 'won') border-emerald-300 bg-emerald-50/40 
                            @elseif($tender->stage_2_status === 'lost') border-rose-300 bg-rose-50/40 
                            @elseif($tender->isStage1Cleared()) border-blue-400 bg-blue-50/20 ring-2 ring-blue-100
                            @else border-stone-200 bg-stone-50 opacity-70 @endif space-y-3">
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black
                                        @if($tender->stage_2_status === 'won') bg-emerald-600 text-white
                                        @elseif($tender->stage_2_status === 'lost') bg-rose-600 text-white
                                        @elseif($tender->isStage1Cleared()) bg-blue-600 text-white
                                        @else bg-stone-300 text-stone-600 @endif">
                                        2
                                    </span>
                                    <h4 class="font-black text-sm text-stone-900">Stage 2: Financial Bid</h4>
                                </div>

                                @if($tender->stage_2_status === 'won')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        🏆 Final Award Won
                                    </span>
                                @elseif($tender->stage_2_status === 'lost')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">
                                        ✗ Financial Lost
                                    </span>
                                @elseif($tender->stage_1_status === 'disqualified')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-stone-200 text-stone-600">
                                        Terminated (Stage 1 Lost)
                                    </span>
                                @elseif($tender->isStage1Cleared())
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-100 text-blue-800 border border-blue-300">
                                        ● Scheduled / In Review
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-stone-200 text-stone-600">
                                        Locked (Awaiting Stage 1)
                                    </span>
                                @endif
                            </div>

                            <div class="text-xs text-stone-600 space-y-1">
                                <div class="flex justify-between">
                                    <span class="font-medium text-stone-500">Financial Opening Date & Time:</span>
                                    <span class="font-bold text-stone-900">
                                        @if($tender->financial_opening_date)
                                            {{ $tender->financial_opening_date->format('d/m/Y \a\t h:i A') }}
                                        @elseif($tender->stage_1_status === 'disqualified')
                                            <span class="text-stone-400 italic">Not applicable</span>
                                        @else
                                            <span class="text-orange-700 italic">To be scheduled upon qualifying Stage 1</span>
                                        @endif
                                    </span>
                                </div>
                                @if($tender->stage_2_completed_at)
                                    <div class="flex justify-between">
                                        <span class="font-medium text-stone-500">Decided On:</span>
                                        <span class="font-bold text-stone-800">{{ $tender->stage_2_completed_at->format('d/m/Y h:i A') }}</span>
                                    </div>
                                @endif
                                @if($tender->stage_2_notes)
                                    <div class="mt-2 p-2 bg-white rounded-xl border border-stone-200 text-stone-700 italic">
                                        "{{ $tender->stage_2_notes }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- ADMIN ACTION PANEL FOR TWO-ENVELOPE (WHEN IN PROGRESS) -->
                    @if(Auth::check() && Auth::user()->isAdmin() && $tender->tender_outcome === 'in_progress')
                        <div class="p-5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-4">
                            @if($tender->current_stage === 'stage_1')
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                                    <h4 class="font-black text-xs uppercase tracking-wider text-stone-800">
                                        Evaluate Stage 1 (Technical Bid) Outcome
                                    </h4>
                                </div>
                                <p class="text-xs text-stone-600">
                                    Did our bid pass the Technical evaluation? If <strong>Qualified (Win)</strong>, enter the date & time for Financial Bid Opening. If <strong>Disqualified (Lose)</strong>, the tender will terminate immediately ("bt khtm").
                                </p>

                                <form method="POST" action="{{ route('tenders.stage-status', $tender) }}" class="space-y-4">
                                    @csrf

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <!-- Action Choice 1: Win / Qualified -->
                                        <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 border-emerald-300 bg-emerald-50/50 cursor-pointer hover:bg-emerald-50 transition" onclick="toggleFinancialDateInput(true)">
                                            <input type="radio" name="stage_1_action" value="win" required checked class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                                            <div>
                                                <div class="font-bold text-xs text-emerald-950">✓ Qualified / Passed Technical (WIN Stage 1)</div>
                                                <div class="text-[11px] text-emerald-800 mt-0.5">Setup & schedule Stage 2 (Financial Opening Date & Time)</div>
                                            </div>
                                        </label>

                                        <!-- Action Choice 2: Lose / Disqualified -->
                                        <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 border-rose-300 bg-rose-50/50 cursor-pointer hover:bg-rose-50 transition" onclick="toggleFinancialDateInput(false)">
                                            <input type="radio" name="stage_1_action" value="lose" required class="mt-0.5 text-rose-600 focus:ring-rose-500">
                                            <div>
                                                <div class="font-bold text-xs text-rose-950">✗ Disqualified / Failed Technical (LOSE Stage 1)</div>
                                                <div class="text-[11px] text-rose-800 mt-0.5">End process immediately ("bt khtm", no Financial stage)</div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Conditional Input: Financial Bid Opening Date/Time -->
                                    <div id="financial_date_container" class="p-4 rounded-xl bg-white border border-[#e5ded4] space-y-2">
                                        <label class="block text-xs font-black text-stone-800">
                                            Stage 2: Financial Bid Opening Date & Time <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="datetime-local" name="financial_opening_date" id="financial_opening_date_input" 
                                               value="{{ old('financial_opening_date') }}"
                                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] focus:border-orange-500 focus:ring-orange-500">
                                        <p class="text-[11px] text-stone-500">Specify the official date & time when Envelope 2 (Financial) will be opened by the department.</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-stone-700 mb-1">Evaluation Remarks / Notes</label>
                                        <input type="text" name="stage_1_notes" placeholder="e.g. Scored 85/100, shortlisted among top 3 bidders..." 
                                               class="w-full text-xs rounded-xl border-[#e5ded4] focus:border-orange-500 focus:ring-orange-500">
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                                            <span>Submit Stage 1 Decision</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </form>
                            @elseif($tender->current_stage === 'stage_2')
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <h4 class="font-black text-xs uppercase tracking-wider text-stone-800">
                                        Evaluate Stage 2 (Financial Bid) Final Result & Award
                                    </h4>
                                </div>
                                <p class="text-xs text-stone-600">
                                    Envelope 2 (Financial) has been opened. Mark whether our bid was awarded (Lowest Evaluated Bidder) or lost.
                                </p>

                                <form method="POST" action="{{ route('tenders.stage-status', $tender) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 border-emerald-300 bg-emerald-50/50 cursor-pointer hover:bg-emerald-50 transition" onclick="toggleWinDocInputs(true)">
                                            <input type="radio" name="stage_2_action" value="win" required checked class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                                            <div>
                                                <div class="font-bold text-xs text-emerald-950">🏆 Final Award Won (Lowest Evaluated Bid)</div>
                                                <div class="text-[11px] text-emerald-800 mt-0.5">Tender will be marked as WON / Awarded</div>
                                            </div>
                                        </label>

                                        <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 border-rose-300 bg-rose-50/50 cursor-pointer hover:bg-rose-50 transition" onclick="toggleWinDocInputs(false)">
                                            <input type="radio" name="stage_2_action" value="lose" required class="mt-0.5 text-rose-600 focus:ring-rose-500">
                                            <div>
                                                <div class="font-bold text-xs text-rose-950">✗ Financial Bid Lost</div>
                                                <div class="text-[11px] text-rose-800 mt-0.5">Tender will be marked as LOST / Closed</div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Win Letters Checkboxes (Post-Award Documents) -->
                                    <div id="win_docs_panel" class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div class="font-bold text-xs text-amber-950 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span>Post-Award Documents Submission (2 Required Letters):</span>
                                            </div>
                                            <span class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded border border-amber-300">
                                                Optional Now — Can update later
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-stone-600">
                                            Agar aap abhi kisi bhi letter ko submit nahi karte, toh status <strong>Pending</strong> rahega ("2 letters add hony hain"). Jab dono letters submit ho jayenge, system <strong>"Documents Completed"</strong> mark karega.
                                        </p>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                                            <!-- Letter 1: Letter of Intent (LOI) -->
                                            <div class="p-3 bg-white rounded-xl border border-stone-200 space-y-2">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" name="has_letter_of_intent" value="1" class="w-4 h-4 text-orange-600 rounded border-stone-300 focus:ring-orange-500">
                                                    <span class="font-black text-xs text-stone-900">1. Letter of Intent (LOI)</span>
                                                </label>
                                                <input type="text" name="letter_of_intent_ref" placeholder="LOI Ref / Dispatch No..." class="w-full text-xs rounded-lg border-stone-200">
                                                <input type="file" name="letter_of_intent_file" accept=".pdf,.docx,.jpg,.png" class="w-full text-[11px] text-stone-500 file:py-1 file:px-2 file:rounded file:border-0 file:bg-orange-50 file:text-orange-700">
                                            </div>

                                            <!-- Letter 2: Award Letter / Work Order -->
                                            <div class="p-3 bg-white rounded-xl border border-stone-200 space-y-2">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" name="has_award_letter" value="1" class="w-4 h-4 text-orange-600 rounded border-stone-300 focus:ring-orange-500">
                                                    <span class="font-black text-xs text-stone-900">2. Award Letter / Work Order</span>
                                                </label>
                                                <input type="text" name="award_letter_ref" placeholder="Award / WO Ref No..." class="w-full text-xs rounded-lg border-stone-200">
                                                <input type="file" name="award_letter_file" accept=".pdf,.docx,.jpg,.png" class="w-full text-[11px] text-stone-500 file:py-1 file:px-2 file:rounded file:border-0 file:bg-orange-50 file:text-orange-700">
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-stone-700 mb-1">Final Remarks / Award Details</label>
                                        <input type="text" name="stage_2_notes" placeholder="e.g. Contract awarded at PKR 14.8M / Runner up by 2%..." 
                                               class="w-full text-xs rounded-xl border-[#e5ded4] focus:border-orange-500 focus:ring-orange-500">
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="px-5 py-2.5 bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                                            <span>Record Final Award Decision</span>
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endif

                @else
                    <!-- ============================================== -->
                    <!-- 1-STAGE WORKFLOW (SINGLE STAGE ONE ENVELOPE)   -->
                    <!-- ============================================== -->
                    <div class="p-5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h4 class="font-black text-sm text-stone-900">Direct Single Stage Evaluation</h4>
                                <p class="text-xs text-stone-600">
                                    Both technical and financial bids are evaluated simultaneously on:
                                    <strong>{{ $tender->opening_date ? $tender->opening_date->format('d/m/Y \a\t h:i A') : 'Scheduled Opening Date' }}</strong>.
                                </p>
                            </div>

                            @if($tender->tender_outcome === 'in_progress')
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                    Awaiting Bid Opening / Decision
                                </span>
                            @endif
                        </div>

                        <!-- Admin Direct Win / Lose Decision Buttons -->
                        @if(Auth::check() && Auth::user()->isAdmin() && $tender->tender_outcome === 'in_progress')
                            <div class="pt-4 border-t border-[#e5ded4] space-y-3">
                                <div class="text-xs font-black uppercase tracking-wider text-stone-700">
                                    Mark Tender Result (Win or Lose):
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- WIN FORM WITH THE 2 LETTERS CHECKBOXES -->
                                    <form method="POST" action="{{ route('tenders.stage-status', $tender) }}" enctype="multipart/form-data" class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 space-y-3">
                                        @csrf
                                        <input type="hidden" name="outcome" value="won">

                                        <div class="flex items-center justify-between">
                                            <div class="font-black text-xs text-emerald-950 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                <span>🏆 Mark as WON / Awarded</span>
                                            </div>
                                        </div>

                                        <p class="text-[11px] text-stone-600">
                                            Attach or check post-award letters now, or update them later. Agar letters submit nahi honge tou status <strong>Pending</strong> rahega.
                                        </p>

                                        <!-- The 2 Checkboxes -->
                                        <div class="space-y-2">
                                            <!-- Letter 1: LOI -->
                                            <div class="p-2.5 bg-white rounded-xl border border-stone-200 space-y-1.5">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" name="has_letter_of_intent" value="1" class="w-4 h-4 text-emerald-600 rounded border-stone-300 focus:ring-emerald-500">
                                                    <span class="font-bold text-xs text-stone-900">Letter of Intent (LOI)</span>
                                                </label>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <input type="text" name="letter_of_intent_ref" placeholder="LOI Ref No..." class="text-[11px] rounded-lg border-stone-200">
                                                    <input type="file" name="letter_of_intent_file" accept=".pdf,.docx,.jpg,.png" class="text-[10px] text-stone-500">
                                                </div>
                                            </div>

                                            <!-- Letter 2: Award Letter / Work Order -->
                                            <div class="p-2.5 bg-white rounded-xl border border-stone-200 space-y-1.5">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" name="has_award_letter" value="1" class="w-4 h-4 text-emerald-600 rounded border-stone-300 focus:ring-emerald-500">
                                                    <span class="font-bold text-xs text-stone-900">Award Letter / Work Order</span>
                                                </label>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <input type="text" name="award_letter_ref" placeholder="Award / WO Ref..." class="text-[11px] rounded-lg border-stone-200">
                                                    <input type="file" name="award_letter_file" accept=".pdf,.docx,.jpg,.png" class="text-[10px] text-stone-500">
                                                </div>
                                            </div>
                                        </div>

                                        <input type="text" name="notes" placeholder="Award notes / remarks (optional)..." class="w-full text-xs rounded-xl border-stone-200">

                                        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                            Confirm Tender WON
                                        </button>
                                    </form>

                                    <!-- LOSE FORM -->
                                    <form method="POST" action="{{ route('tenders.stage-status', $tender) }}" class="p-4 rounded-2xl bg-rose-50/60 border border-rose-200 space-y-3 flex flex-col justify-between">
                                        @csrf
                                        <input type="hidden" name="outcome" value="lost">

                                        <div class="space-y-2">
                                            <div class="font-black text-xs text-rose-950 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-rose-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                <span>❌ Mark as LOST / Disqualified</span>
                                            </div>
                                            <p class="text-[11px] text-stone-600">
                                                Mark tender as lost if bid was not shortlisted or lost during financial opening. Process ends immediately.
                                            </p>
                                            <input type="text" name="notes" placeholder="Reason / remarks..." class="w-full text-xs rounded-xl border-stone-200">
                                        </div>

                                        <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-xl shadow-xs transition" onclick="return confirm('Confirm marking this tender as LOST?')">
                                            Confirm Tender LOST
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- ------------------------------------------------------------- -->
            <!-- POST-AWARD OFFICIAL DOCUMENTATION CARD (WHEN WON)            -->
            <!-- ------------------------------------------------------------- -->
            @if($tender->isWon())
                <div class="bg-white rounded-3xl border border-[#e5ded4] p-6 sm:p-7 shadow-xs space-y-5" id="post_award_documents_card">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#e5ded4] pb-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $tender->isDocumentsCompleted() ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse' }}"></span>
                                <h3 class="font-black text-lg text-stone-900">
                                    Post-Award Official Documents Checklist
                                </h3>
                            </div>
                            <p class="text-xs text-stone-600">
                                Track receipt of <strong>Letter of Intent (LOI)</strong> and <strong>Award Letter / Work Order</strong>.
                            </p>
                        </div>

                        <!-- Status Badge -->
                        <div>
                            @if($tender->isDocumentsCompleted())
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-900 border border-emerald-300 shadow-xs">
                                    <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    <span>✅ Documents Completed</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black bg-amber-100 text-amber-900 border border-amber-300 shadow-xs">
                                    <svg class="w-4 h-4 text-amber-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    <span>⚠️ Pending ({{ 2 - $tender->completedDocumentsCount() }} of 2 Letters to be added)</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Informational Callout -->
                    @if($tender->isDocumentsCompleted())
                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-emerald-950 text-xs font-medium flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <div>
                                <strong>All required post-award documents are verified & completed.</strong>
                                @if($tender->documents_completed_at)
                                    <span class="text-stone-600 ml-1">Completed on {{ $tender->documents_completed_at->format('M d, Y h:i A') }}.</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 text-amber-950 text-xs font-medium flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <div>
                                <strong>Documents are currently Pending.</strong> 
                                @if(!$tender->has_letter_of_intent && !$tender->has_award_letter)
                                    2 letters add hony hain: <em>Letter of Intent (LOI)</em> aur <em>Award Letter / Work Order</em>.
                                @elseif(!$tender->has_letter_of_intent)
                                    1 letter add hona baqi hai: <em>Letter of Intent (LOI)</em>.
                                @else
                                    1 letter add hona baqi hai: <em>Award Letter / Work Order</em>.
                                @endif
                                Submit karne ke baad status automatically <strong>"Documents Completed"</strong> ho jayega.
                            </div>
                        </div>
                    @endif

                    <!-- Editable Documentation Form -->
                    <form method="POST" action="{{ route('tenders.post-award-documents', $tender) }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- ITEM 1: LETTER OF INTENT (LOI) -->
                            <div class="p-5 rounded-2xl border-2 transition-all {{ $tender->has_letter_of_intent ? 'border-emerald-300 bg-emerald-50/20' : 'border-amber-200 bg-[#fcfaf7]' }} space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2.5 cursor-pointer">
                                        <input type="checkbox" name="has_letter_of_intent" value="1" {{ $tender->has_letter_of_intent ? 'checked' : '' }}
                                               class="w-5 h-5 text-emerald-600 rounded-md border-stone-300 focus:ring-emerald-500">
                                        <div>
                                            <span class="font-black text-sm text-stone-900 block">Letter of Intent (LOI)</span>
                                            <span class="text-[11px] text-stone-500">Preliminary notification of intent to award</span>
                                        </div>
                                    </label>
                                    @if($tender->has_letter_of_intent)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            ✓ Received
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-stone-200 text-stone-600">
                                            Pending
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-2 pt-2 border-t border-stone-200/60">
                                    <div>
                                        <label class="block text-[11px] font-bold text-stone-600 mb-0.5">LOI Reference / Dispatch #</label>
                                        <input type="text" name="letter_of_intent_ref" value="{{ old('letter_of_intent_ref', $tender->letter_of_intent_ref) }}" 
                                               placeholder="e.g. LOI-P&DD-2025/89" 
                                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-stone-600 mb-0.5">Issue Date</label>
                                        <input type="date" name="letter_of_intent_date" value="{{ old('letter_of_intent_date', $tender->letter_of_intent_date ? $tender->letter_of_intent_date->format('Y-m-d') : '') }}" 
                                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-stone-600 mb-0.5">Upload LOI Scan / PDF</label>
                                        <input type="file" name="letter_of_intent_file" accept=".pdf,.docx,.jpg,.png" 
                                               class="w-full text-xs text-stone-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-orange-50 file:text-orange-800">
                                        @if($tender->letter_of_intent_file)
                                            <div class="mt-1 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <a href="{{ asset('storage/' . $tender->letter_of_intent_file) }}" target="_blank" class="text-xs font-bold text-orange-700 hover:underline">
                                                    View / Download Uploaded LOI Document
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- ITEM 2: AWARD LETTER / WORK ORDER -->
                            <div class="p-5 rounded-2xl border-2 transition-all {{ $tender->has_award_letter ? 'border-emerald-300 bg-emerald-50/20' : 'border-amber-200 bg-[#fcfaf7]' }} space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2.5 cursor-pointer">
                                        <input type="checkbox" name="has_award_letter" value="1" {{ $tender->has_award_letter ? 'checked' : '' }}
                                               class="w-5 h-5 text-emerald-600 rounded-md border-stone-300 focus:ring-emerald-500">
                                        <div>
                                            <span class="font-black text-sm text-stone-900 block">Award Letter / Work Order</span>
                                            <span class="text-[11px] text-stone-500">Official contract award letter or formal work order</span>
                                        </div>
                                    </label>
                                    @if($tender->has_award_letter)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            ✓ Received
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-stone-200 text-stone-600">
                                            Pending
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-2 pt-2 border-t border-stone-200/60">
                                    <div>
                                        <label class="block text-[11px] font-bold text-stone-600 mb-0.5">Award / Work Order #</label>
                                        <input type="text" name="award_letter_ref" value="{{ old('award_letter_ref', $tender->award_letter_ref) }}" 
                                               placeholder="e.g. WO-GOVT-2025/1102" 
                                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-stone-600 mb-0.5">Issue Date</label>
                                        <input type="date" name="award_letter_date" value="{{ old('award_letter_date', $tender->award_letter_date ? $tender->award_letter_date->format('Y-m-d') : '') }}" 
                                               class="w-full text-xs font-semibold rounded-xl border-[#e5ded4] bg-white focus:border-orange-500 focus:ring-orange-500">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-stone-600 mb-0.5">Upload Award / Work Order Scan</label>
                                        <input type="file" name="award_letter_file" accept=".pdf,.docx,.jpg,.png" 
                                               class="w-full text-xs text-stone-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-orange-50 file:text-orange-800">
                                        @if($tender->award_letter_file)
                                            <div class="mt-1 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <a href="{{ asset('storage/' . $tender->award_letter_file) }}" target="_blank" class="text-xs font-bold text-orange-700 hover:underline">
                                                    View / Download Uploaded Award Document
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Save Documentation Status Button -->
                        <div class="flex items-center justify-between pt-3 border-t border-[#e5ded4]">
                            <div class="text-[11px] text-stone-500">
                                Both checkboxes must be ticked to transition status to <strong>Documents Completed</strong>.
                            </div>
                            <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Save & Update Documentation Status</span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- ------------------------------------------------------------- -->
            <!-- FINANCIAL SECURITIES & TENDER FORM FEES LEDGER CARD           -->
            <!-- (Traditional Tender Only - Bypassed for EPAD 2.0)            -->
            <!-- ------------------------------------------------------------- -->
            @if($tender->tender_type !== 'epad')
            <div class="bg-white rounded-3xl border border-[#e5ded4] p-6 sm:p-7 shadow-xs space-y-5" id="financial_securities_card">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#e5ded4] pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            <h3 class="font-black text-lg text-stone-900">
                                Tender Document Fee & Bid Security (CDR / Pay Order)
                            </h3>
                        </div>
                        <p class="text-xs text-stone-600 mt-0.5">
                            Bank source ledger, payment verification, and earnest money deposit tracking.
                        </p>
                    </div>

                    @if(Auth::check() && Auth::user()->isAdmin())
                        <button type="button" onclick="document.getElementById('edit_financials_form').classList.toggle('hidden')" 
                                class="px-4 py-2 text-xs font-bold bg-[#fcfaf7] hover:bg-stone-100 text-stone-700 border border-[#e5ded4] rounded-xl shadow-xs transition flex items-center gap-1.5 self-start sm:self-auto">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Update Fees / Bid Security</span>
                        </button>
                    @endif
                </div>

                <!-- Display Overview of Both Financial Elements -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- SECTION 1: TENDER FORM FEE -->
                    <div class="p-5 rounded-2xl bg-[#fcfaf7] border border-[#e5ded4] space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-orange-600"></span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-stone-900">1. Tender Document / Form Fee</h4>
                            </div>
                            @if($tender->is_tender_fee_paid)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    ✓ Fee Paid
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">
                                    ✗ Unpaid / Pending
                                </span>
                            @endif
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center py-1 border-b border-stone-200/60">
                                <span class="text-stone-500 font-medium">Tender Fee Amount:</span>
                                <span class="font-mono font-black text-stone-900 text-sm">
                                    {{ $tender->tender_fee_amount ? 'PKR ' . number_format($tender->tender_fee_amount, 2) : 'Not specified' }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center py-1 border-b border-stone-200/60">
                                <span class="text-stone-500 font-medium">Paid From Bank Account:</span>
                                <span class="font-bold text-stone-900 text-right">
                                    @if($tender->tenderFeeBankAccount)
                                        <span class="text-orange-800">{{ $tender->tenderFeeBankAccount->bank_name }}</span>
                                        <div class="text-[10px] text-stone-500">{{ $tender->tenderFeeBankAccount->account_title }}</div>
                                    @elseif($tender->tender_fee_payment_mode)
                                        <span>{{ ucfirst($tender->tender_fee_payment_mode) }}</span>
                                    @else
                                        <span class="text-stone-400 italic">Not recorded</span>
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between items-center py-1 border-b border-stone-200/60">
                                <span class="text-stone-500 font-medium">Receipt / Slip # & Date:</span>
                                <span class="font-bold text-stone-800">
                                    {{ $tender->tender_fee_ref_no ?: '-' }} 
                                    @if($tender->tender_fee_date)
                                        <span class="text-stone-500 text-[11px]">({{ $tender->tender_fee_date->format('d/m/Y') }})</span>
                                    @endif
                                </span>
                            </div>

                            @if($tender->tender_fee_receipt_file)
                                <div class="pt-1">
                                    <a href="{{ asset('storage/' . $tender->tender_fee_receipt_file) }}" target="_blank" class="text-xs font-bold text-orange-700 hover:underline flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Download Tender Fee Payment Receipt</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- SECTION 2: BID SECURITY / EARNEST MONEY (CDR / PAY ORDER) -->
                    <div class="p-5 rounded-2xl bg-amber-50/40 border border-amber-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-amber-950">2. Bid Security / Earnest Money (CDR)</h4>
                            </div>
                            @if($tender->bid_security_status === 'released_returned')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-teal-100 text-teal-800 border border-teal-300">
                                    ✓ Released / Returned to Bank
                                </span>
                            @elseif($tender->bid_security_amount)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-300">
                                    ● Submitted / Active in Bid
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-stone-200 text-stone-600">
                                    Not Registered
                                </span>
                            @endif
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center py-1 border-b border-amber-200/60">
                                <span class="text-stone-500 font-medium">Security Amount:</span>
                                <span class="font-mono font-black text-blue-950 text-sm">
                                    {{ $tender->bid_security_amount ? 'PKR ' . number_format($tender->bid_security_amount, 2) : 'Not specified' }}
                                    @if($tender->bid_security_percentage)
                                        <span class="text-[10px] text-stone-500 font-normal">({{ $tender->bid_security_percentage }}%)</span>
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between items-center py-1 border-b border-amber-200/60">
                                <span class="text-stone-500 font-medium">Instrument & Issued Bank:</span>
                                <span class="font-bold text-stone-900 text-right">
                                    <span class="uppercase font-black text-blue-900">{{ $tender->bid_security_type ?: 'CDR / Pay Order' }}</span>
                                    @if($tender->bidSecurityBankAccount)
                                        <div class="text-[10px] text-stone-600">{{ $tender->bidSecurityBankAccount->bank_name }}</div>
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between items-center py-1 border-b border-amber-200/60">
                                <span class="text-stone-500 font-medium">CDR / Instrument # & Expiry:</span>
                                <span class="font-bold text-stone-800">
                                    {{ $tender->bid_security_instrument_no ?: '-' }}
                                    @if($tender->bid_security_expiry_date)
                                        <span class="text-stone-500 text-[11px]">(Exp: {{ $tender->bid_security_expiry_date->format('d/m/Y') }})</span>
                                    @endif
                                </span>
                            </div>

                            <div class="pt-1 flex items-center justify-between">
                                @if($tender->bid_security_file)
                                    <a href="{{ asset('storage/' . $tender->bid_security_file) }}" target="_blank" class="text-xs font-bold text-blue-700 hover:underline flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Download CDR / Pay Order Scan</span>
                                    </a>
                                @else
                                    <span></span>
                                @endif

                                <!-- One-click release button if submitted and tender has concluded -->
                                @if($tender->bid_security_amount && $tender->bid_security_status === 'submitted' && Auth::check() && Auth::user()->isAdmin())
                                    <form method="POST" action="{{ route('tenders.release-bid-security', $tender) }}" onsubmit="return confirm('Confirm that Bid Security / CDR has been returned from department and deposited back in bank?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 text-[11px] font-bold bg-teal-50 hover:bg-teal-100 text-teal-800 border border-teal-300 rounded-lg transition">
                                            Mark Returned to Bank ✓
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UPDATE FINANCIALS FORM (COLLAPSIBLE) -->
                @if(Auth::check() && Auth::user()->isAdmin())
                    <form id="edit_financials_form" method="POST" action="{{ route('tenders.financial-securities', $tender) }}" enctype="multipart/form-data" class="hidden p-5 rounded-2xl bg-stone-50 border border-[#e5ded4] space-y-4 pt-4 mt-2">
                        @csrf
                        <div class="font-black text-xs uppercase tracking-wider text-stone-800 border-b border-stone-200 pb-2">
                            Update Tender Fee & Bid Security Financial Parameters
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Tender Fee (PKR)</label>
                                <input type="number" step="0.01" name="tender_fee_amount" value="{{ old('tender_fee_amount', $tender->tender_fee_amount) }}" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Fee Paid From Bank Account</label>
                                <select name="tender_fee_bank_account_id" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                                    <option value="">-- Select Bank Account --</option>
                                    @foreach($bankAccounts as $acc)
                                        <option value="{{ $acc->id }}" {{ old('tender_fee_bank_account_id', $tender->tender_fee_bank_account_id) == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->bank_name }} - {{ $acc->account_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-2 rounded-xl border border-stone-300 w-full">
                                    <input type="checkbox" name="is_tender_fee_paid" value="1" {{ old('is_tender_fee_paid', $tender->is_tender_fee_paid) ? 'checked' : '' }} class="w-4 h-4 text-orange-600 rounded">
                                    <span class="text-xs font-bold text-stone-800">Fee is Paid ✓</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Tender Fee Ref / Slip #</label>
                                <input type="text" name="tender_fee_ref_no" value="{{ old('tender_fee_ref_no', $tender->tender_fee_ref_no) }}" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Fee Payment Date</label>
                                <input type="date" name="tender_fee_date" value="{{ old('tender_fee_date', $tender->tender_fee_date ? $tender->tender_fee_date->format('Y-m-d') : '') }}" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Upload Receipt File</label>
                                <input type="file" name="tender_fee_receipt" class="w-full text-xs text-stone-500">
                            </div>
                        </div>

                        <!-- Bid Security Edit Fields -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 pt-2 border-t border-stone-200">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Bid Security (PKR)</label>
                                <input type="number" step="0.01" name="bid_security_amount" value="{{ old('bid_security_amount', $tender->bid_security_amount) }}" class="w-full text-xs rounded-xl border-stone-300 bg-white font-bold text-blue-900">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Instrument Type</label>
                                <select name="bid_security_type" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                                    <option value="cdr" {{ old('bid_security_type', $tender->bid_security_type) === 'cdr' ? 'selected' : '' }}>Call Deposit Receipt (CDR)</option>
                                    <option value="pay_order" {{ old('bid_security_type', $tender->bid_security_type) === 'pay_order' ? 'selected' : '' }}>Pay Order (P.O)</option>
                                    <option value="bank_guarantee" {{ old('bid_security_type', $tender->bid_security_type) === 'bank_guarantee' ? 'selected' : '' }}>Bank Guarantee</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">CDR Issued From Bank</label>
                                <select name="bid_security_bank_account_id" class="w-full text-xs rounded-xl border-stone-300 bg-white">
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
                                <input type="text" name="bid_security_instrument_no" value="{{ old('bid_security_instrument_no', $tender->bid_security_instrument_no) }}" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Security Status</label>
                                <select name="bid_security_status" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                                    <option value="submitted" {{ old('bid_security_status', $tender->bid_security_status) === 'submitted' ? 'selected' : '' }}>Submitted with Bid (Active)</option>
                                    <option value="released_returned" {{ old('bid_security_status', $tender->bid_security_status) === 'released_returned' ? 'selected' : '' }}>Released / Returned</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Expiry Date</label>
                                <input type="date" name="bid_security_expiry_date" value="{{ old('bid_security_expiry_date', $tender->bid_security_expiry_date ? $tender->bid_security_expiry_date->format('Y-m-d') : '') }}" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Awarded Amount (PKR)</label>
                                <input type="number" step="0.01" name="awarded_amount" value="{{ old('awarded_amount', $tender->awarded_amount) }}" placeholder="Contract award value" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-stone-700 mb-1">Work Order #</label>
                                <input type="text" name="work_order_no" value="{{ old('work_order_no', $tender->work_order_no) }}" placeholder="e.g. WO-88192" class="w-full text-xs rounded-xl border-stone-300 bg-white">
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" onclick="document.getElementById('edit_financials_form').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-stone-600 hover:text-stone-900">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold rounded-xl transition">
                                Save Financials
                            </button>
                        </div>
                    </form>
                @endif
            </div>
            @endif

            <!-- ------------------------------------------------------------- -->
            <!-- TENDER SPECIFICATIONS & SIDEBAR DETAILS                       -->
            <!-- ------------------------------------------------------------- -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left 2 Cols: Scope & Attachments -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-3xl border border-[#e5ded4] shadow-xs space-y-6">
                        <div>
                            <h3 class="text-xs font-black text-stone-500 uppercase tracking-wider mb-2">Scope of Work & Tender Description</h3>
                            <div class="text-sm text-stone-800 leading-relaxed whitespace-pre-line font-medium">
                                {{ $tender->description ?: $tender->title }}
                            </div>
                        </div>

                        @if($tender->requirements)
                            <div class="pt-4 border-t border-[#e5ded4]">
                                <h3 class="text-xs font-black text-stone-500 uppercase tracking-wider mb-2">Technical Eligibility & Requirements</h3>
                                <div class="text-xs text-stone-800 leading-relaxed whitespace-pre-line bg-[#fcfaf7] p-4 rounded-2xl border border-[#e5ded4]">
                                    {{ $tender->requirements }}
                                </div>
                            </div>
                        @endif

                        @if($tender->document_path)
                            <div class="pt-4 border-t border-[#e5ded4] flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-stone-900">Official Tender Dossier / RFP Document</div>
                                        <div class="text-xs text-stone-500">Download attached bidding specifications</div>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $tender->document_path) }}" target="_blank" class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white text-xs font-bold rounded-xl transition">
                                    Download RFP
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right 1 Col: Quick Info Card -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-3xl border border-[#e5ded4] shadow-xs space-y-4">
                        <h4 class="font-black text-stone-900 text-xs uppercase tracking-wider">Tender Summary & Parameters</h4>

                        <div class="space-y-3 divide-y divide-[#e5ded4] text-xs">
                            <div class="pt-2 flex justify-between">
                                <span class="text-stone-500 font-medium">Tender No.</span>
                                <span class="font-mono font-bold text-stone-900">{{ $tender->effective_tender_no }}</span>
                            </div>

                            <div class="pt-2 flex justify-between">
                                <span class="text-stone-500 font-medium">Department</span>
                                <span class="font-bold text-stone-900 text-right">{{ $tender->department?->name ?: 'General' }}</span>
                            </div>

                            @if($tender->company)
                                <div class="pt-2 flex justify-between">
                                    <span class="text-stone-500 font-medium">Our Bidding Firm</span>
                                    <span class="font-bold text-orange-800">{{ $tender->company->company_name ?: $tender->company->name }}</span>
                                </div>
                            @endif

                            <div class="pt-2 flex justify-between">
                                <span class="text-stone-500 font-medium">Bidding Procedure</span>
                                <span class="font-bold text-stone-900 text-right">{{ $tender->procedure_label }}</span>
                            </div>

                            @if($tender->isWon())
                                <div class="pt-2 flex justify-between items-center">
                                    <span class="text-stone-500 font-medium">Post-Award Docs</span>
                                    @if($tender->isDocumentsCompleted())
                                        <span class="text-[11px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                            Completed ✅
                                        </span>
                                    @else
                                        <span class="text-[11px] font-black text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                            Pending ({{ $tender->completedDocumentsCount() }}/2) ⚠️
                                        </span>
                                    @endif
                                </div>
                            @endif

                            @if($tender->isEpad())
                                <div class="pt-2 flex justify-between items-center">
                                    <span class="text-stone-500 font-medium">Platform Mode</span>
                                    <span class="text-[11px] font-black text-rose-900 bg-rose-100 px-2.5 py-0.5 rounded-full border border-rose-200">
                                        EPAD 2.0 (Digital E-Procurement)
                                    </span>
                                </div>

                                <div class="pt-2 flex justify-between">
                                    <span class="text-stone-500 font-medium">Closing Date & Time</span>
                                    <span class="font-extrabold text-rose-950 text-right">
                                        {{ ($tender->closing_date ?: $tender->submission_deadline)?->format('d/m/Y \a\t h:i A') ?: '-' }}
                                    </span>
                                </div>
                            @else
                                @if($tender->upload_date || $tender->collection_start_date)
                                    <div class="pt-2 flex justify-between">
                                        <span class="text-stone-500 font-medium">Tender Upload Date</span>
                                        <span class="font-bold text-stone-900 text-right">
                                            {{ ($tender->upload_date ?: $tender->collection_start_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endif

                                <div class="pt-2 flex justify-between">
                                    <span class="text-stone-500 font-medium">Submission Deadline</span>
                                    <span class="font-bold text-stone-900 text-right">
                                        {{ $tender->submission_deadline ? $tender->submission_deadline->format('d/m/Y \a\t h:i A') : '-' }}
                                    </span>
                                </div>

                                <div class="pt-2 flex justify-between">
                                    <span class="text-stone-500 font-medium">Bid Opening Date</span>
                                    <span class="font-bold text-stone-900 text-right">
                                        @if($tender->isTwoEnvelope())
                                            Tech: {{ ($tender->technical_opening_date ?: $tender->opening_date) ? ($tender->technical_opening_date ?: $tender->opening_date)->format('d/m/Y h:i A') : '-' }}
                                        @else
                                            {{ $tender->opening_date ? $tender->opening_date->format('d/m/Y h:i A') : '-' }}
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @if($tender->budget)
                                <div class="pt-2 flex justify-between">
                                    <span class="text-stone-500 font-medium">Estimated Budget</span>
                                    <span class="font-mono font-bold text-emerald-700">PKR {{ number_format($tender->budget, 2) }}</span>
                                </div>
                            @endif

                            @if($tender->isWon() && ($tender->awarded_amount || $tender->effective_work_order_no))
                                <div class="pt-2 flex justify-between">
                                    <span class="text-stone-500 font-medium">Awarded Work Order</span>
                                    <div class="text-right">
                                        <div class="font-mono font-black text-emerald-800">PKR {{ number_format($tender->awarded_amount ?: $tender->budget, 2) }}</div>
                                        @if($tender->effective_work_order_no)
                                            <div class="text-[10px] text-stone-600 font-bold">W.O: {{ $tender->effective_work_order_no }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="pt-2 flex justify-between">
                                <span class="text-stone-500 font-medium">Tender Document Fee</span>
                                <div class="text-right">
                                    @if($tender->tender_fee_amount)
                                        <span class="font-mono font-bold text-stone-900">PKR {{ number_format($tender->tender_fee_amount) }}</span>
                                        @if($tender->is_tender_fee_paid)
                                            <span class="text-[10px] font-bold text-emerald-700">Paid ✓</span>
                                        @else
                                            <span class="text-[10px] font-bold text-rose-600">Unpaid ✗</span>
                                        @endif
                                    @else
                                        <span class="text-stone-400 font-mono text-[11px]">No Fee</span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-2 flex justify-between">
                                <span class="text-stone-500 font-medium">Bid Security (CDR)</span>
                                <div class="text-right">
                                    @if($tender->bid_security_amount)
                                        <span class="font-mono font-bold text-blue-900">PKR {{ number_format($tender->bid_security_amount) }}</span>
                                        <div class="text-[10px] text-stone-500">{{ strtoupper($tender->bid_security_type ?: 'CDR') }} # {{ $tender->bid_security_instrument_no ?: '-' }}</div>
                                    @else
                                        <span class="text-stone-400 font-mono text-[11px]">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function toggleFinancialDateInput(isWin) {
            const container = document.getElementById('financial_date_container');
            const input = document.getElementById('financial_opening_date_input');
            if (isWin) {
                container.classList.remove('hidden');
                input.required = true;
            } else {
                container.classList.add('hidden');
                input.required = false;
            }
        }

        function toggleWinDocInputs(isWin) {
            const panel = document.getElementById('win_docs_panel');
            if (panel) {
                if (isWin) {
                    panel.classList.remove('hidden');
                } else {
                    panel.classList.add('hidden');
                }
            }
        }
    </script>
</x-app-layout>
