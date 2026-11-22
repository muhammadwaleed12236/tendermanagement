<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('vendors.index') }}" class="hover:underline">Vendors</a>
                    <span>/</span>
                    <span class="text-slate-400">Edit</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit: {{ $vendor->name }}
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

            <form method="POST" action="{{ route('vendors.update', $vendor) }}">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-800">Edit Vendor</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Update vendor contact details and balance</p>
                    </div>

                    {{-- Vendor Name --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Vendor Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $vendor->name) }}" required
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errors->has('name') ? 'border-rose-400 bg-rose-50' : '' }}">
                        @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Address</label>
                        <textarea name="address" rows="3"
                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('address', $vendor->address) }}</textarea>
                        @error('address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Opening Balance --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Opening Balance (PKR)</label>
                        <div class="flex rounded-xl overflow-hidden border border-slate-200 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
                            <span class="inline-flex items-center px-3 bg-slate-100 text-slate-500 text-xs font-bold border-r border-slate-200 select-none whitespace-nowrap">PKR</span>
                            <input type="number" name="opening_balance" value="{{ old('opening_balance', $vendor->opening_balance) }}" min="0" step="0.01"
                                   class="flex-1 px-3 py-2 text-sm bg-white focus:outline-none">
                        </div>
                        @error('opening_balance') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status</label>
                        <select name="status" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="active" {{ old('status', $vendor->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $vendor->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <a href="{{ route('vendors.show', $vendor) }}" class="text-xs text-blue-600 hover:underline font-semibold">View Profile</a>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('vendors.index') }}" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition">Cancel</a>
                            <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition shadow-sm">
                                Update Vendor
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
