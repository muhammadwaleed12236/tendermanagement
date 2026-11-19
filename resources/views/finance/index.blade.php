<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Cashflow & Treasury
                    </span>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-semibold text-slate-500">Corporate Finance</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <span>{{ __('Finance & Treasury Management') }}</span>
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full border border-slate-200">PKR</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Live bank balances, petty cash in hand, operational expenses and cashbook ledger
                </p>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex items-center gap-2">
                <button type="button" onclick="document.getElementById('transferModal').classList.remove('hidden')" class="px-3 py-2 text-xs font-bold bg-white hover:bg-slate-50 text-indigo-700 border border-indigo-200 rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span>Fund Transfer</span>
                </button>

                <a href="{{ route('banks.index') }}" class="px-3 py-2 text-xs font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>Bank Accounts</span>
                </a>

                <a href="{{ route('expenses.create') }}" class="px-3.5 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Record Expense</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs font-bold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                </div>
            @endif

            <!-- 1. TOP 4 FINANCIAL KPI CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Liquid Funds -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Liquid Treasury</span>
                            <div class="text-2xl font-black text-slate-900 font-mono mt-1 tracking-tight">
                                PKR {{ number_format($totalLiquidFunds, 2) }}
                            </div>
                            <div class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                                <span>●</span> All Banks & Cash Vaults
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Cash in Hand (Petty Cash) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Petty Cash In Hand</span>
                            <div class="text-2xl font-black text-slate-900 font-mono mt-1 tracking-tight">
                                PKR {{ number_format($cashInHandBalance, 2) }}
                            </div>
                            <div class="text-[11px] text-amber-600 font-semibold mt-1">
                                Ready for daily petty expenses
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Commercial Bank Accounts -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Commercial Bank Balance</span>
                            <div class="text-2xl font-black text-slate-900 font-mono mt-1 tracking-tight">
                                PKR {{ number_format($bankBalances, 2) }}
                            </div>
                            <div class="text-[11px] text-indigo-600 font-semibold mt-1">
                                Across {{ $accounts->where('account_type', '!=', 'petty_cash')->count() }} Active Bank Accounts
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Monthly Expenses -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Expenses (This Month)</span>
                            <div class="text-2xl font-black text-rose-600 font-mono mt-1 tracking-tight">
                                PKR {{ number_format($thisMonthExpenses, 2) }}
                            </div>
                            <div class="text-[11px] text-slate-400 font-medium mt-1">
                                Total All Time: PKR {{ number_format($totalExpensesAllTime, 0) }}
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. BANK ACCOUNTS & CASH VAULTS OVERVIEW CARDS -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Active Bank Accounts & Cash Vaults</h3>
                        <p class="text-xs text-slate-500">Live balance breakdown for corporate expenditure and cheque clearance</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('finance.cashbook') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                            Open Cashbook & Ledger &rarr;
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($accounts as $acc)
                        @php $badge = $acc->getAccountTypeBadge(); @endphp
                        <div class="p-4 rounded-xl border {{ $acc->is_default ? 'border-indigo-300 bg-indigo-50/20 shadow-xs' : 'border-slate-200 bg-slate-50/40' }} hover:border-indigo-400 transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-extrabold text-sm text-slate-900">{{ $acc->bank_name }}</h4>
                                            @if($acc->is_default)
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase bg-indigo-100 text-indigo-700">Default</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-600 font-semibold">{{ $acc->account_title }}</div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $badge['class'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </div>

                                <div class="mt-3 py-2 px-3 bg-white rounded-lg border border-slate-100 font-mono text-xs flex justify-between items-center">
                                    <span class="text-slate-400 text-[10px] uppercase font-sans">A/C No:</span>
                                    <span class="font-bold text-slate-800">{{ $acc->account_number }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-end justify-between">
                                <div>
                                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Available Balance</span>
                                    <span class="text-lg font-black font-mono {{ $acc->current_balance < 0 ? 'text-rose-600' : 'text-slate-900' }}">
                                        PKR {{ number_format($acc->current_balance, 2) }}
                                    </span>
                                </div>

                                <a href="{{ route('banks.statement', $acc) }}" class="px-2.5 py-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition border border-transparent hover:border-indigo-200">
                                    Statement &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 p-6 text-center text-xs text-slate-500">
                            No bank accounts added yet. Click "Bank Accounts" to add your first account.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 3. TWO-COLUMN: Category Expense Breakdown & Recent Expenses -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column (1 Col): Monthly Category Breakdown -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Expense Breakdown</h3>
                            <p class="text-[11px] text-slate-500">Category-wise monthly operational spend</p>
                        </div>
                        <a href="{{ route('expenses.categories') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                            Categories
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($categoryBreakdown as $cat)
                            @php
                                $amount = (float) $cat->expenses_sum_amount;
                                $percentage = $thisMonthExpenses > 0 ? round(($amount / $thisMonthExpenses) * 100, 1) : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="font-bold text-slate-800 flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $cat->color }}"></span>
                                        {{ $cat->name }}
                                    </span>
                                    <span class="font-mono font-bold text-slate-900">
                                        PKR {{ number_format($amount, 0) }}
                                        <span class="text-[10px] text-slate-400 font-sans font-normal">({{ $percentage }}%)</span>
                                    </span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full" style="width: {{ $percentage }}%; background-color: {{ $cat->color }}"></div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">
                                No expenses recorded for this month.
                            </div>
                        @endforelse
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('expenses.index') }}" class="w-full py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition text-center block border border-slate-200">
                            View All Expenses Ledger &rarr;
                        </a>
                    </div>
                </div>

                <!-- Right Column (2 Cols): Recent 6 Expenses -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Recent Expense Vouchers</h3>
                            <p class="text-[11px] text-slate-500">Latest recorded office & site operational disbursements</p>
                        </div>
                        <a href="{{ route('expenses.create') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                            + New Voucher
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200 text-[10px]">
                                    <th class="p-2.5">Voucher #</th>
                                    <th class="p-2.5">Date</th>
                                    <th class="p-2.5">Category & Title</th>
                                    <th class="p-2.5">Paid Via / Payee</th>
                                    <th class="p-2.5 text-right">Amount</th>
                                    <th class="p-2.5 text-center">Status</th>
                                    <th class="p-2.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans">
                                @forelse($recentExpenses as $exp)
                                    @php $st = $exp->getStatusBadge(); @endphp
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="p-2.5 font-mono font-bold text-indigo-600">
                                            <a href="{{ route('expenses.show', $exp) }}" class="hover:underline">
                                                {{ $exp->voucher_no }}
                                            </a>
                                        </td>
                                        <td class="p-2.5 font-mono text-slate-600 whitespace-nowrap">
                                            {{ $exp->expense_date->format('d-M-y') }}
                                        </td>
                                        <td class="p-2.5">
                                            <div class="font-bold text-slate-900">{{ $exp->title }}</div>
                                            <div class="text-[10px] font-semibold text-slate-500 flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $exp->category?->color ?: '#6366f1' }}"></span>
                                                {{ $exp->category?->name ?: 'General' }}
                                            </div>
                                        </td>
                                        <td class="p-2.5">
                                            <div class="font-semibold text-slate-800">{{ $exp->bankAccount ? $exp->bankAccount->bank_name : ucfirst($exp->payment_method) }}</div>
                                            @if($exp->payee_name)
                                                <div class="text-[10px] text-slate-500">To: {{ $exp->payee_name }}</div>
                                            @endif
                                        </td>
                                        <td class="p-2.5 text-right font-mono font-black text-rose-600 whitespace-nowrap">
                                            PKR {{ number_format($exp->amount, 2) }}
                                        </td>
                                        <td class="p-2.5 text-center">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border {{ $st['class'] }}">
                                                <span class="w-1 h-1 rounded-full {{ $st['dot'] }}"></span>
                                                {{ $st['label'] }}
                                            </span>
                                        </td>
                                        <td class="p-2.5 text-right">
                                            <a href="{{ route('expenses.show', $exp) }}" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded text-[10.5px] transition">
                                                View Voucher
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-6 text-center text-slate-400">
                                            No expenses recorded yet. Click "+ Record Expense" to create a voucher.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4. RECENT 10 CASHBOOK TRANSACTIONS (INFLOWS & OUTFLOWS) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Recent Cashbook & Bank Transactions</h3>
                        <p class="text-xs text-slate-500">Live journal of debits, credits, transfers and opening balances</p>
                    </div>
                    <a href="{{ route('finance.cashbook') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                        View Complete Cashbook &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200 text-[10px]">
                                <th class="p-2.5">Txn #</th>
                                <th class="p-2.5">Date</th>
                                <th class="p-2.5">Bank / Account</th>
                                <th class="p-2.5">Category & Description</th>
                                <th class="p-2.5">Type</th>
                                <th class="p-2.5 text-right">Amount</th>
                                <th class="p-2.5 text-right">Balance After</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($recentTransactions as $tx)
                                @php $badge = $tx->getTypeBadge(); @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-2.5 font-mono font-bold text-slate-700">
                                        {{ $tx->transaction_no }}
                                    </td>
                                    <td class="p-2.5 font-mono text-slate-600 whitespace-nowrap">
                                        {{ $tx->transaction_date->format('d-M-y') }}
                                    </td>
                                    <td class="p-2.5">
                                        <div class="font-bold text-slate-900">{{ $tx->bankAccount?->bank_name }}</div>
                                        <div class="text-[10px] font-mono text-slate-400">{{ $tx->bankAccount?->account_number }}</div>
                                    </td>
                                    <td class="p-2.5">
                                        <div class="font-semibold text-slate-800">{{ $tx->description }}</div>
                                        @if($tx->reference_no)
                                            <div class="text-[10px] font-mono text-indigo-600 font-bold">Ref: {{ $tx->reference_no }}</div>
                                        @endif
                                    </td>
                                    <td class="p-2.5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border {{ $badge['class'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 text-right font-mono font-black {{ $tx->type === 'credit' ? 'text-emerald-600' : 'text-rose-600' }} whitespace-nowrap">
                                        {{ $badge['sign'] }} PKR {{ number_format($tx->amount, 2) }}
                                    </td>
                                    <td class="p-2.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                        PKR {{ number_format($tx->balance_after, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-slate-400">
                                        No bank transactions recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- FUND TRANSFER MODAL -->
    <div id="transferModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 animate-scale-in">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 flex items-center justify-center font-bold">
                        ⇄
                    </div>
                    <div>
                        <h3 class="font-black text-sm text-slate-900 uppercase">Execute Fund Transfer</h3>
                        <p class="text-[11px] text-slate-500">Transfer between banks or cash drawer</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('transferModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('finance.transfer') }}" class="space-y-4">
                @csrf
                
                <!-- From Account -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Source Account (Transfer From) <span class="text-rose-500">*</span></label>
                    <select name="from_account_id" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select source account...</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">
                                {{ $acc->bank_name }} ({{ $acc->account_number }}) - Available: PKR {{ number_format($acc->current_balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- To Account -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Destination Account (Transfer To) <span class="text-rose-500">*</span></label>
                    <select name="to_account_id" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select destination account...</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">
                                {{ $acc->bank_name }} ({{ $acc->account_number }}) - Current: PKR {{ number_format($acc->current_balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Amount & Date -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amount (PKR) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="1" name="amount" required placeholder="e.g. 50000" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Transfer Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="transfer_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Reference & Notes -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cheque / Slip Reference #</label>
                    <input type="text" name="reference_no" placeholder="e.g. Cheque # 1500234 or Online Ref # 8829" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reason / Purpose</label>
                    <textarea name="description" rows="2" placeholder="e.g. Cash withdrawal from Meezan Bank for petty expenses" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('transferModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow-xs">
                        Confirm Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
