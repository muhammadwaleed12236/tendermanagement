<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Expense Categories
        $categories = [
            ['name' => 'Office Rent & Tenancy', 'code' => 'EXP-RENT', 'color' => '#6366f1', 'description' => 'Monthly head office / branch rental payments'],
            ['name' => 'Electricity, Gas & Water', 'code' => 'EXP-UTIL', 'color' => '#0ea5e9', 'description' => 'Utility bills for office, warehouse or sites'],
            ['name' => 'Fuel & Transportation', 'code' => 'EXP-FUEL', 'color' => '#f59e0b', 'description' => 'Vehicle fuel, site transit, cargo & carriage'],
            ['name' => 'Staff Salaries & Wages', 'code' => 'EXP-SAL', 'color' => '#10b981', 'description' => 'Staff payroll, daily wage workers & staff allowances'],
            ['name' => 'Tender Submission & Bid Security', 'code' => 'EXP-TNDR', 'color' => '#8b5cf6', 'description' => 'Tender form fees, earnest money, bank guarantee charges'],
            ['name' => 'Stationery, Printing & Photocopy', 'code' => 'EXP-STAT', 'color' => '#ec4899', 'description' => 'Files, dossier printing, documentation and binding'],
            ['name' => 'Site Supplies & Repair/Maintenance', 'code' => 'EXP-REPR', 'color' => '#f43f5e', 'description' => 'Tools, hardware repairs, office maintenance'],
            ['name' => 'Tea, Entertainment & Refreshments', 'code' => 'EXP-ENT', 'color' => '#d97706', 'description' => 'Office refreshments, client meetings, daily tea'],
            ['name' => 'Tax, Audit & Professional Fees', 'code' => 'EXP-TAX', 'color' => '#64748b', 'description' => 'Legal retainers, CA audit fees, PRA/SRB tax consultants'],
            ['name' => 'Miscellaneous & Emergency Petty Cash', 'code' => 'EXP-MISC', 'color' => '#475569', 'description' => 'Uncategorized daily office expenses'],
        ];

        foreach ($categories as $cat) {
            ExpenseCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // 2. Seed Default Cash in Hand & Sample Business Bank Account if empty
        if (BankAccount::count() === 0) {
            BankAccount::create([
                'bank_name' => 'Cash in Hand (Petty Cash)',
                'account_title' => 'Main Office Cash Vault',
                'account_number' => 'CASH-MAIN-01',
                'account_type' => 'petty_cash',
                'currency' => 'PKR',
                'opening_balance' => 50000.00,
                'current_balance' => 50000.00,
                'status' => 'active',
                'is_default' => true,
                'notes' => 'Primary petty cash drawer for daily operational expenses',
            ]);

            BankAccount::create([
                'bank_name' => 'Meezan Bank Limited',
                'account_title' => 'ProWave Technologies / Three Stars Medical',
                'account_number' => '0102-0100456789',
                'iban' => 'PK65MEZN0001020100456789',
                'branch_name' => 'Main Branch Karachi',
                'branch_code' => '0102',
                'account_type' => 'current',
                'currency' => 'PKR',
                'opening_balance' => 500000.00,
                'current_balance' => 500000.00,
                'status' => 'active',
                'is_default' => false,
                'notes' => 'Main Islamic corporate commercial current account for government tenders & AGPR cheques',
            ]);
        }
    }
}
