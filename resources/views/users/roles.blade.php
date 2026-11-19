<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('users.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Users Registry</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Access Control</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    {{ __('Role & Permission Management Matrix') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Review and configure default access privileges for every system role across all modules.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('activity-logs.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Activity Logs</span>
                </a>
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Manage Users</span>
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

            <!-- Role Overviews Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($roles as $key => $roleData)
                    <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="font-black text-sm text-slate-900">{{ $roleData['name'] }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border {{ $roleData['badge'] }}">
                                    {{ strtoupper($key) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                {{ $roleData['description'] }}
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400">Assigned Members:</span>
                            <strong class="font-mono text-slate-800 bg-slate-100 px-2 py-0.5 rounded">{{ $roleCounts[$key] ?? 0 }} Users</strong>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Role Permissions Matrix Form -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">
                            Role Permissions Matrix
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Toggle permissions for each role. (Administrator role always retains 100% full access).
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-400">💡 Changes apply immediately to all role holders</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('roles.update-permissions') }}">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-100 border-b-2 border-slate-200 text-slate-800 uppercase font-black sticky top-0 text-[10px]">
                                <tr>
                                    <th class="p-3.5 bg-slate-100 w-72">Module & Permission</th>
                                    @foreach($roles as $key => $roleData)
                                        <th class="p-3 text-center whitespace-nowrap {{ $key === 'admin' ? 'bg-rose-50 text-rose-900' : 'bg-slate-100' }}">
                                            <div class="font-bold text-[11px]">{{ $roleData['name'] }}</div>
                                            <div class="text-[9px] font-mono text-slate-400 lowercase font-normal">{{ $key }}</div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-sans">
                                @foreach($permissionGroups as $groupName => $perms)
                                    <!-- Group Header Row -->
                                    <tr class="bg-slate-50/80 font-black text-slate-900">
                                        <td colspan="{{ count($roles) + 1 }}" class="p-2.5 px-4 text-xs tracking-wider uppercase bg-slate-100/70 border-y border-slate-200">
                                            <span class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                                {{ $groupName }}
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Permissions Rows -->
                                    @foreach($perms as $permKey => $permLabel)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="p-3 font-semibold text-slate-800 border-r border-slate-100">
                                                <div class="text-xs font-bold text-slate-900">{{ $permLabel }}</div>
                                                <div class="text-[10px] text-slate-400 font-mono">{{ $permKey }}</div>
                                            </td>

                                            @foreach($roles as $roleKey => $roleData)
                                                @php
                                                    $isAllowed = in_array($permKey, $rolePermissionsMap[$roleKey] ?? []);
                                                @endphp
                                                <td class="p-3 text-center border-r border-slate-100 {{ $roleKey === 'admin' ? 'bg-rose-50/20' : '' }}">
                                                    @if($roleKey === 'admin')
                                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black" title="Administrator has all permissions">&#10003;</span>
                                                    @else
                                                        <input type="checkbox" 
                                                               name="role_permissions[{{ $roleKey }}][]" 
                                                               value="{{ $permKey }}" 
                                                               {{ $isAllowed ? 'checked' : '' }} 
                                                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-xs text-slate-500">
                            Configure permissions and click Save to apply system-wide.
                        </span>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-2">
                            <span>Save Role Permissions Matrix</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
