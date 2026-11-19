<?php

namespace App\Services;

use App\Models\RolePermissionSetting;
use Illuminate\Support\Facades\Schema;

class PermissionService
{
    /**
     * All available roles in the system
     */
    public const ROLES = [
        'admin' => [
            'key' => 'admin',
            'name' => 'Administrator',
            'badge' => 'bg-rose-100 text-rose-800 border-rose-200',
            'dot' => 'bg-rose-500',
            'description' => 'Unrestricted access to all modules, financial data, user management, and system logs.',
        ],
        'manager' => [
            'key' => 'manager',
            'name' => 'Operations Manager',
            'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'dot' => 'bg-indigo-500',
            'description' => 'Can manage Bills, Cheques, Departments, Companies, Products, and view activity logs.',
        ],
        'accountant' => [
            'key' => 'accountant',
            'name' => 'Accountant / Treasury Officer',
            'badge' => 'bg-blue-100 text-blue-800 border-blue-200',
            'dot' => 'bg-blue-500',
            'description' => 'Can create and process Bills, generate Invoices, manage AGPR Cheques and deductions.',
        ],
        'operator' => [
            'key' => 'operator',
            'name' => 'Data Entry Operator',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-200',
            'dot' => 'bg-amber-500',
            'description' => 'Can enter new bills, upload products, and enter cheque details. Cannot delete or change system settings.',
        ],
        'auditor' => [
            'key' => 'auditor',
            'name' => 'Auditor / Inspector',
            'badge' => 'bg-purple-100 text-purple-800 border-purple-200',
            'dot' => 'bg-purple-500',
            'description' => 'Read-only access to all financial statements, bills, cheques, and the complete audit trail.',
        ],
        'vendor' => [
            'key' => 'vendor',
            'name' => 'Vendor / Supplier',
            'badge' => 'bg-slate-100 text-slate-800 border-slate-200',
            'dot' => 'bg-slate-500',
            'description' => 'Limited supplier portal access to view their own bills and tenders.',
        ],
    ];

    /**
     * All system permissions categorized by module
     */
    public const PERMISSIONS = [
        'Bills & Invoices' => [
            'bills.view' => 'View bills, invoices and quotations',
            'bills.create' => 'Create bills & generate invoices',
            'bills.edit' => 'Edit bills, items & prices',
            'bills.status' => 'Change bill payment status (Draft/Pending/Paid)',
            'bills.delete' => 'Delete bills and invoice records',
        ],
        'Cheques & AGPR Deductions' => [
            'cheques.view' => 'View cheques & AGPR reconciliation statement',
            'cheques.create' => 'Record cheques, tokens & tax deductions',
            'cheques.delete' => 'Delete cheque records',
        ],
        'Departments & Budget' => [
            'departments.view' => 'View departments and budget status',
            'departments.manage' => 'Manage departments, budget releases & head codes',
        ],
        'Companies & Letterheads' => [
            'companies.view' => 'View supplier companies and letterheads',
            'companies.manage' => 'Add/Edit companies, invoice templates & letterheads',
        ],
        'Products Catalog' => [
            'products.view' => 'Browse products and pricing catalog',
            'products.manage' => 'Add, import CSV and delete products',
        ],
        'Finance, Expenses & Banks' => [
            'finance.view' => 'View finance dashboard, bank accounts & cashbook',
            'finance.expenses' => 'Create, edit & approve expense vouchers',
            'finance.banks' => 'Add and manage bank accounts & opening balances',
            'finance.transfers' => 'Execute fund transfers between bank accounts',
        ],
        'User Management' => [
            'users.view' => 'View system user accounts',
            'users.manage' => 'Create, edit, suspend users and assign roles',
        ],
        'Audit Trail & Activity Log' => [
            'activity_logs.view' => 'View complete system audit trail ("kis ne konsa kaam kiya")',
        ],
    ];

    /**
     * Default permissions mapped to each role
     */
    public static function getDefaultRolePermissions(string $role): array
    {
        $allPermissions = array_keys(self::getAllPermissionsFlat());

        return match ($role) {
            'admin' => $allPermissions,
            'manager' => [
                'bills.view', 'bills.create', 'bills.edit', 'bills.status',
                'cheques.view', 'cheques.create',
                'departments.view', 'departments.manage',
                'companies.view', 'companies.manage',
                'products.view', 'products.manage',
                'finance.view', 'finance.expenses', 'finance.banks', 'finance.transfers',
                'users.view',
                'activity_logs.view',
            ],
            'accountant' => [
                'bills.view', 'bills.create', 'bills.edit', 'bills.status',
                'cheques.view', 'cheques.create',
                'departments.view',
                'companies.view',
                'products.view', 'products.manage',
                'finance.view', 'finance.expenses', 'finance.banks', 'finance.transfers',
                'activity_logs.view',
            ],
            'operator' => [
                'bills.view', 'bills.create',
                'cheques.view', 'cheques.create',
                'departments.view',
                'companies.view',
                'products.view',
                'finance.view', 'finance.expenses',
            ],
            'auditor' => [
                'bills.view',
                'cheques.view',
                'finance.view',
                'departments.view',
                'companies.view',
                'products.view',
                'users.view',
                'activity_logs.view',
            ],
            'vendor' => [
                'bills.view',
            ],
            default => ['bills.view'],
        };
    }

    /**
     * Return flat list of permission key => label
     */
    public static function getAllPermissionsFlat(): array
    {
        $flat = [];
        foreach (self::PERMISSIONS as $group => $items) {
            foreach ($items as $key => $label) {
                $flat[$key] = $label;
            }
        }
        return $flat;
    }

    /**
     * Get active permissions for a given role (checking database override or fallback)
     */
    public static function getPermissionsForRole(string $role): array
    {
        if ($role === 'admin') {
            return array_keys(self::getAllPermissionsFlat());
        }

        try {
            if (Schema::hasTable('role_permission_settings')) {
                $setting = RolePermissionSetting::where('role', $role)->first();
                if ($setting && is_array($setting->permissions)) {
                    return $setting->permissions;
                }
            }
        } catch (\Throwable) {
            // fallback
        }

        return self::getDefaultRolePermissions($role);
    }
}
