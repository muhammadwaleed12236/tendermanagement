<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('purchases.index') }}" class="hover:underline">Purchases</a>
                    <span>/</span>
                    <span class="text-slate-400">Edit {{ $purchase->purchase_number }}</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Purchase: {{ $purchase->purchase_number }}
                </h2>
            </div>
            <a href="{{ route('purchases.show', $purchase) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Purchase
            </a>
        </div>
    </x-slot>

    @php
        $existingItems = $purchase->items->map(function ($it) {
            return [
                'product_id' => $it->product_id ? (string) $it->product_id : '',
                'item_description' => $it->item_description,
                'unit' => $it->unit,
                'quantity' => (float) $it->quantity,
                'unit_price' => (float) $it->unit_price,
                'gst_percent' => (float) $it->gst_percent,
                'sst_percent' => (float) $it->sst_percent,
            ];
        })->toArray();
    @endphp

    <div class="py-6 min-h-screen bg-slate-50"
         x-data="purchaseForm({
             products: {{ Js::from($products) }},
             initialItems: {{ Js::from(old('items', $existingItems)) }}
         })">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-rose-900 mb-1">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Please fix the following validation errors:
                    </div>
                    <ul class="list-disc list-inside text-xs font-semibold text-rose-700 pl-2 space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('purchases.update', $purchase) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- CARD 1: Header Details --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center">1</span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Edit Purchase Order</h3>
                                <p class="text-xs text-slate-400">Update vendor, date or payment status</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Vendor <span class="text-rose-500">*</span></label>
                            <select name="vendor_id" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id', $purchase->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }} {{ $vendor->phone ? '('.$vendor->phone.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Purchase / Bill # <span class="text-rose-500">*</span></label>
                            <input type="text" name="purchase_number" value="{{ old('purchase_number', $purchase->purchase_number) }}" required
                                   class="w-full px-3 py-2 text-sm font-mono border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Purchase Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" required
                                   class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Payment Status <span class="text-rose-500">*</span></label>
                            <select name="payment_status" required class="w-full px-3 py-2 text-sm font-bold border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="unpaid" {{ old('payment_status', $purchase->payment_status) === 'unpaid' ? 'selected' : '' }} class="text-rose-600 font-bold">Unpaid (Add to Vendor Balance)</option>
                                <option value="paid" {{ old('payment_status', $purchase->payment_status) === 'paid' ? 'selected' : '' }} class="text-emerald-600 font-bold">Paid (Settled / Cash)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Purchase Notes / Remarks</label>
                        <input type="text" name="notes" value="{{ old('notes', $purchase->notes) }}" placeholder="Optional invoice remarks..."
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- CARD 2: Line Items --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-xs flex items-center justify-center">2</span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Purchased Products & Items</h3>
                                <p class="text-xs text-slate-400">Modify line items, quantities, rates and optional GST / SST</p>
                            </div>
                        </div>
                        <button type="button" @click="addItem()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Add Product Item
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600">
                                    <th class="text-left px-3 py-2.5 font-bold uppercase w-10">#</th>
                                    <th class="text-left px-3 py-2.5 font-bold uppercase min-w-[200px]">Product / Description <span class="text-rose-500">*</span></th>
                                    <th class="text-left px-3 py-2.5 font-bold uppercase w-24">Unit</th>
                                    <th class="text-left px-3 py-2.5 font-bold uppercase w-24">Qty <span class="text-rose-500">*</span></th>
                                    <th class="text-left px-3 py-2.5 font-bold uppercase w-32">Rate (PKR) <span class="text-rose-500">*</span></th>
                                    <th class="text-left px-3 py-2.5 font-bold uppercase w-24">GST %</th>
                                    <th class="text-left px-3 py-2.5 font-bold uppercase w-24">SST %</th>
                                    <th class="text-right px-3 py-2.5 font-bold uppercase w-36">Total (PKR)</th>
                                    <th class="text-center px-2 py-2.5 font-bold uppercase w-12"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-3 py-2 text-slate-400 font-semibold" x-text="index + 1"></td>
                                        <td class="px-3 py-2 space-y-1">
                                            <select class="w-full text-xs border border-slate-200 rounded-lg py-1 px-2 focus:ring-1 focus:ring-blue-500 bg-white"
                                                    :name="'items['+index+'][product_id]'"
                                                    x-model="item.product_id"
                                                    @change="onProductSelect(index)">
                                                <option value="">-- Choose from Catalog (or Type Below) --</option>
                                                <template x-for="p in products" :key="p.id">
                                                    <option :value="p.id" x-text="p.description + (p.item_code ? ' ['+p.item_code+']' : '')" :selected="String(p.id) === String(item.product_id)"></option>
                                                </template>
                                            </select>
                                            <input type="text"
                                                   :name="'items['+index+'][item_description]'"
                                                   x-model="item.item_description"
                                                   required
                                                   class="w-full text-xs font-semibold border border-slate-200 rounded-lg py-1.5 px-2 focus:ring-1 focus:ring-blue-500">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="text" :name="'items['+index+'][unit]'" x-model="item.unit" class="w-full text-xs border border-slate-200 rounded-lg py-1.5 px-2 focus:ring-1 focus:ring-blue-500">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01" min="0.01" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" required class="w-full text-xs font-semibold border border-slate-200 rounded-lg py-1.5 px-2 focus:ring-1 focus:ring-blue-500 text-right">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01" min="0" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" required class="w-full text-xs font-semibold border border-slate-200 rounded-lg py-1.5 px-2 focus:ring-1 focus:ring-blue-500 text-right">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01" min="0" max="100" :name="'items['+index+'][gst_percent]'" x-model.number="item.gst_percent" placeholder="0" class="w-full text-xs border border-slate-200 rounded-lg py-1.5 px-2 focus:ring-1 focus:ring-blue-500 text-right">
                                            <template x-if="calcGstAmount(item) > 0">
                                                <div class="text-[10px] text-emerald-700 text-right mt-0.5" x-text="'+'+calcGstAmount(item).toFixed(2)"></div>
                                            </template>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" step="0.01" min="0" max="100" :name="'items['+index+'][sst_percent]'" x-model.number="item.sst_percent" placeholder="0" class="w-full text-xs border border-slate-200 rounded-lg py-1.5 px-2 focus:ring-1 focus:ring-blue-500 text-right">
                                            <template x-if="calcSstAmount(item) > 0">
                                                <div class="text-[10px] text-indigo-700 text-right mt-0.5" x-text="'+'+calcSstAmount(item).toFixed(2)"></div>
                                            </template>
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            <span class="font-bold text-slate-900 text-xs" x-text="'PKR ' + calcLineTotal(item).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                        </td>
                                        <td class="px-2 py-2 text-center">
                                            <button type="button" @click="removeItem(index)" :disabled="items.length === 1" class="p-1 text-slate-400 hover:text-rose-600 disabled:opacity-30 disabled:cursor-not-allowed transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" @click="addItem()" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Add Another Product Item</span>
                        </button>
                        <span class="text-xs text-slate-400" x-text="items.length + ' item(s)'"></span>
                    </div>

                    <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200 max-w-md ml-auto space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span>Base Items Subtotal:</span>
                            <span class="font-bold text-slate-800" x-text="'PKR ' + totalSubtotal().toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span>Total GST:</span>
                            <span class="font-bold text-emerald-700" x-text="'PKR ' + totalGst().toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span>Total SST:</span>
                            <span class="font-bold text-indigo-700" x-text="'PKR ' + totalSst().toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                        </div>
                        <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-sm font-black text-slate-900">
                            <span>Grand Total Payable:</span>
                            <span class="text-base text-blue-700 font-extrabold" x-text="'PKR ' + grandTotal().toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('purchases.show', $purchase) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-7 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition shadow-sm">
                        Update Purchase
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function purchaseForm(config) {
            return {
                products: config.products || [],
                items: config.initialItems || [],

                addItem() {
                    this.items.push({
                        product_id: '',
                        item_description: '',
                        unit: 'Nos',
                        quantity: 1,
                        unit_price: 0,
                        gst_percent: 0,
                        sst_percent: 0
                    });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                onProductSelect(index) {
                    const selectedId = this.items[index].product_id;
                    if (!selectedId) return;

                    const product = this.products.find(p => String(p.id) === String(selectedId));
                    if (product) {
                        this.items[index].item_description = product.description;
                        if (product.unit) this.items[index].unit = product.unit;
                        if (parseFloat(product.default_price) > 0) {
                            this.items[index].unit_price = parseFloat(product.default_price);
                        }
                    }
                },

                calcBase(item) {
                    const q = parseFloat(item.quantity) || 0;
                    const p = parseFloat(item.unit_price) || 0;
                    return q * p;
                },

                calcGstAmount(item) {
                    const base = this.calcBase(item);
                    const pct = parseFloat(item.gst_percent) || 0;
                    return pct > 0 ? (base * (pct / 100)) : 0;
                },

                calcSstAmount(item) {
                    const base = this.calcBase(item);
                    const pct = parseFloat(item.sst_percent) || 0;
                    return pct > 0 ? (base * (pct / 100)) : 0;
                },

                calcLineTotal(item) {
                    return this.calcBase(item) + this.calcGstAmount(item) + this.calcSstAmount(item);
                },

                totalSubtotal() {
                    return this.items.reduce((sum, item) => sum + this.calcBase(item), 0);
                },

                totalGst() {
                    return this.items.reduce((sum, item) => sum + this.calcGstAmount(item), 0);
                },

                totalSst() {
                    return this.items.reduce((sum, item) => sum + this.calcSstAmount(item), 0);
                },

                grandTotal() {
                    return this.items.reduce((sum, item) => sum + this.calcLineTotal(item), 0);
                }
            };
        }
    </script>
</x-app-layout>
