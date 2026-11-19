<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Accountability & Compliance</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-semibold text-purple-600">Audit Trail</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2.5">
                    <span>{{ __('Activity Log & Audit Trail') }}</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                        Kis ne konsa kaam kiya
                    </span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Comprehensive chronological record tracking every user action, status modification, record deletion, and authentication event.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="window.print()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Print Audit Sheet</span>
                </button>
                <a href="{{ route('users.index') }}" class="px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-900 border border-indigo-200 text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Users & Permissions</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- 4 Metrics KPI Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 no-print">
                <!-- 1. Total Activities -->
                <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Actions Logged</div>
                        <div class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ number_format($totalCount) }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Historical audit entries</div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center text-purple-600 font-bold">
                        📋
                    </div>
                </div>

                <!-- 2. Today's Activities -->
                <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Today's Activities</div>
                        <div class="text-2xl font-black text-emerald-800 mt-1 font-mono">{{ number_format($todayCount) }}</div>
                        <div class="text-[10px] text-emerald-600 mt-0.5">Actions performed today</div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 font-bold">
                        ⚡
                    </div>
                </div>

                <!-- 3. Active Users -->
                <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Active Operators</div>
                        <div class="text-2xl font-black text-blue-800 mt-1 font-mono">{{ $uniqueUsersCount }}</div>
                        <div class="text-[10px] text-blue-600 mt-0.5">Team members recorded</div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 font-bold">
                        👥
                    </div>
                </div>

                <!-- 4. Most Active Module -->
                <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-indigo-700 uppercase tracking-wider">Primary Work Area</div>
                        <div class="text-xl font-black text-indigo-900 mt-1 truncate max-w-[150px]">{{ $topModule }}</div>
                        <div class="text-[10px] text-indigo-600 mt-0.5">Highest activity volume</div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-600 font-bold">
                        🎯
                    </div>
                </div>
            </div>

            <!-- Activity Logs Registry Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                
                <!-- Filter Bar -->
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 no-print">
                    <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5 items-center">
                        
                        <!-- Search Box -->
                        <div class="lg:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-0.5">Search Detail / IP</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description, user, IP..." class="w-full text-xs rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- User Filter -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-0.5">Filter By User</label>
                            <select name="user_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-300 py-1.5 font-semibold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Users</option>
                                @foreach($usersList as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->role }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Module Filter -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-0.5">Filter By Module</label>
                            <select name="module" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-300 py-1.5 font-semibold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Modules</option>
                                @foreach($modulesList as $mod)
                                    <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Filter -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-0.5">Filter By Action</label>
                            <select name="action" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-300 py-1.5 font-semibold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Actions</option>
                                @foreach($actionsList as $act)
                                    <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit & Reset -->
                        <div class="flex items-end gap-1.5 pt-4">
                            <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-lg transition">
                                Apply
                            </button>
                            @if(request('search') || request('user_id') || request('module') || request('action') || request('date_from') || request('date_to'))
                                <a href="{{ route('activity-logs.index') }}" class="px-2.5 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Printable Report Header -->
                <div class="p-6 border-b border-slate-200 hidden print:block bg-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-black uppercase text-slate-900">
                                {{ Auth::user()->company_name ?: 'ProWave Technologies Management' }}
                            </h2>
                            <h3 class="text-sm font-bold text-slate-600 mt-0.5">
                                Executive Activity Audit Trail & Compliance Report
                            </h3>
                        </div>
                        <div class="text-right text-xs font-mono text-slate-500">
                            <div>Generated: {{ now()->format('d M, Y h:i A') }}</div>
                            <div>Auditor: {{ Auth::user()->name }} ({{ Auth::user()->role }})</div>
                        </div>
                    </div>
                </div>

                <!-- Activity Log Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10.5px]">
                            <tr>
                                <th class="p-3 text-center w-10">#</th>
                                <th class="p-3 w-40 whitespace-nowrap">Timestamp</th>
                                <th class="p-3 w-56">User / Actor</th>
                                <th class="p-3 w-32">Action</th>
                                <th class="p-3 w-36">Module</th>
                                <th class="p-3">Detailed Description</th>
                                <th class="p-3 w-36">IP Address & Device</th>
                                <th class="p-3 text-center w-16 no-print">Info</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($logs as $index => $log)
                                @php
                                    $actionBadge = $log->getActionBadge();
                                    $moduleBadge = $log->getModuleBadge();
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-3 text-center font-bold text-slate-400">
                                        {{ $logs->firstItem() + $index }}
                                    </td>
                                    <td class="p-3 font-mono text-slate-700 whitespace-nowrap">
                                        <div class="font-bold text-[11px]">{{ $log->created_at->format('d-M-y h:i A') }}</div>
                                        <div class="text-[9.5px] text-slate-400 font-sans">{{ $log->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-[11px] shrink-0">
                                                {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900 text-xs">
                                                    {{ $log->user_name }}
                                                </div>
                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9.5px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                    {{ ucfirst($log->user_role) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg text-[11px] font-bold border {{ $actionBadge['class'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $actionBadge['dot'] }}"></span>
                                            <span>{{ $actionBadge['label'] }}</span>
                                        </span>
                                    </td>
                                    <td class="p-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold border {{ $moduleBadge['class'] }}">
                                            {{ $moduleBadge['label'] }}
                                        </span>
                                    </td>
                                    <td class="p-3 font-medium text-slate-800 leading-snug">
                                        {!! $log->description !!}
                                    </td>
                                    <td class="p-3 font-mono text-slate-500 text-[11px] whitespace-nowrap">
                                        <div>{{ $log->ip_address ?: '127.0.0.1' }}</div>
                                        @if($log->user_agent)
                                            <div class="text-[9.5px] text-slate-400 font-sans truncate max-w-[140px]" title="{{ $log->user_agent }}">
                                                {{ Str::limit($log->user_agent, 20) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center no-print">
                                        @if(!empty($log->properties))
                                            <button type="button" 
                                                    onclick="showLogDetails('{{ $log->id }}', '{{ addslashes($log->description) }}', {{ json_encode($log->properties) }}, '{{ $log->user_name }}', '{{ $log->created_at->format('d-M-y h:i A') }}')" 
                                                    class="p-1 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition" 
                                                    title="View Change Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </button>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-slate-400">
                                        No activity logs found. All future user actions will be automatically recorded here.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 no-print">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- DETAILS MODAL: Inspect Properties / Old vs New Values -->
    <div id="log-details-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all flex flex-col">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <div>
                    <h4 class="font-extrabold text-sm flex items-center gap-2">
                        <span>Activity Change Details</span>
                    </h4>
                    <p class="text-[11px] text-slate-300 mt-0.5" id="modal-log-meta">
                        User action metadata snapshot
                    </p>
                </div>
                <button type="button" onclick="closeLogDetailsModal()" class="text-slate-400 hover:text-white text-xl font-bold leading-none">&times;</button>
            </div>

            <div class="p-6 space-y-3 overflow-y-auto max-h-[70vh]">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-800 font-semibold" id="modal-log-desc">
                    <!-- Injected via JS -->
                </div>

                <div class="space-y-1">
                    <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wide">Payload / Properties JSON:</span>
                    <pre class="p-3.5 bg-slate-900 text-emerald-400 font-mono text-xs rounded-xl overflow-x-auto" id="modal-log-json">
                        <!-- Injected via JS -->
                    </pre>
                </div>
            </div>

            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button type="button" onclick="closeLogDetailsModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function showLogDetails(id, desc, props, user, time) {
            document.getElementById('modal-log-desc').textContent = desc;
            document.getElementById('modal-log-meta').textContent = 'By ' + user + ' on ' + time;
            document.getElementById('modal-log-json').textContent = JSON.stringify(props, null, 2);
            document.getElementById('log-details-modal').classList.remove('hidden');
        }

        function closeLogDetailsModal() {
            document.getElementById('log-details-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
