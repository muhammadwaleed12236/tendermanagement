<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-purple-600 mb-1">
                    <a href="{{ route('companies.index') }}" class="hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Back to Companies</span>
                    </a>
                    <span>/</span>
                    <span class="text-slate-400">Invoice Designs Gallery</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    Invoice Designs Gallery (2 A4 Pages Per Row)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Har page per 2 A4 invoice designs side-by-side hain. Kisi bhi invoice per <strong>Double Click</strong> karein aur select karein k ye design kis company ko assign karna hai.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('companies.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span>Manage Companies</span>
                </a>
                <a href="{{ route('bills.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Bills List</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Scoped Theme Styles -->
    <style>
        .invoice-sheet-preview {
            width: 800px;
            height: 1131px;
            background: #fff;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }
        @include('bills.partials.invoice_templates_css')
    </style>

    <div class="py-6 bg-slate-100 min-h-screen" x-data="invoiceGallery()">

        <!-- Helper Instructions & Legend Bar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" /></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">
                            💡 Double-Click to Assign: Kisi bhi A4 invoice card per double-click karein company assign karne k liye.
                        </div>
                        <div class="text-[11px] text-slate-500">
                            Assign karne k baad invoice k niche colored circle <span class="text-emerald-600 font-bold">● Green</span> show ho jaye ga.
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 text-xs font-bold">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>● Assigned</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-400 border border-slate-200">
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                        <span>○ Unassigned</span>
                    </span>
                    <span class="text-purple-600 bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-200">
                        Total 7 Patterns
                    </span>
                </div>
            </div>
        </div>

        <!-- Global Toast Notification -->
        <div x-show="toast.visible" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-4"
             class="fixed bottom-6 right-6 z-50 max-w-md bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-3 text-sm"
             style="display: none;">
            <div class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1 font-medium" x-text="toast.message"></div>
            <button @click="toast.visible = false" class="text-slate-400 hover:text-white">&times;</button>
        </div>

        <!-- 2 A4 Pages Per Row Grid (Compact, Seamless, Double-Click Enabled) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-20">

                @foreach($templateOptions as $tplKey => $tplMeta)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-200 overflow-hidden flex flex-col group"
                         :class="isAssigned('{{ $tplKey }}') ? 'ring-2 ring-emerald-500/40 border-emerald-300' : 'hover:border-purple-300'">
                        
                        <!-- Top Mini Header: Pattern Name & Double Click Badge -->
                        <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full shrink-0 shadow-2xs" style="background-color: {{ $tplMeta['color'] }}"></span>
                                <span class="text-xs font-black text-slate-800">
                                    #{{ $loop->iteration }} {{ $tplMeta['name'] }}
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-400 font-semibold group-hover:text-purple-600 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" /></svg>
                                    Double-click to Assign
                                </span>
                            </div>
                        </div>

                        <!-- Scaled A4 Viewport Container (Aspect Ratio 1 : 1.414, No Scrollbar) -->
                        <div class="a4-viewport relative w-full overflow-hidden bg-slate-50 cursor-pointer select-none border-b border-slate-100"
                             style="aspect-ratio: 1 / 1.414;"
                             @dblclick="openAssignModal('{{ $tplKey }}', '{{ addslashes($tplMeta['name']) }}', '{{ $tplMeta['color'] }}')"
                             title="Double-click to assign this design to a company">
                            
                            <!-- Scaled Invoice Sheet -->
                            <div class="a4-scalable origin-top-left absolute top-0 left-0 pointer-events-none"
                                 :style="'width: 800px; height: 1131px; transform: scale(' + scale + ');'">
                                <div class="invoice-sheet-preview">
                                    @include('bills.partials.invoice_template_body', ['activeTemplate' => $tplKey])
                                </div>
                            </div>

                            <!-- Hover Overlay with Quick Buttons -->
                            <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/10 transition-all flex items-center justify-center pointer-events-none">
                                <div class="opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-200 pointer-events-auto flex items-center gap-2">
                                    <button type="button" 
                                            @click.stop="openAssignModal('{{ $tplKey }}', '{{ addslashes($tplMeta['name']) }}', '{{ $tplMeta['color'] }}')"
                                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-xl flex items-center gap-1.5 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        <span>Assign Company</span>
                                    </button>
                                    @if($bill)
                                        <a href="{{ route('bills.invoice-design', [$bill, 'template' => $tplKey]) }}" 
                                           target="_blank"
                                           @click.stop
                                           class="px-3.5 py-2 bg-white/95 hover:bg-white text-slate-800 text-xs font-bold rounded-xl shadow-xl border border-slate-200 flex items-center gap-1 transition">
                                            <span>Full View</span>
                                            <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Status Bar with Colored Circles for Assigned Companies -->
                        <div class="px-4 py-3 bg-white flex items-center justify-between gap-2 mt-auto">
                            <div class="flex items-center gap-2 flex-wrap min-h-[28px]">
                                <template x-if="getAssignedCompanies('{{ $tplKey }}').length > 0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <template x-for="comp in getAssignedCompanies('{{ $tplKey }}')" :key="comp.id">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shadow-2xs"></span>
                                                <span x-text="comp.company_name"></span>
                                            </span>
                                        </template>
                                    </div>
                                </template>
                                
                                <template x-if="getAssignedCompanies('{{ $tplKey }}').length === 0">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-400 border border-slate-200">
                                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                        <span>○ Unassigned</span>
                                    </span>
                                </template>
                            </div>

                            <button type="button" 
                                    @click.stop="openAssignModal('{{ $tplKey }}', '{{ addslashes($tplMeta['name']) }}', '{{ $tplMeta['color'] }}')"
                                    class="text-xs font-bold text-purple-600 hover:text-purple-700 hover:underline shrink-0 flex items-center gap-1">
                                <span>Assign / Change</span>
                                <span>&rarr;</span>
                            </button>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>

        <!-- Double-Click Assignment Modal ("na karun tou meri marzi") -->
        <div x-show="modal.open" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Backdrop (Clicking cancels without doing anything) -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="closeModal()"></div>

            <!-- Modal Dialog Container -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 p-6 space-y-5"
                     @click.stop>
                    
                    <!-- Modal Header -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-sm shrink-0"
                                 :style="'background-color: ' + modal.templateColor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-base text-slate-900" x-text="modal.templateName"></h3>
                                <p class="text-xs text-slate-500">Invoice Pattern Assignment</p>
                            </div>
                        </div>
                        <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold p-1 leading-none">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="space-y-4">
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-600">
                            <div class="font-bold text-slate-700 mb-1.5 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Currently Assigned Companies:</span>
                            </div>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <template x-for="c in getAssignedCompanies(modal.templateKey)" :key="c.id">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span x-text="c.company_name"></span>
                                    </span>
                                </template>
                                <span x-show="getAssignedCompanies(modal.templateKey).length === 0" class="text-slate-400 italic">
                                    No company assigned yet
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                                Ye design kis company ko assign karna hai? *
                            </label>
                            <select x-model="modal.companyId" 
                                    class="w-full rounded-xl border-slate-300 text-sm font-bold text-slate-900 focus:ring-purple-500 focus:border-purple-500 shadow-2xs py-2 px-3">
                                <option value="">-- Choose Company --</option>
                                <template x-for="c in companies" :key="c.id">
                                    <option :value="c.id" x-text="c.company_name + (c.invoice_template === modal.templateKey ? ' (Already this design)' : '')"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Modal Footer ("na karun tou meri marzi" - Cancel button) -->
                    <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                        <button type="button" 
                                @click="closeModal()" 
                                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                            Cancel (Chhor dyn)
                        </button>
                        <button type="button" 
                                @click="submitAssignment()" 
                                :disabled="modal.saving || !modal.companyId"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 text-white text-xs font-bold rounded-xl shadow-md transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span x-text="modal.saving ? 'Saving...' : 'Assign Design'"></span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Dynamic Scaling & Modal State Controller -->
    <script>
        function invoiceGallery() {
            return {
                companies: @json($companies),
                scale: 0.55,
                modal: {
                    open: false,
                    templateKey: '',
                    templateName: '',
                    templateColor: '',
                    companyId: '',
                    saving: false
                },
                toast: {
                    visible: false,
                    message: ''
                },
                init() {
                    this.$nextTick(() => {
                        this.updateScale();
                    });
                    window.addEventListener('resize', () => this.updateScale());
                },
                updateScale() {
                    const firstViewport = document.querySelector('.a4-viewport');
                    if (firstViewport) {
                        const w = firstViewport.clientWidth;
                        if (w > 0) {
                            this.scale = (w / 800);
                        }
                    }
                },
                getAssignedCompanies(tplKey) {
                    return this.companies.filter(c => (c.invoice_template || 'navy_wave') === tplKey);
                },
                isAssigned(tplKey) {
                    return this.getAssignedCompanies(tplKey).length > 0;
                },
                openAssignModal(tplKey, name, color) {
                    this.modal.templateKey = tplKey;
                    this.modal.templateName = name;
                    this.modal.templateColor = color;
                    
                    const assigned = this.getAssignedCompanies(tplKey);
                    this.modal.companyId = assigned.length > 0 ? assigned[0].id : '';
                    this.modal.open = true;
                },
                closeModal() {
                    this.modal.open = false;
                },
                async submitAssignment() {
                    if (!this.modal.companyId) {
                        alert('Please select a company to assign.');
                        return;
                    }
                    this.modal.saving = true;
                    try {
                        const response = await fetch("{{ route('companies.assign-template') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                company_id: this.modal.companyId,
                                invoice_template: this.modal.templateKey
                            })
                        });
                        const data = await response.json();
                        if (response.ok && data.success) {
                            const comp = this.companies.find(c => c.id == this.modal.companyId);
                            if (comp) {
                                comp.invoice_template = this.modal.templateKey;
                            }
                            this.toast.message = `Design successfully assigned to ${comp ? comp.company_name : 'company'}!`;
                            this.toast.visible = true;
                            setTimeout(() => { this.toast.visible = false; }, 4000);
                            this.closeModal();
                        } else {
                            alert(data.message || 'Error assigning template.');
                        }
                    } catch (e) {
                        console.error(e);
                        alert('An error occurred while assigning design.');
                    } finally {
                        this.modal.saving = false;
                    }
                }
            };
        }
    </script>
</x-app-layout>
