<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-400">Vendors</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Vendors Directory
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage registered vendors – contact info, address, and opening balances.</p>
            </div>
            <a href="{{ route('vendors.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add New Vendor
            </a>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-sm shadow-xs font-semibold">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-800 text-sm shadow-xs font-semibold">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                    <div class="text-xs text-slate-500 font-semibold mb-1">Total Vendors</div>
                    <div class="text-2xl font-black text-slate-900">{{ number_format($totalVendors) }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                    <div class="text-xs text-slate-500 font-semibold mb-1">Active</div>
                    <div class="text-2xl font-black text-emerald-600">{{ number_format($activeVendors) }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                    <div class="text-xs text-slate-500 font-semibold mb-1">Total Opening Balance</div>
                    <div class="text-lg font-black text-blue-700">PKR {{ number_format($totalOpeningBalance, 2) }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                    <div class="text-xs text-slate-500 font-semibold mb-1">Total Current Balance</div>
                    <div class="text-lg font-black text-indigo-700">PKR {{ number_format($totalCurrentBalance, 2) }}</div>
                </div>
            </div>

            {{-- Search & Filter --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
                <form method="GET" action="{{ route('vendors.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, phone, address..."
                               class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <select name="status" class="px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition">Search</button>
                    @if($search || $status)
                        <a href="{{ route('vendors.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition text-center">Clear</a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                @if($vendors->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="text-left px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">#</th>
                                    <th class="text-left px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Vendor Name</th>
                                    <th class="text-left px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Phone</th>
                                    <th class="text-left px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Address</th>
                                    <th class="text-right px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Opening Balance</th>
                                    <th class="text-right px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Current Balance</th>
                                    <th class="text-center px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Status</th>
                                    <th class="text-center px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($vendors as $vendor)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-4 py-3 text-xs text-slate-400 font-semibold">{{ $vendors->firstItem() + $loop->index }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-slate-900 text-sm">{{ $vendor->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-700">{{ $vendor->phone ?? '—' }}</td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-slate-600 max-w-xs truncate">{{ $vendor->address ?? '—' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="text-sm font-semibold text-blue-700">PKR {{ number_format((float)$vendor->opening_balance, 2) }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="text-sm font-black text-indigo-700">PKR {{ number_format((float)($vendor->current_balance ?? $vendor->opening_balance), 2) }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($vendor->status === 'active')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('vendors.show', $vendor) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                <a href="{{ route('vendors.edit', $vendor) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                                @if(Auth::user()->isAdmin())
                                                    <form method="POST" action="{{ route('vendors.destroy', $vendor) }}"
                                                          onsubmit="return confirm('Delete vendor \'{{ addslashes($vendor->name) }}\'? This cannot be undone.')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($vendors->hasPages())
                        <div class="px-4 py-3 border-t border-slate-100">{{ $vendors->links() }}</div>
                    @endif
                @else
                    <div class="py-16 text-center">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-sm font-bold text-slate-500">No vendors found</p>
                        <p class="text-xs text-slate-400 mt-1">
                            @if($search || $status) Try different search terms. @else Add your first vendor to get started. @endif
                        </p>
                        @if(!$search && !$status)
                            <a href="{{ route('vendors.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                Add First Vendor
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
