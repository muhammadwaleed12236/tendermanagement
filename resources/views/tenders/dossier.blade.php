<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFP Dossier - {{ $tender->effective_tender_no }} - {{ config('app.name', 'Tender Management') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f7f6f2;
            color: #1c1917;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .print-border {
                border-color: #d6d3d1 !important;
            }
            .page-break {
                page-break-before: always;
            }
            .shadow-none-print {
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4 sm:px-6">

    <!-- Top Action Bar (hidden when printing) -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('tenders.show', $tender) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-stone-300 text-stone-700 hover:text-stone-900 hover:bg-stone-50 font-bold text-xs shadow-sm transition">
            <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Tender</span>
        </a>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-extrabold text-xs shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print / Save as PDF</span>
            </button>
        </div>
    </div>

    <!-- Official Dossier Document Card -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl border border-stone-200 shadow-xl overflow-hidden print-border shadow-none-print">
        
        <!-- Document Header Banner -->
        <div class="bg-gradient-to-r from-stone-900 via-stone-800 to-stone-900 text-white px-8 py-8 border-b-4 border-orange-500">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5 text-xs font-black tracking-widest uppercase text-orange-400 mb-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                        Official Procurement Dossier
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight">
                        REQUEST FOR PROPOSAL (RFP)
                    </h1>
                    <p class="text-xs text-stone-300 mt-1">
                        Bidding Terms, Scope of Work & Eligibility Specifications
                    </p>
                </div>
                <div class="text-left sm:text-right font-mono">
                    <div class="text-[11px] text-stone-400 font-semibold uppercase">Tender Identifier</div>
                    <div class="text-lg font-black text-white">{{ $tender->effective_tender_no }}</div>
                    <div class="text-[11px] text-stone-400 mt-0.5">Ref: {{ $tender->reference_no }}</div>
                </div>
            </div>
        </div>

        <div class="p-8 sm:p-10 space-y-8">

            <!-- Title & Procuring Entity Information -->
            <div class="border-b border-stone-200 pb-6">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-orange-600">Procurement Subject</span>
                <h2 class="text-xl sm:text-2xl font-black text-stone-900 mt-1 leading-snug">
                    {{ $tender->title }}
                </h2>
                
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs pt-4 border-t border-stone-100">
                    <div>
                        <span class="text-stone-400 block font-semibold">Issuing Department:</span>
                        <span class="font-bold text-stone-900">{{ $tender->department?->name ?: 'Public Procurement Division' }}</span>
                        @if($tender->department?->code)
                            <span class="text-stone-500">({{ $tender->department->code }})</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-stone-400 block font-semibold">Bidding Procedure:</span>
                        <span class="font-bold text-stone-900">{{ $tender->procedure_label }}</span>
                    </div>
                    <div>
                        <span class="text-stone-400 block font-semibold">Procurement Category:</span>
                        <span class="font-bold text-stone-900">{{ ucfirst($tender->category ?: 'Goods & Store Items') }}</span>
                    </div>
                    @if($tender->contact_person)
                        <div>
                            <span class="text-stone-400 block font-semibold">Department Contact:</span>
                            <span class="font-bold text-stone-900">{{ $tender->contact_person }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Key Tender Parameters Table -->
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-stone-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Critical Dates & Financial Deadlines</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200">
                        <span class="text-[10px] uppercase font-bold text-stone-500 block">Submission Deadline</span>
                        <div class="font-mono font-bold text-sm text-stone-900 mt-0.5">
                            {{ $tender->submission_deadline ? $tender->submission_deadline->format('d/m/Y') : '-' }}
                        </div>
                        <div class="text-[11px] text-stone-600 font-semibold">
                            up to {{ $tender->submission_deadline ? $tender->submission_deadline->format('h:i A') : '-' }}
                        </div>
                    </div>

                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200">
                        <span class="text-[10px] uppercase font-bold text-stone-500 block">Technical Opening Date</span>
                        <div class="font-mono font-bold text-sm text-stone-900 mt-0.5">
                            {{ ($tender->technical_opening_date ?: $tender->opening_date) ? ($tender->technical_opening_date ?: $tender->opening_date)->format('d/m/Y') : '-' }}
                        </div>
                        <div class="text-[11px] text-stone-600 font-semibold">
                            at {{ ($tender->technical_opening_date ?: $tender->opening_date) ? ($tender->technical_opening_date ?: $tender->opening_date)->format('h:i A') : '-' }}
                        </div>
                    </div>

                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200">
                        <span class="text-[10px] uppercase font-bold text-stone-500 block">Financial Opening Date</span>
                        <div class="font-mono font-bold text-sm text-stone-900 mt-0.5">
                            {{ $tender->financial_opening_date ? $tender->financial_opening_date->format('d/m/Y') : 'Upon Technical Clearance' }}
                        </div>
                        <div class="text-[11px] text-stone-600 font-semibold">
                            {{ $tender->financial_opening_date ? 'at ' . $tender->financial_opening_date->format('h:i A') : 'Post Stage 1' }}
                        </div>
                    </div>

                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200">
                        <span class="text-[10px] uppercase font-bold text-stone-500 block">Estimated Budget</span>
                        <div class="font-mono font-black text-sm text-stone-900 mt-0.5">
                            @if($tender->budget)
                                PKR {{ number_format($tender->budget, 2) }}
                            @else
                                <span class="text-stone-400">Undisclosed</span>
                            @endif
                        </div>
                        <div class="text-[11px] text-stone-600 font-semibold">
                            Allocated Procurement Funds
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scope of Work & Narrative -->
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-stone-900 mb-2">
                    Scope of Work & Specification Details
                </h3>
                <div class="text-xs text-stone-800 leading-relaxed whitespace-pre-line bg-stone-50/70 p-5 rounded-2xl border border-stone-200">
                    {{ $tender->description ?: 'Procurement of goods, technical supplies, and specialized materials as described in the official procurement schedule and tender notice.' }}
                </div>
            </div>

            <!-- Technical Eligibility & Bid Requirements -->
            @if($tender->requirements)
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-stone-900 mb-2">
                        Technical Eligibility & Evaluation Criteria
                    </h3>
                    <div class="text-xs text-stone-800 leading-relaxed whitespace-pre-line bg-stone-50/70 p-5 rounded-2xl border border-stone-200">
                        {{ $tender->requirements }}
                    </div>
                </div>
            @endif

            <!-- Security & Fees Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Bid Security (CDR) -->
                <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200 space-y-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-stone-900 block">Bid Security (CDR / Pay Order)</span>
                    <div class="text-xs text-stone-700 space-y-1">
                        <div class="flex justify-between">
                            <span class="text-stone-500">Security Type:</span>
                            <span class="font-bold text-stone-900">{{ strtoupper($tender->bid_security_type ?: 'CDR / Call Deposit') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-500">Required Security Amount:</span>
                            <span class="font-mono font-bold text-stone-900">
                                @if($tender->bid_security_amount)
                                    PKR {{ number_format($tender->bid_security_amount, 2) }}
                                @else
                                    As specified in notice (2% - 5%)
                                @endif
                            </span>
                        </div>
                        @if($tender->bid_security_instrument_no)
                            <div class="flex justify-between">
                                <span class="text-stone-500">Instrument #:</span>
                                <span class="font-mono font-bold text-stone-800">{{ $tender->bid_security_instrument_no }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tender Document Fee -->
                <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200 space-y-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-stone-900 block">Tender Document Fee</span>
                    <div class="text-xs text-stone-700 space-y-1">
                        <div class="flex justify-between">
                            <span class="text-stone-500">Fee Amount:</span>
                            <span class="font-mono font-bold text-stone-900">
                                @if($tender->tender_fee_amount)
                                    PKR {{ number_format($tender->tender_fee_amount, 2) }}
                                @else
                                    Free / Exempted
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-500">Fee Status:</span>
                            <span class="font-bold {{ $tender->is_tender_fee_paid ? 'text-emerald-700' : 'text-stone-700' }}">
                                {{ $tender->is_tender_fee_paid ? 'Paid & Verified' : 'Standard Submission' }}
                            </span>
                        </div>
                        @if($tender->tenderFeeBankAccount)
                            <div class="flex justify-between">
                                <span class="text-stone-500">Deposit Account:</span>
                                <span class="font-semibold text-stone-800">{{ $tender->tenderFeeBankAccount->bank_name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Official Sign-off and Verification Footer -->
            <div class="pt-8 border-t-2 border-stone-200 grid grid-cols-2 gap-8 text-xs text-stone-600">
                <div>
                    <div class="h-16 flex items-end">
                        <div class="w-48 border-b border-stone-400"></div>
                    </div>
                    <div class="font-bold text-stone-900 mt-1">Authorized Bidding Officer</div>
                    <div class="text-[11px] text-stone-500">{{ $tender->company?->company_name ?: ($tender->company?->name ?: config('app.name')) }}</div>
                </div>
                <div class="text-right">
                    <div class="h-16 flex items-end justify-end">
                        <div class="w-48 border-b border-stone-400"></div>
                    </div>
                    <div class="font-bold text-stone-900 mt-1">Procurement Committee Seal</div>
                    <div class="text-[11px] text-stone-500">{{ $tender->department?->name ?: 'Public Procurement Division' }}</div>
                </div>
            </div>

            <div class="pt-4 text-center text-[10px] text-stone-400 font-mono border-t border-stone-100">
                Official Document generated by ProWave Technologies Tender Management System &bull; Timestamp: {{ now()->format('Y-m-d H:i:s') }}
            </div>

        </div>
    </div>

</body>
</html>