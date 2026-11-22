<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('purchases.index') }}" class="hover:underline">Purchases</a>
                    <span>/</span>
                    <span class="text-slate-400">{{ $purchase->purchase_number }}</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Purchase Order: {{ $purchase->purchase_number }}
                </h2>
            </div>
            <div class="flex items-center gap-2.5">
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Voucher</span>
                </button>
                <a href="{{ route('purchases.edit', $purchase) }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('purchases.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50 print:bg-white print:py-0">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 print:max-w-full print:px-0">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-sm shadow-xs font-semibold print:hidden">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Printable Purchase Voucher Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6 print:border-0 print:shadow-none print:p-2">
                
                {{-- Header Block --}}
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-slate-200">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 border border-blue-200 mb-2">
                            PURCHASE VOUCHER
                        </span>
                        <h1 class="text-2xl font-black text-slate-900">{{ $purchase->purchase_number }}</h1>
                        <p class="text-xs text-slate-500 mt-1">Date: <span class="font-bold text-slate-800">{{ $purchase->purchase_date->format('d F Y') }}</span></p>
                    </div>

                    <div class="text-left sm:text-right">
                        <div class="text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Payment Status</div>
                        <form method="POST" action="{{ route('purchases.toggle-status', $purchase) }}" class="inline print:hidden">
                            @csrf @method('PATCH')
                            @if($purchase->isPaid())
                                <button type="submit" title="Click to change to Unpaid" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200 transition">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> PAID (Settled)
                                </button>
                            @else
                                <button type="submit" title="Click to change to Paid" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 text-rose-800 border border-rose-300 hover:bg-rose-200 transition">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> UNPAID (Vendor Balance: +{{ $purchase->formatted_total_amount }})
                                </button>
                            @endif
                        </form>
                        <div class="hidden print:block">
                            <span class="text-xs font-black uppercase {{ $purchase->isPaid() ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ strtoupper($purchase->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Two-Column Info: Vendor Details vs Purchase Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                    <div>
                        <div class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-1.5">Supplier / Vendor:</div>
                        <div class="text-sm font-extrabold text-slate-900">
                            {{ $purchase->vendor->name ?? '—' }}
                        </div>
                        @if($purchase->vendor)
                            @if($purchase->vendor->phone)
                                <div class="text-slate-600 mt-0.5">Phone: <span class="font-medium text-slate-800">{{ $purchase->vendor->phone }}</span></div>
                            @endif
                            @if($purchase->vendor->address)
                                <div class="text-slate-600 mt-0.5">Address: <span class="font-medium text-slate-800">{{ $purchase->vendor->address }}</span></div>
                            @endif
                            <div class="mt-2 pt-2 border-t border-slate-200 text-[11px]">
                                <span class="text-slate-500">Vendor Outstanding Balance:</span>
                                <span class="font-black text-blue-700">PKR {{ number_format((float)($purchase->vendor->current_balance ?? $purchase->vendor->opening_balance), 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-1 sm:text-right">
                        <div class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-1.5">Record Summary:</div>
                        <div><span class="text-slate-500">Purchase Reference:</span> <span class="font-mono font-bold text-slate-800">{{ $purchase->purchase_number }}</span></div>
                        <div><span class="text-slate-500">Purchase Date:</span> <span class="font-bold text-slate-800">{{ $purchase->purchase_date->format('d M Y') }}</span></div>
                        @if($purchase->creator)
                            <div><span class="text-slate-500">Recorded By:</span> <span class="font-semibold text-slate-700">{{ $purchase->creator->name }}</span></div>
                        @endif
                        @if($purchase->notes)
                            <div class="mt-2 text-slate-600 italic">"{{ $purchase->notes }}"</div>
                        @endif
                    </div>
                </div>

                {{-- Line Items Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-200 text-slate-700">
                                <th class="text-left px-3 py-2.5 font-bold uppercase w-10">#</th>
                                <th class="text-left px-3 py-2.5 font-bold uppercase">Product / Description</th>
                                <th class="text-center px-3 py-2.5 font-bold uppercase w-20">Unit</th>
                                <th class="text-right px-3 py-2.5 font-bold uppercase w-20">Qty</th>
                                <th class="text-right px-3 py-2.5 font-bold uppercase w-28">Rate (PKR)</th>
                                <th class="text-right px-3 py-2.5 font-bold uppercase w-24">GST</th>
                                <th class="text-right px-3 py-2.5 font-bold uppercase w-24">SST</th>
                                <th class="text-right px-3 py-2.5 font-bold uppercase w-32">Total (PKR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($purchase->items as $item)
                                <tr>
                                    <td class="px-3 py-3 text-slate-400 font-semibold">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-3">
                                        <div class="font-bold text-slate-900 text-sm">{{ $item->item_description }}</div>
                                        @if($item->product && $item->product->item_code)
                                            <div class="text-[10px] text-slate-400 font-mono">Code: {{ $item->product->item_code }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-center text-slate-600">{{ $item->unit }}</td>
                                    <td class="px-3 py-3 text-right font-semibold text-slate-800">{{ number_format((float)$item->quantity, 2) }}</td>
                                    <td class="px-3 py-3 text-right font-semibold text-slate-800">{{ number_format((float)$item->unit_price, 2) }}</td>
                                    <td class="px-3 py-3 text-right text-slate-600">
                                        @if((float)$item->gst_percent > 0)
                                            <div class="font-bold text-emerald-700">{{ number_format((float)$item->gst_amount, 2) }}</div>
                                            <div class="text-[10px] text-slate-400">({{ $item->gst_percent }}%)</div>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-right text-slate-600">
                                        @if((float)$item->sst_percent > 0)
                                            <div class="font-bold text-indigo-700">{{ number_format((float)$item->sst_amount, 2) }}</div>
                                            <div class="text-[10px] text-slate-400">({{ $item->sst_percent }}%)</div>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-right font-black text-slate-900">
                                        {{ number_format((float)$item->total_amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totals Summary Box --}}
                <div class="flex justify-end pt-4 border-t border-slate-200">
                    <div class="w-full sm:w-80 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Base Items Subtotal:</span>
                            <span class="font-bold text-slate-800">PKR {{ number_format((float)$purchase->subtotal_amount, 2) }}</span>
                        </div>
                        @if((float)$purchase->gst_amount > 0)
                            <div class="flex justify-between text-emerald-700 font-semibold">
                                <span>Total General Sales Tax (GST):</span>
                                <span>PKR {{ number_format((float)$purchase->gst_amount, 2) }}</span>
                            </div>
                        @endif
                        @if((float)$purchase->sst_amount > 0)
                            <div class="flex justify-between text-indigo-700 font-semibold">
                                <span>Total Sindh Sales Tax (SST):</span>
                                <span>PKR {{ number_format((float)$purchase->sst_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="pt-2 border-t border-slate-300 flex justify-between text-sm font-black text-slate-900">
                            <span>Grand Total:</span>
                            <span class="text-base text-blue-700">PKR {{ number_format((float)$purchase->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Signatures for Printing --}}
                <div class="hidden print:grid grid-cols-2 pt-16 gap-8 text-xs text-center">
                    <div class="border-t border-slate-400 pt-2 font-bold text-slate-700">
                        Received By (Warehouse / Store Incharge)
                    </div>
                    <div class="border-t border-slate-400 pt-2 font-bold text-slate-700">
                        Authorized Signatory / Finance Officer
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
