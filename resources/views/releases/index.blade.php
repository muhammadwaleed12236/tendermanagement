<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('departments.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Departments</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500">Financial Overview</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Budget Releases & Department Allocation Sheet') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Department-wise comparative statement of original budget ceilings, authorized quarterly releases, and available released balances.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="window.print()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Sheet
                </button>
                @if(Auth::user()->isAdmin())
                    <button type="button" onclick="openReleaseModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        + Issue Budget Release
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- 5 High-Level Metrics (Title Case / Abcd style) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 overflow-x-auto">
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; flex-wrap: nowrap; min-width: 820px; gap: 8px;">
                    <!-- 1. Original Budget -->
                    <div style="flex: 1; min-width: 150px; padding: 0 14px; border-right: 1px solid #e2e8f0;">
                        <div class="text-[11px] font-bold text-slate-500 whitespace-nowrap">Original Budget</div>
                        <div class="text-base xl:text-lg font-black text-slate-900 mt-0.5 whitespace-nowrap">PKR {{ number_format($totalOriginalBudget, 2) }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5 whitespace-nowrap">Total sanctioned ceiling</div>
                    </div>

                    <!-- 2. Expenditure -->
                    <div style="flex: 1; min-width: 150px; padding: 0 14px; border-right: 1px solid #e2e8f0;">
                        <div class="text-[11px] font-bold text-rose-600 whitespace-nowrap">Expenditure</div>
                        <div class="text-base xl:text-lg font-black text-rose-600 mt-0.5 whitespace-nowrap">PKR {{ number_format($totalExpenditure, 2) }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5 whitespace-nowrap">Disbursed via paid bills</div>
                    </div>

                    <!-- 3. Released Budget -->
                    <div style="flex: 1; min-width: 150px; padding: 0 14px; border-right: 1px solid #e2e8f0;">
                        <div class="text-[11px] font-bold text-indigo-700 whitespace-nowrap">Released Budget</div>
                        <div class="text-base xl:text-lg font-black text-indigo-700 mt-0.5 whitespace-nowrap">PKR {{ number_format($totalReleasedBudget, 2) }}</div>
                        <div class="text-[10px] text-indigo-500 mt-0.5 whitespace-nowrap">Authorized releases</div>
                    </div>

                    <!-- 4. Released Budget Balance -->
                    <div style="flex: 1; min-width: 150px; padding: 0 14px; border-right: 1px solid #e2e8f0;">
                        <div class="text-[11px] font-bold text-emerald-800 whitespace-nowrap">Released Budget Balance</div>
                        <div class="text-base xl:text-lg font-black text-emerald-700 mt-0.5 whitespace-nowrap">PKR {{ number_format($totalReleasedBalance, 2) }}</div>
                        <div class="text-[10px] text-emerald-700 font-semibold mt-0.5 whitespace-nowrap">✓ Available for invoices</div>
                    </div>

                    <!-- 5. Unreleased Balance -->
                    <div style="flex: 1; min-width: 150px; padding: 0 14px;">
                        <div class="text-[11px] font-bold text-amber-800 whitespace-nowrap">Unreleased Balance</div>
                        <div class="text-base xl:text-lg font-black text-amber-700 mt-0.5 whitespace-nowrap">PKR {{ number_format($totalUnreleased, 2) }}</div>
                        <div class="text-[10px] text-amber-600 mt-0.5 whitespace-nowrap">Awaiting tranches</div>
                    </div>
                </div>
            </div>

            <!-- Department Wise Budget Release Statement -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Header with Filters -->
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
                            <span>Department-wise Budget & Release Statement</span>
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700">FY {{ $selectedYear }}</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Comparative statement of original sanctioned budgets, expenditures, releases, and remaining released balance. <span class="text-indigo-600 font-semibold">Click any Original Budget to view/add Detail Object Heads.</span>
                        </p>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('releases.index') }}" style="display: flex; flex-direction: row; align-items: center; flex-wrap: nowrap; gap: 8px;">
                        <div>
                            <select name="year" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-300 py-1.5 font-semibold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 whitespace-nowrap">
                                <option value="2026" {{ $selectedYear == '2026' ? 'selected' : '' }}>Year: 2026</option>
                                <option value="2026-2027" {{ $selectedYear == '2026-2027' ? 'selected' : '' }}>Year: 2026-2027</option>
                                <option value="2025" {{ $selectedYear == '2025' ? 'selected' : '' }}>Year: 2025</option>
                            </select>
                        </div>
                        <div>
                            <select name="department_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-300 py-1.5 font-semibold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 whitespace-nowrap">
                                <option value="">All Departments</option>
                                @foreach($dropdownDepartments as $dept)
                                    <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->code }} - {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($departmentId || $selectedYear != '2026')
                            <a href="{{ route('releases.index') }}" class="text-xs text-rose-600 hover:underline px-2 py-1 font-semibold whitespace-nowrap">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- The Statement Table (Title Case / Abcd Headings) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-700">
                            <tr>
                                <th class="py-3 px-4 text-center w-20">Year</th>
                                <th class="py-3 px-4">Detail Object Desc</th>
                                <th class="py-3 px-4 text-right">Original Budget</th>
                                <th class="py-3 px-4 text-right">Expenditure</th>
                                <th class="py-3 px-4 text-right">Released Budget</th>
                                <th class="py-3 px-4 text-right">Released Budget Balance</th>
                                <th class="py-3 px-4 text-center w-28 no-print">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($summaryRows as $row)
                                <tr class="hover:bg-slate-50/75 transition">
                                    <!-- Year -->
                                    <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-600 text-xs">
                                        {{ $row['year'] }}
                                    </td>

                                    <!-- Detail Object Desc (Department / Cost Center) -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100">
                                                {{ $row['code'] }}
                                            </span>
                                            <a href="{{ route('departments.show', $row['department']) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition">
                                                {{ $row['name'] }}
                                            </a>
                                        </div>
                                        @if($row['department']->cost_center_description)
                                            <div class="text-[11px] text-slate-400 mt-0.5 ml-1">
                                                Cost Center: {{ $row['department']->cost_center_description }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Original Budget (Clickable to view/add Detail Object Heads) -->
                                    <td class="py-3.5 px-4 text-right font-mono">
                                        <button type="button" 
                                                onclick="openBudgetDetailModal({{ $row['department']->id }}, '{{ addslashes($row['name']) }}', '{{ $row['code'] }}', {{ $row['original_budget'] }})"
                                                class="group inline-flex flex-col items-end hover:opacity-90 focus:outline-none text-right transition"
                                                title="Click to view & add Detail Object Heads for this budget">
                                            <span class="font-black text-sm text-indigo-700 underline decoration-indigo-300 decoration-dotted group-hover:decoration-solid group-hover:text-indigo-900">
                                                {{ number_format($row['original_budget']) }}
                                            </span>
                                            <span class="text-[10px] font-sans font-bold text-slate-500 group-hover:text-indigo-700 flex items-center gap-1">
                                                <span>{{ $row['department']->budgetHeads->count() }} Heads</span>
                                                <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </span>
                                        </button>
                                    </td>

                                    <!-- Expenditure -->
                                    <td class="py-3.5 px-4 text-right font-bold font-mono {{ $row['expenditure'] > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                        {{ number_format($row['expenditure']) }}
                                    </td>

                                    <!-- Released Budget -->
                                    <td class="py-3.5 px-4 text-right font-black text-indigo-700 font-mono">
                                        {{ number_format($row['released_budget']) }}
                                    </td>

                                    <!-- Released Budget Balance -->
                                    <td class="py-3.5 px-4 text-right font-black text-emerald-700 font-mono bg-emerald-50/20">
                                        {{ number_format($row['released_balance']) }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-3.5 px-4 text-center no-print">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('departments.show', $row['department']) }}" title="View Department" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded transition">
                                                Details
                                            </a>
                                            <button type="button" 
                                                    onclick="openBudgetDetailModal({{ $row['department']->id }}, '{{ addslashes($row['name']) }}', '{{ $row['code'] }}', {{ $row['original_budget'] }})" 
                                                    title="Manage Budget Heads" 
                                                    class="px-2 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded transition">
                                                Heads
                                            </button>
                                            @if(Auth::user()->isAdmin() && $row['unreleased'] > 0)
                                                <button type="button" onclick="openReleaseModalForDept('{{ $row['department']->id }}', '{{ addslashes($row['name']) }}', '{{ $row['unreleased'] }}')" title="Quick Release Funds" class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded transition">
                                                    + Release
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-500">
                                        No departments found matching the criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <!-- Grand Totals Row (Matching User's Format) -->
                        <tfoot class="bg-slate-100 border-t-2 border-slate-300 font-bold text-xs">
                            <tr>
                                <td colspan="2" class="py-3.5 px-4 text-slate-900 tracking-wide font-bold">
                                    Total:
                                </td>
                                <td class="py-3.5 px-4 text-right text-slate-900 font-mono text-sm">
                                    {{ number_format($totalOriginalBudget) }}
                                </td>
                                <td class="py-3.5 px-4 text-right text-rose-600 font-mono text-sm">
                                    {{ number_format($totalExpenditure) }}
                                </td>
                                <td class="py-3.5 px-4 text-right text-indigo-700 font-mono text-sm">
                                    {{ number_format($totalReleasedBudget) }}
                                </td>
                                <td class="py-3.5 px-4 text-right text-emerald-700 font-mono text-sm bg-emerald-50/50">
                                    {{ number_format($totalReleasedBalance) }}
                                </td>
                                <td class="py-3.5 px-4 no-print"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Detail Object Desc Statement (Exact layout from user sheet) -->
            @if($selectedDepartment || $detailObjectHeads->isNotEmpty())
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100">
                                    {{ $selectedDepartment->code ?? 'DEPT' }}
                                </span>
                                <h3 class="font-extrabold text-lg text-slate-900">
                                    {{ $selectedDepartment->name ?? 'Department' }} - Detail Object Desc Statement
                                </h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                Head-wise original sanctioned budgets, expenditures, releases, and remaining released balance.
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    onclick="openBudgetDetailModal({{ $selectedDepartment->id ?? 2 }}, '{{ addslashes($selectedDepartment->name ?? 'Civil Works') }}', '{{ $selectedDepartment->code ?? 'CWD' }}', {{ $selectedDepartment->budget_allocated ?? 8500000 }})"
                                    class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                + Add / Edit Budget Heads
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-700">
                                <tr>
                                    <th class="py-3 px-4 text-center w-20">Year</th>
                                    <th class="py-3 px-4">Detail Object Desc</th>
                                    <th class="py-3 px-4 text-right">Original Budget</th>
                                    <th class="py-3 px-4 text-right">Expenditure</th>
                                    <th class="py-3 px-4 text-right">Released Budget</th>
                                    <th class="py-3 px-4 text-right">Released Budget Balance</th>
                                    <th class="py-3 px-4 text-center w-20 no-print">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans">
                                @forelse($detailObjectHeads as $head)
                                    <tr class="hover:bg-slate-50/75 transition">
                                        <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-600 text-xs">
                                            {{ $head->year }}
                                        </td>
                                        <td class="py-3.5 px-4 font-mono text-xs font-bold text-slate-800">
                                            {{ $head->head_name }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-mono font-semibold text-slate-800">
                                            {{ number_format($head->original_budget) }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-mono font-semibold {{ $head->expenditure > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                            {{ number_format($head->expenditure) }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-mono font-bold text-indigo-700">
                                            {{ number_format($head->released_budget) }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-mono font-bold text-emerald-700 bg-emerald-50/30">
                                            {{ number_format($head->released_balance) }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center no-print">
                                            <form method="POST" action="{{ route('budget-heads.destroy', $head) }}" onsubmit="return confirm('Remove this budget head?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition" title="Delete head">
                                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-slate-500 text-xs">
                                            No detail object heads recorded yet for this department. Click "+ Add / Edit Budget Heads" to allocate ceiling.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-slate-100 border-t-2 border-slate-300 font-bold text-xs">
                                <tr>
                                    <td colspan="2" class="py-3.5 px-4 text-slate-900 font-bold">
                                        Total:
                                    </td>
                                    <td class="py-3.5 px-4 text-right text-slate-900 font-mono text-sm">
                                        {{ number_format($detailObjectHeads->sum('original_budget')) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right text-rose-600 font-mono text-sm">
                                        {{ number_format($detailObjectHeads->sum('expenditure')) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right text-indigo-700 font-mono text-sm">
                                        {{ number_format($detailObjectHeads->sum('released_budget')) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right text-emerald-700 font-mono text-sm bg-emerald-50/50">
                                        {{ number_format($detailObjectHeads->sum(fn($h) => $h->released_balance)) }}
                                    </td>
                                    <td class="py-3.5 px-4 no-print"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Individual Release Tranches & Transaction History -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Individual Release Tranches & Sanction Orders</h3>
                        <p class="text-xs text-slate-500">Historical transaction log of all fund release notifications issued to departments</p>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <button type="button" onclick="openReleaseModal()" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                            + Release New Funds
                        </button>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold">
                            <tr>
                                <th class="p-3">Release Date</th>
                                <th class="p-3">Department</th>
                                <th class="p-3">Quarter / Tranche</th>
                                <th class="p-3">Sanction Ref #</th>
                                <th class="p-3 text-right">Released Amount</th>
                                <th class="p-3">Remarks / Head</th>
                                <th class="p-3 text-center no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($releases as $rel)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3 font-mono font-bold text-slate-700">
                                        {{ $rel->release_date->format('d M, Y') }}
                                    </td>
                                    <td class="p-3">
                                        <div class="font-bold text-slate-900">{{ $rel->department->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $rel->department->code }}</div>
                                    </td>
                                    <td class="p-3">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $rel->quarter }} ({{ $rel->financial_year }})
                                        </span>
                                    </td>
                                    <td class="p-3 font-mono text-slate-600">
                                        {{ $rel->reference_no ?: '---' }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-indigo-700">
                                        PKR {{ number_format($rel->amount, 2) }}
                                    </td>
                                    <td class="p-3 text-slate-500">
                                        {{ $rel->remarks ?: 'General Authorized Release' }}
                                    </td>
                                    <td class="p-3 text-center no-print">
                                        @if(Auth::user()->isAdmin())
                                            <form method="POST" action="{{ route('releases.destroy', $rel) }}" onsubmit="return confirm('Revoke this release?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold text-[11px]">
                                                    Revoke
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-slate-500">
                                        No release tranches recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($releases->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $releases->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Quick Issue Release Modal (Head-wise Release Allocation) -->
    @if(Auth::user()->isAdmin())
        <div id="release-modal-overlay" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl border border-slate-200 overflow-hidden my-8 flex flex-col max-h-[92vh]">
                <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="font-bold text-base text-white">Issue Budget Release Tranche</h3>
                        <p class="text-xs text-slate-300">Authorize quarterly fund releases per Detail Object Head</p>
                    </div>
                    <button type="button" onclick="closeReleaseModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none p-1 transition">&times;</button>
                </div>

                <form method="POST" action="{{ route('releases.store') }}" class="p-6 space-y-4 overflow-y-auto flex-1" id="global-release-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Target Department <span class="text-rose-500">*</span></label>
                            <select name="department_id" id="modal-dept-select" required onchange="onModalDeptChange()" class="w-full text-xs rounded-lg border-slate-300 font-medium">
                                <option value="">-- Choose Department --</option>
                                @foreach($dropdownDepartments as $dept)
                                    <option value="{{ $dept->id }}" data-unreleased="{{ $dept->unreleasedBudget() }}" data-heads="{{ $dept->budgetHeads->count() }}">
                                        {{ $dept->code }} - {{ $dept->name }} (Unreleased: PKR {{ number_format($dept->unreleasedBudget()) }})
                                    </option>
                                @endforeach
                            </select>
                            <div id="modal-unreleased-hint" class="text-[11px] text-slate-500 mt-1"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Quarter / Tranche <span class="text-rose-500">*</span></label>
                                <select name="quarter" required class="w-full text-xs rounded-lg border-slate-300">
                                    <option value="Q1 (Jul - Sep)">Q1 (Jul - Sep)</option>
                                    <option value="Q2 (Oct - Dec)">Q2 (Oct - Dec)</option>
                                    <option value="Q3 (Jan - Mar)">Q3 (Jan - Mar)</option>
                                    <option value="Q4 (Apr - Jun)">Q4 (Apr - Jun)</option>
                                    <option value="Special Supplementary Tranche">Special Supplementary Tranche</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Financial Year <span class="text-rose-500">*</span></label>
                                <input type="text" name="financial_year" value="2026-2027" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Release Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="release_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Sanction / Notification Reference No.</label>
                            <input type="text" name="reference_no" placeholder="e.g. FD/EXP/2026/049" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                    </div>

                    <!-- Dynamic Head-wise Allocation Container -->
                    <div id="modal-heads-allocation-wrapper" class="space-y-2 pt-2 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-800">
                                Detail Object Head Allocation
                            </label>
                            <div id="modal-heads-quick-btns" class="hidden flex flex-wrap items-center gap-1.5">
                                <div class="inline-flex items-center rounded border border-slate-300 bg-white shadow-2xs overflow-hidden focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500">
                                    <span class="px-1.5 py-0.5 text-[10px] font-bold text-slate-500 bg-slate-50 border-r border-slate-200 select-none">Auto %</span>
                                    <input type="number" 
                                           id="modal-custom-release-pct" 
                                           step="0.01" 
                                           min="0" 
                                           max="100" 
                                           placeholder="25" 
                                           class="w-12 px-1 py-0.5 text-[11px] font-mono font-bold text-slate-800 text-right border-0 focus:ring-0 focus:outline-none"
                                           oninput="applyCustomModalPercentage(this.value)">
                                    <span class="px-1 py-0.5 text-[10px] font-bold text-slate-500 select-none">%</span>
                                </div>
                                <button type="button" onclick="setCustomModalPercentage(25)" class="px-2 py-0.5 text-[10px] font-semibold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded border border-indigo-200 transition">
                                    Auto 25% (Q1)
                                </button>
                                <button type="button" onclick="setCustomModalPercentage(38.75)" class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded border border-emerald-200 transition">
                                    Auto 38.75%
                                </button>
                                <button type="button" onclick="clearModalHeads()" class="px-2 py-0.5 text-[10px] font-semibold text-rose-500 hover:text-rose-700 transition">
                                    Clear
                                </button>
                            </div>
                        </div>

                        <!-- Table or Loading or Lump Sum Input -->
                        <div id="modal-heads-container" class="border border-slate-200 rounded-xl overflow-hidden shadow-sm max-h-60 overflow-y-auto">
                            <div class="p-4 text-center text-xs text-slate-400">
                                Select a target department above to load its Detail Object Heads.
                            </div>
                        </div>

                        <!-- Fallback Lump Sum Input (if no heads exist) -->
                        <div id="modal-lump-sum-row" class="hidden">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Release Amount (PKR) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="1" id="modal-amount-input" name="amount" placeholder="e.g. 5000000" class="w-full text-xs rounded-lg border-slate-300 font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Remarks / Purpose</label>
                        <input type="text" name="remarks" placeholder="Notes on this release..." class="w-full text-xs rounded-lg border-slate-300">
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-200 shrink-0">
                        <div class="text-xs font-mono font-bold text-emerald-700" id="modal-release-live-total">
                            Total Release: PKR 0.00
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="closeReleaseModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                                Cancel
                            </button>
                            <button type="submit" id="btn-modal-confirm-release" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                Confirm Fund Release
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Budget Detail & Object Heads Allocation Modal (Title Case / Abcd style) -->
    <div id="budget-detail-modal-overlay" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 flex flex-col max-h-[92vh]">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-slate-900 to-indigo-950 text-white flex items-center justify-between shrink-0">
                <div>
                    <div class="flex items-center gap-2">
                        <span id="modal-head-dept-code" class="text-xs font-mono font-bold bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded border border-indigo-500/30">DEPT</span>
                        <h3 class="text-base font-bold text-white tracking-wide" id="modal-head-dept-name">Department Name</h3>
                    </div>
                    <p class="text-xs text-slate-300 mt-0.5">Detail Object Desc & Head-wise Budget Allocation (Original & Released Breakdown)</p>
                </div>
                <button type="button" onclick="closeBudgetDetailModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none p-1 transition">&times;</button>
            </div>

            <!-- Department Budget Summary Pill Bar -->
            <div class="px-6 py-3 bg-slate-50 border-b border-slate-200 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs shrink-0">
                <div class="p-2.5 bg-white rounded-lg border border-slate-200">
                    <span class="text-[11px] font-bold text-slate-500 block">Sanctioned Ceiling</span>
                    <span class="text-sm font-black text-slate-800 font-mono" id="modal-dept-allocated-badge">PKR 0.00</span>
                </div>
                <div class="p-2.5 bg-white rounded-lg border border-slate-200">
                    <span class="text-[11px] font-bold text-indigo-600 block">Allocated in Heads</span>
                    <span class="text-sm font-black text-indigo-700 font-mono" id="modal-dept-heads-sum">PKR 0.00</span>
                </div>
                <div class="p-2.5 bg-white rounded-lg border border-slate-200">
                    <span class="text-[11px] font-bold text-amber-600 block">Unallocated Ceiling</span>
                    <span class="text-sm font-black text-amber-700 font-mono" id="modal-dept-unallocated">PKR 0.00</span>
                </div>
                <div class="p-2.5 bg-white rounded-lg border border-slate-200">
                    <span class="text-[11px] font-bold text-emerald-600 block">Total Released in Heads</span>
                    <span class="text-sm font-black text-emerald-700 font-mono" id="modal-dept-heads-released">PKR 0.00</span>
                </div>
            </div>

            <!-- Modal Body: Scrollable Table of Heads -->
            <div class="p-6 overflow-y-auto space-y-5 flex-1">
                
                <!-- Table (Title Case / Abcd style) -->
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold text-[11px]">
                            <tr>
                                <th class="py-2.5 px-3 text-center w-16">Year</th>
                                <th class="py-2.5 px-3">Detail Object Desc</th>
                                <th class="py-2.5 px-3 text-right">Original Budget</th>
                                <th class="py-2.5 px-3 text-right">Expenditure</th>
                                <th class="py-2.5 px-3 text-right">Released Budget</th>
                                <th class="py-2.5 px-3 text-right">Released Budget Balance</th>
                                <th class="py-2.5 px-3 text-center w-14">Action</th>
                            </tr>
                        </thead>
                        <tbody id="modal-heads-tbody" class="divide-y divide-slate-100 font-sans">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                        <tfoot class="bg-slate-50 border-t-2 border-slate-300 font-bold text-xs font-mono">
                            <tr>
                                <td colspan="2" class="py-3 px-3 text-slate-900 font-sans font-bold">Total:</td>
                                <td class="py-3 px-3 text-right text-slate-900" id="modal-foot-original">0</td>
                                <td class="py-3 px-3 text-right text-rose-600" id="modal-foot-expenditure">0</td>
                                <td class="py-3 px-3 text-right text-indigo-700" id="modal-foot-released">0</td>
                                <td class="py-3 px-3 text-right text-emerald-700 bg-emerald-50/50" id="modal-foot-balance">0</td>
                                <td class="py-3 px-3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Add New Head Form Card -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <h4 class="font-bold text-xs text-slate-800 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-[10px]">+</span>
                            Add / Allocate Budget to Object Head
                        </h4>
                        <span class="text-[10px] text-slate-500">Assign ceiling & released funds</span>
                    </div>

                    <form id="add-head-form" onsubmit="submitAddHeadForm(event)" class="space-y-3">
                        <input type="hidden" id="form-dept-id" value="">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                            <!-- Standard Code Picker -->
                            <div class="sm:col-span-5">
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Select Detail Object Desc / Head</label>
                                <select id="form-head-preset" onchange="onHeadPresetChange(this.value)" class="w-full text-xs rounded-lg border-slate-300 py-1.5 font-medium text-slate-700">
                                    <option value="">-- Choose Standard Object Head --</option>
                                    <option value="A03302-WATER">A03302-WATER</option>
                                    <option value="A03806-TRANSPORTATION OF GOODS">A03806-TRANSPORTATION OF GOODS</option>
                                    <option value="A03901-STATIONERY">A03901-STATIONERY</option>
                                    <option value="A03902-PRINTING AND PUBLICATION">A03902-PRINTING AND PUBLICATION</option>
                                    <option value="A03905-NEWSPAPERS, PERIODICALS AND BOOKS">A03905-NEWSPAPERS, PERIODICALS AND BOOKS</option>
                                    <option value="A03906-UNIFORMS AND PROTECTIVE CLOTHING">A03906-UNIFORMS AND PROTECTIVE CLOTHING</option>
                                    <option value="A03919-PAYMENTS TO OTHER FOR SERVICES RENDER">A03919-PAYMENTS TO OTHER FOR SERVICES RENDER</option>
                                    <option value="A03942-COST OF OTHER STORES">A03942-COST OF OTHER STORES</option>
                                    <option value="A03955-OTHER STORE - TEAR GAS/ COMPUTER STATS">A03955-OTHER STORE - TEAR GAS/ COMPUTER STATS</option>
                                    <option value="A03970-OTHERS">A03970-OTHERS</option>
                                    <option value="A12101-MACHINERY AND EQUIPMENT">A12101-MACHINERY AND EQUIPMENT</option>
                                    <option value="A12201-FURNITURE AND FIXTURES">A12201-FURNITURE AND FIXTURES</option>
                                    <option value="A12701-HARDWARE">A12701-HARDWARE</option>
                                    <option value="A12702-SOFTWARE">A12702-SOFTWARE</option>
                                    <option value="A12703-I.T. EQUIPMENT">A12703-I.T. EQUIPMENT</option>
                                    <option value="CUSTOM">-- Custom Object Head --</option>
                                </select>
                                <input type="text" id="form-head-custom" placeholder="e.g. A03999-SPECIAL PROCUREMENT" class="hidden w-full text-xs rounded-lg border-slate-300 mt-1 py-1.5 font-mono">
                            </div>

                            <!-- Original Budget Input -->
                            <div class="sm:col-span-3">
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Total Original Budget (PKR) <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" min="0" id="form-head-original" placeholder="0" required class="w-full text-xs rounded-lg border-slate-300 py-1.5 font-mono font-bold text-right text-slate-800">
                            </div>

                            <!-- Released Budget Input -->
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Released Budget (PKR)</label>
                                <input type="number" step="0.01" min="0" id="form-head-released" placeholder="0" class="w-full text-xs rounded-lg border-slate-300 py-1.5 font-mono font-bold text-right text-indigo-700">
                            </div>

                            <!-- Submit Button -->
                            <div class="sm:col-span-2">
                                <button type="submit" id="btn-save-head" class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                    + Add Head
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-slate-100 border-t border-slate-200 flex items-center justify-between shrink-0">
                <span class="text-[11px] text-slate-500">Changes are saved directly to this department's ledger.</span>
                <button type="button" onclick="closeBudgetDetailModal()" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-lg transition">
                    Done / Close
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openReleaseModal() {
            document.getElementById('release-modal-overlay').classList.remove('hidden');
            onModalDeptChange();
        }

        function closeReleaseModal() {
            document.getElementById('release-modal-overlay').classList.add('hidden');
        }

        function openReleaseModalForDept(deptId, deptName, maxAmount) {
            var select = document.getElementById('modal-dept-select');
            select.value = deptId;
            openReleaseModal();
        }

        function onModalDeptChange() {
            var select = document.getElementById('modal-dept-select');
            var opt = select.options[select.selectedIndex];
            var deptId = select.value;
            var unreleased = opt ? opt.getAttribute('data-unreleased') : null;
            var hint = document.getElementById('modal-unreleased-hint');
            var container = document.getElementById('modal-heads-container');
            var quickBtns = document.getElementById('modal-heads-quick-btns');
            var lumpSumRow = document.getElementById('modal-lump-sum-row');
            var lumpSumInput = document.getElementById('modal-amount-input');

            if (unreleased && parseFloat(unreleased) > 0) {
                hint.textContent = 'Maximum available to release: PKR ' + Number(unreleased).toLocaleString();
            } else if (unreleased) {
                hint.textContent = 'Ceiling exhausted: 100% of budget already released.';
            } else {
                hint.textContent = '';
            }

            if (!deptId) {
                container.innerHTML = `<div class="p-4 text-center text-xs text-slate-400">Select a target department above to load its Detail Object Heads.</div>`;
                quickBtns.classList.add('hidden');
                lumpSumRow.classList.add('hidden');
                recalculateModalReleaseTotal();
                return;
            }

            container.innerHTML = `<div class="p-4 text-center text-xs text-slate-400">Loading Detail Object Heads for department...</div>`;

            fetch(`/departments/${deptId}/budget-heads?year=2026`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                const heads = data.heads || [];
                if (heads.length > 0) {
                    quickBtns.classList.remove('hidden');
                    lumpSumRow.classList.add('hidden');
                    if (lumpSumInput) lumpSumInput.removeAttribute('required');

                    let html = `
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold sticky top-0 z-10">
                                <tr>
                                    <th class="py-2 px-3">Detail Object Desc</th>
                                    <th class="py-2 px-3 text-right">Original Budget</th>
                                    <th class="py-2 px-3 text-right">Expenditure</th>
                                    <th class="py-2 px-3 text-right">Released Budget</th>
                                    <th class="py-2 px-3 text-right">Released Budget Balance</th>
                                    <th class="py-2 px-3 text-right w-36">Tranche Release (PKR)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans">
                    `;
                    heads.forEach(h => {
                        const exp = parseFloat(h.expenditure) || 0;
                        const rel = parseFloat(h.released_budget) || 0;
                        const bal = Math.max(0, rel - exp);
                        html += `
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-1.5 px-3 font-mono font-bold text-slate-800">${escapeHtml(h.head_name)}</td>
                                <td class="py-1.5 px-3 text-right font-mono text-slate-600">${Number(h.original_budget).toLocaleString()}</td>
                                <td class="py-1.5 px-3 text-right font-mono ${exp > 0 ? 'text-rose-600' : 'text-slate-400'}">${exp.toLocaleString()}</td>
                                <td class="py-1.5 px-3 text-right font-mono text-indigo-700 font-semibold">${rel.toLocaleString()}</td>
                                <td class="py-1.5 px-3 text-right font-mono text-emerald-700 font-bold bg-emerald-50/40">${bal.toLocaleString()}</td>
                                <td class="py-1.5 px-3 text-right">
                                    <input type="number" step="0.01" min="0" 
                                           name="heads[${h.id}][amount]" 
                                           data-orig="${h.original_budget}" 
                                           placeholder="0" 
                                           class="modal-head-release-input w-full text-xs rounded-lg border-slate-300 py-1 font-mono font-bold text-right text-emerald-700 focus:ring-emerald-500 focus:border-emerald-500"
                                           oninput="recalculateModalReleaseTotal()">
                                </td>
                            </tr>
                        `;
                    });
                    html += `</tbody></table>`;
                    container.innerHTML = html;
                } else {
                    quickBtns.classList.add('hidden');
                    lumpSumRow.classList.remove('hidden');
                    if (lumpSumInput) {
                        lumpSumInput.setAttribute('required', 'required');
                        if (unreleased) lumpSumInput.max = unreleased;
                    }
                    container.innerHTML = `<div class="p-4 text-center text-xs text-slate-500">No detail object heads configured yet for this department. Enter total release amount below.</div>`;
                }
                recalculateModalReleaseTotal();
            })
            .catch(err => {
                container.innerHTML = `<div class="p-4 text-center text-xs text-rose-500">Error loading heads: ${err.message}</div>`;
                lumpSumRow.classList.remove('hidden');
            });
        }

        function recalculateModalReleaseTotal() {
            const inputs = document.querySelectorAll('.modal-head-release-input');
            let total = 0;
            if (inputs.length > 0) {
                inputs.forEach(inp => {
                    const val = parseFloat(inp.value) || 0;
                    total += val;
                });
            } else {
                const lump = document.getElementById('modal-amount-input');
                total = lump ? (parseFloat(lump.value) || 0) : 0;
            }

            const formatted = 'PKR ' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const liveTotal = document.getElementById('modal-release-live-total');
            const confirmBtn = document.getElementById('btn-modal-confirm-release');
            if (liveTotal) liveTotal.textContent = 'Total Release: ' + formatted;
            if (confirmBtn && total > 0) {
                confirmBtn.textContent = 'Confirm Release (' + formatted + ')';
            } else if (confirmBtn) {
                confirmBtn.textContent = 'Confirm Fund Release';
            }
        }

        function applyCustomModalPercentage(val) {
            const pct = parseFloat(val);
            if (isNaN(pct) || pct < 0) {
                clearModalHeads(false);
                return;
            }
            const ratio = pct / 100;
            const inputs = document.querySelectorAll('.modal-head-release-input');
            inputs.forEach(inp => {
                const orig = parseFloat(inp.getAttribute('data-orig')) || 0;
                if (orig > 0) {
                    inp.value = Math.round(orig * ratio);
                }
            });
            recalculateModalReleaseTotal();
        }

        function setCustomModalPercentage(pct) {
            const input = document.getElementById('modal-custom-release-pct');
            if (input) input.value = pct;
            applyCustomModalPercentage(pct);
        }

        function autoFillModalHeads(percentage) {
            setCustomModalPercentage(percentage * 100);
        }

        function clearModalHeads(clearPctInput = true) {
            if (clearPctInput) {
                const input = document.getElementById('modal-custom-release-pct');
                if (input) input.value = '';
            }
            const inputs = document.querySelectorAll('.modal-head-release-input');
            inputs.forEach(inp => {
                inp.value = '';
            });
            recalculateModalReleaseTotal();
        }

        let currentModalDeptId = null;

        function openBudgetDetailModal(deptId, deptName, deptCode, originalBudget) {
            currentModalDeptId = deptId;
            document.getElementById('form-dept-id').value = deptId;
            document.getElementById('modal-head-dept-name').textContent = deptName;
            document.getElementById('modal-head-dept-code').textContent = deptCode;
            document.getElementById('modal-dept-allocated-badge').textContent = 'PKR ' + Number(originalBudget).toLocaleString();

            document.getElementById('budget-detail-modal-overlay').classList.remove('hidden');
            loadBudgetHeads(deptId);
        }

        function closeBudgetDetailModal() {
            document.getElementById('budget-detail-modal-overlay').classList.add('hidden');
        }

        function onHeadPresetChange(val) {
            const customInput = document.getElementById('form-head-custom');
            if (val === 'CUSTOM') {
                customInput.classList.remove('hidden');
                customInput.focus();
            } else {
                customInput.classList.add('hidden');
            }
        }

        function loadBudgetHeads(deptId) {
            const tbody = document.getElementById('modal-heads-tbody');
            tbody.innerHTML = `<tr><td colspan="7" class="py-6 text-center text-slate-400">Loading budget heads...</td></tr>`;

            fetch(`/departments/${deptId}/budget-heads?year=2026`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                renderModalHeads(data);
            })
            .catch(err => {
                tbody.innerHTML = `<tr><td colspan="7" class="py-4 text-center text-rose-500">Error loading heads.</td></tr>`;
            });
        }

        let loadedHeads = [];
        let editingHeadId = null;

        function renderModalHeads(data) {
            const tbody = document.getElementById('modal-heads-tbody');
            const heads = data.heads || [];
            loadedHeads = heads;
            const totals = data.totals || {};

            document.getElementById('modal-dept-heads-sum').textContent = 'PKR ' + Number(totals.original_budget || 0).toLocaleString();
            document.getElementById('modal-dept-unallocated').textContent = 'PKR ' + Number(totals.unallocated || 0).toLocaleString();
            document.getElementById('modal-dept-heads-released').textContent = 'PKR ' + Number(totals.released_budget || 0).toLocaleString();

            document.getElementById('modal-foot-original').textContent = Number(totals.original_budget || 0).toLocaleString();
            document.getElementById('modal-foot-expenditure').textContent = Number(totals.expenditure || 0).toLocaleString();
            document.getElementById('modal-foot-released').textContent = Number(totals.released_budget || 0).toLocaleString();
            document.getElementById('modal-foot-balance').textContent = Number(totals.released_balance || 0).toLocaleString();

            if (heads.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="py-6 text-center text-slate-400">No budget heads added yet. Select a head below to allocate budget.</td></tr>`;
                return;
            }

            let html = '';
            heads.forEach(h => {
                const balance = Math.max(0, (parseFloat(h.released_budget) || 0) - (parseFloat(h.expenditure) || 0));
                html += `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-2.5 px-3 text-center font-mono text-slate-600 font-bold">${h.year || '2026'}</td>
                        <td class="py-2.5 px-3 font-mono font-bold text-slate-800">${escapeHtml(h.head_name)}</td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">${Number(h.original_budget).toLocaleString()}</td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold ${h.expenditure > 0 ? 'text-rose-600' : 'text-slate-400'}">${Number(h.expenditure).toLocaleString()}</td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold text-indigo-700">${Number(h.released_budget).toLocaleString()}</td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-700 bg-emerald-50/40">${Number(balance).toLocaleString()}</td>
                        <td class="py-2.5 px-3 text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <button type="button" onclick="editBudgetHead(${h.id})" class="text-slate-400 hover:text-indigo-600 transition" title="Edit head amounts">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button type="button" onclick="deleteBudgetHead(${h.id})" class="text-slate-400 hover:text-rose-600 transition" title="Delete head">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function editBudgetHead(id) {
            const h = loadedHeads.find(item => item.id == id);
            if (!h) return;

            editingHeadId = id;
            const presetSelect = document.getElementById('form-head-preset');
            const customInput = document.getElementById('form-head-custom');

            let found = false;
            for (let i = 0; i < presetSelect.options.length; i++) {
                if (presetSelect.options[i].value === h.head_name) {
                    presetSelect.selectedIndex = i;
                    found = true;
                    break;
                }
            }

            if (!found) {
                presetSelect.value = 'CUSTOM';
                customInput.value = h.head_name;
                customInput.classList.remove('hidden');
            } else {
                customInput.classList.add('hidden');
            }

            document.getElementById('form-head-original').value = h.original_budget;
            document.getElementById('form-head-released').value = h.released_budget;

            const btn = document.getElementById('btn-save-head');
            btn.textContent = 'Update Head';
            btn.className = 'w-full py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition';

            let cancelBtn = document.getElementById('btn-cancel-edit-head');
            if (!cancelBtn) {
                cancelBtn = document.createElement('button');
                cancelBtn.type = 'button';
                cancelBtn.id = 'btn-cancel-edit-head';
                cancelBtn.textContent = 'Cancel Edit';
                cancelBtn.className = 'w-full py-1 px-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-[11px] font-semibold rounded-lg transition mt-1';
                cancelBtn.onclick = resetHeadForm;
                btn.parentNode.appendChild(cancelBtn);
            }
            cancelBtn.style.display = 'block';
        }

        function resetHeadForm() {
            editingHeadId = null;
            document.getElementById('form-head-preset').value = '';
            const custom = document.getElementById('form-head-custom');
            custom.value = '';
            custom.classList.add('hidden');
            document.getElementById('form-head-original').value = '';
            document.getElementById('form-head-released').value = '';

            const btn = document.getElementById('btn-save-head');
            btn.textContent = '+ Add Head';
            btn.className = 'w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition';

            const cancelBtn = document.getElementById('btn-cancel-edit-head');
            if (cancelBtn) cancelBtn.style.display = 'none';
        }

        function submitAddHeadForm(e) {
            e.preventDefault();
            const deptId = currentModalDeptId;
            if (!deptId) return;

            const presetSelect = document.getElementById('form-head-preset');
            const customInput = document.getElementById('form-head-custom');
            let headName = presetSelect.value === 'CUSTOM' ? customInput.value.trim() : presetSelect.value;

            if (!headName) {
                alert('Please select or specify a Detail Object Head.');
                return;
            }

            const originalBudget = document.getElementById('form-head-original').value;
            const releasedBudget = document.getElementById('form-head-released').value || 0;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            const btn = document.getElementById('btn-save-head');
            btn.disabled = true;
            btn.textContent = editingHeadId ? 'Updating...' : 'Saving...';

            const url = editingHeadId ? `/budget-heads/${editingHeadId}` : `/departments/${deptId}/budget-heads`;
            const method = editingHeadId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    head_name: headName,
                    original_budget: originalBudget,
                    released_budget: releasedBudget,
                    year: '2026'
                })
            })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                resetHeadForm();
                loadBudgetHeads(deptId);
            })
            .catch(err => {
                btn.disabled = false;
                btn.textContent = editingHeadId ? 'Update Head' : '+ Add Head';
                alert('Error saving budget head: ' + err.message);
            });
        }

        function deleteBudgetHead(headId) {
            if (!confirm('Are you sure you want to delete this Detail Object Head?')) return;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            fetch(`/budget-heads/${headId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(res => res.json())
            .then(res => {
                if (currentModalDeptId) {
                    loadBudgetHeads(currentModalDeptId);
                }
            })
            .catch(err => {
                alert('Error deleting head: ' + err.message);
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            nav, header {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
        }
    </style>
</x-app-layout>
