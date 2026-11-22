<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-400">Purchasing</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Product Purchases
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Record vendor purchases, items, optional GST/SST and track vendor balances.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('vendors.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Vendors Directory</span>
                </a>
                <a href="{{ route('purchases.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>New Purchase</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-sm shadow-xs font-semibold">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-800 text-sm shadow-xs font-semibold">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Metrics Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                    <div class="text-xs text-slate-500 font-semibold mb-1">Total Purchases</div>
                    <div class="text-2xl font-black text-slate-900">{{ number_format($totalPurchasesCount) }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                    <div class="text-xs text-slate-500 font-semibold mb-1">Total Purchased Value</div>
                    <div class="text-lg font-black text-blue-700">PKR {{ number_format($totalPurchasedValue, 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                    <div class="text-xs text-slate-500 font-semibold mb-1">Paid Purchases</div>
                    <div class="text-lg font-black text-emerald-600">PKR {{ number_format($totalPaidValue, 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                    <div class="text-xs text-slate-500 font-semibold mb-1">Unpaid (Payable to Vendors)</div>
                    <div class="text-lg font-black text-rose-600">PKR {{ number_format($totalUnpaidValue, 2) }}</div>
                </div>
            </div>

            {{-- Search & Filter Controls --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
                <form method="GET" action="{{ route('purchases.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search by purchase #, notes..."
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <select name="vendor_id" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Vendors</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}" {{ (string)$vendorId === (string)$v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="payment_status" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Payment Statuses</option>
                            <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="date" name="from_date" value="{{ $fromDate }}" class="w-full px-2.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" title="From Date">
                        <span class="text-slate-400 text-xs">to</span>
                        <input type="date" name="to_date" value="{{ $toDate }}" class="w-full px-2.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" title="To Date">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition text-center">
                            Filter
                        </button>
                        @if($search || $vendorId || $status || $fromDate || $toDate)
                            <a href="{{ route('purchases.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition text-center">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Purchases Table --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                @if($purchases->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="text-left px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Purchase #</th>
                                    <th class="text-left px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Date</th>
                                    <th class="text-left px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Vendor</th>
                                    <th class="text-center px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Items</th>
                                    <th class="text-right px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Subtotal</th>
                                    <th class="text-right px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">GST/SST</th>
                                    <th class="text-right px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Total Amount</th>
                                    <th class="text-center px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Payment Status</th>
                                    <th class="text-center px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($purchases as $purchase)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('purchases.show', $purchase) }}" class="font-bold text-blue-600 hover:underline">
                                                {{ $purchase->purchase_number }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs">
                                            {{ $purchase->purchase_date->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($purchase->vendor)
                                                <a href="{{ route('vendors.show', $purchase->vendor) }}" class="font-bold text-slate-900 hover:text-blue-600">
                                                    {{ $purchase->vendor->name }}
                                                </a>
                                                @if($purchase->vendor->phone)
                                                    <div class="text-xs text-slate-400">{{ $purchase->vendor->phone }}</div>
                                                @endif
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                                {{ $purchase->items->count() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right text-xs font-semibold text-slate-700">
                                            PKR {{ number_format((float)$purchase->subtotal_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-xs text-slate-600">
                                            @php $tax = (float)$purchase->gst_amount + (float)$purchase->sst_amount; @endphp
                                            @if($tax > 0)
                                                <span class="font-semibold text-indigo-700">PKR {{ number_format($tax, 2) }}</span>
                                                <div class="text-[10px] text-slate-400">
                                                    G: {{ number_format((float)$purchase->gst_amount, 2) }} | S: {{ number_format((float)$purchase->sst_amount, 2) }}
                                                </div>
                                            @else
                                                <span class="text-slate-400">0.00</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="text-sm font-black text-slate-900">
                                                PKR {{ number_format((float)$purchase->total_amount, 2) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <form method="POST" action="{{ route('purchases.toggle-status', $purchase) }}" class="inline">
                                                @csrf @method('PATCH')
                                                @if($purchase->isPaid())
                                                    <button type="submit" title="Click to mark Unpaid"
                                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 hover:bg-emerald-200 transition">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                                    </button>
                                                @else
                                                    <button type="submit" title="Click to mark Paid"
                                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 hover:bg-rose-200 transition">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Unpaid
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <a href="{{ route('purchases.show', $purchase) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View / Print">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                <a href="{{ route('purchases.edit', $purchase) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                                @if(Auth::user()->isAdmin())
                                                    <form method="POST" action="{{ route('purchases.destroy', $purchase) }}"
                                                          onsubmit="return confirm('Delete purchase \'{{ $purchase->purchase_number }}\'? This will update the vendor\'s balance.')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($purchases->hasPages())
                        <div class="px-4 py-3 border-t border-slate-100">
                            {{ $purchases->links() }}
                        </div>
                    @endif
                @else
                    <div class="py-16 text-center">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p class="text-sm font-bold text-slate-500">No purchase records found</p>
                        <p class="text-xs text-slate-400 mt-1">
                            @if($search || $vendorId || $status || $fromDate || $toDate)
                                Try clearing your search filters.
                            @else
                                Record your first purchase from a registered vendor.
                            @endif
                        </p>
                        <a href="{{ route('purchases.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Record New Purchase
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
