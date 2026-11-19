<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    {{ __('Departments & Budget Centers') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage government/organizational departments, budget ceilings, and project allocations.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('releases.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-200 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Budget Releases Sheet
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('departments.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                        + Add New Department
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($departments as $dept)
                    @php
                        $spent = $dept->totalSpent();
                        $budget = $dept->budget_allocated > 0 ? $dept->budget_allocated : 1;
                        $pct = min(100, round(($spent / $budget) * 100, 1));
                    @endphp
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:border-slate-300 transition flex flex-col justify-between space-y-6">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs font-bold bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded">
                                    {{ $dept->code }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ $dept->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    ● {{ ucfirst($dept->status) }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-slate-900 mt-3">
                                <a href="{{ route('departments.show', $dept) }}" class="hover:text-indigo-600">
                                    {{ $dept->name }}
                                </a>
                            </h3>

                            <div class="text-xs text-slate-500 mt-1">
                                Head: <strong class="text-slate-700">{{ $dept->head_of_department ?: 'Unassigned' }}</strong>
                            </div>

                            <p class="text-xs text-slate-600 mt-2 line-clamp-2">
                                {{ $dept->description ?: 'No operational description provided.' }}
                            </p>
                        </div>

                        <!-- Budget Utilization Bar -->
                        <div class="space-y-2 pt-4 border-t border-slate-100">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium">Budget Ceiling:</span>
                                <span class="font-bold text-slate-900">PKR {{ number_format($dept->budget_allocated, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium">Disbursed (Bills):</span>
                                <span class="font-bold text-emerald-600">PKR {{ number_format($spent, 2) }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full @if($pct > 80) bg-rose-500 @elseif($pct > 50) bg-amber-500 @else bg-indigo-600 @endif" style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="flex justify-between text-[11px] text-slate-400">
                                <span>Remaining: PKR {{ number_format($dept->remainingBudget(), 2) }}</span>
                                <span>{{ $dept->bills->count() }} Invoices</span>
                            </div>

                            <div class="flex items-center justify-between pt-3">
                                <a href="{{ route('departments.show', $dept) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                    View Details & Bills &rarr;
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('departments.edit', $dept) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                                        Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
