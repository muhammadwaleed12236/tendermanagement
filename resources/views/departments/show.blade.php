<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('departments.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Back to Departments</a>
                    <span class="text-slate-300">•</span>
                    <span class="font-mono text-xs font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded">Cost Center: {{ $department->code }}</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ $department->name }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">Contact Person: <span class="font-semibold text-slate-700">{{ $department->head_of_department ?: 'Unassigned' }}</span></p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" 
                        onclick="openBudgetDetailModal({{ $department->id }}, '{{ addslashes($department->name) }}', '{{ $department->code }}', {{ $totalAllocated }})"
                        class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Manage Detail Budget Heads
                </button>
                <a href="{{ route('releases.index', ['department_id' => $department->id]) }}" class="px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition inline-flex items-center gap-1.5 border border-indigo-200 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Releases Sheet
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('departments.edit', $department) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                        Edit Department
                    </a>
                @endif
                <a href="{{ route('bills.create', ['department_id' => $department->id]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    + Generate Bill for Dept
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <!-- 4 Financial Budget Metric Cards (Title Case / Abcd style) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- 1. Original Budget (Clickable to open Head details) -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative group cursor-pointer hover:border-indigo-300 transition"
                     onclick="openBudgetDetailModal({{ $department->id }}, '{{ addslashes($department->name) }}', '{{ $department->code }}', {{ $totalAllocated }})">
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] font-bold text-slate-500">Annual Budget</div>
                        <span class="text-[10px] font-bold text-indigo-600 group-hover:underline">Click for Heads &rarr;</span>
                    </div>
                    <div class="text-xl font-black text-slate-900 mt-1">PKR {{ number_format($totalAllocated, 2) }}</div>
                    <div class="text-[11px] text-slate-500 mt-1 flex items-center justify-between">
                        <span>Total approved ceiling</span>
                        <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100">{{ $budgetHeads->count() }} Heads</span>
                    </div>
                </div>

                <!-- 2. Expenditure -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-[11px] font-bold text-rose-600">Expenditure</div>
                    <div class="text-xl font-black text-rose-600 mt-1">PKR {{ number_format($totalSpent, 2) }}</div>
                    <div class="text-[11px] text-slate-500 mt-1">Across {{ $department->bills->where('status', 'paid')->count() }} cleared bills</div>
                </div>

                <!-- 3. Released Budget -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-[11px] font-bold text-indigo-700">Released Budget</div>
                    <div class="text-xl font-black text-indigo-600 mt-1">PKR {{ number_format($totalReleased, 2) }}</div>
                    <div class="text-[11px] text-slate-500 mt-1">{{ $department->releases->count() }} Release Tranches (Unreleased: PKR {{ number_format($unreleased, 2) }})</div>
                </div>

                <!-- 4. Released Budget Balance -->
                <div class="bg-emerald-50/60 p-5 rounded-xl border border-emerald-200 shadow-sm">
                    <div class="text-[11px] font-bold text-emerald-800">Released Budget Balance</div>
                    <div class="text-2xl font-black text-emerald-700 mt-1">PKR {{ number_format($availableReleased, 2) }}</div>
                    <div class="text-[11px] text-emerald-800 font-semibold mt-1">✓ Available for new product invoices</div>
                </div>
            </div>

            <!-- Detail Object Desc Statement (Exact layout from user sheet) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100">
                                {{ $department->code }}
                            </span>
                            <h3 class="font-extrabold text-lg text-slate-900">
                                Detail Object Desc Statement
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Breakdown of original sanctioned budget, expenditure, releases, and remaining balance across all object heads.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                onclick="openBudgetDetailModal({{ $department->id }}, '{{ addslashes($department->name) }}', '{{ $department->code }}', {{ $totalAllocated }})"
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
                            @forelse($budgetHeads as $head)
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
                                    {{ number_format($budgetHeads->sum('original_budget')) }}
                                </td>
                                <td class="py-3.5 px-4 text-right text-rose-600 font-mono text-sm">
                                    {{ number_format($budgetHeads->sum('expenditure')) }}
                                </td>
                                <td class="py-3.5 px-4 text-right text-indigo-700 font-mono text-sm">
                                    {{ number_format($budgetHeads->sum('released_budget')) }}
                                </td>
                                <td class="py-3.5 px-4 text-right text-emerald-700 font-mono text-sm bg-emerald-50/50">
                                    {{ number_format($budgetHeads->sum(fn($h) => $h->released_balance)) }}
                                </td>
                                <td class="py-3.5 px-4 no-print"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Quarterly Budget Releases Management (Release Section) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Quarterly Budget Releases (Tranches)</h3>
                        <p class="text-xs text-slate-500">Government fund releases per quarter to allow departmental spending</p>
                    </div>

                    @if(Auth::user()->isAdmin() && $unreleased > 0)
                        <button type="button" onclick="document.getElementById('release-modal').classList.toggle('hidden')" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                            + Release New Quarter Funds
                        </button>
                    @endif
                </div>

                <!-- Quick Inline Release Form (Head-wise Release Allocation) -->
                @if(Auth::user()->isAdmin())
                    <div id="release-modal" class="{{ $errors->any() ? '' : 'hidden' }} p-6 bg-slate-50 border-b border-slate-200">
                        <form method="POST" action="{{ route('departments.releases.store', $department) }}" class="space-y-5" id="dept-release-form">
                            @csrf
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-3">
                                <div>
                                    <h4 class="font-bold text-sm text-slate-800">Fund Release Authorization Form</h4>
                                    <p class="text-xs text-slate-500">Allocate quarterly authorized release per Detail Object Head. (Max Unreleased Ceiling: PKR {{ number_format($unreleased, 2) }})</p>
                                </div>
                                <div class="px-3 py-1 bg-indigo-50 border border-indigo-200 rounded-lg text-xs font-mono font-bold text-indigo-700">
                                    Total Release: <span id="form-total-release-badge">PKR 0.00</span>
                                </div>
                            </div>
                            
                            <!-- Basic Notification Info -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
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
                                    <input type="text" name="financial_year" value="2026-2027" required class="w-full text-xs rounded-lg border-slate-300 font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Release Date <span class="text-rose-500">*</span></label>
                                    <input type="date" name="release_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-lg border-slate-300">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sanction / Notification Ref #</label>
                                    <input type="text" name="reference_no" placeholder="e.g. FD/EXP/2026/049" class="w-full text-xs rounded-lg border-slate-300">
                                </div>
                            </div>

                            @if($budgetHeads->isNotEmpty())
                                <!-- Head-wise Release Allocation Table -->
                                <div class="space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <label class="block text-xs font-bold text-slate-800">
                                            Allocate Release per Detail Object Head ({{ $budgetHeads->count() }} Heads)
                                        </label>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <!-- Custom Percentage Input Box -->
                                            <div class="inline-flex items-center rounded-lg border border-slate-300 bg-white shadow-2xs overflow-hidden focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500">
                                                <span class="px-2 py-1 text-[11px] font-bold text-slate-500 bg-slate-50 border-r border-slate-200 select-none">Auto %</span>
                                                <input type="number" 
                                                       id="custom-release-pct" 
                                                       step="0.01" 
                                                       min="0" 
                                                       max="100" 
                                                       placeholder="e.g. 25" 
                                                       class="w-16 px-2 py-1 text-xs font-mono font-bold text-slate-800 text-right border-0 focus:ring-0 focus:outline-none"
                                                       oninput="applyCustomPercentage(this.value)">
                                                <span class="px-1.5 py-1 text-xs font-bold text-slate-500 select-none">%</span>
                                            </div>

                                            <button type="button" onclick="setCustomPercentage(25)" class="px-2.5 py-1 text-[11px] font-semibold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded border border-indigo-200 transition">
                                                Auto 25% (Q1)
                                            </button>
                                            <button type="button" onclick="setCustomPercentage(38.75)" class="px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded border border-emerald-200 transition">
                                                Auto 38.75%
                                            </button>
                                            <button type="button" onclick="clearHeadReleases()" class="px-2 py-1 text-[11px] font-semibold text-rose-500 hover:text-rose-700 transition">
                                                Clear
                                            </button>
                                        </div>
                                    </div>

                                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm max-h-80 overflow-y-auto">
                                        <table class="w-full text-left border-collapse text-xs">
                                            <thead class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold sticky top-0 z-10">
                                                <tr>
                                                    <th class="py-2.5 px-3">Detail Object Desc</th>
                                                    <th class="py-2.5 px-3 text-right">Original Budget</th>
                                                    <th class="py-2.5 px-3 text-right">Expenditure</th>
                                                    <th class="py-2.5 px-3 text-right">Released Budget</th>
                                                    <th class="py-2.5 px-3 text-right">Released Budget Balance</th>
                                                    <th class="py-2.5 px-3 text-right w-44">Tranche Release (PKR)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 font-sans">
                                                @foreach($budgetHeads as $h)
                                                    @php
                                                        $headUnreleased = max(0, (float) $h->original_budget - (float) $h->released_budget);
                                                        $headBalance = max(0, (float) $h->released_budget - (float) $h->expenditure);
                                                    @endphp
                                                    <tr class="hover:bg-slate-50 transition">
                                                        <td class="py-2 px-3 font-mono font-bold text-slate-800">
                                                            {{ $h->head_name }}
                                                        </td>
                                                        <td class="py-2 px-3 text-right font-mono text-slate-600 font-semibold" data-orig="{{ (float) $h->original_budget }}">
                                                            {{ number_format($h->original_budget) }}
                                                        </td>
                                                        <td class="py-2 px-3 text-right font-mono font-semibold {{ $h->expenditure > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                                            {{ number_format($h->expenditure) }}
                                                        </td>
                                                        <td class="py-2 px-3 text-right font-mono text-indigo-700 font-semibold">
                                                            {{ number_format($h->released_budget) }}
                                                        </td>
                                                        <td class="py-2 px-3 text-right font-mono text-emerald-700 font-bold bg-emerald-50/40">
                                                            {{ number_format($headBalance) }}
                                                        </td>
                                                        <td class="py-2 px-3 text-right">
                                                            <div class="relative">
                                                                <input type="number" 
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       max="{{ $headUnreleased > 0 ? $headUnreleased : '' }}"
                                                                       name="heads[{{ $h->id }}][amount]" 
                                                                       data-orig="{{ (float) $h->original_budget }}"
                                                                       placeholder="0" 
                                                                       class="head-release-input w-full text-xs rounded-lg border-slate-300 py-1.5 font-mono font-bold text-right text-emerald-700 focus:ring-emerald-500 focus:border-emerald-500"
                                                                       oninput="recalculateTotalRelease()">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-slate-100 border-t-2 border-slate-300 font-bold text-xs sticky bottom-0 z-10">
                                                <tr>
                                                    <td colspan="5" class="py-2.5 px-3 text-slate-800 font-bold">Total Tranche Release:</td>
                                                    <td class="py-2.5 px-3 text-right font-mono font-black text-emerald-700 text-sm" id="table-total-release">
                                                        PKR 0.00
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Release Amount (PKR) <span class="text-rose-500">*</span></label>
                                    <input type="number" step="0.01" min="1" max="{{ $unreleased }}" name="amount" id="lump-sum-amount" placeholder="e.g. 5000000" required class="w-full text-xs rounded-lg border-slate-300 font-bold">
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Remarks / Purpose</label>
                                <input type="text" name="remarks" placeholder="e.g. 1st Quarter Budget Release sanction order..." class="w-full text-xs rounded-lg border-slate-300">
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-200">
                                <button type="button" onclick="document.getElementById('release-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                                    Cancel
                                </button>
                                <button type="submit" id="btn-submit-release" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                    Authorize Head-wise Release
                                </button>
                            </div>
                        </form>
                    </div>

                    <script>
                        function recalculateTotalRelease() {
                            const inputs = document.querySelectorAll('.head-release-input');
                            let total = 0;
                            inputs.forEach(inp => {
                                const val = parseFloat(inp.value) || 0;
                                total += val;
                            });

                            const formatted = 'PKR ' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            const badge = document.getElementById('form-total-release-badge');
                            const tableTot = document.getElementById('table-total-release');
                            if (badge) badge.textContent = formatted;
                            if (tableTot) tableTot.textContent = formatted;

                            const btn = document.getElementById('btn-submit-release');
                            if (btn && total > 0) {
                                btn.textContent = 'Authorize Release (' + formatted + ')';
                            } else if (btn) {
                                btn.textContent = 'Authorize Head-wise Release';
                            }
                        }

                        function applyCustomPercentage(val) {
                            const pct = parseFloat(val);
                            if (isNaN(pct) || pct < 0) {
                                clearHeadReleases(false);
                                return;
                            }
                            const ratio = pct / 100;
                            const inputs = document.querySelectorAll('.head-release-input');
                            inputs.forEach(inp => {
                                const orig = parseFloat(inp.getAttribute('data-orig')) || 0;
                                if (orig > 0) {
                                    inp.value = Math.round(orig * ratio);
                                }
                            });
                            recalculateTotalRelease();
                        }

                        function setCustomPercentage(pct) {
                            const input = document.getElementById('custom-release-pct');
                            if (input) input.value = pct;
                            applyCustomPercentage(pct);
                        }

                        function autoFillHeadRelease(percentage) {
                            setCustomPercentage(percentage * 100);
                        }

                        function clearHeadReleases(clearPctInput = true) {
                            if (clearPctInput) {
                                const input = document.getElementById('custom-release-pct');
                                if (input) input.value = '';
                            }
                            const inputs = document.querySelectorAll('.head-release-input');
                            inputs.forEach(inp => {
                                inp.value = '';
                            });
                            recalculateTotalRelease();
                        }
                    </script>
                @endif

                <!-- Releases Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold">
                            <tr>
                                <th class="p-3">Release Date</th>
                                <th class="p-3">Quarter / Tranche</th>
                                <th class="p-3">Sanction Ref #</th>
                                <th class="p-3 text-right">Released Amount</th>
                                <th class="p-3">Remarks</th>
                                <th class="p-3">Authorized By</th>
                                <th class="p-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($department->releases as $release)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3 font-mono font-bold text-slate-700">
                                        {{ $release->release_date->format('d M, Y') }}
                                    </td>
                                    <td class="p-3">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $release->quarter }} ({{ $release->financial_year }})
                                        </span>
                                    </td>
                                    <td class="p-3 font-mono text-slate-600">
                                        {{ $release->reference_no ?: '---' }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-indigo-700">
                                        PKR {{ number_format($release->amount, 2) }}
                                    </td>
                                    <td class="p-3 text-slate-500">
                                        {{ $release->remarks ?: 'General Authorized Release' }}
                                    </td>
                                    <td class="p-3 text-slate-600">
                                        {{ $release->creator ? $release->creator->name : 'System Admin' }}
                                    </td>
                                    <td class="p-3 text-center">
                                        @if(Auth::user()->isAdmin())
                                            <form method="POST" action="{{ route('releases.destroy', $release) }}" onsubmit="return confirm('Revoke this release?')">
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
                                        No releases authorized yet. Click "+ Release New Quarter Funds" to authorize budget spending.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($department->releases->count() > 0)
                            <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
                                <tr>
                                    <td colspan="3" class="p-3 text-slate-700">Total Authorized Releases:</td>
                                    <td class="p-3 text-right font-mono font-black text-indigo-700">PKR {{ number_format($totalReleased, 2) }}</td>
                                    <td colspan="3" class="p-3 text-slate-500 text-[11px]">(Remaining Unreleased: PKR {{ number_format($unreleased, 2) }})</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Invoices & Bills Incurred under this Department -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Departmental Invoices & Running Bills ({{ $department->bills->count() }})</h3>
                        <p class="text-xs text-slate-400">Bills processed within the released budget envelope</p>
                    </div>
                    <a href="{{ route('bills.create', ['department_id' => $department->id]) }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                        + Generate Bill
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($department->bills as $bill)
                        <div class="p-5 hover:bg-slate-50 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">{{ $bill->bill_number }}</span>
                                    <span class="text-xs font-bold text-slate-800">{{ $bill->vendor ? ($bill->vendor->company_name ?: $bill->vendor->name) : 'Department Direct' }}</span>
                                </div>
                                <div class="text-xs text-slate-600 mt-1 font-medium">
                                    {{ $bill->milestone_description }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    Date: {{ $bill->invoice_date->format('M d, Y') }} • Products Billed: <strong class="text-slate-600">{{ $bill->items->count() }} Items</strong>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="text-base font-extrabold text-slate-900">PKR {{ number_format($bill->net_amount, 2) }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold mt-0.5
                                        @if($bill->status === 'paid') bg-emerald-50 text-emerald-700
                                        @elseif($bill->status === 'approved') bg-indigo-50 text-indigo-700
                                        @elseif($bill->status === 'verified_by_dept') bg-blue-50 text-blue-700
                                        @elseif($bill->status === 'rejected') bg-rose-50 text-rose-700
                                        @else bg-amber-50 text-amber-700 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $bill->status)) }}
                                    </span>
                                </div>
                                <a href="{{ route('bills.show', $bill) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-sm text-slate-500">No bills generated for this department yet.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Budget Detail & Object Heads Allocation Modal (Title Case / Abcd style) -->
    <div id="budget-detail-modal-overlay" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 flex flex-col max-h-[92vh]">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-slate-900 to-indigo-950 text-white flex items-center justify-between shrink-0">
                <div>
                    <div class="flex items-center gap-2">
                        <span id="modal-head-dept-code" class="text-xs font-mono font-bold bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded border border-indigo-500/30">{{ $department->code }}</span>
                        <h3 class="text-base font-bold text-white tracking-wide" id="modal-head-dept-name">{{ $department->name }}</h3>
                    </div>
                    <p class="text-xs text-slate-300 mt-0.5">Detail Object Desc & Head-wise Budget Allocation (Original & Released Breakdown)</p>
                </div>
                <button type="button" onclick="closeBudgetDetailModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none p-1 transition">&times;</button>
            </div>

            <!-- Department Budget Summary Pill Bar -->
            <div class="px-6 py-3 bg-slate-50 border-b border-slate-200 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs shrink-0">
                <div class="p-2.5 bg-white rounded-lg border border-slate-200">
                    <span class="text-[11px] font-bold text-slate-500 block">Sanctioned Ceiling</span>
                    <span class="text-sm font-black text-slate-800 font-mono" id="modal-dept-allocated-badge">PKR {{ number_format($totalAllocated) }}</span>
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
                        <input type="hidden" id="form-dept-id" value="{{ $department->id }}">
                        
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

    <!-- Script for Dynamic Budget Heads Modal -->
    <script>
        let currentModalDeptId = {{ $department->id }};

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
            window.location.reload();
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
</x-app-layout>
