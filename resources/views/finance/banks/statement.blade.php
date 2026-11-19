<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('banks.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Bank Accounts</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Account Statement</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <span>{{ $bank->bank_name }}</span>
                    <span class="text-xs font-mono font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full border border-indigo-200">{{ $bank->account_number }}</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Account Title: <span class="font-bold text-slate-700">{{ $bank->account_title }}</span>
                    @if($bank->iban) • IBAN: <span class="font-mono">{{ $bank->iban }}</span> @endif
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="px-3.5 py-2 text-xs font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Statement</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Balance Summary Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Initial Opening Balance</span>
                    <div class="text-xl font-bold font-mono text-slate-700 mt-1">
                        PKR {{ number_format($bank->opening_balance, 2) }}
                    </div>
                </div>

                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Inflow (Deposits)</span>
                    <div class="text-xl font-black font-mono text-emerald-600 mt-1">
                        + PKR {{ number_format($totalCredit, 2) }}
                    </div>
                </div>

                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Outflow (Disbursements)</span>
                    <div class="text-xl font-black font-mono text-rose-600 mt-1">
                        - PKR {{ number_format($totalDebit, 2) }}
                    </div>
                </div>

                <div class="border-l border-slate-100 pl-4">
                    <span class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">Current Net Balance</span>
                    <div class="text-2xl font-black font-mono {{ $bank->current_balance < 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">
                        PKR {{ number_format($bank->current_balance, 2) }}
                    </div>
                </div>
            </div>

            <!-- 2. Statement Filters -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs no-print">
                <form method="GET" action="{{ route('banks.statement', $bank) }}" class="flex flex-wrap items-end gap-3 text-xs">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Transaction Type</label>
                        <select name="type" class="text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                            <option value="">All Transactions</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credits / Inflows Only</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debits / Outflows Only</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white font-bold rounded-lg hover:bg-black transition">
                        Filter
                    </button>

                    @if(request('type') || request('date_from') || request('date_to'))
                        <a href="{{ route('banks.statement', $bank) }}" class="px-3 py-2 text-rose-600 font-bold hover:underline">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- 3. Transactions Statement Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden print:border-none print:shadow-none">
                
                <!-- Print Header -->
                <div class="p-6 border-b border-slate-200 hidden print:block">
                    <h2 class="text-lg font-black uppercase text-slate-900">{{ Auth::user()->company_name ?: 'ProWave Technologies' }}</h2>
                    <h3 class="text-sm font-bold text-slate-700">Bank Account Ledger Statement: {{ $bank->bank_name }}</h3>
                    <p class="text-xs font-mono text-slate-500">A/C: {{ $bank->account_number }} | Title: {{ $bank->account_title }} | Generated on {{ date('d-M-Y h:i A') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200 text-[10px]">
                                <th class="p-3 text-center">#</th>
                                <th class="p-3">Date</th>
                                <th class="p-3 font-mono">Txn #</th>
                                <th class="p-3">Description & Particulars</th>
                                <th class="p-3">Reference #</th>
                                <th class="p-3 text-right">Debit (Outflow)</th>
                                <th class="p-3 text-right">Credit (Inflow)</th>
                                <th class="p-3 text-right font-bold">Balance (PKR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($transactions as $index => $tx)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-3 text-center font-bold text-slate-400">
                                        {{ $transactions->firstItem() + $index }}
                                    </td>
                                    <td class="p-3 font-mono text-slate-700 whitespace-nowrap">
                                        {{ $tx->transaction_date->format('d-M-y') }}
                                    </td>
                                    <td class="p-3 font-mono text-slate-600 whitespace-nowrap">
                                        {{ $tx->transaction_no }}
                                    </td>
                                    <td class="p-3 font-medium text-slate-900">
                                        {{ $tx->description }}
                                        @if($tx->expense)
                                            <span class="text-[10px] text-slate-400 block font-normal">Expense Voucher: {{ $tx->expense->voucher_no }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 font-mono text-indigo-600 font-semibold whitespace-nowrap">
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
                                    <td colspan="8" class="p-8 text-center text-slate-400">
                                        No transactions recorded for this account.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="p-4 border-t border-slate-100 no-print">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
