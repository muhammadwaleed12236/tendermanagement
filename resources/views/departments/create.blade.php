<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('departments.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Back to Departments</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-slate-500 uppercase">New Department Setup</span>
            </div>
            <h2 class="font-extrabold text-xl text-slate-900 leading-tight">
                {{ __('Create Department / Directorate') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200 shadow-sm">
                <form method="POST" action="{{ route('departments.store') }}" class="space-y-4">
                    @csrf

                    <!-- Row 1: Dept Name (2 cols) + Code (1 col) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Department Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                   placeholder="e.g. Department of Information Technology & Digital Infrastructure" 
                                   class="w-full text-xs sm:text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                            @error('name') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Code / Acronym <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="code" value="{{ old('code') }}" required 
                                   placeholder="e.g. DIT, CWD, HMS" 
                                   class="w-full text-xs sm:text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 uppercase py-2">
                            @error('code') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Row 2: Cost Center Description Bill (2 cols) + City (1 col) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1 flex items-center justify-between">
                                <span>Cost Center Description Bill ("To," Section Name) <span class="text-rose-500">*</span></span>
                                <span class="text-[10px] font-normal text-indigo-600 lowercase">bill ke top-left "to," me print hoga</span>
                            </label>
                            <input type="text" name="cost_center_description" value="{{ old('cost_center_description') }}" 
                                   placeholder="e.g. Office of the XYZ" 
                                   class="w-full text-xs sm:text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                            @error('cost_center_description') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                City / Office Location
                            </label>
                            <input type="text" name="city" value="{{ old('city', 'Karachi.') }}" 
                                   placeholder="e.g. Karachi." 
                                   class="w-full text-xs sm:text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                        </div>
                    </div>

                    <!-- Row 3: Head of Dept + Annual Budget + Status (3 equal cols) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Head of Department / Director
                            </label>
                            <input type="text" name="head_of_department" value="{{ old('head_of_department') }}" 
                                   placeholder="e.g. Engr. Ahmad Nawaz" 
                                   class="w-full text-xs sm:text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                            @error('head_of_department') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Annual Budget Ceiling (PKR) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="budget_allocated" value="{{ old('budget_allocated', '50000000') }}" required 
                                   placeholder="e.g. 50000000" 
                                   class="w-full text-xs sm:text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                            @error('budget_allocated') <span class="text-[11px] text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Operational Status <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" class="w-full text-xs sm:text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive / Dormant</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 4: Scope / Description (compact) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                            Scope & Mandate Description (Optional)
                        </label>
                        <textarea name="description" rows="2" 
                                  placeholder="Briefly define this department's procurement areas, authority, and operations..." 
                                  class="w-full text-xs sm:text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5">{{ old('description') }}</textarea>
                    </div>

                    <!-- Footer Actions -->
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('departments.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                            Save Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
