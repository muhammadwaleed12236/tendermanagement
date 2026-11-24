<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('epads.index') }}" class="text-slate-400 hover:text-slate-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                        {{ __('+ Create ePads Record') }}
                    </h2>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-rose-100 text-[#881337] border border-rose-200">EPAD 2.0</span>
                </div>
                <p class="text-sm text-slate-500 mt-0.5 ml-7">Publish and track digital e-procurement contracts and submitted quotation bids.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('epads.index') }}" class="px-4 py-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition shadow-2xs">
                    Cancel & Return
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ bidAmount: '{{ old('bid_amount', '') }}', formattedAmount: '' }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Validation Errors Alert -->
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

            <!-- Workflow Guidance Box -->
            <div class="p-4 rounded-2xl bg-gradient-to-r from-rose-50/90 via-amber-50/60 to-blue-50/70 border border-rose-200 flex items-start gap-3.5 shadow-2xs">
                <div class="w-9 h-9 rounded-xl bg-rose-700 text-white flex items-center justify-center shrink-0 shadow-xs font-black text-sm">
                    ℹ️
                </div>
                <div class="text-xs text-slate-700 space-y-1">
                    <div class="font-extrabold text-rose-950 text-sm">Ref # Workflow Notice</div>
                    <p class="leading-relaxed">
                        The <strong>Ref #</strong> is not automatically generated at initial creation. It is manually entered after the bid has been submitted on the ePads portal. You can leave the Ref # blank now and update it anytime from the listing table once submitted.
                    </p>
                </div>
            </div>

            <!-- Creation Form Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <h3 class="text-sm font-extrabold tracking-tight">ePads Procurement Details</h3>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400">All fields marked * are required</span>
                </div>

                <form method="POST" action="{{ route('epads.store') }}" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <!-- 1. Items / Work Description -->
                    <div>
                        <label for="items_description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Items / Work Description <span class="text-rose-600">*</span>
                        </label>
                        <textarea id="items_description" 
                                  name="items_description" 
                                  rows="3" 
                                  required 
                                  placeholder="e.g. Procurement of Misc. Electric Items, Cables, LED Lights, or PR-925 Filters for Special Vehicles..." 
                                  class="w-full text-sm rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 p-3 leading-relaxed @error('items_description') border-rose-300 ring-1 ring-rose-300 @enderror">{{ old('items_description') }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Provide a clear description of the items, materials, or scope of work.</p>
                    </div>

                    <!-- Row 2: Department & Procurement Type -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- 2. Department Name -->
                        <div>
                            <label for="department_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Department Name <span class="text-rose-600">*</span>
                            </label>
                            <select id="department_id" 
                                    name="department_id" 
                                    required 
                                    class="w-full text-sm font-semibold rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 py-2.5 bg-white text-slate-800 @error('department_id') border-rose-300 ring-1 ring-rose-300 @enderror">
                                <option value="">-- Select Procuring Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} {{ $dept->code ? '(' . $dept->code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Select the procuring government agency or department.</p>
                        </div>

                        <!-- 3. Procurement Type (Exactly Petty Purchase or Request for Quotation) -->
                        <div>
                            <label for="procurement_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Procurement Type <span class="text-rose-600">*</span>
                            </label>
                            <select id="procurement_type" 
                                    name="procurement_type" 
                                    required 
                                    class="w-full text-sm font-semibold rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 py-2.5 bg-white text-slate-800 @error('procurement_type') border-rose-300 ring-1 ring-rose-300 @enderror">
                                <option value="">-- Select Procurement Type --</option>
                                <option value="Petty Purchase" {{ old('procurement_type', 'Petty Purchase') === 'Petty Purchase' ? 'selected' : '' }}>
                                    Petty Purchase
                                </option>
                                <option value="Request for Quotation" {{ old('procurement_type') === 'Request for Quotation' ? 'selected' : '' }}>
                                    Request for Quotation
                                </option>
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Select either Petty Purchase or Request for Quotation.</p>
                        </div>
                    </div>

                    <!-- Row 3: Closing Date & Time + Bid Amount -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- 4. Closing Date & Time -->
                        <div>
                            <label for="closing_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Closing Date & Time <span class="text-rose-600">*</span>
                            </label>
                            <input type="datetime-local" 
                                   id="closing_date" 
                                   name="closing_date" 
                                   value="{{ old('closing_date', now()->addDays(7)->format('Y-m-d\TH:i')) }}" 
                                   required 
                                   class="w-full text-sm font-semibold rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 py-2.5 @error('closing_date') border-rose-300 ring-1 ring-rose-300 @enderror">
                            <p class="text-[11px] text-slate-400 mt-1">Official submission closing deadline for this procurement.</p>
                        </div>

                        <!-- 5. Bid Amount -->
                        <div>
                            <label for="bid_amount" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Bid Amount (PKR) <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <span class="text-xs font-black text-rose-700">PKR</span>
                                </div>
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       id="bid_amount" 
                                       name="bid_amount" 
                                       value="{{ old('bid_amount') }}" 
                                       required 
                                       placeholder="e.g. 250000" 
                                       class="w-full text-sm font-extrabold rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 pl-14 py-2.5 @error('bid_amount') border-rose-300 ring-1 ring-rose-300 @enderror">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Enter total submitted quotation / tender bid amount.</p>
                        </div>
                    </div>

                    <!-- Row 4: Optional Ref # & Status Workflow -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Ref # (Manual entry after bid submission) -->
                        <div>
                            <label for="ref_no" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Ref # (Optional at Creation)
                            </label>
                            <input type="text" 
                                   id="ref_no" 
                                   name="ref_no" 
                                   value="{{ old('ref_no') }}" 
                                   placeholder="e.g. P210075 (Enter after bid submission)" 
                                   class="w-full font-mono text-xs font-bold rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 py-2">
                            <span class="text-[10px] text-slate-500 mt-1 block">Leave blank if not yet submitted; add anytime after submission.</span>
                        </div>

                        <!-- Status Workflow (Accepted: Green, Rejected: Red) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Status Workflow <span class="text-rose-600">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2 pt-0.5">
                                <label class="cursor-pointer border-2 p-2 rounded-xl flex items-center gap-2 bg-white transition hover:bg-emerald-50/50 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                                    <input type="radio" name="status" value="Accepted" class="text-emerald-600 focus:ring-emerald-500" {{ old('status', 'Accepted') === 'Accepted' ? 'checked' : '' }}>
                                    <span class="text-xs font-black text-emerald-700">● Accepted</span>
                                </label>

                                <label class="cursor-pointer border-2 p-2 rounded-xl flex items-center gap-2 bg-white transition hover:bg-rose-50/50 has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50">
                                    <input type="radio" name="status" value="Rejected" class="text-rose-600 focus:ring-rose-500" {{ old('status') === 'Rejected' ? 'checked' : '' }}>
                                    <span class="text-xs font-black text-rose-700">● Rejected</span>
                                </label>
                            </div>
                            <span class="text-[10px] text-slate-500 mt-1 block">Accepted = Green indicator, Rejected = Red indicator.</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <a href="{{ route('epads.index') }}" class="px-4 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                            Cancel & Return to Directory
                        </a>

                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-rose-700 hover:bg-rose-800 text-white text-xs font-extrabold rounded-xl shadow-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save & Create ePads Record</span>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
