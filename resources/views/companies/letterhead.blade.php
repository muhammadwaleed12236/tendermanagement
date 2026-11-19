<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-purple-600 mb-1">
                    <a href="{{ route('companies.index') }}" class="hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Companies</span>
                    </a>
                    <span>/</span>
                    <a href="{{ route('companies.letterheads') }}" class="hover:underline">Letterhead Manager</a>
                    <span>/</span>
                    <span class="text-slate-400">{{ $company->company_name }}</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Letterhead & Inner Body Designer: {{ $company->company_name }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Upload your official company letterhead (Header, Footer, or Full Page A4), adjust body margins, and customize inner quotation body texts.
                </p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Company Switcher -->
                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-2xs">
                    <span class="text-xs font-bold text-slate-500">SWITCH COMPANY:</span>
                    <select onchange="window.location.href='/companies/' + this.value + '/letterhead'" 
                            class="text-xs font-bold text-slate-900 border-none bg-transparent p-0 focus:ring-0 cursor-pointer">
                        @foreach($allCompanies as $c)
                            <option value="{{ $c->id }}" {{ $c->id === $company->id ? 'selected' : '' }}>
                                {{ $c->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <a href="{{ route('companies.letterheads') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                    <span>All Letterheads</span>
                </a>

                @if($bill)
                    <a href="{{ route('bills.quotation', [$bill, 1]) }}?company_id={{ $company->id }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-2 px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <span>Test in Quotation View ↗</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <style>
        /* ==============================================================
           7 DISTINCT INNER BODY DESIGN PATTERNS FOR LIVE PREVIEW & PRINT
           ============================================================== */
        .items-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 11.5px;
        }
        .total-box {
            margin-left: auto;
            width: 280px;
            font-size: 11.5px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 10px;
        }

        /* 1. CORPORATE NAVY */
        .body-corporate_navy .items-table {
            border-collapse: separate;
            border-spacing: 0;
            border: 1.5px solid #1e3a8a;
            border-radius: 8px;
            overflow: hidden;
        }
        .body-corporate_navy .items-table th {
            background: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 10px;
            border-right: 1px solid #2563eb;
            border-bottom: 2px solid #172554;
            letter-spacing: 0.5px;
        }
        .body-corporate_navy .items-table th:last-child { border-right: none; }
        .body-corporate_navy .items-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #f1f5f9;
            color: #1e293b;
        }
        .body-corporate_navy .items-table td:last-child { border-right: none; }
        .body-corporate_navy .items-table tr:nth-child(even) td { background: #f8fafc; }
        .body-corporate_navy .items-table tr:last-child td { border-bottom: none; }
        .body-corporate_navy .total-box {
            border: 1.5px solid #1e3a8a;
            border-radius: 8px;
            overflow: hidden;
        }
        .body-corporate_navy .total-row {
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
        }
        .body-corporate_navy .grand-total {
            background: #1e3a8a;
            color: #ffffff;
            font-weight: 900;
            border-bottom: none;
            padding: 7px 10px;
        }
        .body-corporate_navy .grand-total span:last-child { color: #38bdf8 !important; }

        /* 2. CLASSIC MONOCHROME */
        .body-classic_mono .items-table {
            border-collapse: collapse;
            border-top: 2.5px solid #000;
            border-bottom: 2.5px solid #000;
        }
        .body-classic_mono .items-table th {
            background: #ffffff;
            color: #000000;
            font-weight: 900;
            padding: 8px 10px;
            border-bottom: 1.5px solid #000;
            text-transform: uppercase;
        }
        .body-classic_mono .items-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #cbd5e1;
            color: #000000;
            background: #ffffff;
        }
        .body-classic_mono .items-table tr:last-child td { border-bottom: none; }
        .body-classic_mono .total-box {
            border-top: 1.5px solid #000;
            border-radius: 0;
            width: 270px;
        }
        .body-classic_mono .total-row { padding: 4px 0; border-bottom: none; }
        .body-classic_mono .grand-total {
            border-top: 1px solid #000;
            border-bottom: 3.5px double #000;
            font-weight: 900;
            font-size: 13px;
            padding: 6px 0;
            margin-top: 4px;
            color: #000;
        }

        /* 3. MODERN SLATE TECH */
        .body-modern_slate .items-table {
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            overflow: hidden;
        }
        .body-modern_slate .items-table th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 800;
            padding: 8px 10px;
            border-bottom: 1px solid #cbd5e1;
            border-right: 1px solid #e2e8f0;
        }
        .body-modern_slate .items-table th:last-child { border-right: none; }
        .body-modern_slate .items-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #f1f5f9;
            border-right: 1px solid #f8fafc;
            color: #334155;
        }
        .body-modern_slate .items-table td:last-child { border-right: none; }
        .body-modern_slate .items-table tr:nth-child(even) td { background: #f8fafc; }
        .body-modern_slate .items-table tr:last-child td { border-bottom: none; }
        .body-modern_slate .total-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 6px 10px;
            width: 280px;
        }
        .body-modern_slate .total-row { padding: 4px 0; border-bottom: 1px dashed #cbd5e1; }
        .body-modern_slate .grand-total {
            border-bottom: none;
            border-top: 1.5px solid #334155;
            font-weight: 900;
            padding-top: 6px;
            margin-top: 4px;
            color: #0f172a;
        }
        .body-modern_slate .grand-total span:last-child { color: #0284c7 !important; }

        /* 4. WARM CRIMSON */
        .body-warm_crimson .items-table {
            border-collapse: collapse;
            border: 1.5px solid #991b1b;
        }
        .body-warm_crimson .items-table th {
            background: #991b1b;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 10px;
            border: 1px solid #7f1d1d;
        }
        .body-warm_crimson .items-table td {
            padding: 7px 10px;
            border: 1px solid #fecdd3;
            color: #4c0519;
        }
        .body-warm_crimson .items-table tr:nth-child(even) td { background: #fff1f2; }
        .body-warm_crimson .total-box {
            border: 1.5px solid #991b1b;
            border-radius: 6px;
            overflow: hidden;
        }
        .body-warm_crimson .total-row { border-bottom: 1px solid #fecdd3; background: #ffffff; }
        .body-warm_crimson .grand-total {
            background: #991b1b;
            color: #ffffff;
            border-bottom: none;
            padding: 7px 10px;
            font-weight: 900;
        }
        .body-warm_crimson .grand-total span:last-child { color: #fef08a !important; }

        /* 5. FOREST GREEN */
        .body-forest_green .items-table {
            border-collapse: separate;
            border-spacing: 0;
            border: 1.5px solid #065f46;
            border-radius: 8px;
            overflow: hidden;
        }
        .body-forest_green .items-table th {
            background: #065f46;
            color: #ffffff;
            font-weight: 700;
            padding: 8px 10px;
            border-right: 1px solid #047857;
            border-bottom: 1.5px solid #064e3b;
        }
        .body-forest_green .items-table th:last-child { border-right: none; }
        .body-forest_green .items-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #d1fae5;
            border-right: 1px solid #e6f4ea;
            color: #064e3b;
        }
        .body-forest_green .items-table td:last-child { border-right: none; }
        .body-forest_green .items-table tr:nth-child(even) td { background: #f0fdf4; }
        .body-forest_green .items-table tr:last-child td { border-bottom: none; }
        .body-forest_green .total-box {
            border: 1.5px solid #065f46;
            border-radius: 8px;
            overflow: hidden;
        }
        .body-forest_green .total-row { border-bottom: 1px solid #d1fae5; background: #ffffff; }
        .body-forest_green .grand-total {
            background: #ecfdf5;
            color: #065f46;
            border-top: 1.5px solid #065f46;
            border-bottom: none;
            padding: 7px 10px;
            font-weight: 900;
        }
        .body-forest_green .grand-total span:last-child { color: #047857 !important; }

        /* 6. LEDGER GRID */
        .body-ledger_grid .items-table {
            border-collapse: collapse;
            border: 2px solid #334155;
        }
        .body-ledger_grid .items-table th {
            background: #334155;
            color: #f8fafc;
            font-weight: 800;
            padding: 8px 10px;
            border: 1px solid #475569;
        }
        .body-ledger_grid .items-table td {
            padding: 6px 10px;
            border: 1px solid #64748b;
            color: #0f172a;
            background: #ffffff;
        }
        .body-ledger_grid .total-box {
            border: 2px solid #334155;
            border-radius: 0;
            width: 280px;
        }
        .body-ledger_grid .total-row {
            padding: 5px 8px;
            border-bottom: 1px solid #64748b;
            background: #ffffff;
        }
        .body-ledger_grid .grand-total {
            background: #f1f5f9;
            color: #0f172a;
            border-top: 2px solid #334155;
            border-bottom: none;
            font-weight: 900;
            padding: 6px 8px;
        }

        /* 7. MINIMAL DOTTED */
        .body-minimal_dotted .items-table {
            border-collapse: collapse;
        }
        .body-minimal_dotted .items-table th {
            background: transparent;
            color: #475569;
            font-weight: 700;
            padding: 7px 6px;
            border-bottom: 2px dotted #64748b;
            text-transform: uppercase;
        }
        .body-minimal_dotted .items-table td {
            padding: 8px 6px;
            border-bottom: 1px dotted #94a3b8;
            color: #1e293b;
            background: transparent;
        }
        .body-minimal_dotted .items-table tr:last-child td { border-bottom: none; }
        .body-minimal_dotted .total-box {
            border-top: 1.5px dotted #64748b;
            border-radius: 0;
            width: 270px;
        }
        .body-minimal_dotted .total-row { padding: 4px 0; border-bottom: 1px dotted #e2e8f0; }
        .body-minimal_dotted .grand-total {
            border-top: 2px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            font-weight: 800;
            padding: 6px 0;
            margin-top: 4px;
            color: #0f172a;
        }

        /* Integrated Table Footer (tfoot) Styling for all themes */
        .items-table tfoot td { padding: 7px 10px; }
        .body-corporate_navy .items-table tfoot { border-top: 2px solid #1e3a8a; background: #f8fafc; }
        .body-corporate_navy .items-table tfoot .grand-total-row td { background: #1e3a8a; color: #fff; font-weight: 900; }
        .body-corporate_navy .items-table tfoot .grand-total-row td:last-child { color: #38bdf8 !important; }

        .body-classic_mono .items-table tfoot { border-top: 2px solid #000; }
        .body-classic_mono .items-table tfoot td { border: none; color: #000; }
        .body-classic_mono .items-table tfoot .grand-total-row td { border-top: 1px solid #000; border-bottom: 3.5px double #000; font-weight: 900; }

        .body-modern_slate .items-table tfoot { border-top: 1.5px solid #cbd5e1; background: #f8fafc; }
        .body-modern_slate .items-table tfoot .grand-total-row td { border-top: 1.5px solid #334155; color: #0284c7; font-weight: 900; }

        .body-warm_crimson .items-table tfoot { border-top: 2px solid #991b1b; background: #fff1f2; }
        .body-warm_crimson .items-table tfoot .grand-total-row td { background: #991b1b; color: #fff; font-weight: 900; }
        .body-warm_crimson .items-table tfoot .grand-total-row td:last-child { color: #fef08a !important; }

        .body-forest_green .items-table tfoot { border-top: 2px solid #065f46; background: #f0fdf4; }
        .body-forest_green .items-table tfoot .grand-total-row td { background: #ecfdf5; color: #065f46; border-top: 1.5px solid #065f46; font-weight: 900; }

        .body-ledger_grid .items-table tfoot { border-top: 2px solid #334155; }
        .body-ledger_grid .items-table tfoot td { border: 1px solid #64748b; }
        .body-ledger_grid .items-table tfoot .grand-total-row td { background: #f1f5f9; font-weight: 900; border-top: 2px solid #334155; }

        .body-minimal_dotted .items-table tfoot { border-top: 2px dotted #64748b; }
        .body-minimal_dotted .items-table tfoot td { border: none; border-bottom: 1px dotted #e2e8f0; }
        .body-minimal_dotted .items-table tfoot .grand-total-row td { border-top: 2px solid #0f172a; border-bottom: 1px solid #0f172a; font-weight: 900; }
    </style>

    <!-- Flash message -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-sm shadow-xs font-semibold">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="py-6 bg-slate-100 min-h-screen" x-data="letterheadDesigner()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- ==========================================
                     LEFT COLUMN: CONTROLS & UPLOADS (5 Cols)
                     ========================================== -->
                <div class="lg:col-span-5 space-y-6">

                    <form action="{{ route('companies.letterhead.update', $company) }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="space-y-6">
                        @csrf

                        <!-- 1. Letterhead Mode Selector -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                                    <span>1. Letterhead Mode</span>
                                </h3>
                                <span class="text-[11px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md">
                                    A4 Format
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer border-2 rounded-xl p-3 text-center transition flex flex-col items-center justify-between"
                                       :class="form.letterhead_type === 'header_footer' ? 'border-purple-600 bg-purple-50/50 text-purple-900 shadow-2xs' : 'border-slate-200 hover:border-slate-300 text-slate-700'">
                                    <input type="radio" name="letterhead_type" value="header_footer" x-model="form.letterhead_type" class="sr-only">
                                    <svg class="w-5 h-5 mb-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16M4 19h16M4 12h16"/></svg>
                                    <div class="text-[11px] font-extrabold leading-tight">Header & Footer</div>
                                    <div class="text-[9px] text-slate-400 mt-0.5">2 Banners</div>
                                </label>

                                <label class="cursor-pointer border-2 rounded-xl p-3 text-center transition flex flex-col items-center justify-between"
                                       :class="form.letterhead_type === 'full_page' ? 'border-purple-600 bg-purple-50/50 text-purple-900 shadow-2xs' : 'border-slate-200 hover:border-slate-300 text-slate-700'">
                                    <input type="radio" name="letterhead_type" value="full_page" x-model="form.letterhead_type" class="sr-only">
                                    <svg class="w-5 h-5 mb-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <div class="text-[11px] font-extrabold leading-tight">Full A4 Sheet</div>
                                    <div class="text-[9px] text-slate-400 mt-0.5">1 Full Image</div>
                                </label>

                                <label class="cursor-pointer border-2 rounded-xl p-3 text-center transition flex flex-col items-center justify-between"
                                       :class="form.letterhead_type === 'system_default' ? 'border-purple-600 bg-purple-50/50 text-purple-900 shadow-2xs' : 'border-slate-200 hover:border-slate-300 text-slate-700'">
                                    <input type="radio" name="letterhead_type" value="system_default" x-model="form.letterhead_type" class="sr-only">
                                    <svg class="w-5 h-5 mb-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    <div class="text-[11px] font-extrabold leading-tight">Built-in Layout</div>
                                    <div class="text-[9px] text-slate-400 mt-0.5">Classic HTML</div>
                                </label>
                            </div>
                        </div>

                        <!-- 2. Image Upload Sections -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-5">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                <span>2. Letterhead File Uploads</span>
                            </h3>

                            <!-- HEADER IMAGE (When mode == header_footer) -->
                            <div x-show="form.letterhead_type === 'header_footer'" class="space-y-3">
                                <label class="block text-xs font-bold text-slate-700">
                                    Top Header Banner Image (PNG, JPG, SVG)
                                </label>

                                <div class="flex items-center gap-4">
                                    <template x-if="headerPreview">
                                        <div class="relative w-28 h-16 rounded-lg border border-slate-200 overflow-hidden bg-slate-50 shrink-0 shadow-2xs">
                                            <img :src="headerPreview" class="w-full h-full object-contain">
                                        </div>
                                    </template>

                                    <div class="flex-1">
                                        <input type="file" 
                                               name="header_image_file" 
                                               accept="image/*" 
                                               @change="onFileChange($event, 'header')"
                                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                        <p class="text-[10px] text-slate-400 mt-1">Recommended: 1200x200px or full page width with transparent/white background.</p>
                                    </div>
                                </div>

                                @if($company->header_image)
                                    <div class="flex items-center justify-between text-xs text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                                        <span class="truncate">Current: {{ basename($company->header_image) }}</span>
                                        <button type="submit" 
                                                formaction="{{ route('companies.letterhead.remove-image', $company) }}" 
                                                name="image_type" 
                                                value="header" 
                                                class="text-rose-600 hover:text-rose-800 font-bold ml-2">
                                            Remove
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- FOOTER IMAGE (When mode == header_footer) -->
                            <div x-show="form.letterhead_type === 'header_footer'" class="space-y-3 pt-3 border-t border-slate-100">
                                <label class="block text-xs font-bold text-slate-700">
                                    Bottom Footer Banner Image (PNG, JPG, SVG)
                                </label>

                                <div class="flex items-center gap-4">
                                    <template x-if="footerPreview">
                                        <div class="relative w-28 h-16 rounded-lg border border-slate-200 overflow-hidden bg-slate-50 shrink-0 shadow-2xs">
                                            <img :src="footerPreview" class="w-full h-full object-contain">
                                        </div>
                                    </template>

                                    <div class="flex-1">
                                        <input type="file" 
                                               name="footer_image_file" 
                                               accept="image/*" 
                                               @change="onFileChange($event, 'footer')"
                                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                        <p class="text-[10px] text-slate-400 mt-1">Recommended: 1200x120px for branch addresses, contact info, bank accounts.</p>
                                    </div>
                                </div>

                                @if($company->footer_image)
                                    <div class="flex items-center justify-between text-xs text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                                        <span class="truncate">Current: {{ basename($company->footer_image) }}</span>
                                        <button type="submit" 
                                                formaction="{{ route('companies.letterhead.remove-image', $company) }}" 
                                                name="image_type" 
                                                value="footer" 
                                                class="text-rose-600 hover:text-rose-800 font-bold ml-2">
                                            Remove
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- FULL PAGE IMAGE (When mode == full_page) -->
                            <div x-show="form.letterhead_type === 'full_page'" class="space-y-3">
                                <label class="block text-xs font-bold text-slate-700">
                                    Full Page A4 Letterhead Graphic / Scan (PNG, JPG)
                                </label>

                                <div class="flex items-center gap-4">
                                    <template x-if="fullPreview">
                                        <div class="relative w-20 h-28 rounded-lg border border-slate-200 overflow-hidden bg-slate-50 shrink-0 shadow-2xs">
                                            <img :src="fullPreview" class="w-full h-full object-cover">
                                        </div>
                                    </template>

                                    <div class="flex-1">
                                        <input type="file" 
                                               name="full_letterhead_image_file" 
                                               accept="image/*" 
                                               @change="onFileChange($event, 'full_page')"
                                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer">
                                        <p class="text-[10px] text-slate-400 mt-1">Full A4 scan (e.g. 2480x3508px). The inner body will print right inside the margins.</p>
                                    </div>
                                </div>

                                @if($company->full_letterhead_image)
                                    <div class="flex items-center justify-between text-xs text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                                        <span class="truncate">Current: {{ basename($company->full_letterhead_image) }}</span>
                                        <button type="submit" 
                                                formaction="{{ route('companies.letterhead.remove-image', $company) }}" 
                                                name="image_type" 
                                                value="full_page" 
                                                class="text-rose-600 hover:text-rose-800 font-bold ml-2">
                                            Remove
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div x-show="form.letterhead_type === 'system_default'" class="p-3 bg-slate-50 rounded-xl text-xs text-slate-600 border border-slate-200">
                                Uses the clean built-in typographic header and divider borders using {{ $company->company_name }}'s credentials (Vendor #, NTN, STRN, SST).
                            </div>
                        </div>

                        <!-- 3. Margins & Dimensions (Live Sliders) -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-4">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span>3. Dimensions & Margins (Live Alignment)</span>
                            </h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div x-show="form.letterhead_type === 'header_footer'">
                                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                                        <span>Header Height</span>
                                        <span class="text-purple-600 font-mono" x-text="form.header_height + 'px'"></span>
                                    </label>
                                    <input type="range" min="40" max="300" step="5" x-model.number="form.header_height" name="header_height" class="w-full accent-purple-600">
                                </div>

                                <div x-show="form.letterhead_type === 'header_footer'">
                                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                                        <span>Footer Height</span>
                                        <span class="text-purple-600 font-mono" x-text="form.footer_height + 'px'"></span>
                                    </label>
                                    <input type="range" min="20" max="250" step="5" x-model.number="form.footer_height" name="footer_height" class="w-full accent-purple-600">
                                </div>

                                <div>
                                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                                        <span>Body Top Margin</span>
                                        <span class="text-purple-600 font-mono" x-text="form.body_top_margin + 'px'"></span>
                                    </label>
                                    <input type="range" min="30" max="400" step="5" x-model.number="form.body_top_margin" name="body_top_margin" class="w-full accent-purple-600">
                                    <p class="text-[9px] text-slate-400">Prevents body from overlapping header.</p>
                                </div>

                                <div>
                                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                                        <span>Body Bottom Margin</span>
                                        <span class="text-purple-600 font-mono" x-text="form.body_bottom_margin + 'px'"></span>
                                    </label>
                                    <input type="range" min="20" max="300" step="5" x-model.number="form.body_bottom_margin" name="body_bottom_margin" class="w-full accent-purple-600">
                                    <p class="text-[9px] text-slate-400">Prevents body from overlapping footer.</p>
                                </div>

                                <div class="col-span-2">
                                    <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                                        <span>Left & Right Side Margin</span>
                                        <span class="text-purple-600 font-mono" x-text="form.body_side_margin + 'px'"></span>
                                    </label>
                                    <input type="range" min="15" max="100" step="5" x-model.number="form.body_side_margin" name="body_side_margin" class="w-full accent-purple-600">
                                </div>
                            </div>
                        </div>

                        <!-- 4. Table & Body Design Pattern ("sb ki body ki designing change hogi taky alag alag pattren lagy") -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                    <span>4. Body Table & Summary Pattern</span>
                                </h3>
                                <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">
                                    7 Unique Styles
                                </span>
                            </div>
                            <p class="text-xs text-slate-500">
                                Har company k liye alag table aur total summary design select karein. Is se comparative quotations mein har vendor ka quotation mukhtalif aur authentic lagta hai.
                            </p>

                            <div class="space-y-2">
                                @if(isset($bodyStyleOptions))
                                    @foreach($bodyStyleOptions as $key => $opt)
                                        <label class="block cursor-pointer p-3 rounded-xl border-2 transition"
                                               :class="form.body_style === '{{ $key }}' ? 'border-indigo-600 bg-indigo-50/40 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex items-center gap-2.5">
                                                    <input type="radio" name="body_style" value="{{ $key }}" x-model="form.body_style" class="text-indigo-600 focus:ring-indigo-500">
                                                    <span class="w-3.5 h-3.5 rounded-full shrink-0 shadow-xs" style="background-color: {{ $opt['color'] }};"></span>
                                                    <span class="text-xs font-bold text-slate-900">{{ $opt['name'] }}</span>
                                                </div>
                                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md uppercase"
                                                      style="background-color: {{ $opt['color'] }}15; color: {{ $opt['color'] }};">
                                                    {{ $opt['badge'] }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-1 ml-6 leading-relaxed">
                                                {{ $opt['desc'] }}
                                            </p>
                                        </label>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Column Controls ("unit assing karna hai nh wo sb hat mai ho") -->
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5">
                                <div class="text-xs font-bold text-slate-800 flex items-center justify-between">
                                    <span>Table Columns Control:</span>
                                    <span class="text-[10px] text-indigo-600 font-bold bg-indigo-50 px-1.5 py-0.5 rounded">Customizable</span>
                                </div>
                                <div class="grid grid-cols-2 gap-3 pt-1">
                                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="show_unit_column" value="1" x-model="form.show_unit_column" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span>Show Unit Column</span>
                                    </label>
                                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="show_sr_column" value="1" x-model="form.show_sr_column" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span>Show S# Column</span>
                                    </label>
                                </div>
                                <p class="text-[10px] text-slate-400">
                                    Unit uncheck karein to table se "Unit" (Nos, Ream) column hide ho jayega aur Item Description column automatically wide ho jayega.
                                </p>
                            </div>

                            <!-- Totals Box Layout Structure ("layout change ho sakein nichy sb ka total same hai") -->
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5">
                                <div class="text-xs font-bold text-slate-800 flex items-center justify-between">
                                    <span>Totals Summary Box Layout:</span>
                                    <span class="text-[10px] text-indigo-600 font-bold bg-indigo-50 px-1.5 py-0.5 rounded">5 Variations</span>
                                </div>
                                <div class="space-y-1.5">
                                    @if(isset($totalLayoutOptions))
                                        @foreach($totalLayoutOptions as $tKey => $tOpt)
                                            <label class="block cursor-pointer p-2 rounded-lg border text-xs transition"
                                                   :class="form.total_layout === '{{ $tKey }}' ? 'border-indigo-600 bg-white font-bold text-indigo-900 shadow-2xs' : 'border-slate-200 hover:border-slate-300 text-slate-700'">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <input type="radio" name="total_layout" value="{{ $tKey }}" x-model="form.total_layout" class="text-indigo-600 focus:ring-indigo-500">
                                                        <span>{{ $tOpt['name'] }}</span>
                                                    </div>
                                                    <span class="text-[9px] font-mono font-bold text-slate-400">{{ $tOpt['badge'] }}</span>
                                                </div>
                                                <p class="text-[10px] font-normal text-slate-400 ml-5 mt-0.5">{{ $tOpt['desc'] }}</p>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- 5. Inner Body Customization (Subject, Terms, Signatory) -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-4">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                                <span>5. Inner Body Customization</span>
                            </h3>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Default Subject Line
                                </label>
                                <input type="text" 
                                       name="default_subject" 
                                       x-model="form.default_subject" 
                                       placeholder="e.g. Quotation for the Supply of Procurement / Store Items"
                                       class="w-full rounded-xl border-slate-300 text-xs font-bold text-slate-900 py-2 px-3 focus:ring-purple-500 focus:border-purple-500 shadow-2xs">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Default Terms & Conditions
                                </label>
                                <textarea name="default_terms" 
                                          rows="3" 
                                          x-model="form.default_terms" 
                                          placeholder="1. Prices are inclusive of all applicable Government Taxes & 18% GST.&#10;2. Delivery period: 7-10 days."
                                          class="w-full rounded-xl border-slate-300 text-xs text-slate-900 py-2 px-3 focus:ring-purple-500 focus:border-purple-500 shadow-2xs font-mono"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Signatory / Stamp Title
                                </label>
                                <input type="text" 
                                       name="signatory_title" 
                                       x-model="form.signatory_title" 
                                       placeholder="e.g. Authorized Signatory & Stamp / Proprietor"
                                       class="w-full rounded-xl border-slate-300 text-xs font-bold text-slate-900 py-2 px-3 focus:ring-purple-500 focus:border-purple-500 shadow-2xs">
                            </div>

                            <!-- Stationary Print Mode Checkbox -->
                            <div class="pt-2 border-t border-slate-100 flex items-start gap-2.5">
                                <input type="checkbox" 
                                       name="show_on_print" 
                                       id="show_on_print" 
                                       value="1" 
                                       x-model="form.show_on_print"
                                       class="rounded text-purple-600 focus:ring-purple-500 mt-0.5">
                                <label for="show_on_print" class="text-xs text-slate-700 font-semibold cursor-pointer">
                                    <strong>Print Letterhead Images on Paper:</strong> Checked rakhein agar plain white paper ya PDF download karna hai. Uncheck karein agar office k pehle se printed stationary letterhead paper per print nikalna hai (sirf body text print hoga).
                                </label>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="sticky bottom-6 z-20 bg-white/95 backdrop-blur-md p-4 rounded-2xl border border-slate-200 shadow-xl flex items-center justify-between">
                            <div class="text-xs text-slate-500">
                                Changes are saved per company.
                            </div>
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-extrabold text-xs rounded-xl shadow-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Save Letterhead Settings</span>
                            </button>
                        </div>

                    </form>

                </div>


                <!-- ==========================================
                     RIGHT COLUMN: LIVE REAL-TIME A4 PREVIEW (7 Cols)
                     ========================================== -->
                <div class="lg:col-span-7 space-y-4">

                    <!-- Live Preview Top Bar -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-black text-slate-800 uppercase tracking-wider">Live Real-time A4 Preview</span>
                            <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono font-bold">1:1.414 Standard A4</span>
                        </div>

                        <!-- Toggle Preview Paper Mode -->
                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    @click="previewPaperMode = 'digital'"
                                    :class="previewPaperMode === 'digital' ? 'bg-purple-600 text-white font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                    class="px-2.5 py-1 text-xs rounded-lg transition">
                                📄 Digital / Plain Paper
                            </button>
                            <button type="button" 
                                    @click="previewPaperMode = 'stationary'"
                                    :class="previewPaperMode === 'stationary' ? 'bg-purple-600 text-white font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                    class="px-2.5 py-1 text-xs rounded-lg transition">
                                🖨️ Stationary (Body Only)
                            </button>
                        </div>
                    </div>

                    <!-- The Real-time Scaled A4 Sheet -->
                    <div class="w-full bg-slate-200 p-6 rounded-2xl shadow-inner flex justify-center overflow-hidden">
                        
                        <!-- A4 Document Container (800px fixed width, min-height 1131px) -->
                        <div class="bg-white shadow-2xl relative select-none"
                             :class="'body-' + form.body_style"
                             :style="{
                                 width: '780px',
                                 minHeight: '1100px',
                                 backgroundImage: (form.letterhead_type === 'full_page' && fullPreview && previewPaperMode !== 'stationary') ? 'url(' + fullPreview + ')' : 'none',
                                 backgroundSize: '100% 100%',
                                 backgroundRepeat: 'no-repeat',
                                 boxSizing: 'border-box'
                             }">

                            <!-- TOP HEADER IMAGE (Header & Footer mode) -->
                            <div x-show="form.letterhead_type === 'header_footer' && headerPreview && previewPaperMode !== 'stationary'" 
                                 class="absolute top-0 left-0 w-full overflow-hidden"
                                 :style="{ height: form.header_height + 'px' }">
                                <img :src="headerPreview" class="w-full h-full object-contain" style="display: block;">
                            </div>

                            <!-- BUILT-IN SYSTEM HEADER (When system_default mode) -->
                            <div x-show="form.letterhead_type === 'system_default' && previewPaperMode !== 'stationary'"
                                 class="absolute top-0 left-0 w-full px-10 pt-8 pb-4"
                                 style="border-bottom: 3px double #0f172a;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <div>
                                        <div style="font-size: 22px; font-weight: 900; color: #0f172a; text-transform: uppercase;">
                                            {{ $company->company_name }}
                                        </div>
                                        <div style="font-size: 11px; color: #475569; line-height: 1.5; margin-top: 3px;">
                                            <div>{{ $company->address ?: 'Office # 1/2 Block-6, PECHS, Karachi' }}</div>
                                            <div>Phone: {{ $company->contact_number ?: '+92 331 2449892' }} | NTN: {{ $company->ntn ?: '3437101-0' }} | STRN: {{ $company->gst ?: '3277876289057' }}</div>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-size: 18px; font-weight: 900; color: #1e293b; letter-spacing: 1px;">QUOTATION</div>
                                        <div style="font-size: 11px; font-family: monospace; color: #64748b;">Ref: QTN-2026-001</div>
                                        <div style="font-size: 11px; color: #64748b;">Date: {{ now()->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- INNER DOCUMENT BODY (Adjusted with dynamic top, bottom, side margins!) -->
                            <div :style="{
                                     paddingTop: form.body_top_margin + 'px',
                                     paddingBottom: form.body_bottom_margin + 'px',
                                     paddingLeft: form.body_side_margin + 'px',
                                     paddingRight: form.body_side_margin + 'px'
                                 }"
                                 class="relative z-10 text-[12px] text-slate-800 leading-normal">
                                
                                <!-- Meta Grid: Addressed To & Quotation Ref -->
                                <div class="flex justify-between items-start gap-4 mb-5 pb-3 border-b border-slate-200">
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Addressed To:</div>
                                        <div class="font-extrabold text-[13px] text-slate-900">The Directorate of Transit Trade Karachi</div>
                                        <div class="text-slate-500 text-[11px]">Custom House, Karachi, Pakistan</div>
                                    </div>
                                    <div class="text-right text-[11px] text-slate-600 space-y-0.5">
                                        <div><strong>Quotation Ref:</strong> <span class="font-mono">QTN-{{ $bill ? $bill->bill_number : '2026-0045' }}-01</span></div>
                                        <div><strong>Date:</strong> <span>{{ now()->format('d/m/Y') }}</span></div>
                                        <div><strong>Validity:</strong> <span>30 Days</span></div>
                                    </div>
                                </div>

                                <!-- Dynamic Subject Line -->
                                <div class="font-bold text-[13px] text-slate-900 underline mb-4" x-text="'Subject: ' + (form.default_subject || 'Quotation for the Supply of Procurement / Store Items')">
                                </div>

                                <!-- Items Table Preview (Dynamically styled by form.body_style, columns customizable) -->
                                <table class="items-table">
                                    <thead>
                                        <tr>
                                            <th x-show="form.show_sr_column" style="width: 7%; text-align: center;">S#</th>
                                            <th :style="{ width: (form.show_unit_column ? (form.show_sr_column ? '48%' : '55%') : (form.show_sr_column ? '58%' : '65%')) }" style="text-align: left;">Particulars / Description of Items</th>
                                            <th x-show="form.show_unit_column" style="width: 10%; text-align: center;">Unit</th>
                                            <th style="width: 10%; text-align: center;">Qty</th>
                                            <th style="width: 12%; text-align: right;">Unit Rate</th>
                                            <th style="width: 13%; text-align: right;">Total (PKR)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(it, idx) in previewItems" :key="idx">
                                            <tr>
                                                <td x-show="form.show_sr_column" style="text-align: center;" x-text="idx + 1"></td>
                                                <td><strong x-text="it.name"></strong></td>
                                                <td x-show="form.show_unit_column" style="text-align: center;" x-text="it.unit"></td>
                                                <td style="text-align: center; font-family: monospace;" x-text="it.qty"></td>
                                                <td style="text-align: right; font-family: monospace;" x-text="Number(it.rate).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></td>
                                                <td style="text-align: right; font-family: monospace; font-weight: bold;" x-text="Number(it.total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <!-- Layout Option 1: In-Table Accounting Footer (tfoot) -->
                                    <tfoot x-show="activeTotalLayout === 'in_table_footer'">
                                        <tr>
                                            <td :colspan="computedColSpan" style="text-align: right; font-weight: bold; padding: 7px 10px;">SUB TOTAL:</td>
                                            <td style="text-align: right; font-family: monospace; font-weight: bold; padding: 7px 10px;" x-text="'PKR ' + Number(grossTotal).toLocaleString('en-US', {minimumFractionDigits: 2})"></td>
                                        </tr>
                                        <tr>
                                            <td :colspan="computedColSpan" style="text-align: right; padding: 6px 10px;" x-text="'GST (' + gstRate + '%):'"></td>
                                            <td style="text-align: right; font-family: monospace; padding: 6px 10px;" x-text="'PKR ' + Number(gstAmount).toLocaleString('en-US', {minimumFractionDigits: 2})"></td>
                                        </tr>
                                        <tr class="grand-total-row">
                                            <td :colspan="computedColSpan" style="text-align: right; font-weight: 900; font-size: 13.5px; padding: 8px 10px;">GRAND TOTAL:</td>
                                            <td style="text-align: right; font-family: monospace; font-weight: 900; font-size: 13.5px; padding: 8px 10px;" x-text="'PKR ' + Number(grandTotal).toLocaleString('en-US', {minimumFractionDigits: 2})"></td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- Layout Option 2: Right Floating Box Card -->
                                <div x-show="activeTotalLayout === 'card_box'" class="flex justify-end mb-6">
                                    <div class="total-box">
                                        <div class="total-row">
                                            <span>Sub Total:</span>
                                            <span class="font-mono font-bold" x-text="'PKR ' + Number(grossTotal).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                        </div>
                                        <div class="total-row">
                                            <span x-text="'GST (' + gstRate + '%):'"></span>
                                            <span class="font-mono" x-text="'PKR ' + Number(gstAmount).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                        </div>
                                        <div class="total-row grand-total">
                                            <span>Grand Total:</span>
                                            <span class="font-mono" x-text="'PKR ' + Number(grandTotal).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Layout Option 3: Split Two-Column Layout (Amount in Words Left + Totals Right) -->
                                <div x-show="activeTotalLayout === 'split_banner'" class="grid grid-cols-12 gap-4 mb-6 items-stretch">
                                    <div class="col-span-7 bg-slate-50/80 p-3 rounded-xl border border-slate-200 text-[11px] flex flex-col justify-between">
                                        <div>
                                            <div class="text-[9.5px] font-bold text-slate-400 uppercase tracking-wider">Amount Chargeable in Words:</div>
                                            <div class="font-bold text-slate-800 text-[11.5px] mt-0.5">PKR One Hundred Seven Thousand Three Hundred Eighty Only</div>
                                        </div>
                                        <div class="text-[10px] text-slate-500 mt-2 pt-2 border-t border-slate-200 flex items-center justify-between">
                                            <span>Payment Terms: 30 Days</span>
                                            <span class="font-mono">All Taxes Inclusive</span>
                                        </div>
                                    </div>
                                    <div class="col-span-5">
                                        <div class="total-box" style="width: 100%;">
                                            <div class="total-row">
                                                <span>Sub Total:</span>
                                                <span class="font-mono font-bold" x-text="'PKR ' + Number(grossTotal).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                            </div>
                                            <div class="total-row">
                                                <span x-text="'GST (' + gstRate + '%):'"></span>
                                                <span class="font-mono" x-text="'PKR ' + Number(gstAmount).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                            </div>
                                            <div class="total-row grand-total">
                                                <span>Grand Total:</span>
                                                <span class="font-mono" x-text="'PKR ' + Number(grandTotal).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Layout Option 4: Wide Highlight Strip Banner -->
                                <div x-show="activeTotalLayout === 'wide_bar'" class="mb-6 rounded-xl overflow-hidden border border-slate-300 shadow-2xs">
                                    <div class="grid grid-cols-3 text-center text-xs divide-x divide-slate-200 bg-slate-50">
                                        <div class="p-2.5">
                                            <div class="text-[10px] text-slate-500 uppercase font-bold">Sub Total</div>
                                            <div class="font-mono font-extrabold text-slate-800 mt-0.5" x-text="'PKR ' + Number(grossTotal).toLocaleString('en-US', {minimumFractionDigits: 2})"></div>
                                        </div>
                                        <div class="p-2.5">
                                            <div class="text-[10px] text-slate-500 uppercase font-bold" x-text="'Sales Tax GST (' + gstRate + '%)'"></div>
                                            <div class="font-mono font-bold text-slate-700 mt-0.5" x-text="'PKR ' + Number(gstAmount).toLocaleString('en-US', {minimumFractionDigits: 2})"></div>
                                        </div>
                                        <div class="p-2.5 bg-slate-900 text-white flex flex-col justify-center">
                                            <div class="text-[10px] text-slate-300 uppercase font-bold">Grand Total</div>
                                            <div class="font-mono font-black text-amber-300 text-sm mt-0.5" x-text="'PKR ' + Number(grandTotal).toLocaleString('en-US', {minimumFractionDigits: 2})"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dynamic Terms & Conditions -->
                                <div class="border-t border-dashed border-slate-300 pt-3 mb-10 text-[10.5px] text-slate-600 space-y-1">
                                    <div class="font-bold text-slate-800">Terms & Conditions:</div>
                                    <div class="whitespace-pre-line font-mono" x-text="form.default_terms || '1. Prices are inclusive of all applicable Government Taxes & 18% GST.&#10;2. Delivery period: Within 7-10 days of purchase order.&#10;3. Quotation validity: 30 days.'"></div>
                                </div>

                                <!-- Signatures Block -->
                                <div class="flex justify-between items-end pt-8">
                                    <div class="text-center w-48 border-t border-slate-800 pt-2 text-[11px] font-bold text-slate-700">
                                        Prepared By (Sales Dept)
                                    </div>
                                    <div class="text-center w-56 border-t border-slate-800 pt-2 text-[11px] font-bold text-slate-900">
                                        <div>For {{ $company->company_name }}</div>
                                        <div class="text-[10px] text-slate-500 font-normal" x-text="form.signatory_title || 'Authorized Signatory & Stamp'"></div>
                                    </div>
                                </div>

                            </div>

                            <!-- BOTTOM FOOTER IMAGE (Header & Footer mode) -->
                            <div x-show="form.letterhead_type === 'header_footer' && footerPreview && previewPaperMode !== 'stationary'" 
                                 class="absolute bottom-0 left-0 w-full overflow-hidden"
                                 :style="{ height: form.footer_height + 'px' }">
                                <img :src="footerPreview" class="w-full h-full object-contain" style="display: block;">
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Alpine.js Live Interactive State Controller -->
    <script>
        function letterheadDesigner() {
            return {
                previewPaperMode: 'digital',
                form: {
                    letterhead_type: '{{ $company->letterhead_type ?: "system_default" }}',
                    body_style: '{{ $company->body_style ?: "corporate_navy" }}',
                    total_layout: '{{ $company->total_layout ?: "auto" }}',
                    show_unit_column: {{ $company->show_unit_column ? 'true' : 'false' }},
                    show_sr_column: {{ $company->show_sr_column ? 'true' : 'false' }},
                    header_height: {{ $company->header_height ?: 140 }},
                    footer_height: {{ $company->footer_height ?: 80 }},
                    body_top_margin: {{ $company->body_top_margin ?: 150 }},
                    body_bottom_margin: {{ $company->body_bottom_margin ?: 90 }},
                    body_side_margin: {{ $company->body_side_margin ?: 45 }},
                    show_on_print: {{ $company->show_on_print ? 'true' : 'false' }},
                    default_subject: '{{ addslashes($company->default_subject ?: "") }}',
                    default_terms: `{!! addslashes($company->default_terms ?: "") !!}`,
                    signatory_title: '{{ addslashes($company->signatory_title ?: "") }}'
                },
                headerPreview: '{{ $company->header_image_url }}',
                footerPreview: '{{ $company->footer_image_url }}',
                fullPreview: '{{ $company->full_letterhead_image_url }}',

                previewItems: @json($previewItems),
                grossTotal: {{ $previewGross }},
                gstRate: {{ $gstRate }},
                gstAmount: {{ $previewGst }},
                grandTotal: {{ $previewGrandTotal }},

                get activeTotalLayout() {
                    if (this.form.total_layout && this.form.total_layout !== 'auto') {
                        return this.form.total_layout;
                    }
                    if (this.form.body_style === 'classic_mono' || this.form.body_style === 'ledger_grid') {
                        return 'in_table_footer';
                    }
                    if (this.form.body_style === 'modern_slate') {
                        return 'split_banner';
                    }
                    if (this.form.body_style === 'warm_crimson') {
                        return 'wide_bar';
                    }
                    return 'card_box';
                },

                get computedColSpan() {
                    let span = 2; // Description + Qty
                    if (this.form.show_sr_column) span++;
                    if (this.form.show_unit_column) span++;
                    span++; // Unit rate
                    return span;
                },

                onFileChange(event, type) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (type === 'header') {
                            this.headerPreview = e.target.result;
                        } else if (type === 'footer') {
                            this.footerPreview = e.target.result;
                        } else if (type === 'full_page') {
                            this.fullPreview = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            };
        }
    </script>
</x-app-layout>
