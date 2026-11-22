<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('vendors.index') }}" class="hover:underline">Vendors</a>
                    <span>/</span>
                    <span class="text-slate-400">{{ $vendor->name }}</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $vendor->name }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('purchases.create') }}?vendor_id={{ $vendor->id }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Record Purchase</span>
                </a>
                <a href="{{ route('vendors.edit', $vendor) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('vendors.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-sm shadow-xs font-semibold">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Balance Cards Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
                    <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Opening Balance</div>
                    <div class="text-2xl font-black text-blue-900">PKR {{ number_format((float)$vendor->opening_balance, 2) }}</div>
                    <div class="text-[11px] text-blue-500 mt-1">Initial balance registered with vendor</div>
                </div>
                <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5">
                    <div class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-1">Current Balance (Payable)</div>
                    <div class="text-2xl font-black text-indigo-900">PKR {{ number_format((float)($vendor->current_balance ?? $vendor->opening_balance), 2) }}</div>
                    <div class="text-[11px] text-indigo-500 mt-1">Opening balance + unpaid purchases</div>
                </div>
            </div>

            {{-- Vendor Details Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800">Vendor Contact Details</h3>
                    @if($vendor->status === 'active')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> Inactive
                        </span>
                    @endif
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <div class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-1">Vendor Name</div>
                        <div class="text-sm font-bold text-slate-900">{{ $vendor->name }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-1">Phone Number</div>
                        <div class="text-sm text-slate-700 font-semibold">{{ $vendor->phone ?? '—' }}</div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-1">Address</div>
                        <div class="text-sm text-slate-700 leading-relaxed">{{ $vendor->address ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-1">Registered On</div>
                        <div class="text-slate-600">{{ $vendor->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    @if($vendor->creator)
                        <div>
                            <div class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-1">Registered By</div>
                            <div class="text-slate-600">{{ $vendor->creator->name }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Purchases History from this Vendor --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Purchase History</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Purchases recorded from this supplier</p>
                    </div>
                    <a href="{{ route('purchases.create') }}?vendor_id={{ $vendor->id }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>New Purchase</span>
                    </a>
                </div>

                @if($vendor->purchases->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600">
                                    <th class="text-left px-4 py-2.5 font-bold uppercase">Purchase #</th>
                                    <th class="text-left px-4 py-2.5 font-bold uppercase">Date</th>
                                    <th class="text-center px-4 py-2.5 font-bold uppercase">Items</th>
                                    <th class="text-right px-4 py-2.5 font-bold uppercase">GST/SST</th>
                                    <th class="text-right px-4 py-2.5 font-bold uppercase">Total (PKR)</th>
                                    <th class="text-center px-4 py-2.5 font-bold uppercase">Payment Status</th>
                                    <th class="text-center px-4 py-2.5 font-bold uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($vendor->purchases()->latest('purchase_date')->get() as $p)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-4 py-2.5 font-bold">
                                            <a href="{{ route('purchases.show', $p) }}" class="text-blue-600 hover:underline">
                                                {{ $p->purchase_number }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2.5 text-slate-600">{{ $p->purchase_date->format('d M Y') }}</td>
                                        <td class="px-4 py-2.5 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                                {{ $p->items->count() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2.5 text-right text-slate-600">
                                            PKR {{ number_format((float)$p->gst_amount + (float)$p->sst_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-2.5 text-right font-black text-slate-900">
                                            PKR {{ number_format((float)$p->total_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-2.5 text-center">
                                            @if($p->isPaid())
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Unpaid
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2.5 text-center">
                                            <a href="{{ route('purchases.show', $p) }}" class="text-blue-600 hover:text-blue-800 font-bold">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-8 text-center text-slate-400 text-xs">
                        No purchases recorded from this vendor yet.
                    </div>
                @endif
            </div>

            {{-- Danger Zone --}}
            @if(Auth::user()->isAdmin())
                <div class="bg-white rounded-2xl border border-rose-200 shadow-xs p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-bold text-rose-700">Delete Vendor</div>
                            <div class="text-xs text-rose-500 mt-0.5">This action is permanent and cannot be undone.</div>
                        </div>
                        <form method="POST" action="{{ route('vendors.destroy', $vendor) }}"
                              onsubmit="return confirm('Permanently delete vendor \'{{ addslashes($vendor->name) }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete Vendor
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
