<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('users.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Users Registry</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Account Settings</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    Edit User: {{ $user->name }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Update role assignment, adjust fine-grained permissions, or toggle account status.
                </p>
            </div>
            <a href="{{ route('users.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- CARD 1: Identity & Credentials -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-700 font-bold text-xs flex items-center justify-center">1</span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">User Identity & Authentication</h3>
                            <p class="text-[11px] text-slate-400">Leave password fields blank to keep existing password unchanged</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')<span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email')<span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <!-- New Password (Optional) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">New Password (Optional)</label>
                            <input type="password" name="password" placeholder="Leave empty to keep current password" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password')<span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Re-type new password" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Company / Department Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Assigned Company / Organization</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" placeholder="e.g. Al-Mehran Gasoline or Treasury Office" class="w-full text-xs rounded-lg border-slate-300">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mobile / WhatsApp Contact</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +92 300 1234567" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Role Selection & Status -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">2</span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Role & Access Level</h3>
                                <p class="text-[11px] text-slate-400">Current role: <strong class="text-indigo-700">{{ $user->getRoleDisplayName() }}</strong></p>
                            </div>
                        </div>

                        <!-- Status toggle -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-slate-700">Account Status:</label>
                            <select name="status" class="text-xs font-bold rounded-lg border-slate-300 py-1">
                                <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>🟢 Active</option>
                                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>⚪ Inactive / Suspended</option>
                            </select>
                        </div>
                    </div>

                    <!-- Role Radio Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($roles as $key => $roleData)
                            <label class="relative flex items-start p-3.5 rounded-xl border {{ old('role', $user->role) === $key ? 'border-indigo-500 bg-indigo-50/40' : 'border-slate-200 bg-slate-50/50' }} hover:border-indigo-400 cursor-pointer transition group">
                                <input type="radio" name="role" value="{{ $key }}" {{ old('role', $user->role) === $key ? 'checked' : '' }} onchange="onRoleChanged('{{ $key }}')" class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                <div class="ms-3 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-xs text-slate-900 group-hover:text-indigo-900">{{ $roleData['name'] }}</span>
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold border {{ $roleData['badge'] }}">
                                            {{ strtoupper($key) }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1 leading-snug">{{ $roleData['description'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- CARD 3: Fine-Grained Permissions Customization -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-amber-50 text-amber-800 font-bold text-xs flex items-center justify-center">3</span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Module Permissions</h3>
                                <p class="text-[11px] text-slate-400">Checked items are active for this user account</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="selectAllPermissions(true)" class="px-2.5 py-1 text-[11px] font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                                Check All
                            </button>
                            <button type="button" onclick="selectAllPermissions(false)" class="px-2.5 py-1 text-[11px] font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                                Uncheck All
                            </button>
                        </div>
                    </div>

                    <!-- Permission Groups Grid -->
                    <div class="space-y-4">
                        @foreach($permissionGroups as $groupName => $perms)
                            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-wide flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                        {{ $groupName }}
                                    </span>
                                    <button type="button" onclick="toggleGroupCheckboxes('{{ Str::slug($groupName) }}')" class="text-[10.5px] font-semibold text-indigo-600 hover:underline">
                                        Toggle Group
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 group-container-{{ Str::slug($groupName) }}">
                                    @foreach($perms as $permKey => $permLabel)
                                        @php
                                            $isChecked = false;
                                            if (is_array(old('custom_permissions'))) {
                                                $isChecked = in_array($permKey, old('custom_permissions'));
                                            } elseif ($user->isAdmin()) {
                                                $isChecked = true;
                                            } elseif (is_array($user->custom_permissions)) {
                                                $isChecked = in_array($permKey, $user->custom_permissions);
                                            } else {
                                                $isChecked = in_array($permKey, $rolePermissions);
                                            }
                                        @endphp
                                        <label class="flex items-start gap-2 p-2 rounded-lg hover:bg-white transition cursor-pointer border border-transparent hover:border-slate-200">
                                            <input type="checkbox" name="custom_permissions[]" value="{{ $permKey }}" class="perm-checkbox mt-0.5 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded" {{ $isChecked ? 'checked' : '' }}>
                                            <div class="flex-1 leading-tight">
                                                <div class="text-xs font-bold text-slate-800">{{ $permLabel }}</div>
                                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $permKey }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Bar -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                        &larr; Cancel & Return
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-2">
                        <span>Save Changes</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function selectAllPermissions(check) {
            document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = check);
        }

        function toggleGroupCheckboxes(slug) {
            const container = document.querySelector('.group-container-' + slug);
            if (container) {
                const boxes = container.querySelectorAll('.perm-checkbox');
                const anyUnchecked = Array.from(boxes).some(b => !b.checked);
                boxes.forEach(b => b.checked = anyUnchecked);
            }
        }

        function onRoleChanged(roleKey) {
            if (roleKey === 'admin') {
                selectAllPermissions(true);
            }
        }
    </script>
</x-app-layout>
