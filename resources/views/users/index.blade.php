<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Access Control & Security</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-semibold text-rose-600">Enterprise RBAC</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    {{ __('User Accounts & Role Permissions') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Manage team members, assign operational roles, customize fine-grained permissions, and monitor access status.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('activity-logs.index') }}" class="px-3.5 py-2 bg-purple-50 hover:bg-purple-100 text-purple-900 border border-purple-200 text-xs font-bold rounded-lg shadow-2xs transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>View Activity Logs</span>
                </a>
                <a href="{{ route('roles.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>Role Permissions Matrix</span>
                </a>
                <a href="{{ route('users.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span>+ Add New User</span>
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

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- 4 Metric KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- 1. Total Users -->
                <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Accounts</div>
                        <div class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ $totalUsers }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Registered system users</div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 font-bold">
                        👥
                    </div>
                </div>

                <!-- 2. Active Accounts -->
                <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Active Users</div>
                        <div class="text-2xl font-black text-emerald-800 mt-1 font-mono">{{ $activeUsers }}</div>
                        <div class="text-[10px] text-emerald-600 mt-0.5">Can authenticate & act</div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 font-bold">
                        ⚡
                    </div>
                </div>

                <!-- 3. Admins & Managers -->
                <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Admins & Managers</div>
                        <div class="text-2xl font-black text-rose-800 mt-1 font-mono">{{ $adminUsers }}</div>
                        <div class="text-[10px] text-rose-600 mt-0.5">Elevated administrative privileges</div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 font-bold">
                        🛡️
                    </div>
                </div>

                <!-- 4. Operators & Staff -->
                <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-indigo-700 uppercase tracking-wider">Operational Staff</div>
                        <div class="text-2xl font-black text-indigo-800 mt-1 font-mono">{{ $staffUsers }}</div>
                        <div class="text-[10px] text-indigo-600 mt-0.5">Accountants, Operators, Auditors</div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-600 font-bold">
                        💼
                    </div>
                </div>
            </div>

            <!-- Users Registry Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                
                <!-- Filter Bar -->
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">
                            Team Members & Assigned Roles
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Filter by operational role, account activity status, or search user credentials.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-2">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Name, Email, Phone..." class="text-xs rounded-lg border-slate-300 py-1.5 focus:ring-indigo-500 focus:border-indigo-500 w-52">
                        </div>
                        <div>
                            <select name="role" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-300 py-1.5 font-semibold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Roles</option>
                                @foreach($roles as $key => $roleData)
                                    <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $roleData['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-300 py-1.5 font-semibold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                            </select>
                        </div>
                        <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition whitespace-nowrap">
                            Filter
                        </button>
                        @if(request('search') || request('role') || request('status'))
                            <a href="{{ route('users.index') }}" class="text-xs text-rose-600 hover:underline px-2 py-1 font-semibold whitespace-nowrap">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10.5px]">
                            <tr>
                                <th class="p-3.5 text-center w-12">#</th>
                                <th class="p-3.5">User / Contact Details</th>
                                <th class="p-3.5">Assigned Role</th>
                                <th class="p-3.5">Permissions Status</th>
                                <th class="p-3.5 text-center">Account Status</th>
                                <th class="p-3.5">Last Login</th>
                                <th class="p-3.5 text-right w-28">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($users as $index => $u)
                                @php
                                    $badge = $u->getRoleBadge();
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-3.5 text-center font-bold text-slate-400">
                                        {{ $users->firstItem() + $index }}
                                    </td>
                                    <td class="p-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900 text-sm flex items-center gap-1.5">
                                                    <span>{{ $u->name }}</span>
                                                    @if($u->id === Auth::id())
                                                        <span class="px-1.5 py-0.2 rounded text-[9.5px] font-bold bg-blue-100 text-blue-800 border border-blue-200">You</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-slate-500 font-mono">{{ $u->email }}</div>
                                                @if($u->company_name)
                                                    <div class="text-[11px] text-indigo-700 font-semibold mt-0.5">{{ $u->company_name }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3.5">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border {{ $badge['badge'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                            <span>{{ $badge['name'] }}</span>
                                        </span>
                                        <div class="text-[10px] text-slate-400 mt-0.5 line-clamp-1 max-w-xs">{{ $badge['description'] }}</div>
                                    </td>
                                    <td class="p-3.5">
                                        @if($u->isAdmin())
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                                <svg class="w-3 h-3 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                Full System Access
                                            </span>
                                        @elseif(!empty($u->custom_permissions))
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200" title="{{ implode(', ', $u->custom_permissions) }}">
                                                <span>⚙️ Custom Overrides ({{ count($u->custom_permissions) }})</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                <span>Standard Role Defaults</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center">
                                        @if($u->isActive())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-slate-600 font-mono text-[11px]">
                                        @if($u->last_login_at)
                                            <div>{{ $u->last_login_at->format('d-M-y h:i A') }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $u->last_login_ip ?: 'Local' }} &bull; {{ $u->last_login_at->diffForHumans() }}</div>
                                        @else
                                            <span class="text-slate-400">Never logged in</span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('users.edit', $u) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 rounded-lg text-xs font-semibold border border-slate-200 hover:border-indigo-300 transition inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Edit
                                            </a>
                                            @if($u->id !== Auth::id())
                                                <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Are you sure you want to delete user account [{{ $u->name }}]? This action cannot be undone.');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Delete User">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400">
                                        No users matching criteria found. Click "+ Add New User" to register team members.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $users->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
