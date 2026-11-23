<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('bills.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Back to Bills Registry</a>
                    <span class="text-slate-300">•</span>
                    <span class="font-mono text-xs font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded">BILL #. {{ $bill->bill_number }}</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Bill / Cash Memo Voucher') }}
                </h2>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('bills.sales-tax-invoice', $bill) }}" target="_blank" class="px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold rounded-lg shadow-2xs flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Sales Tax Invoice</span>
                </a>
                <a href="{{ route('bills.invoice-design', $bill) }}" target="_blank" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold rounded-lg shadow-2xs flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                    <span>Invoice Designs</span>
                    <span class="text-[10px] bg-rose-200 text-rose-800 px-1 py-0.2 rounded font-extrabold">7 Patterns</span>
                </a>
                <div class="relative inline-block text-left" x-data="{ qOpen: false, qtnModalOpen: false, modalQtnNum: 1, modalCompanyId: '{{ $bill->company_id ?: ($companies->first()?->id ?? 1) }}' }" @click.outside="qOpen = false">
                    <button @click="qOpen = !qOpen" type="button" class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold rounded-lg shadow-2xs flex items-center gap-1.5 transition">
                        <span>3 Quotations</span>
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="qOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute right-0 z-30 mt-1.5 w-56 rounded-xl bg-white p-1.5 shadow-xl border border-slate-200 divide-y divide-slate-100 text-left font-sans text-xs" 
                         style="display: none;">
                        <div class="py-1">
                            <a href="{{ route('bills.quotation', [$bill, 1]) }}" target="_blank" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 font-semibold transition">
                                <span>Quotation 1</span>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1 py-0.5 rounded">L1</span>
                            </a>
                            <a href="{{ route('bills.quotation', [$bill, 2]) }}" target="_blank" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 font-semibold transition">
                                <span>Quotation 2</span>
                            </a>
                            <a href="{{ route('bills.quotation', [$bill, 3]) }}" target="_blank" class="flex items-center justify-between px-3 py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 font-semibold transition">
                                <span>Quotation 3</span>
                            </a>
                        </div>
                        <div class="pt-1">
                            <button type="button" 
                                    @click="qtnModalOpen = true; qOpen = false;" 
                                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-blue-600 hover:bg-blue-50 font-bold transition text-xs">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    <span>Print by Company...</span>
                                </span>
                                <span class="text-[9px] bg-blue-100 text-blue-700 px-1 py-0.2 rounded font-bold">Pick</span>
                            </button>
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
                                        <p class="text-[11px] text-slate-300">Bill # <span class="font-mono font-bold text-white">{{ $bill->bill_number }}</span></p>
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
                                    <a :href="'/bills/{{ $bill->id }}/quotation/' + modalQtnNum + '?company_id=' + modalCompanyId" 
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
                <a href="{{ route('bills.comparative-statement', $bill) }}" target="_blank" class="px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-lg shadow-2xs flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Comparative Statement</span>
                </a>
                <a href="{{ route('bills.edit', $bill) }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Edit Bill</span>
                </a>
                <a href="{{ route('bills.print', $bill) }}" target="_blank" class="px-4 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-lg shadow-sm flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Print Bill / Memo</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Main Bill Document Paper Container -->
            <div class="bg-white rounded-2xl border border-slate-300 shadow-md p-8 md:p-12 space-y-8 font-sans text-slate-800">

                <!-- 1. Top Section: To, & Bill Meta Header -->
                <div class="flex flex-col md:flex-row justify-between items-start gap-6 border-b pb-6 border-slate-200">
                    <!-- Left: "To," Section -->
                    <div class="space-y-1">
                        <div class="text-sm font-bold text-slate-900">To,</div>
                        <div class="text-lg font-black text-slate-900 ps-4">
                            {{ $bill->department ? ($bill->department->cost_center_description ?: $bill->department->name) : 'Office of the XYZ' }}
                        </div>
                        <div class="text-sm font-semibold text-slate-600 ps-4">
                            {{ $bill->department ? ($bill->department->city ?: 'Karachi.') : 'Karachi.' }}
                        </div>
                    </div>

                    <!-- Right: Official Bill Meta Box (Dashed Border Style like Image 2) -->
                    <div class="border-2 border-dashed border-slate-700 p-4 rounded-lg bg-slate-50 min-w-[280px] space-y-1 font-mono text-xs">
                        <div class="flex justify-between items-center pb-1 border-b border-slate-300">
                            <span class="font-extrabold text-sm text-slate-900 uppercase">BILL #.</span>
                            <span class="font-black text-base text-slate-900">{{ $bill->bill_number }}</span>
                        </div>
                        <div class="flex justify-between pt-1">
                            <span class="text-slate-500 font-semibold">Company:</span>
                            <span class="font-bold text-slate-900">{{ $bill->company ? $bill->company->company_name : ($bill->vendor ? ($bill->vendor->company_name ?: $bill->vendor->name) : 'M/S. COMZON') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-semibold">NTN #:</span>
                            <span class="font-bold text-slate-800">{{ $bill->ntn_number ?: ($bill->company?->ntn ?: ($bill->vendor?->tax_number ?: '2128115-7')) }}</span>
                        </div>
                        @if(((float)$bill->gst_amount > 0 || (float)$bill->gst_rate > 0) && ($bill->strn_number ?: ($bill->company?->gst ?: $bill->vendor?->strn_number)))
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-semibold">Sale Tax (GST) #:</span>
                            <span class="font-bold text-slate-800">{{ $bill->strn_number ?: ($bill->company?->gst ?: ($bill->vendor?->strn_number ?: '1700212811518')) }}</span>
                        </div>
                        @endif
                        @if($bill->sst_number ?: $bill->company?->sst)
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-semibold">SST / SRB #:</span>
                            <span class="font-bold text-slate-800">{{ $bill->sst_number ?: $bill->company?->sst }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-semibold">{{ $bill->vendor_type ?: 'AGPR' }} Vendor #:</span>
                            <span class="font-bold text-slate-800">{{ $bill->vendor_number ?: ($bill->agpr_vendor_no ?: ($bill->company?->vendor_no ?: ($bill->vendor?->agpr_vendor_no ?: '30368984'))) }}</span>
                        </div>
                    </div>
                </div>


                <!-- 3. Centered Title (Image 2 Style) -->
                <div class="text-center py-2 border-y-2 border-slate-800 space-y-1">
                    <h1 class="text-xl font-black uppercase tracking-widest text-slate-900">BILL / CASH MEMO</h1>
                    <div class="text-sm font-bold text-slate-700 underline decoration-2 underline-offset-4">
                        {{ $bill->head_of_account ?: ($bill->department->cost_center_description ?: 'Cost of Other Stores') }}
                    </div>
                </div>

                <!-- 4. Products Table (Image 2 Clean Style) -->
                @php
                    $hasUnit = $bill->items->contains(fn($i) => !empty($i->unit));
                @endphp
                <div class="border border-slate-800 rounded-lg overflow-hidden">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100 border-b-2 border-slate-800 text-slate-900 font-black uppercase">
                                <th class="p-3 w-14 text-center border-r border-slate-300">S. #.</th>
                                <th class="p-3 border-r border-slate-300">Description</th>
                                @if($hasUnit)
                                    <th class="p-3 w-20 text-center border-r border-slate-300">Unit</th>
                                @endif
                                <th class="p-3 w-20 text-center border-r border-slate-300">Qty</th>
                                <th class="p-3 w-28 text-right border-r border-slate-300">Rate</th>
                                <th class="p-3 w-32 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 font-sans">
                            @forelse($bill->items as $idx => $item)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="p-2.5 text-center font-bold text-slate-600 border-r border-slate-200">{{ $idx + 1 }}</td>
                                    <td class="p-2.5 font-medium text-slate-800 border-r border-slate-200">{{ $item->item_name }}</td>
                                    @if($hasUnit)
                                        <td class="p-2.5 text-center font-semibold text-slate-700 border-r border-slate-200">{{ $item->unit ?: '-' }}</td>
                                    @endif
                                    <td class="p-2.5 text-center font-semibold text-slate-700 border-r border-slate-200">{{ (int)$item->quantity == $item->quantity ? (int)$item->quantity : $item->quantity }}</td>
                                    <td class="p-2.5 text-right font-medium text-slate-700 border-r border-slate-200">{{ number_format($item->unit_price) }}</td>
                                    <td class="p-2.5 text-right font-bold text-slate-900">{{ number_format($item->total_price) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $hasUnit ? 6 : 5 }}" class="p-4 text-center text-slate-400">No items listed.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- 5. Bottom Right Totals Grid (Image 2 Exact Style) -->
                <div class="flex justify-end pt-2">
                    <div class="w-72 border-2 border-slate-800 rounded-lg overflow-hidden text-xs divide-y divide-slate-800 font-bold">
                        <div class="flex justify-between p-2.5 bg-slate-50">
                            <span class="text-slate-700">Amount</span>
                            <span class="text-slate-900 font-black text-sm">{{ number_format($bill->gross_amount) }}</span>
                        </div>
                        @if((float)$bill->gst_amount > 0 || (float)$bill->gst_rate > 0)
                        <div class="flex justify-between p-2.5 bg-slate-50">
                            <span class="text-slate-700">{{ (float)$bill->gst_rate > 0 ? (int)$bill->gst_rate . '%' : '18%' }} GST</span>
                            <span class="text-slate-900 font-black text-sm">{{ number_format($bill->gst_amount) }}</span>
                        </div>
                        @endif
                        @if($bill->tax_deduction > 0)
                            <div class="flex justify-between p-2.5 bg-slate-50 text-rose-600">
                                <span>Withholding Deduction</span>
                                <span class="font-black text-sm">-{{ number_format($bill->tax_deduction) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between p-3 bg-slate-900 text-white font-black text-sm">
                            <span>Total Amount</span>
                            <span class="text-emerald-400 text-base">PKR {{ number_format($bill->net_amount) }}</span>
                        </div>
                    </div>
                </div>

                <!-- 6. Status and Actions -->
                <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-400 uppercase font-bold">Voucher Status:</span>
                        @if($bill->status === 'paid')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-300">
                                ● PAID
                            </span>
                        @elseif($bill->status === 'approved' || $bill->status === 'confirmed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                ● CONFIRMED BILL
                            </span>
                        @elseif($bill->status === 'draft' || $bill->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-amber-50 text-amber-700 border border-amber-300">
                                ● DRAFT BILL
                            </span>
                        @elseif($bill->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-rose-50 text-rose-700 border border-rose-300">
                                ● REJECTED
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-slate-100 text-slate-700 border border-slate-300">
                                ● {{ strtoupper(str_replace('_', ' ', $bill->status)) }}
                            </span>
                        @endif
                    </div>

                    @if(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isEvaluator()))
                        <div class="flex items-center gap-2">
                            <a href="{{ route('bills.edit', $bill) }}" class="px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-bold rounded-lg transition flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span>Edit Bill</span>
                            </a>

                            <form method="POST" action="{{ route('bills.status', $bill) }}" class="inline-flex items-center gap-2">
                                @csrf
                                @method('PATCH')

                                @if($bill->status !== 'approved' && $bill->status !== 'confirmed' && $bill->status !== 'paid')
                                    <button type="submit" name="status" value="approved" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-2xs">
                                        ✓ Mark as Confirmed Bill
                                    </button>
                                @endif

                                @if($bill->status !== 'draft' && $bill->status !== 'pending' && $bill->status !== 'paid')
                                    <button type="submit" name="status" value="pending" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 text-xs font-bold rounded-lg transition">
                                        Mark as Draft
                                    </button>
                                @endif

                                @if($bill->status !== 'paid')
                                    <button type="submit" name="status" value="paid" class="px-3.5 py-1.5 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-lg transition shadow-2xs">
                                        Mark as Paid
                                    </button>
                                @else
                                    <button type="submit" name="status" value="approved" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 text-xs font-bold rounded-lg transition">
                                        Revert from Paid
                                    </button>
                                @endif
                            </form>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
