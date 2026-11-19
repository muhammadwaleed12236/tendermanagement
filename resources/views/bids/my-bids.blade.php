<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('My Submitted Bids') }}
        </h2>
        <p class="text-sm text-slate-500 mt-1">Track all your proposals and evaluation statuses.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="divide-y divide-slate-100">
                    @forelse($bids as $bid)
                        <div class="p-6 hover:bg-slate-50 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded">
                                        {{ $bid->tender->reference_no }}
                                    </span>
                                    <span class="text-xs text-indigo-600 font-semibold">{{ $bid->tender->category }}</span>
                                </div>
                                <h3 class="text-base font-bold text-slate-900">
                                    <a href="{{ route('tenders.show', $bid->tender) }}" class="hover:text-indigo-600">{{ $bid->tender->title }}</a>
                                </h3>
                                <div class="text-xs text-slate-500 flex flex-wrap gap-x-4 gap-y-1 pt-1">
                                    <span>Submitted: <strong>{{ $bid->submitted_at->format('M d, Y h:i A') }}</strong></span>
                                    <span>Quoted Price: <strong class="text-slate-800">PKR {{ number_format($bid->bid_amount, 2) }}</strong></span>
                                    @if($bid->evaluations->isNotEmpty())
                                        <span>Average Evaluation Score: <strong class="text-indigo-600">{{ $bid->averageScore() }}/100</strong></span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-4 shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($bid->status === 'accepted') bg-emerald-50 text-emerald-700
                                    @elseif($bid->status === 'under_review') bg-blue-50 text-blue-700
                                    @elseif($bid->status === 'rejected') bg-rose-50 text-rose-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    ● {{ ucfirst(str_replace('_', ' ', $bid->status)) }}
                                </span>

                                <a href="{{ route('tenders.show', $bid->tender) }}" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition">
                                    View Tender &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-500">
                            <h4 class="font-bold text-slate-700 text-base">No bids submitted yet</h4>
                            <p class="text-sm mt-1 mb-4">You have not submitted proposals for any tenders yet.</p>
                            <a href="{{ route('tenders.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold shadow-sm">
                                Explore Open Tenders
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <div>
                {{ $bids->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
