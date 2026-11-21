<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-400">Reports & Analytics Hub</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </span>
                    <span>Reports & Analytics</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Centralized hub for all departmental master data, procurement analytics, expenditure, and audits.
                </p>
            </div>

            <!-- Quick Action -->
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.master-data') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Open Master Data Management</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Featured Primary Report: Master Data Management -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-500 via-orange-500 to-amber-600 p-6 sm:p-8 text-white shadow-xl shadow-amber-500/20">
                <div class="relative z-10 max-w-3xl space-y-3">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-bold uppercase tracking-wider text-amber-100 border border-white/30">
                        ⭐ Primary Analytical Report
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Master Data Management Report
                    </h3>
                    <p class="text-xs sm:text-sm text-amber-100 leading-relaxed font-medium">
                        Complete item-level procurement records featuring: <strong>Sr #, Years, Quarter, Head of Account, Head Description, Cost Center, Department Name, Item Description, Qty, Rate, and Vendor Name</strong>. Includes real-time multi-filter queries and one-click Excel/CSV export.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <a href="{{ route('reports.master-data') }}" class="px-5 py-2.5 rounded-xl bg-white text-slate-900 font-extrabold text-xs shadow-md hover:bg-amber-50 transition inline-flex items-center gap-2">
                            <span>Open Master Data Report</span>
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <a href="{{ route('reports.master-data.export', ['format' => 'xls']) }}" class="px-4 py-2.5 rounded-xl bg-amber-900/40 hover:bg-amber-900/60 text-white font-bold text-xs border border-white/20 transition inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Download Full Excel (.xls)</span>
                        </a>
                    </div>
                </div>

                <!-- Background Graphic -->
                <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/10 pointer-events-none blur-xl"></div>
            </div>

            <!-- Reports Directory Grid -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <span>All Reports & Analytics Modules</span>
                    </h3>
                    <span class="text-xs text-slate-400 font-medium">Automatic real-time sync with database</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                    <!-- 1. Master Data Management -->
                    <div class="bg-white p-5 rounded-2xl border-2 border-amber-300 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold shadow-2xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-amber-100 text-amber-800 uppercase">Core</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-amber-600 transition">Master Data Management</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    Sr #, Years, Quarter, Head of Account, Head Description, Cost Center, Dept, Item, Qty, Rate & Vendor.
                                </p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-amber-700 font-mono">{{ number_format($totalItemsCount) }} Records</span>
                            <a href="{{ route('reports.master-data') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group-hover:translate-x-0.5 transition">
                                <span>Open Report</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- 2. Billing Daily Report -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold shadow-2xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">Daily</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition">Daily Billing Register</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    Cheque-wise daily dispatch log, net amounts, GST, income tax deductions, and AGPR vendor details.
                                </p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-600 font-mono">{{ number_format($totalBillsCount) }} Bills</span>
                            <a href="{{ route('bills.daily-report') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group-hover:translate-x-0.5 transition">
                                <span>Open Report</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- 3. Tenders & Work Order Reports -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold shadow-2xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">Tenders</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-emerald-600 transition">Tenders & Work Order Audits</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    Bidding outcomes, comparative statements, CDR security deposits, contractor performance, and contracts won.
                                </p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-600">Procurement</span>
                            <a href="{{ route('tenders.reports') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group-hover:translate-x-0.5 transition">
                                <span>Open Report</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- 4. Budget & Fund Releases Compliance -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold shadow-2xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-100 text-indigo-800 uppercase">Finance</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-indigo-600 transition">Budget Releases & Ceilings</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    Quarterly tranche authorizations, original allocation vs. released budget, and remaining headroom per department.
                                </p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-600 font-mono">{{ $totalDepartments }} Departments</span>
                            <a href="{{ route('releases.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group-hover:translate-x-0.5 transition">
                                <span>Open Report</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- 5. Cheques & AGPR Register -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold shadow-2xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-800 uppercase">Payments</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-rose-600 transition">Cheque Disbursement Register</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    Disbursed and cleared cheques, AGPR delivery dates, bank reconciliation, and vendor payments.
                                </p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-600">Disbursements</span>
                            <a href="{{ route('cheques.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group-hover:translate-x-0.5 transition">
                                <span>Open Report</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- 6. Activity & Audit Trail -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold shadow-2xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">Security</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-slate-700 transition">Activity & Audit Trail Logs</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    Complete audit trail tracking who created, updated, or deleted records with IP addresses and timestamps.
                                </p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-600">Security</span>
                            <a href="{{ route('activity-logs.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group-hover:translate-x-0.5 transition">
                                <span>Open Report</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
