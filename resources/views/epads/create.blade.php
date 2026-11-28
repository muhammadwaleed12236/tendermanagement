<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('epads.index') }}" class="text-stone-400 hover:text-stone-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <h2 class="font-black text-2xl text-stone-900 tracking-tight">
                        {{ __('e-Pads Procurement Entry') }}
                    </h2>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-rose-100 text-[#881337] border border-rose-200">EPAD 2.0</span>
                </div>
                <p class="text-xs text-stone-500 mt-1 ml-7">Record online bid submissions and evaluation status</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('epads.index') }}" class="px-4 py-2 text-xs font-bold text-stone-700 bg-white border border-stone-200 hover:bg-stone-50 rounded-xl transition shadow-xs">
                    ← Cancel & Return to Directory
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        bidAmount: '{{ old('bid_amount', '') }}',
        get formattedAmount() {
            if (!this.bidAmount || isNaN(this.bidAmount)) return '0.00';
            return Number(this.bidAmount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        selectedStatus: '{{ old('status', 'Accepted / Awarded') }}',
        fileName: '',
        fileSize: '',
        handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) {
                this.fileName = file.name;
                const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
                this.fileSize = `${sizeMb} MB`;
            }
        },
        clearFile() {
            this.fileName = '';
            this.fileSize = '';
            this.$refs.loiFileInput.value = '';
        }
    }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm space-y-1.5 shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-rose-900">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Please correct the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 pl-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Form Card Matching Client Reference Screenshot 02 -->
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-sm overflow-hidden">
                <!-- Card Header (Dark matching Screenshot 02) -->
                <div class="px-6 py-4 bg-[#0f172a] text-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-xs animate-pulse"></span>
                        <div>
                            <h3 class="text-sm font-extrabold tracking-tight">e-Pads Procurement Entry</h3>
                            <p class="text-[11px] text-slate-400 font-normal">Record online bid submissions and evaluation status</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400">All fields marked * are required</span>
                </div>

                <form method="POST" action="{{ route('epads.store') }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <!-- Row 1: Four Fields (Vendor Name, Department Name, Procurement Type, Ref. Number / Bid ID) -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- 1. VENDOR NAME * -->
                        <div>
                            <label for="company_id" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                                VENDOR NAME <span class="text-rose-600">*</span>
                            </label>
                            <select id="company_id" 
                                    name="company_id" 
                                    required 
                                    class="w-full text-xs font-semibold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2.5 bg-white text-stone-800 @error('company_id') border-rose-300 ring-1 ring-rose-300 @enderror">
                                <option value="">-- Select Vendor --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name ?: $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-stone-400 mt-1">Select registered vendor / bidder from list</p>
                        </div>

                        <!-- 2. DEPARTMENT NAME * -->
                        <div>
                            <label for="department_id" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                                DEPARTMENT NAME <span class="text-rose-600">*</span>
                            </label>
                            <select id="department_id" 
                                    name="department_id" 
                                    required 
                                    class="w-full text-xs font-semibold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2.5 bg-white text-stone-800 @error('department_id') border-rose-300 ring-1 ring-rose-300 @enderror">
                                <option value="">-- Select Procuring ...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} {{ $dept->code ? '(' . $dept->code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-stone-400 mt-1">Select the procuring government agency or department</p>
                        </div>

                        <!-- 3. PROCUREMENT TYPE * -->
                        <div>
                            <label for="procurement_type" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                                PROCUREMENT TYPE <span class="text-rose-600">*</span>
                            </label>
                            <select id="procurement_type" 
                                    name="procurement_type" 
                                    required 
                                    class="w-full text-xs font-semibold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2.5 bg-white text-stone-800 @error('procurement_type') border-rose-300 ring-1 ring-rose-300 @enderror">
                                <option value="Open Competitive Bid." {{ old('procurement_type') === 'Open Competitive Bid.' ? 'selected' : '' }}>Open Competitive Bid.</option>
                                <option value="Petty Purchase" {{ old('procurement_type', 'Petty Purchase') === 'Petty Purchase' ? 'selected' : '' }}>Petty Purchase</option>
                                <option value="Request for Quotation" {{ old('procurement_type') === 'Request for Quotation' ? 'selected' : '' }}>Request for Quotation</option>
                            </select>
                            <p class="text-[10px] text-stone-400 mt-1">Select procurement / bidding procedure method</p>
                        </div>

                        <!-- 4. REF. NUMBER / BID ID * -->
                        <div>
                            <label for="ref_no" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                                REF. NUMBER / BID ID <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="ref_no" 
                                       name="ref_no" 
                                       value="{{ old('ref_no') }}" 
                                       required 
                                       placeholder="e.g. EP-2026-9842" 
                                       class="w-full text-xs font-mono font-bold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2.5 pr-8 @error('ref_no') border-rose-300 ring-1 ring-rose-300 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </div>
                            </div>
                            <p class="text-[10px] text-stone-400 mt-1">Enter the e-PADS reference number or bid ID</p>
                        </div>
                    </div>

                    <!-- Row 2: Items / Work Description (Full Width Textarea) -->
                    <div>
                        <label for="items_description" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                            ITEMS / WORK DESCRIPTION <span class="text-rose-600">*</span>
                        </label>
                        <textarea id="items_description" 
                                  name="items_description" 
                                  rows="3" 
                                  required 
                                  placeholder="e.g. Split Unit 2 Tone, T3 Compressor, with Stabilizer, Stand and Installation Charges including Transportation..." 
                                  class="w-full text-xs rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 p-3 leading-relaxed @error('items_description') border-rose-300 ring-1 ring-rose-300 @enderror">{{ old('items_description') }}</textarea>
                        <p class="text-[10px] text-stone-400 mt-1">Provide a detailed description of the items, materials, or scope of work as advertised in the tender notice.</p>
                    </div>

                    <!-- Row 3: Two Fields (Closing Date & Time, Bid Amount) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Left: CLOSING DATE & TIME * -->
                        <div>
                            <label for="closing_date" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                                CLOSING DATE & TIME <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <input type="datetime-local" 
                                       id="closing_date" 
                                       name="closing_date" 
                                       value="{{ old('closing_date', now()->addDays(7)->format('Y-m-d\TH:i')) }}" 
                                       required 
                                       class="w-full text-xs font-semibold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 py-2.5 pr-9 @error('closing_date') border-rose-300 ring-1 ring-rose-300 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </div>
                            <p class="text-[10px] text-stone-400 mt-1">Official submission closing deadline on e-PADS</p>
                        </div>

                        <!-- Right: BID AMOUNT (PKR) * with formatted live preview -->
                        <div>
                            <label for="bid_amount" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                                BID AMOUNT (PKR) <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       id="bid_amount" 
                                       name="bid_amount" 
                                       x-model="bidAmount"
                                       required 
                                       placeholder="e.g. 250,000" 
                                       class="w-full text-xs font-bold rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500 pl-12 pr-28 py-2.5 @error('bid_amount') border-rose-300 ring-1 ring-rose-300 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="font-mono text-xs font-black text-stone-800" x-text="formattedAmount"></span>
                                </div>
                            </div>
                            <p class="text-[10px] text-stone-400 mt-1">Enter total submitted quotation / tender bid amount in PKR</p>
                        </div>
                    </div>

                    <!-- Row 4: BID STATUS Box (Matching Screenshot 02) -->
                    <div class="p-4 rounded-xl bg-stone-50/70 border border-stone-200/80 space-y-2.5">
                        <div>
                            <label class="block text-xs font-bold text-stone-900 uppercase tracking-wider">
                                BID STATUS <span class="text-rose-600">*</span>
                            </label>
                            <p class="text-[11px] text-stone-500">Is this bid accepted or rejected? (Evaluation Status)</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                            <!-- Option 1: Under Evaluation / Pending -->
                            <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2.5 transition text-xs font-bold"
                                   :class="selectedStatus === 'Under Evaluation / Pending' ? 'border-amber-400 bg-amber-50 text-amber-900 ring-1 ring-amber-400' : 'border-stone-200 bg-white text-stone-700 hover:bg-stone-50'">
                                <input type="radio" name="status" value="Under Evaluation / Pending" x-model="selectedStatus" class="w-4 h-4 text-amber-600 focus:ring-amber-500">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Under Evaluation / Pending</span>
                            </label>

                            <!-- Option 2: Accepted / Awarded -->
                            <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2.5 transition text-xs font-bold"
                                   :class="selectedStatus === 'Accepted / Awarded' ? 'border-emerald-500 bg-emerald-50 text-emerald-900 ring-1 ring-emerald-500' : 'border-stone-200 bg-white text-stone-700 hover:bg-stone-50'">
                                <input type="radio" name="status" value="Accepted / Awarded" x-model="selectedStatus" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Accepted / Awarded</span>
                            </label>

                            <!-- Option 3: Rejected / Disqualified -->
                            <label class="cursor-pointer border rounded-xl p-2.5 flex items-center gap-2.5 transition text-xs font-bold"
                                   :class="selectedStatus === 'Rejected / Disqualified' ? 'border-rose-400 bg-rose-50 text-rose-900 ring-1 ring-rose-400' : 'border-stone-200 bg-white text-stone-700 hover:bg-stone-50'">
                                <input type="radio" name="status" value="Rejected / Disqualified" x-model="selectedStatus" class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                <span>Rejected / Disqualified</span>
                            </label>
                        </div>

                        <p class="text-[10px] text-stone-400 pt-0.5 flex items-center gap-1">
                            <span>▸</span>
                            <span>Technical & Financial Evaluation Status (Accepted / Technically Qualified / Rejected)</span>
                        </p>
                    </div>

                    <!-- Row 5: ATTACHMENT: LETTER OF INTENT (LOI) Box (Matching Screenshot 02) -->
                    <div class="p-4 rounded-xl bg-stone-50/70 border border-stone-200/80 space-y-2.5">
                        <div>
                            <label class="block text-xs font-bold text-stone-900 uppercase tracking-wider">
                                ATTACHMENT: LETTER OF INTENT (LOI)
                            </label>
                            <p class="text-[11px] text-stone-500">Upload Letter of intent (LOI) document once bid is accepted/awarded.</p>
                        </div>

                        <!-- Upload Dropzone -->
                        <div class="relative border-2 border-dashed border-stone-300 hover:border-orange-400 rounded-xl p-4 bg-white transition text-center cursor-pointer"
                             @click="$refs.loiFileInput.click()">
                            <input type="file" 
                                   name="letter_of_intent_file" 
                                   x-ref="loiFileInput"
                                   @change="handleFileSelect"
                                   accept=".pdf,.doc,.docx" 
                                   class="hidden">
                            <div class="flex items-center justify-center gap-2 text-stone-600">
                                <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <span class="text-xs font-bold text-stone-800">Upload Letter of intent (PDF / Docs)</span>
                            </div>
                            <p class="text-[11px] text-stone-400 mt-1">Drag and drop file here or Browse (Max 5MB)</p>
                        </div>

                        <!-- Selected File Display -->
                        <template x-if="fileName">
                            <div class="p-2.5 rounded-lg bg-orange-50 border border-orange-200 flex items-center justify-between text-xs font-bold text-orange-950">
                                <div class="flex items-center gap-2 truncate">
                                    <svg class="w-4 h-4 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="truncate" x-text="fileName"></span>
                                    <span class="text-[10px] text-stone-500 font-normal" x-text="`(${fileSize})`"></span>
                                </div>
                                <button type="button" @click.stop="clearFile()" class="text-stone-400 hover:text-rose-600 p-1 font-bold text-sm" title="Remove attachment">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Bottom Action Buttons: Cancel & Return (Left) vs Save Draft & Save Record (Right) -->
                    <div class="pt-4 border-t border-stone-200/80 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <a href="{{ route('epads.index') }}" class="w-full sm:w-auto px-4 py-2.5 text-xs font-bold text-stone-700 bg-white border border-stone-200 hover:bg-stone-50 rounded-xl transition text-center shadow-xs">
                            ← Cancel & Return to Directory
                        </a>

                        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                            <button type="submit" name="action" value="draft" class="px-5 py-2.5 text-xs font-bold text-stone-700 bg-white border border-stone-300 hover:bg-stone-50 rounded-xl transition shadow-xs">
                                Save Draft
                            </button>

                            <button type="submit" name="action" value="record" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-black rounded-xl shadow-md transition">
                                <span>Save Record</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
