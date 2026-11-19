<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('expenses.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Expenses Registry</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Payment Voucher</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <span>Voucher # {{ $expense->voucher_no }}</span>
                    @php $st = $expense->getStatusBadge(); @endphp
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full border {{ $st['class'] }}">
                        {{ $st['label'] }}
                    </span>
                </h2>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="px-4 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Voucher</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 font-sans">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Printable Voucher Sheet -->
            <div class="bg-white p-8 sm:p-10 rounded-2xl border border-slate-200 shadow-sm print:border-none print:shadow-none print:p-0 space-y-6">
                
                <!-- Voucher Header -->
                <div class="border-b-2 border-slate-900 pb-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">
                            {{ $expense->company ? $expense->company->name : (Auth::user()->company_name ?: 'ProWave Technologies') }}
                        </h1>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            Government Contractor & General Order Suppliers
                        </p>
                        <div class="inline-block mt-2 px-3 py-1 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded">
                            Payment / Debit Voucher
                        </div>
                    </div>

                    <div class="text-left sm:text-right font-mono text-xs space-y-1">
                        <div><span class="text-slate-400 font-sans">Voucher #:</span> <span class="font-bold text-slate-900 text-sm">{{ $expense->voucher_no }}</span></div>
                        <div><span class="text-slate-400 font-sans">Date:</span> <span class="font-bold text-slate-800">{{ $expense->expense_date->format('d-M-Y') }}</span></div>
                        <div><span class="text-slate-400 font-sans">Prepared By:</span> <span class="font-bold text-slate-800">{{ $expense->user?->name ?: 'System Admin' }}</span></div>
                    </div>
                </div>

                <!-- Voucher Body Grid -->
                <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <span class="text-slate-400 uppercase font-bold text-[10px] block">Paid To (Payee / Beneficiary):</span>
                        <span class="font-black text-slate-900 text-sm">{{ $expense->payee_name ?: 'Bearer / Cashier' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-bold text-[10px] block">Expense Category:</span>
                        <span class="font-bold text-indigo-700 text-sm">{{ $expense->category?->name ?: 'General Operations' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-bold text-[10px] block">Payment Method:</span>
                        <span class="font-bold text-slate-800 uppercase">{{ $expense->payment_method }}</span>
                        @if($expense->cheque_no)
                            <span class="font-mono text-indigo-600 block">(Cheque # {{ $expense->cheque_no }})</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-bold text-[10px] block">Deducted From Account:</span>
                        <span class="font-bold text-slate-800">
                            {{ $expense->bankAccount ? $expense->bankAccount->bank_name : 'Direct Cash' }}
                        </span>
                        @if($expense->bankAccount)
                            <span class="font-mono text-slate-500 block text-[11px]">A/C: {{ $expense->bankAccount->account_number }}</span>
                        @endif
                    </div>
                </div>

                <!-- Expense Particulars Table -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-[10.5px] border-b border-slate-200">
                            <tr>
                                <th class="p-3">#</th>
                                <th class="p-3">Particulars / Description</th>
                                <th class="p-3 text-right">Amount (PKR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="p-3 font-bold text-slate-400 text-center">1</td>
                                <td class="p-3">
                                    <div class="font-black text-slate-900 text-sm">{{ $expense->title }}</div>
                                    @if($expense->description)
                                        <div class="text-slate-600 mt-1">{{ $expense->description }}</div>
                                    @endif
                                    @if($expense->department)
                                        <div class="text-[11px] text-indigo-600 mt-1 font-semibold">Tender Department: {{ $expense->department->name }} ({{ $expense->department->code }})</div>
                                    @endif
                                </td>
                                <td class="p-3 text-right font-mono font-black text-base text-slate-900 whitespace-nowrap">
                                    PKR {{ number_format($expense->amount, 2) }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-slate-50 border-t-2 border-slate-300 font-bold">
                            <tr>
                                <td colspan="2" class="p-3 text-right uppercase tracking-wider text-slate-700">Net Total Disbursed:</td>
                                <td class="p-3 text-right font-mono font-black text-lg text-rose-600 whitespace-nowrap">
                                    PKR {{ number_format($expense->amount, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Attached Receipt Image (if present) -->
                @if($expense->receipt_file)
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-2 no-print">
                        <span class="text-xs font-bold text-slate-700 uppercase flex items-center gap-1.5">
                            📎 Attached Bill / Receipt Proof:
                        </span>
                        <div class="pt-2">
                            <a href="{{ asset($expense->receipt_file) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-bold text-indigo-600 hover:bg-slate-50 transition">
                                <span>View / Download Receipt</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Signatures Grid -->
                <div class="pt-12 grid grid-cols-4 gap-4 text-center text-xs">
                    <div>
                        <div class="border-b border-slate-400 pb-1 font-bold text-slate-800">
                            {{ $expense->user?->name ?: 'Accountant' }}
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase mt-1 block">Prepared By</span>
                    </div>

                    <div>
                        <div class="border-b border-slate-400 pb-1 font-bold text-slate-800 min-h-[1.5rem]">
                            &nbsp;
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase mt-1 block">Checked By</span>
                    </div>

                    <div>
                        <div class="border-b border-slate-400 pb-1 font-bold text-slate-800">
                            {{ $expense->approver?->name ?: 'Manager / Director' }}
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase mt-1 block">Approved By</span>
                    </div>

                    <div>
                        <div class="border-b border-slate-400 pb-1 font-bold text-slate-800 min-h-[1.5rem]">
                            &nbsp;
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase mt-1 block">Received By (Payee)</span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
