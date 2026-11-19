<x-app-layout>
    <div class="py-8 bg-stone-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Page Header Card -->
            <div class="bg-white rounded-2xl border border-stone-200/80 shadow-xs p-6 sm:p-7 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 shadow-xs">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h1 class="text-2xl font-black text-stone-900 tracking-tight">Notification Center</h1>
                            @if($unreadCount > 0)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-500 text-white animate-pulse">
                                    {{ $unreadCount }} Unread
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    All Caught Up
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-stone-500 mt-1">
                            Real-time alerts for Tenders, Stage evaluations, CDR bid security expiries, Invoices & Budget releases
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-amber-800 bg-amber-100 hover:bg-amber-200 transition shadow-2xs">
                                <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Mark All as Read</span>
                            </button>
                        </form>
                    @endif

                    @if($totalCount > 0)
                        <form action="{{ route('notifications.clear-read') }}" method="POST" onsubmit="return confirm('Clear all read notifications?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium text-stone-500 hover:text-rose-600 hover:bg-rose-50 border border-stone-200 hover:border-rose-200 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Clear Read</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white rounded-xl border border-stone-200 p-2.5 flex flex-wrap items-center justify-between gap-2 shadow-2xs">
                <!-- Status Tabs -->
                <div class="flex items-center gap-1 text-xs">
                    <a href="{{ route('notifications.index', ['status' => 'all', 'category' => $category]) }}" 
                       class="px-3.5 py-1.5 rounded-lg font-bold transition {{ $status === 'all' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        All ({{ $totalCount }})
                    </a>
                    <a href="{{ route('notifications.index', ['status' => 'unread', 'category' => $category]) }}" 
                       class="px-3.5 py-1.5 rounded-lg font-bold transition {{ $status === 'unread' ? 'bg-amber-600 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Unread ({{ $unreadCount }})
                    </a>
                    <a href="{{ route('notifications.index', ['status' => 'read', 'category' => $category]) }}" 
                       class="px-3.5 py-1.5 rounded-lg font-bold transition {{ $status === 'read' ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                        Read
                    </a>
                </div>

                <!-- Category Filters -->
                <div class="flex items-center gap-1 text-xs overflow-x-auto">
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider mr-1">Filter:</span>
                    <a href="{{ route('notifications.index', ['status' => $status, 'category' => 'all']) }}" 
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ $category === 'all' ? 'bg-stone-200 text-stone-900 font-bold' : 'text-stone-500 hover:bg-stone-100' }}">
                        All Types
                    </a>
                    <a href="{{ route('notifications.index', ['status' => $status, 'category' => 'tenders']) }}" 
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ $category === 'tenders' ? 'bg-amber-100 text-amber-900 font-bold' : 'text-stone-500 hover:bg-stone-100' }}">
                        📄 Tenders & CDR
                    </a>
                    <a href="{{ route('notifications.index', ['status' => $status, 'category' => 'bills']) }}" 
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ $category === 'bills' ? 'bg-emerald-100 text-emerald-900 font-bold' : 'text-stone-500 hover:bg-stone-100' }}">
                        💰 Bills & Invoices
                    </a>
                    <a href="{{ route('notifications.index', ['status' => $status, 'category' => 'cheques']) }}" 
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ $category === 'cheques' ? 'bg-blue-100 text-blue-900 font-bold' : 'text-stone-500 hover:bg-stone-100' }}">
                        🏦 Cheques
                    </a>
                    <a href="{{ route('notifications.index', ['status' => $status, 'category' => 'alerts']) }}" 
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ $category === 'alerts' ? 'bg-rose-100 text-rose-900 font-bold' : 'text-stone-500 hover:bg-stone-100' }}">
                        ⚠️ Urgent Alerts
                    </a>
                </div>
            </div>

            <!-- Notifications List -->
            @if($notifications->count() > 0)
                <div class="space-y-3">
                    @foreach($notifications as $n)
                        @php
                            $data = $n->data;
                            $isUnread = is_null($n->read_at);
                            $level = $data['level'] ?? 'info';
                            
                            $borderClass = match($level) {
                                'danger' => 'border-l-4 border-l-rose-500 bg-rose-50/30',
                                'warning' => 'border-l-4 border-l-amber-500 bg-amber-50/20',
                                'success' => 'border-l-4 border-l-emerald-500 bg-emerald-50/20',
                                default => 'border-l-4 border-l-blue-500 bg-blue-50/20',
                            };

                            $badgeClass = match($level) {
                                'danger' => 'bg-rose-100 text-rose-800 border-rose-200',
                                'warning' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'success' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                default => 'bg-blue-100 text-blue-800 border-blue-200',
                            };

                            $iconBg = match($level) {
                                'danger' => 'bg-rose-100 text-rose-600',
                                'warning' => 'bg-amber-100 text-amber-600',
                                'success' => 'bg-emerald-100 text-emerald-600',
                                default => 'bg-blue-100 text-blue-600',
                            };
                        @endphp

                        <div class="bg-white rounded-xl border border-stone-200/90 shadow-2xs hover:shadow-xs transition p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 {{ $borderClass }} {{ $isUnread ? 'ring-1 ring-amber-400/50' : 'opacity-90' }}">
                            <div class="flex items-start gap-3.5 flex-1 min-w-0">
                                <!-- Level Icon -->
                                <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0 mt-0.5">
                                    @if($level === 'danger')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    @elseif($level === 'warning')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @elseif($level === 'success')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="text-xs uppercase font-black px-2 py-0.5 rounded-md border {{ $badgeClass }}">
                                            {{ str_replace('_', ' ', $data['type'] ?? 'Alert') }}
                                        </span>
                                        @if($isUnread)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-ping"></span>
                                                New
                                            </span>
                                        @endif
                                        <span class="text-[11px] text-stone-400">
                                            {{ $n->created_at->diffForHumans() }} ({{ $n->created_at->format('d M Y, h:i A') }})
                                        </span>
                                    </div>

                                    <h3 class="text-sm font-bold text-stone-900">
                                        {{ $data['title'] ?? 'System Notification' }}
                                    </h3>
                                    <p class="text-xs text-stone-600 mt-1 leading-relaxed">
                                        {{ $data['message'] ?? '' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Right CTAs -->
                            <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                @if(!empty($data['action_url']))
                                    <a href="{{ route('notifications.read', $n->id) }}" 
                                       class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-stone-900 hover:bg-stone-800 transition shadow-xs">
                                        <span>{{ $data['action_text'] ?? 'View Details' }}</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                @endif

                                @if($isUnread)
                                    <form action="{{ route('notifications.read', $n->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="Mark as read" class="p-1.5 rounded-lg text-stone-400 hover:text-emerald-700 hover:bg-emerald-50 transition border border-stone-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('notifications.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete notification" class="p-1.5 rounded-lg text-stone-400 hover:text-rose-600 hover:bg-rose-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl border border-stone-200 p-12 text-center shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-stone-900">No Notifications Found</h3>
                    <p class="text-xs text-stone-500 mt-1 max-w-md mx-auto">
                        @if($status === 'unread')
                            You have no unread notifications right now. Everything is up to date!
                        @else
                            No notifications match your current filter selection.
                        @endif
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('notifications.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-stone-700 bg-stone-100 hover:bg-stone-200 transition">
                            Reset Filters
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
