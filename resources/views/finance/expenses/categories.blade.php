<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('expenses.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Expenses Registry</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Classification</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    {{ __('Expense Categories') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Manage categories for classifying operational, site and office expenses
                </p>
            </div>

            <a href="{{ route('expenses.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                Back to Expenses
            </a>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs font-bold flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column (1 Col): Add Category Form -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="font-black text-sm text-slate-900 uppercase">Create New Category</h3>
                        <p class="text-[11px] text-slate-400">Add custom expense classification</p>
                    </div>

                    <form method="POST" action="{{ route('expenses.categories.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="e.g. Vehicle Repairs & Tyre" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Code / Short Tag</label>
                            <input type="text" name="code" placeholder="e.g. EXP-VEH" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Theme Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="color" value="#6366f1" class="h-9 w-14 rounded border border-slate-300 cursor-pointer">
                                <span class="text-xs text-slate-500">Used for charts & badges</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description</label>
                            <textarea name="description" rows="2" placeholder="Brief explanation of what falls under this category..." class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                            Save Category
                        </button>
                    </form>
                </div>

                <!-- Right Column (2 Cols): Existing Categories List -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">All Active Categories</h3>
                        <span class="text-xs font-bold text-slate-500 font-mono">{{ $categories->count() }} Categories</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200 text-[10px]">
                                    <th class="p-3 text-center">#</th>
                                    <th class="p-3">Category Name</th>
                                    <th class="p-3">Code</th>
                                    <th class="p-3 text-center">Vouchers</th>
                                    <th class="p-3 text-right">Total Spent</th>
                                    <th class="p-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans">
                                @forelse($categories as $index => $cat)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="p-3 text-center font-bold text-slate-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="p-3 font-semibold text-slate-900">
                                            <div class="flex items-center gap-2">
                                                <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $cat->color }}"></span>
                                                <span class="font-bold">{{ $cat->name }}</span>
                                            </div>
                                            @if($cat->description)
                                                <div class="text-[10px] text-slate-400 mt-0.5 ml-5">{{ $cat->description }}</div>
                                            @endif
                                        </td>
                                        <td class="p-3 font-mono font-bold text-slate-600">
                                            {{ $cat->code ?: '-' }}
                                        </td>
                                        <td class="p-3 text-center font-mono font-bold text-slate-700">
                                            {{ $cat->expenses_count }}
                                        </td>
                                        <td class="p-3 text-right font-mono font-black text-slate-900 whitespace-nowrap">
                                            PKR {{ number_format($cat->expenses_sum_amount ?: 0, 2) }}
                                        </td>
                                        <td class="p-3 text-right">
                                            @if($cat->expenses_count === 0)
                                                <form method="POST" action="{{ route('expenses.categories.destroy', $cat) }}" onsubmit="return confirm('Delete category {{ $cat->name }}?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[10px] text-slate-400 font-bold uppercase">In Use</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-6 text-center text-slate-400">
                                            No categories found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
