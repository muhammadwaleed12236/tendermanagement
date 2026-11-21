<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-400">Catalog & Bill Items</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Products & Bill Items Catalog
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Create item descriptions or import in bulk to quickly pick them when generating bills and invoices.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('bills.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Use in New Bill</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Success / Alert Messages -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-sm shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-800 text-sm shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Grid: Left (Create Single & Import) | Right (Catalog List) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

                <!-- Left Column: Forms (4 cols) -->
                <div class="lg:col-span-4 space-y-4">

                    <!-- Quick Add Single Product Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </span>
                                <div>
                                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Quick Add Product</h3>
                                    <p class="text-[11px] text-slate-400">Head of Account, Desc, Product & Rate</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-0.5 rounded">Single</span>
                        </div>

                        <form method="POST" action="{{ route('products.store') }}" class="space-y-3">
                            @csrf

                            <!-- 1. Head of Account -->
                            <div>
                                <label for="head_of_account" class="block text-[11px] font-bold text-slate-700 mb-1 flex items-center justify-between">
                                    <span class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>Head of Account</span>
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-normal">e.g. 00343 / A03901</span>
                                </label>
                                <input type="text" name="head_of_account" id="head_of_account" value="{{ old('head_of_account') }}"
                                       placeholder="e.g. 00343 or A03901"
                                       class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-1.5 px-2.5 font-mono">
                                @error('head_of_account')
                                    <p class="text-rose-600 text-[10px] mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 2. Head of Description -->
                            <div>
                                <label for="head_description" class="block text-[11px] font-bold text-slate-700 mb-1 flex items-center justify-between">
                                    <span class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        <span>Head of Description</span>
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-normal">Optional</span>
                                </label>
                                <input type="text" name="head_description" id="head_description" value="{{ old('head_description') }}"
                                       placeholder="e.g. FRUIT HEADS OF ACCOUNTS"
                                       class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-1.5 px-2.5 font-medium">
                                @error('head_description')
                                    <p class="text-rose-600 text-[10px] mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 3. Product Description -->
                            <div>
                                <label for="description" class="block text-[11px] font-bold text-slate-700 mb-1">
                                    Product Description <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="2" required
                                          placeholder="e.g. Fresh Apples & Fruit Basket / Cat6 Ethernet Cable"
                                          class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-1.5 px-2.5 placeholder-slate-400">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-rose-600 text-[10px] mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 4. Rate & Unit (Compact 2 cols) -->
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label for="default_price" class="block text-[11px] font-bold text-slate-700 mb-1">Rate (PKR)</label>
                                    <input type="number" step="0.01" min="0" name="default_price" id="default_price" value="{{ old('default_price', 0) }}"
                                           placeholder="0.00"
                                           class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 font-mono text-right font-bold py-1.5 px-2.5">
                                </div>
                                <div>
                                    <label for="unit" class="block text-[11px] font-bold text-slate-700 mb-1">Unit</label>
                                    <select name="unit" id="unit" class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 py-1.5 px-2.5">
                                        <option value="Nos">Nos</option>
                                        <option value="Units">Units</option>
                                        <option value="Pcs">Pcs</option>
                                        <option value="Box">Box</option>
                                        <option value="Pack">Pack</option>
                                        <option value="Kg">Kg</option>
                                        <option value="Meters">Meters</option>
                                        <option value="Set">Set</option>
                                        <option value="LumpSum">LumpSum</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center justify-center gap-1.5 mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Save Product</span>
                            </button>
                        </form>
                    </div>

                    <!-- Import Card (File Upload & Paste Tabs) -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3" x-data="{ tab: 'file' }">
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-xs flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </span>
                                <div>
                                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Bulk Import</h3>
                                    <p class="text-[11px] text-slate-400">CSV/Excel upload ya paste</p>
                                </div>
                            </div>

                            <a href="{{ route('products.sample-csv') }}" class="text-[11px] font-bold text-blue-600 hover:underline flex items-center gap-1" title="Download template">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Sample CSV</span>
                            </a>
                        </div>

                        <!-- Tab buttons -->
                        <div class="grid grid-cols-2 gap-1 p-1 bg-slate-100 rounded-lg text-xs font-semibold">
                            <button type="button" @click="tab = 'file'" :class="tab === 'file' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-900'" class="py-1 rounded-md transition text-center text-[11px]">
                                📁 File Upload
                            </button>
                            <button type="button" @click="tab = 'paste'" :class="tab === 'paste' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-900'" class="py-1 rounded-md transition text-center text-[11px]">
                                📋 Paste Text
                            </button>
                        </div>

                        <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf

                            <!-- Tab 1: File Upload -->
                            <div x-show="tab === 'file'" class="space-y-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Choose File (.csv, .xlsx, .txt)</label>
                                    <input type="file" name="file" accept=".csv,.xlsx,.xls,.txt"
                                           class="w-full text-xs text-slate-600 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-lg cursor-pointer">
                                </div>
                                <p class="text-[10px] text-slate-500 leading-tight">
                                    Cols: <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-700 font-mono">Description</code>, <code class="bg-slate-100 px-1 py-0.5 rounded text-amber-700 font-mono">Head of Account</code>, <code class="bg-slate-100 px-1 py-0.5 rounded text-indigo-700 font-mono">Head of Description</code>, <code class="bg-slate-100 px-1 py-0.5 rounded text-emerald-700 font-mono">Rate</code>.
                                </p>
                            </div>

                            <!-- Tab 2: Paste Text -->
                            <div x-show="tab === 'paste'" style="display: none;" class="space-y-2">
                                <div>
                                    <label for="paste_text" class="block text-[11px] font-bold text-slate-700 mb-1">
                                        Paste from Excel (Desc, Head Account, Head Desc, Unit, Rate)
                                    </label>
                                    <textarea name="paste_text" id="paste_text" rows="4"
                                              placeholder="Fresh Apples&#9;00343&#9;FRUIT HEADS OF ACCOUNTS&#9;Kg&#9;250&#10;Cat6 Cable&#9;A03901&#9;Stationary&#9;Nos&#9;18500"
                                              class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 font-mono placeholder-slate-400"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="default_unit" class="block text-[11px] font-bold text-slate-700 mb-1">Default Unit</label>
                                <select name="default_unit" id="default_unit" class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 py-1 px-2.5">
                                    <option value="Nos">Nos</option>
                                    <option value="Units">Units</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Box">Box</option>
                                    <option value="Pack">Pack</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Meters">Meters</option>
                                    <option value="Set">Set</option>
                                    <option value="LumpSum">LumpSum</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <span>Import Products</span>
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Right Column: Products Catalog Table (8 cols - maximum room) -->
                <div class="lg:col-span-8 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2.5 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">Products Catalog</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ number_format($totalCount) }} Total
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400">Head of Account, Head of Description, Product Description & Rate</p>
                        </div>

                        <!-- Search form -->
                        <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2">
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Search description, head, code..."
                                       class="w-48 sm:w-60 text-xs rounded-xl border-slate-300 focus:border-blue-500 pl-8 py-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            @if($search)
                                <a href="{{ route('products.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold">Clear</a>
                            @endif
                        </form>
                    </div>

                    @if($products->isEmpty())
                        <div class="p-8 text-center rounded-xl bg-slate-50 border border-dashed border-slate-200">
                            <svg class="w-9 h-9 text-slate-300 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            <p class="text-xs font-bold text-slate-600">No products found</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Add your first product description or import a file using the forms on the left.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-slate-100">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200 text-[11px]">
                                    <tr>
                                        <th class="py-2.5 px-2 w-8 text-center">#</th>
                                        <th class="py-2.5 px-2.5 w-32 whitespace-nowrap">Head of Account</th>
                                        <th class="py-2.5 px-2.5 w-48 whitespace-nowrap">Head of Description</th>
                                        <th class="py-2.5 px-3">Product Description</th>
                                        <th class="py-2.5 px-3 w-32 text-right whitespace-nowrap">Rate (PKR)</th>
                                        <th class="py-2.5 px-2 w-16 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    @foreach($products as $idx => $prod)
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <!-- Index -->
                                            <td class="py-2.5 px-2 text-center font-bold text-slate-400 text-[11px]">
                                                {{ $products->firstItem() + $idx }}
                                            </td>

                                            <!-- 1. Head of Account -->
                                            <td class="py-2.5 px-2.5 whitespace-nowrap">
                                                @if($prod->head_of_account)
                                                    <span class="inline-block px-1.5 py-0.5 rounded text-[11px] font-mono font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                        {{ $prod->head_of_account }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-300 text-xs">—</span>
                                                @endif
                                            </td>

                                            <!-- 2. Head of Description -->
                                            <td class="py-2.5 px-2.5">
                                                @if($prod->head_description)
                                                    <span class="text-xs font-semibold text-slate-800 line-clamp-1" title="{{ $prod->head_description }}">
                                                        {{ $prod->head_description }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-300 text-xs">—</span>
                                                @endif
                                            </td>

                                            <!-- 3. Product Description -->
                                            <td class="py-2.5 px-3">
                                                <span class="font-medium text-slate-900 text-xs">
                                                    {{ $prod->description }}
                                                </span>
                                            </td>

                                            <!-- 4. Rate & Unit -->
                                            <td class="py-2.5 px-3 text-right whitespace-nowrap font-mono">
                                                <span class="font-bold text-slate-800 text-xs">
                                                    {{ $prod->default_price > 0 ? number_format($prod->default_price, 2) : '-' }}
                                                </span>
                                                <span class="text-[10px] text-slate-400 font-sans font-medium">/ {{ $prod->unit ?: 'Nos' }}</span>
                                            </td>

                                            <!-- Action -->
                                            <td class="py-2.5 px-2 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button type="button" 
                                                            onclick="openEditProductModal({{ json_encode($prod) }})"
                                                            class="text-slate-400 hover:text-blue-600 transition" 
                                                            title="Edit Product">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('products.destroy', $prod) }}" onsubmit="return confirm('Delete this product item?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-slate-300 hover:text-rose-600 transition" title="Delete product">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-2">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="edit-product-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 hidden">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md p-5 space-y-3">
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </span>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Edit Product</h3>
                </div>
                <button type="button" onclick="closeEditProductModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="edit-product-form" method="POST" action="" class="space-y-3">
                @csrf
                @method('PUT')

                <!-- 1. Head of Account -->
                <div>
                    <label for="edit-head-of-account" class="block text-[11px] font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Head of Account</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-normal">e.g. 00343 / A03901</span>
                    </label>
                    <input type="text" name="head_of_account" id="edit-head-of-account"
                           placeholder="e.g. 00343 or A03901"
                           class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 font-mono py-1.5 px-2.5">
                </div>

                <!-- 2. Head of Description -->
                <div>
                    <label for="edit-head-description" class="block text-[11px] font-bold text-slate-700 mb-1 flex items-center justify-between">
                        <span class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            <span>Head of Description</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-normal">Optional</span>
                    </label>
                    <input type="text" name="head_description" id="edit-head-description"
                           placeholder="e.g. FRUIT HEADS OF ACCOUNTS"
                           class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 font-medium py-1.5 px-2.5">
                </div>

                <!-- 3. Product Description -->
                <div>
                    <label for="edit-description" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Product Description <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" id="edit-description" rows="2" required
                              class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 py-1.5 px-2.5"></textarea>
                </div>

                <!-- 4. Rate & Unit -->
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="edit-default-price" class="block text-[11px] font-bold text-slate-700 mb-1">Rate (PKR)</label>
                        <input type="number" step="0.01" min="0" name="default_price" id="edit-default-price"
                               class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 font-mono text-right font-bold py-1.5 px-2.5">
                    </div>
                    <div>
                        <label for="edit-unit" class="block text-[11px] font-bold text-slate-700 mb-1">Unit</label>
                        <select name="unit" id="edit-unit" class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 py-1.5 px-2.5">
                            <option value="Nos">Nos</option>
                            <option value="Units">Units</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Box">Box</option>
                            <option value="Pack">Pack</option>
                            <option value="Kg">Kg</option>
                            <option value="Meters">Meters</option>
                            <option value="Set">Set</option>
                            <option value="LumpSum">LumpSum</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeEditProductModal()" class="px-3.5 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditProductModal(product) {
            const form = document.getElementById('edit-product-form');
            form.action = `/products/${product.id}`;
            document.getElementById('edit-head-of-account').value = product.head_of_account || '';
            document.getElementById('edit-head-description').value = product.head_description || '';
            document.getElementById('edit-description').value = product.description || '';
            document.getElementById('edit-default-price').value = product.default_price || 0;
            document.getElementById('edit-unit').value = product.unit || 'Nos';
            document.getElementById('edit-product-modal').classList.remove('hidden');
        }

        function closeEditProductModal() {
            document.getElementById('edit-product-modal').classList.add('hidden');
        }
    </script>

        </div>
    </div>
</x-app-layout>
