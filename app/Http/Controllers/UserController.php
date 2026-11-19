<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\RolePermissionSetting;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        // Statistics
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $adminUsers = User::whereIn('role', ['admin', 'manager'])->count();
        $staffUsers = User::whereIn('role', ['accountant', 'operator', 'auditor'])->count();

        $roles = PermissionService::ROLES;

        return view('users.index', compact('users', 'totalUsers', 'activeUsers', 'adminUsers', 'staffUsers', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = PermissionService::ROLES;
        $permissionGroups = PermissionService::PERMISSIONS;

        return view('users.create', compact('roles', 'permissionGroups'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'string', Rule::in(array_keys(PermissionService::ROLES))],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'custom_permissions' => ['nullable', 'array'],
            'custom_permissions.*' => ['string'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['custom_permissions'] = $request->input('custom_permissions', null);

        $user = User::create($validated);

        ActivityLog::record(
            action: 'created',
            module: 'Users',
            description: "Created new user [{$user->name}] ({$user->email}) with role [{$user->getRoleDisplayName()}] and status [{$user->status}].",
            subject: $user,
            properties: [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'permissions' => $user->custom_permissions,
            ]
        );

        return redirect()->route('users.index')->with('success', "User [{$user->name}] created successfully.");
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = PermissionService::ROLES;
        $permissionGroups = PermissionService::PERMISSIONS;
        $rolePermissions = PermissionService::getPermissionsForRole($user->role);

        return view('users.edit', compact('user', 'roles', 'permissionGroups', 'rolePermissions'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'string', Rule::in(array_keys(PermissionService::ROLES))],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'custom_permissions' => ['nullable', 'array'],
            'custom_permissions.*' => ['string'],
        ]);

        // Safety check: Prevent deactivating yourself
        if ($user->id === Auth::id() && $validated['status'] === 'inactive') {
            return back()->withErrors(['status' => 'You cannot deactivate your own logged-in account.']);
        }

        // Safety check: Prevent demoting yourself from admin
        if ($user->id === Auth::id() && $user->isAdmin() && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'You cannot remove admin role from your own account.']);
        }

        $oldRole = $user->role;
        $oldStatus = $user->status;

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['custom_permissions'] = $request->input('custom_permissions', null);

        $user->update($validated);

        ActivityLog::record(
            action: 'updated',
            module: 'Users',
            description: "Updated user [{$user->name}] ({$user->email}). Role: {$oldRole} &rarr; {$user->role}, Status: {$oldStatus} &rarr; {$user->status}.",
            subject: $user,
            properties: [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'custom_permissions' => $user->custom_permissions,
            ]
        );

        return redirect()->route('users.index')->with('success', "User [{$user->name}] updated successfully.");
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the only administrator account.');
        }

        $userName = $user->name;
        $userEmail = $user->email;
        $userRole = $user->getRoleDisplayName();

        $user->delete();

        ActivityLog::record(
            action: 'deleted',
            module: 'Users',
            description: "Deleted user account [{$userName}] ({$userEmail}) with role [{$userRole}].",
            properties: ['name' => $userName, 'email' => $userEmail, 'role' => $userRole]
        );

        return redirect()->route('users.index')->with('success', "User [{$userName}] was deleted successfully.");
    }

    /**
     * Manage Roles and their default permissions matrix
     */
    public function roles()
    {
        $roles = PermissionService::ROLES;
        $permissionGroups = PermissionService::PERMISSIONS;

        // Load active permissions for each role
        $rolePermissionsMap = [];
        foreach ($roles as $key => $roleData) {
            $rolePermissionsMap[$key] = PermissionService::getPermissionsForRole($key);
        }

        // User count per role
        $roleCounts = User::selectRaw('role, count(*) as count')->groupBy('role')->pluck('count', 'role')->toArray();

        return view('users.roles', compact('roles', 'permissionGroups', 'rolePermissionsMap', 'roleCounts'));
    }

    /**
     * Update default permissions for roles
     */
    public function updateRolePermissions(Request $request)
    {
        $matrix = $request->input('role_permissions', []);

        foreach (array_keys(PermissionService::ROLES) as $roleKey) {
            if ($roleKey === 'admin') {
                continue; // Admin always has all
            }

            $perms = isset($matrix[$roleKey]) && is_array($matrix[$roleKey]) ? array_values($matrix[$roleKey]) : [];

            RolePermissionSetting::updateOrCreate(
                ['role' => $roleKey],
                ['permissions' => $perms]
            );
        }

        ActivityLog::record(
            action: 'updated',
            module: 'Users',
            description: "Updated global Role Permissions matrix for all user roles.",
            properties: $matrix
        );

        return redirect()->route('roles.index')->with('success', 'Role permissions matrix updated successfully.');
    }
}
