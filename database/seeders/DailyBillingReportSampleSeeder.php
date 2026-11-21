<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Company;
use App\Models\Department;
use App\Models\DepartmentBudgetHead;
use App\Models\User;
use Illuminate\Database\Seeder;

class DailyBillingReportSampleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'System Admin',
                'email' => 'admin@tender.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'company_name' => 'Al-Madina Enterprises',
                'phone' => '+92-300-1234567',
                'address' => 'HQ Complex, Karachi',
            ]);
        }

        // 1. Department: Chief Controller of Stamps Karachi (KA-8914)
        $dept = Department::firstOrCreate(
            ['code' => 'KA-8914'],
            [
                'name' => 'Chief Controller of Stamps Karachi',
                'cost_center_description' => 'Office of the Chief Controller of Stamps',
                'city' => 'Karachi',
                'head_of_department' => 'Controller of Stamps',
                'budget_allocated' => 50000000.00,
                'status' => 'active',
            ]
        );

        // Budget heads
        DepartmentBudgetHead::firstOrCreate(
            ['department_id' => $dept->id, 'head_code' => 'A13801'],
            [
                'head_name' => 'Maintenance of Parks & Gardens',
                'year' => '2026-2027',
                'original_budget' => 5000000.00,
                'released_budget' => 2000000.00,
            ]
        );

        DepartmentBudgetHead::firstOrCreate(
            ['department_id' => $dept->id, 'head_code' => 'A03970'],
            [
                'head_name' => 'Others',
                'year' => '2026-2027',
                'original_budget' => 10000000.00,
                'released_budget' => 5000000.00,
            ]
        );

        // 2. Companies: Shaheen Enterprises and Al-Madina Enterprises
        $shaheen = Company::firstOrCreate(
            ['company_name' => 'Shaheen Enterprises'],
            [
                'vendor_no' => '30368984',
                'ntn' => '3437101-0',
                'gst' => '3277876289057',
                'sst' => 'S3437101-2',
                'status' => 'active',
                'is_default' => true,
            ]
        );

        $almadina = Company::firstOrCreate(
            ['company_name' => 'Al-Madina Enterprises'],
            [
                'vendor_no' => '30368995',
                'ntn' => '2847291-5',
                'gst' => '3277876289099',
                'sst' => 'S2847291-1',
                'status' => 'active',
                'is_default' => false,
            ]
        );

        // 3. Bill 1: 10885 (27-Oct-26, 1st Qtr, A13801, Maintenance of Parks & Gardens, KA-8914, 15% GST, Amount 7,763, Shaheen Enterprises, 29-Oct-26, Rider By Hand)
        $gross1 = round(7763 / 1.15, 2);
        $gstAmt1 = 7763 - $gross1;
        $bill1 = Bill::firstOrCreate(
            ['bill_number' => '10885'],
            [
                'quarter' => '1st Qtr',
                'department_id' => $dept->id,
                'company_id' => $shaheen->id,
                'head_code' => 'A13801',
                'head_description' => 'Maintenance of Parks & Gardens',
                'head_of_account' => 'A13801 - Maintenance of Parks & Gardens',
                'cost_center' => 'KA-8914',
                'gross_amount' => $gross1,
                'gst_rate' => 15.00,
                'gst_amount' => $gstAmt1,
                'tax_deduction' => 0,
                'net_amount' => 7763.00,
                'agpr_vendor_no' => $shaheen->vendor_no,
                'strn_number' => $shaheen->gst,
                'ntn_number' => $shaheen->ntn,
                'sst_number' => $shaheen->sst,
                'invoice_date' => '2026-10-27',
                'dispatch_date' => '2026-10-29',
                'remarks' => 'Rider By Hand',
                'milestone_description' => 'Maintenance of Parks & Gardens as per sanctioned contract',
                'status' => 'approved',
                'created_by' => $admin?->id,
            ]
        );

        if ($bill1->items()->count() === 0) {
            $bill1->items()->create([
                'item_name' => 'Garden Maintenance & Lawn Care Supplies',
                'unit' => 'Job',
                'quantity' => 1,
                'unit_price' => $gross1,
                'total_price' => $gross1,
            ]);
        }

        // 4. Bill 2: 10890 (27-Oct-26, 1st Qtr, A03970, Others, KA-8914, 18% GST, Amount 38,840, Al-Madina Enterprises, 29-Oct-26, Rider By Hand)
        $gross2 = round(38840 / 1.18, 2);
        $gstAmt2 = 38840 - $gross2;
        $bill2 = Bill::firstOrCreate(
            ['bill_number' => '10890'],
            [
                'quarter' => '1st Qtr',
                'department_id' => $dept->id,
                'company_id' => $almadina->id,
                'head_code' => 'A03970',
                'head_description' => 'Others',
                'head_of_account' => 'A03970 - Others',
                'cost_center' => 'KA-8914',
                'gross_amount' => $gross2,
                'gst_rate' => 18.00,
                'gst_amount' => $gstAmt2,
                'tax_deduction' => 0,
                'net_amount' => 38840.00,
                'agpr_vendor_no' => $almadina->vendor_no,
                'strn_number' => $almadina->gst,
                'ntn_number' => $almadina->ntn,
                'sst_number' => $almadina->sst,
                'invoice_date' => '2026-10-27',
                'dispatch_date' => '2026-10-29',
                'remarks' => 'Rider By Hand',
                'milestone_description' => 'Procurement of departmental miscellaneous items',
                'status' => 'approved',
                'created_by' => $admin?->id,
            ]
        );

        if ($bill2->items()->count() === 0) {
            $bill2->items()->create([
                'item_name' => 'Office Stationary & Stores Procurement',
                'unit' => 'Job',
                'quantity' => 1,
                'unit_price' => $gross2,
                'total_price' => $gross2,
            ]);
        }
    }
}
