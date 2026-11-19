<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('finance.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Finance Overview</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Disbursements & Costs</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    {{ __('Expenses & Payment Vouchers') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Track office rent, utilities, fuel, staff wages, site supplies and tender costs
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('expenses.categories') }}" class="px-3.5 py-2 text-xs font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Categories</span>
                </a>

                <a href="{{ route('expenses.create') }}" class="px-4 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Record New Expense</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs font-bold flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                </div>
            @endif

            <!-- 1. Top KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Expenses (This Month)</span>
                    <div class="text-2xl font-black text-rose-600 font-mono mt-1">
                        PKR {{ number_format($totalPaidMonth, 2) }}
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">{{ $totalCountMonth }} vouchers issued this month</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Expenses (All Time)</span>
                    <div class="text-2xl font-black text-slate-900 font-mono mt-1">
                        PKR {{ number_format($totalPaidAllTime, 2) }}
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">Total recorded disbursements</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Categories Active</span>
                    <div class="text-2xl font-black text-indigo-700 font-mono mt-1">
                        {{ $categories->count() }}
                    </div>
                    <div class="text-[11px] text-indigo-600 mt-1">Rent, Utilities, Fuel, Wages, etc.</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Approvals</span>
                    <div class="text-2xl font-black text-amber-600 font-mono mt-1">
                        {{ $pendingApprovalCount }}
                    </div>
                    <div class="text-[11px] text-amber-600 mt-1">Vouchers awaiting clearance</div>
                </div>
            </div>

            <!-- 2. Search & Filter Bar -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <form method="GET" action="{{ route('expenses.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3 text-xs">
                    
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Search Voucher / Title / Payee</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search voucher #, payee, title..." class="w-full text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Category</label>
                        <select name="category_id" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                            <option value="">All Methods</option>
                            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cheque" {{ request('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="online" {{ request('payment_method') === 'online' ? 'selected' : '' }}>Online Transfer</option>
                        </select>
                    </div>

                    <!-- Bank Account -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Paid From Account</label>
                        <select name="bank_account_id" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500">
                            <option value="">All Accounts</option>
                            @foreach($bankAccounts as $ba)
                                <option value="{{ $ba->id }}" {{ request('bank_account_id') == $ba->id ? 'selected' : '' }}>{{ $ba->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit & Reset -->
                    <div class="flex items-end gap-1.5">
                        <button type="submit" class="w-full py-2 bg-slate-900 text-white font-bold rounded-lg hover:bg-black transition text-xs">
                            Filter
                        </button>
                        @if(request('search') || request('category_id') || request('payment_method') || request('bank_account_id') || request('date_from') || request('date_to'))
                            <a href="{{ route('expenses.index') }}" class="px-2.5 py-2 text-rose-600 font-bold hover:underline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- 3. Expenses Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Expense Vouchers Registry</h3>
                    <span class="text-xs font-bold text-slate-500 font-mono">{{ $expenses->total() }} Records Found</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200 text-[10px]">
                                <th class="p-3 text-center">#</th>
                                <th class="p-3">Voucher #</th>
                                <th class="p-3">Date</th>
                                <th class="p-3">Category</th>
                                <th class="p-3">Title & Particulars</th>
                                <th class="p-3">Paid From / Method</th>
                                <th class="p-3">Payee / Beneficiary</th>
                                <th class="p-3 text-right">Amount</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($expenses as $index => $exp)
                                @php $st = $exp->getStatusBadge(); @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-3 text-center font-bold text-slate-400">
                                        {{ $expenses->firstItem() + $index }}
                                    </td>
                                    <td class="p-3 font-mono font-bold text-indigo-600 whitespace-nowrap">
                                        <a href="{{ route('expenses.show', $exp) }}" class="hover:underline">
                                            {{ $exp->voucher_no }}
                                        </a>
                                        @if($exp->receipt_file)
                                            <span class="inline-block text-[9px] text-emerald-600 font-bold font-sans">📎 Receipt</span>
                                        @endif
                                    </td>
                                    <td class="p-3 font-mono text-slate-600 whitespace-nowrap">
                                        {{ $exp->expense_date->format('d-M-Y') }}
                                    </td>
                                    <td class="p-3 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10.5px] font-bold bg-slate-50 border border-slate-200 text-slate-800">
                                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $exp->category?->color ?: '#6366f1' }}"></span>
                                            {{ $exp->category?->name ?: 'General' }}
                                        </span>
                                    </td>
                                    <td class="p-3 font-medium text-slate-900">
                                        <div class="font-bold">{{ $exp->title }}</div>
                                        @if($exp->description)
                                            <div class="text-[10px] text-slate-500 line-clamp-1">{{ $exp->description }}</div>
                                        @endif
                                    </td>
                                    <td class="p-3 whitespace-nowrap">
                                        <div class="font-semibold text-slate-800">
                                            {{ $exp->bankAccount ? $exp->bankAccount->bank_name : ucfirst($exp->payment_method) }}
                                        </div>
                                        @if($exp->cheque_no)
                                            <div class="text-[10px] font-mono text-indigo-600">Chq: {{ $exp->cheque_no }}</div>
                                        @endif
                                    </td>
                                    <td class="p-3 font-semibold text-slate-700 whitespace-nowrap">
                                        {{ $exp->payee_name ?: '-' }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-sm text-rose-600 whitespace-nowrap">
                                        PKR {{ number_format($exp->amount, 2) }}
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border {{ $st['class'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                                            {{ $st['label'] }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('expenses.show', $exp) }}" class="px-2.5 py-1 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                                                Voucher
                                            </a>
                                            <form method="POST" action="{{ route('expenses.destroy', $exp) }}" onsubmit="return confirm('Delete this expense voucher? If paid via bank, balance will be reverted.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="p-8 text-center text-slate-400">
                                        No expenses found matching the criteria. Click "+ Record New Expense" above.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
