<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    {{ __('Contractor Invoicing & Bills Registry') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Track procurement milestone payments, deductions, and financial approvals.</p>
            </div>
            <a href="{{ route('bills.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                + Generate New Running Bill
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ qtnModalOpen: false, modalBillId: null, modalBillNo: '', modalQtnNum: 1, modalCompanyId: '{{ $companies->first()?->id ?? 1 }}' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Invoiced</div>
                    <div class="text-xl font-black text-slate-900 mt-1">PKR {{ number_format($stats['total_amount'], 2) }}</div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Paid / Disbursed</div>
                    <div class="text-xl font-black text-emerald-600 mt-1">PKR {{ number_format($stats['paid_amount'], 2) }}</div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Review</div>
                    <div class="text-xl font-black text-amber-600 mt-1">{{ $stats['pending_count'] }} Bills</div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Approved for Payment</div>
                    <div class="text-xl font-black text-indigo-600 mt-1">{{ $stats['approved_count'] }} Bills</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <form method="GET" action="{{ route('bills.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Search Keyword</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Bill #, vendor name, milestone..." class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Department</label>
                        <select name="department_id" class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                        <div class="flex gap-2">
                            <select name="status" class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified_by_dept" {{ request('status') == 'verified_by_dept' ? 'selected' : '' }}>Verified by Dept</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Bills Registry Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="p-4">Bill Voucher #</th>
                                <th class="p-4">Bill Head / Description</th>
                                <th class="p-4">Vendor / Contractor</th>
                                <th class="p-4">Department</th>
                                <th class="p-4">Gross & Tax</th>
                                <th class="p-4">Net Payable</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($bills as $bill)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 font-mono font-bold text-slate-800">
                                        <a href="{{ route('bills.show', $bill) }}" class="text-indigo-600 hover:underline">
                                            {{ $bill->bill_number }}
                                        </a>
                                        <div class="text-[11px] font-normal text-slate-400 mt-0.5">Date: {{ $bill->invoice_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="p-4">
                                        @if($bill->tender)
                                            <a href="{{ route('tenders.show', $bill->tender) }}" class="font-semibold text-slate-800 hover:text-indigo-600 block line-clamp-1">
                                                {{ $bill->tender->title }}
                                            </a>
                                        @else
                                            <span class="font-semibold text-slate-800 block line-clamp-1">
                                                {{ $bill->head_of_account ?: ($bill->department ? ($bill->department->cost_center_description ?: $bill->department->name) : 'Departmental Purchase') }}
                                            </span>
                                        @endif
                                        <div class="text-xs text-slate-500 line-clamp-1 mt-0.5">{{ $bill->milestone_description }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-800">{{ $bill->vendor ? ($bill->vendor->company_name ?: $bill->vendor->name) : 'Department Direct' }}</div>
                                        <div class="text-xs text-slate-400">{{ $bill->vendor ? ($bill->vendor->phone ?: $bill->vendor->email) : 'Internal Procurement' }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span class="font-mono text-xs font-semibold bg-slate-100 text-slate-700 px-2 py-0.5 rounded">
                                            {{ $bill->department ? $bill->department->code : 'General' }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-xs font-medium text-slate-700">Gross: PKR {{ number_format($bill->gross_amount, 2) }}</div>
                                        <div class="text-[11px] text-rose-500">Tax: -PKR {{ number_format($bill->tax_deduction, 2) }}</div>
                                    </td>
                                    <td class="p-4 font-extrabold text-slate-900">
                                        PKR {{ number_format($bill->net_amount, 2) }}
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                            @if($bill->status === 'paid') bg-emerald-50 text-emerald-700 border border-emerald-200
                                            @elseif($bill->status === 'approved') bg-indigo-50 text-indigo-700 border border-indigo-200
                                            @elseif($bill->status === 'verified_by_dept') bg-blue-50 text-blue-700 border border-blue-200
                                            @elseif($bill->status === 'rejected') bg-rose-50 text-rose-700 border border-rose-200
                                            @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                            ● {{ ucfirst(str_replace('_', ' ', $bill->status)) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                                            <button @click="open = !open" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-lg border border-blue-200 shadow-2xs transition">
                                                <span>Actions</span>
                                                <svg class="w-3.5 h-3.5 text-blue-600 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>

                                            <div x-show="open" 
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                 x-transition:leave-end="transform opacity-0 scale-95"
                                                 class="absolute right-0 z-30 mt-1.5 w-60 rounded-xl bg-white p-1.5 shadow-xl border border-slate-200 divide-y divide-slate-100 text-left font-sans text-xs" 
                                                 style="display: none;">
                                                <div class="py-1">
                                                    <a href="{{ route('bills.show', $bill) }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold transition">
                                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        <span>View Bill Voucher</span>
                                                    </a>
                                                    <a href="{{ route('bills.print', $bill) }}" target="_blank" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold transition">
                                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                        <span>Print Cash Memo Bill</span>
                                                    </a>
                                                    <a href="{{ route('bills.sales-tax-invoice', $bill) }}" target="_blank" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-indigo-700 bg-indigo-50/60 hover:bg-indigo-100 font-bold transition">
                                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        <span>Sales Tax Invoice</span>
                                                    </a>
                                                    <a href="{{ route('bills.invoice-design', $bill) }}" target="_blank" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-rose-700 bg-rose-50/60 hover:bg-rose-100 font-bold transition mt-0.5">
                                                        <span class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                                            <span>Invoice Designs</span>
                                                        </span>
                                                        <span class="text-[9px] bg-rose-100 text-rose-700 px-1 py-0.2 rounded font-extrabold uppercase">7 Patterns</span>
                                                    </a>
                                                </div>

                                                <div class="py-1">
                                                    <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Comparative Quotations</div>
                                                    <a href="{{ route('bills.quotation', [$bill, 1]) }}" target="_blank" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition font-medium">
                                                        <span>Quotation 1</span>
                                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">L1</span>
                                                    </a>
                                                    <a href="{{ route('bills.quotation', [$bill, 2]) }}" target="_blank" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition font-medium">
                                                        <span>Quotation 2</span>
                                                    </a>
                                                    <a href="{{ route('bills.quotation', [$bill, 3]) }}" target="_blank" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition font-medium">
                                                        <span>Quotation 3</span>
                                                    </a>
                                                    <button type="button" 
                                                            @click="modalBillId = {{ $bill->id }}; modalBillNo = '{{ $bill->bill_number }}'; modalCompanyId = '{{ $bill->company_id ?: ($companies->first()?->id ?? 1) }}'; qtnModalOpen = true; open = false;" 
                                                            class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-blue-600 hover:bg-blue-50 font-bold transition text-xs mt-0.5">
                                                        <span class="flex items-center gap-1.5">
                                                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                                            <span>Print by Company...</span>
                                                        </span>
                                                        <span class="text-[9px] bg-blue-100 text-blue-700 px-1 py-0.2 rounded font-bold">Pick</span>
                                                    </button>
                                                </div>

                                                <div class="pt-1">
                                                    <a href="{{ route('bills.comparative-statement', $bill) }}" target="_blank" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-emerald-700 bg-emerald-50 hover:bg-emerald-100 font-bold transition">
                                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        <span>Comparative Statement</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-12 text-center text-slate-500">
                                        No bills or invoices match the selected criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $bills->links() }}
                </div>
            </div>

        </div>

        <!-- Modal: Choose Company for Quotation -->
        <div x-show="qtnModalOpen" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200" @click.away="qtnModalOpen = false">
                <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold tracking-tight">Print Quotation by Company</h3>
                            <p class="text-[11px] text-slate-300">Bill # <span x-text="modalBillNo" class="font-mono font-bold text-white"></span></p>
                        </div>
                    </div>
                    <button type="button" @click="qtnModalOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg">✕</button>
                </div>

                <div class="p-5 space-y-4">
                    <!-- 1. Select Quotation Type -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Select Quotation Rate / Level</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" 
                                    @click="modalQtnNum = 1" 
                                    :class="modalQtnNum == 1 ? 'bg-blue-600 text-white border-blue-600 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                    class="p-2.5 rounded-xl border text-center font-bold text-xs transition">
                                <div>Quotation 1</div>
                                <span class="text-[10px] opacity-80">Lowest (L1)</span>
                            </button>
                            <button type="button" 
                                    @click="modalQtnNum = 2" 
                                    :class="modalQtnNum == 2 ? 'bg-blue-600 text-white border-blue-600 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                    class="p-2.5 rounded-xl border text-center font-bold text-xs transition">
                                <div>Quotation 2</div>
                                <span class="text-[10px] opacity-80">+5% Rate</span>
                            </button>
                            <button type="button" 
                                    @click="modalQtnNum = 3" 
                                    :class="modalQtnNum == 3 ? 'bg-blue-600 text-white border-blue-600 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                    class="p-2.5 rounded-xl border text-center font-bold text-xs transition">
                                <div>Quotation 3</div>
                                <span class="text-[10px] opacity-80">+8% Rate</span>
                            </button>
                        </div>
                    </div>

                    <!-- 2. Select Company / Supplier -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Select Company / Letterhead</label>
                        <select x-model="modalCompanyId" class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-blue-500 py-2.5 bg-white text-slate-800">
                            @if(isset($companies))
                                @foreach($companies as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->company_name }} (NTN: {{ $c->ntn ?: '-' }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">This company's name, address, NTN, GST, & SST will be printed on the quotation.</p>
                    </div>

                    <!-- Footer buttons -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" @click="qtnModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">Cancel</button>
                        <a :href="'/bills/' + modalBillId + '/quotation/' + modalQtnNum + '?company_id=' + modalCompanyId" 
                           target="_blank" 
                           @click="qtnModalOpen = false"
                           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>Open & Print Quotation</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
