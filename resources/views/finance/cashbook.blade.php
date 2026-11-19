<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('finance.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Finance Overview</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Treasury Journal</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    {{ __('Cashbook & Bank General Ledger') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Unified audit ledger of all bank deposits, cheque clearances, disbursements and fund transfers
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="px-3.5 py-2 text-xs font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Cashbook</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Top Totals -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Credits / Inflows</span>
                    <div class="text-2xl font-black text-emerald-600 font-mono mt-1">
                        + PKR {{ number_format($totalCredit, 2) }}
                    </div>
                    <div class="text-[11px] text-emerald-600 font-semibold mt-1">Deposits, cheque clearances, incoming funds</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Debits / Outflows</span>
                    <div class="text-2xl font-black text-rose-600 font-mono mt-1">
                        - PKR {{ number_format($totalDebit, 2) }}
                    </div>
                    <div class="text-[11px] text-rose-600 font-semibold mt-1">Expenses, vendor payouts & withdrawals</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Net Movement (Filtered Period)</span>
                    @php $net = $totalCredit - $totalDebit; @endphp
                    <div class="text-2xl font-black font-mono {{ $net >= 0 ? 'text-indigo-700' : 'text-amber-600' }} mt-1">
                        PKR {{ number_format($net, 2) }}
                    </div>
                    <div class="text-[11px] text-slate-500 mt-1">Total transactions: {{ $transactions->total() }}</div>
                </div>
            </div>

            <!-- 2. Filter Form -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs no-print">
                <form method="GET" action="{{ route('finance.cashbook') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 text-xs">
                    <!-- Bank Account -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Account</label>
                        <select name="account_id" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                            <option value="">All Accounts & Cash</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Type</label>
                        <select name="type" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                            <option value="">All Debits & Credits</option>
                            <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credits (Inflows) Only</option>
                            <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debits (Outflows) Only</option>
                        </select>
                    </div>

                    <!-- From Date -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                    </div>

                    <!-- To Date -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end gap-1.5">
                        <button type="submit" class="w-full py-2 bg-slate-900 text-white font-bold rounded-lg hover:bg-black transition text-xs">
                            Filter
                        </button>
                        @if(request('account_id') || request('type') || request('date_from') || request('date_to'))
                            <a href="{{ route('finance.cashbook') }}" class="px-3 py-2 text-rose-600 font-bold hover:underline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- 3. Cashbook Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden print:border-none print:shadow-none">
                
                <div class="p-6 border-b border-slate-200 hidden print:block">
                    <h2 class="text-lg font-black uppercase text-slate-900">{{ Auth::user()->company_name ?: 'ProWave Technologies' }}</h2>
                    <h3 class="text-sm font-bold text-slate-700">Cashbook & Bank General Ledger</h3>
                    <p class="text-xs font-mono text-slate-500">Period: {{ request('date_from') ?: 'All' }} to {{ request('date_to') ?: 'Present' }} | Printed on {{ date('d-M-Y h:i A') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200 text-[10px]">
                                <th class="p-3 text-center">#</th>
                                <th class="p-3">Date</th>
                                <th class="p-3 font-mono">Txn #</th>
                                <th class="p-3">Account / Cash Vault</th>
                                <th class="p-3">Category & Particulars</th>
                                <th class="p-3 font-mono">Ref #</th>
                                <th class="p-3 text-right">Debit (Outflow)</th>
                                <th class="p-3 text-right">Credit (Inflow)</th>
                                <th class="p-3 text-right font-bold">Balance After</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($transactions as $index => $tx)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-3 text-center font-bold text-slate-400">
                                        {{ $transactions->firstItem() + $index }}
                                    </td>
                                    <td class="p-3 font-mono text-slate-700 whitespace-nowrap">
                                        {{ $tx->transaction_date->format('d-M-Y') }}
                                    </td>
                                    <td class="p-3 font-mono text-slate-600 whitespace-nowrap">
                                        {{ $tx->transaction_no }}
                                    </td>
                                    <td class="p-3 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $tx->bankAccount?->bank_name }}</div>
                                        <div class="text-[10px] font-mono text-slate-400">{{ $tx->bankAccount?->account_number }}</div>
                                    </td>
                                    <td class="p-3 font-medium text-slate-900">
                                        <div>{{ $tx->description }}</div>
                                        <div class="text-[10px] text-slate-400 capitalize font-mono">{{ str_replace('_', ' ', $tx->category) }}</div>
                                    </td>
                                    <td class="p-3 font-mono text-indigo-600 font-bold whitespace-nowrap">
                                        {{ $tx->reference_no ?: '-' }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-bold text-rose-600 whitespace-nowrap">
                                        @if($tx->type === 'debit')
                                            PKR {{ number_format($tx->amount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-3 text-right font-mono font-bold text-emerald-600 whitespace-nowrap">
                                        @if($tx->type === 'credit')
                                            PKR {{ number_format($tx->amount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-slate-900 whitespace-nowrap">
                                        PKR {{ number_format($tx->balance_after, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-8 text-center text-slate-400">
                                        No transactions recorded in the cashbook yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="p-4 border-t border-slate-100 no-print">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
