<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-rose-600 mb-1">
                    <a href="{{ route('companies.index') }}" class="hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Companies</span>
                    </a>
                    <span>/</span>
                    <span class="text-slate-400">Letterhead Manager</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Company Letterhead & Body Manager
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Manage official letterhead graphics (Header & Footer or Full A4 Page) and customize inner document body for all companies.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('companies.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span>Manage Companies</span>
                </a>
                <a href="{{ route('companies.invoice-gallery') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                    <span>Invoice Designs Gallery</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Summary Stats Banner -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xl">
                        {{ $totalCount }}
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Companies</div>
                        <div class="text-sm font-black text-slate-800">Configured in System</div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-black text-xl">
                        {{ $customCount }}
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Custom Letterheads</div>
                        <div class="text-sm font-black text-slate-800">Uploaded Header / Footer</div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl">
                        {{ $totalCount - $customCount }}
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">System Default Layout</div>
                        <div class="text-sm font-black text-slate-800">Standard Typographic Format</div>
                    </div>
                </div>
            </div>

            <!-- Companies Letterhead Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($companies as $comp)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between">
                        
                        <div class="p-6 space-y-4">
                            <!-- Card Header -->
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-black text-lg text-slate-900 tracking-tight">
                                            {{ $comp->company_name }}
                                        </h3>
                                        @if($comp->is_default)
                                            <span class="px-2 py-0.5 text-[10px] font-black bg-blue-100 text-blue-800 rounded-md">PRIMARY</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        Vendor #: <span class="font-mono font-bold">{{ $comp->vendor_no ?: 'N/A' }}</span> | NTN: <span class="font-mono font-bold">{{ $comp->ntn ?: 'N/A' }}</span>
                                    </div>
                                </div>

                                <div>
                                    @if($comp->letterhead_type === 'header_footer')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                                            <span>Header & Footer</span>
                                        </span>
                                    @elseif($comp->letterhead_type === 'full_page')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                            <span>Full Page A4</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                            <span>Built-in Layout</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Preview Thumbnails -->
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 flex items-center gap-4">
                                @if($comp->letterhead_type === 'header_footer' && ($comp->header_image || $comp->footer_image))
                                    <div class="space-y-1.5 flex-1">
                                        @if($comp->header_image)
                                            <div class="h-10 rounded border border-slate-300 bg-white overflow-hidden shadow-2xs">
                                                <img src="{{ $comp->header_image_url }}" class="w-full h-full object-contain" alt="Header Banner">
                                            </div>
                                        @else
                                            <div class="h-10 rounded border border-dashed border-slate-300 flex items-center justify-center text-[10px] text-slate-400">
                                                No Header Image Uploaded
                                            </div>
                                        @endif

                                        @if($comp->footer_image)
                                            <div class="h-8 rounded border border-slate-300 bg-white overflow-hidden shadow-2xs">
                                                <img src="{{ $comp->footer_image_url }}" class="w-full h-full object-contain" alt="Footer Banner">
                                            </div>
                                        @else
                                            <div class="h-8 rounded border border-dashed border-slate-300 flex items-center justify-center text-[10px] text-slate-400">
                                                No Footer Image Uploaded
                                            </div>
                                        @endif
                                    </div>
                                @elseif($comp->letterhead_type === 'full_page' && $comp->full_letterhead_image)
                                    <div class="w-16 h-22 rounded border border-slate-300 bg-white overflow-hidden shrink-0 shadow-2xs">
                                        <img src="{{ $comp->full_letterhead_image_url }}" class="w-full h-full object-cover" alt="Full A4 Letterhead">
                                    </div>
                                    <div class="text-xs text-slate-600">
                                        <strong>Full A4 Sheet Graphic:</strong> {{ basename($comp->full_letterhead_image) }}
                                    </div>
                                @else
                                    <div class="w-full py-4 text-center text-xs text-slate-400">
                                        Using standard typographic letterhead with company NTN, STRN & Vendor #.
                                    </div>
                                @endif
                            </div>

                            <!-- Settings Metadata Pills -->
                            <div class="flex items-center gap-2 flex-wrap text-[11px] text-slate-500">
                                <span class="bg-slate-100 px-2 py-0.5 rounded font-mono">Top Margin: {{ $comp->body_top_margin ?: 150 }}px</span>
                                <span class="bg-slate-100 px-2 py-0.5 rounded font-mono">Bottom Margin: {{ $comp->body_bottom_margin ?: 90 }}px</span>
                                @if($comp->default_subject)
                                    <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded font-bold truncate max-w-xs">
                                        Subject: {{ $comp->default_subject }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500">
                                Print Mode: <strong>{{ $comp->show_on_print ? 'Print Graphics' : 'Pre-printed Stationary' }}</strong>
                            </span>

                            <a href="{{ route('companies.letterhead', $comp) }}" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Customize Letterhead & Body &rarr;</span>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
