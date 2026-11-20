<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-400">Settings</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Companies & Supplier Setup
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Manage different companies to issue bills, sales tax invoices, and cheques (Company Name, Vendor #, NTN, GST, SST).
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('companies.letterheads') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Letterhead Manager</span>
                </a>
                <a href="{{ route('companies.invoice-gallery') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                    <span>Invoice Designs Gallery</span>
                    <span class="bg-rose-800 text-[10px] px-1.5 py-0.5 rounded-full font-extrabold">7 Patterns</span>
                </a>
                <a href="{{ route('bills.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Generate Bill</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen" x-data="{ 
        editModalOpen: false,
        editCompany: { id: null, company_name: '', vendor_no: '', ntn: '', gst: '', sst: '', invoice_template: 'navy_wave', address: '', contact_number: '', email: '', is_default: false },
        openEdit(comp) {
            this.editCompany = Object.assign({}, comp);
            if (!this.editCompany.invoice_template) this.editCompany.invoice_template = 'navy_wave';
            this.editModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Success / Error -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-sm shadow-xs font-semibold">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-800 text-sm shadow-xs font-semibold">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm space-y-1 shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-rose-900">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Please fix the following validation errors:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs font-semibold text-rose-700 pl-2">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 2-Column Grid: Left (Add Company Form) | Right (Companies Directory) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Left Column: Add New Company Card (5 cols) -->
                <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Add New Company</h3>
                                <p class="text-[11px] text-slate-400">Register company details for billing</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded">Setup</span>
                    </div>

                    <form method="POST" action="{{ route('companies.store') }}" class="space-y-4">
                        @csrf

                        <!-- 1. Company Name -->
                        <div>
                            <label for="company_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Company Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required
                                   placeholder="e.g. M/S. COMZON / BuildWell Engineering"
                                   class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        </div>

                        <!-- 2. Vendor No (AGPR) & NTN Number -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="vendor_no" class="block text-xs font-bold text-slate-700 mb-1">Vendor No (AGPR)</label>
                                <input type="text" name="vendor_no" id="vendor_no" value="{{ old('vendor_no') }}"
                                       placeholder="e.g. 30368984"
                                       class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 font-mono">
                            </div>
                            <div>
                                <label for="ntn" class="block text-xs font-bold text-slate-700 mb-1">NTN Number</label>
                                <input type="text" name="ntn" id="ntn" value="{{ old('ntn') }}"
                                       placeholder="e.g. 3437101-0"
                                       class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 font-mono">
                            </div>
                        </div>

                        <!-- 3. GST (STRN) & SST (Sindh Sales Tax) -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="gst" class="block text-xs font-bold text-slate-700 mb-1">GST / STRN Number</label>
                                <input type="text" name="gst" id="gst" value="{{ old('gst') }}"
                                       placeholder="e.g. 3277876289057"
                                       class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 font-mono">
                            </div>
                            <div>
                                <label for="sst" class="block text-xs font-bold text-slate-700 mb-1">SST Number (Sindh Tax)</label>
                                <input type="text" name="sst" id="sst" value="{{ old('sst') }}"
                                       placeholder="e.g. S3437101-2"
                                       class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 font-mono">
                            </div>
                        </div>

                        <!-- 4. Contact Number & Email -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="contact_number" class="block text-xs font-bold text-slate-700 mb-1">Contact Phone</label>
                                <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}"
                                       placeholder="e.g. 0331-2449892"
                                       class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email (Optional)</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                       placeholder="e.g. info@company.com"
                                       class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- 5. Address -->
                        <div>
                            <label for="address" class="block text-xs font-bold text-slate-700 mb-1">Office / Business Address</label>
                            <textarea name="address" id="address" rows="2"
                                      placeholder="e.g. Office # 1/2 Block # 6, PECHS Nursery Karachi"
                                      class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 placeholder-slate-400">{{ old('address') }}</textarea>
                        </div>

                        <!-- 6. Invoice Design Pattern / Template -->
                        <div>
                            <label for="invoice_template" class="block text-xs font-bold text-slate-700 mb-1">Sales Tax Invoice Pattern</label>
                            <select name="invoice_template" id="invoice_template" class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-blue-500 py-2 bg-white">
                                @if(isset($templateOptions))
                                    @foreach($templateOptions as $tKey => $tOpt)
                                        <option value="{{ $tKey }}" {{ old('invoice_template', 'navy_wave') === $tKey ? 'selected' : '' }}>
                                            {{ $tOpt['name'] }} ({{ $tOpt['badge'] }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">Assign one of the 7 commercial invoice design patterns for this company.</p>
                        </div>

                        <!-- 7. Quotation Body Table Design Pattern -->
                        <div>
                            <label for="body_style" class="block text-xs font-bold text-slate-700 mb-1">Quotation Body Table Style</label>
                            <select name="body_style" id="body_style" class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-blue-500 py-2 bg-white">
                                @if(isset($bodyStyleOptions))
                                    @foreach($bodyStyleOptions as $bKey => $bOpt)
                                        <option value="{{ $bKey }}" {{ old('body_style', 'corporate_navy') === $bKey ? 'selected' : '' }}>
                                            {{ $bOpt['name'] }} ({{ $bOpt['badge'] }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">Select unique inner body styling (table & totals box) for this company's quotations.</p>
                        </div>

                        <!-- 8. Default Checkbox -->
                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                            <label for="is_default" class="text-xs font-semibold text-slate-700 select-none cursor-pointer">
                                Set as Primary Default Company for New Bills
                            </label>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Save Company</span>
                        </button>
                    </form>
                </div>

                <!-- Right Column: Companies Table (7 cols) -->
                <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">Configured Companies</h3>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ number_format($totalCount) }} Total
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400">Pick any company when creating bills to auto-fill its NTN, GST, and SST.</p>
                        </div>

                        <!-- Search -->
                        <form method="GET" action="{{ route('companies.index') }}" class="flex items-center gap-2">
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Search company, NTN..."
                                       class="w-44 sm:w-52 text-xs rounded-xl border-slate-300 focus:border-blue-500 pl-8 py-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            @if($search)
                                <a href="{{ route('companies.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold">Clear</a>
                            @endif
                        </form>
                    </div>

                    @if($companies->isEmpty())
                        <div class="p-10 text-center rounded-xl bg-slate-50 border border-dashed border-slate-200">
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            <p class="text-xs font-bold text-slate-600">No companies found</p>
                            <p class="text-[11px] text-slate-400 mt-1">Add your first company using the form on the left.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-slate-100">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                                    <tr>
                                        <th class="p-3 w-10 text-center">#</th>
                                        <th class="p-3">Company Details</th>
                                        <th class="p-3">Vendor / Tax Registrations</th>
                                        <th class="p-3 w-28 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($companies as $idx => $comp)
                                        <tr class="hover:bg-slate-50/60 transition {{ $comp->is_default ? 'bg-blue-50/20' : '' }}">
                                            <td class="p-3 text-center font-bold text-slate-400">
                                                {{ $companies->firstItem() + $idx }}
                                            </td>
                                            <td class="p-3">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="font-bold text-slate-900 text-sm">{{ $comp->company_name }}</span>
                                                    @if($comp->is_default)
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-blue-100 text-blue-700 border border-blue-200">Default</span>
                                                    @endif
                                                </div>
                                                @php
                                                    $tplKey = $comp->invoice_template ?: 'navy_wave';
                                                    $tplInfo = $templateOptions[$tplKey] ?? ($templateOptions['navy_wave'] ?? null);
                                                @endphp
                                                <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                                                    @if($tplInfo)
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">
                                                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $tplInfo['color'] }};"></span>
                                                            <span>Invoice: {{ $tplInfo['badge'] }}</span>
                                                        </span>
                                                    @endif
                                                    @php
                                                        $bodyKey = $comp->body_style ?: 'corporate_navy';
                                                        $bodyInfo = $bodyStyleOptions[$bodyKey] ?? null;
                                                    @endphp
                                                    @if($bodyInfo)
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-200">
                                                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $bodyInfo['color'] }};"></span>
                                                            <span>Body: {{ $bodyInfo['badge'] }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($comp->address)
                                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $comp->address }}</p>
                                                @endif
                                                @if($comp->contact_number)
                                                    <p class="text-[10px] text-slate-400 mt-0.5 font-mono">Tel: {{ $comp->contact_number }}</p>
                                                @endif
                                            </td>
                                            <td class="p-3 font-mono space-y-0.5">
                                                <div class="flex items-center gap-1.5 text-[11px]">
                                                    <span class="text-slate-400">Vendor #:</span>
                                                    <strong class="text-slate-700">{{ $comp->vendor_no ?: '-' }}</strong>
                                                </div>
                                                <div class="flex items-center gap-1.5 text-[11px]">
                                                    <span class="text-slate-400">NTN:</span>
                                                    <strong class="text-slate-800">{{ $comp->ntn ?: '-' }}</strong>
                                                </div>
                                                <div class="flex items-center gap-2 text-[10px] text-slate-500">
                                                    <span>GST: {{ $comp->gst ?: '-' }}</span>
                                                    <span>•</span>
                                                    <span>SST: {{ $comp->sst ?: '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="p-3 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    @if(!$comp->is_default)
                                                        <form method="POST" action="{{ route('companies.set-default', $comp) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="px-2 py-1 text-[10px] font-bold text-blue-600 hover:bg-blue-50 rounded transition" title="Make default">
                                                                Set Default
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <a href="{{ route('companies.letterhead', $comp) }}" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Letterhead & Body Designer">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </a>

                                                    <button type="button" @click="openEdit({{ json_encode($comp) }})" class="p-1 text-slate-400 hover:text-blue-600 transition" title="Edit company">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>

                                                    <form method="POST" action="{{ route('companies.destroy', $comp) }}" onsubmit="return confirm('Delete this company?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Delete company">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                            {{ $companies->links() }}
                        </div>
                    @endif
                </div>

            </div>

        </div>

        <!-- Edit Company Modal -->
        <div x-show="editModalOpen" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-150">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">✎</span>
                        <h4 class="text-sm font-bold text-slate-900">Edit Company Details</h4>
                    </div>
                    <button type="button" @click="editModalOpen = false" class="w-7 h-7 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition">
                        ✕
                    </button>
                </div>

                <form :action="'{{ url('/companies') }}/' + editCompany.id" method="POST" class="p-5 space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Company Name *</label>
                        <input type="text" name="company_name" x-model="editCompany.company_name" required class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Vendor No (AGPR)</label>
                            <input type="text" name="vendor_no" x-model="editCompany.vendor_no" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 font-mono">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">NTN Number</label>
                            <input type="text" name="ntn" x-model="editCompany.ntn" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">GST / STRN</label>
                            <input type="text" name="gst" x-model="editCompany.gst" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 font-mono">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">SST Number</label>
                            <input type="text" name="sst" x-model="editCompany.sst" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Contact Phone</label>
                            <input type="text" name="contact_number" x-model="editCompany.contact_number" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" x-model="editCompany.email" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Address</label>
                        <textarea name="address" x-model="editCompany.address" rows="2" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Invoice Pattern</label>
                            <select name="invoice_template" x-model="editCompany.invoice_template" class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-blue-500 py-2 bg-white">
                                @if(isset($templateOptions))
                                    @foreach($templateOptions as $tKey => $tOpt)
                                        <option value="{{ $tKey }}">
                                            {{ $tOpt['name'] }} ({{ $tOpt['badge'] }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Quotation Body Style</label>
                            <select name="body_style" x-model="editCompany.body_style" class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-blue-500 py-2 bg-white">
                                @if(isset($bodyStyleOptions))
                                    @foreach($bodyStyleOptions as $bKey => $bOpt)
                                        <option value="{{ $bKey }}">
                                            {{ $bOpt['name'] }} ({{ $bOpt['badge'] }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_default" id="edit_is_default" value="1" x-model="editCompany.is_default" class="rounded text-blue-600 focus:ring-blue-500">
                        <label for="edit_is_default" class="font-semibold text-slate-700 select-none cursor-pointer">
                            Primary Default Company
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm transition">Update Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
