<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('vendors.index') }}" class="hover:underline">Vendors</a>
                    <span>/</span>
                    <span class="text-slate-400">Add New</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Add New Vendor
                </h2>
            </div>
            <a href="{{ route('vendors.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Vendors
            </a>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-rose-900 mb-1">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Please fix the following errors:
                    </div>
                    <ul class="list-disc list-inside text-xs font-semibold text-rose-700 pl-2 space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('vendors.store') }}">
                @csrf
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-800">Vendor Information</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Fill in the vendor's contact details and opening balance</p>
                    </div>

                    {{-- Vendor Name --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Vendor Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="e.g. Muhammad Zubair"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('name') ? 'border-rose-400 bg-rose-50' : '' }}">
                        @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="e.g. 0300-1234567"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Address</label>
                        <textarea name="address" rows="3" placeholder="Complete address of the vendor..."
                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('address') }}</textarea>
                        @error('address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Opening Balance --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Opening Balance (PKR)</label>
                        <div class="flex rounded-xl overflow-hidden border border-slate-200 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
                            <span class="inline-flex items-center px-3 bg-slate-100 text-slate-500 text-xs font-bold border-r border-slate-200 select-none whitespace-nowrap">PKR</span>
                            <input type="number" name="opening_balance" value="{{ old('opening_balance', '0') }}" min="0" step="0.01"
                                   class="flex-1 px-3 py-2 text-sm bg-white focus:outline-none">
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400">Initial receivable/payable balance at time of registration</p>
                        @error('opening_balance') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status</label>
                        <select name="status" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                        <a href="{{ route('vendors.index') }}" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                            Save Vendor
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
