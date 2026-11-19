<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BudgetRelease;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@tender.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'company_name' => 'Al-Madina Enterprises',
                'phone' => '+92-300-1234567',
                'address' => 'HQ Procurement Complex, Islamabad',
            ]
        );

        // 2. Evaluator User
        $evaluator = User::firstOrCreate(
            ['email' => 'evaluator@tender.com'],
            [
                'name' => 'Engr. Tariq Mehmood',
                'password' => Hash::make('password'),
                'role' => 'evaluator',
                'company_name' => 'Technical Evaluation Committee',
                'phone' => '+92-321-7654321',
                'address' => 'Directorate of Technical Audits, Lahore',
            ]
        );

        // 3. Vendor 1
        $vendor1 = User::firstOrCreate(
            ['email' => 'vendor1@techcorp.com'],
            [
                'name' => 'Zubair Ahmed',
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'company_name' => 'M/s XYZ Engineering & Store Supplies',
                'phone' => '03123456789',
                'address' => 'Plot 42, Software Technology Park, Karachi.',
                'tax_number' => '2128115-7',
                'strn_number' => '1700212811518',
                'agpr_vendor_no' => '30368984',
            ]
        );
        $vendor1->update([
            'company_name' => 'M/s XYZ Engineering & Store Supplies',
            'tax_number' => '2128115-7',
            'strn_number' => '1700212811518',
            'agpr_vendor_no' => '30368984',
            'phone' => '03123456789',
        ]);

        // 4. Vendor 2
        $vendor2 = User::firstOrCreate(
            ['email' => 'vendor2@buildwell.com'],
            [
                'name' => 'Farhan Qureshi',
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'company_name' => 'BuildWell Engineering & Construction',
                'phone' => '+92-345-9988776',
                'address' => 'Industrial Area Sector I-9, Islamabad',
                'tax_number' => 'NTN-1938472-1',
            ]
        );

        // 5. Departments
        $deptIT = Department::firstOrCreate(
            ['code' => 'ITD'],
            [
                'name' => 'Information Technology & Digitalization',
                'cost_center_description' => 'Office of the Director (IT & Technical Logistics)',
                'city' => 'Karachi.',
                'head_of_department' => 'Dr. Kamran Malik',
                'budget_allocated' => 120000000.00,
                'description' => 'Oversees cloud infrastructure, software licensing, enterprise networking, and digital governance.',
                'status' => 'active',
            ]
        );
        $deptIT->update([
            'cost_center_description' => 'Office of the Director (IT & Technical Logistics)',
            'city' => 'Karachi.',
        ]);

        $deptCW = Department::firstOrCreate(
            ['code' => 'CWD'],
            [
                'name' => 'Civil Works & Infrastructure Development',
                'cost_center_description' => 'Office of the Executive Engineer (Civil Works)',
                'city' => 'Karachi.',
                'head_of_department' => 'Engr. Salman Bashir',
                'budget_allocated' => 450000000.00,
                'description' => 'Responsible for building infrastructure, seismic retrofitting, highways, and public healthcare facilities.',
                'status' => 'active',
            ]
        );

        $deptHealth = Department::firstOrCreate(
            ['code' => 'HMS'],
            [
                'name' => 'Healthcare & Medical Services',
                'cost_center_description' => 'Office of the XYZ',
                'city' => 'Karachi.',
                'head_of_department' => 'Dr. Ayesha Siddiqui',
                'budget_allocated' => 250000000.00,
                'description' => 'Procurement of biomedical machinery, hospital pharmaceuticals, surgical consumables, and lab reagents.',
                'status' => 'active',
            ]
        );
        $deptHealth->update([
            'cost_center_description' => 'Office of the XYZ',
            'city' => 'Karachi.',
        ]);

        $deptLogistics = Department::firstOrCreate(
            ['code' => 'LTF'],
            [
                'name' => 'Logistics & Transport Fleet',
                'head_of_department' => 'Col. (R) Javed Akhtar',
                'budget_allocated' => 90000000.00,
                'description' => 'Inter-city cold-chain transport, heavy distribution vehicles, dispatch centers, and warehousing.',
                'status' => 'active',
            ]
        );

        // 6. Quarterly Budget Releases (e.g. Q1, Q2)
        BudgetRelease::firstOrCreate(
            ['department_id' => $deptIT->id, 'quarter' => 'Q1 (Jul - Sep)'],
            [
                'financial_year' => '2026-2027',
                'amount' => 30000000.00, // 30M released out of 120M
                'release_date' => now()->subMonths(3),
                'reference_no' => 'FD-REL-IT-2026-Q1',
                'remarks' => 'First Quarter Release for Cloud Infrastructure & Hardware Upgrades',
                'created_by' => $admin->id,
            ]
        );

        BudgetRelease::firstOrCreate(
            ['department_id' => $deptIT->id, 'quarter' => 'Q2 (Oct - Dec)'],
            [
                'financial_year' => '2026-2027',
                'amount' => 30000000.00, // another 30M
                'release_date' => now()->subMonth(),
                'reference_no' => 'FD-REL-IT-2026-Q2',
                'remarks' => 'Second Quarter Tranche for Software Licenses and System Maintenance',
                'created_by' => $admin->id,
            ]
        );

        BudgetRelease::firstOrCreate(
            ['department_id' => $deptCW->id, 'quarter' => 'Q1 (Jul - Sep)'],
            [
                'financial_year' => '2026-2027',
                'amount' => 120000000.00,
                'release_date' => now()->subMonths(3),
                'reference_no' => 'FD-REL-CW-2026-Q1',
                'remarks' => 'First Quarter Capital Outlay for Healthcare & Road Infrastructure',
                'created_by' => $admin->id,
            ]
        );

        BudgetRelease::firstOrCreate(
            ['department_id' => $deptHealth->id, 'quarter' => 'Q1 (Jul - Sep)'],
            [
                'financial_year' => '2026-2027',
                'amount' => 60000000.00,
                'release_date' => now()->subMonths(3),
                'reference_no' => 'FD-REL-HMS-2026-Q1',
                'remarks' => 'Q1 Drug & Reagent Consumables Release',
                'created_by' => $admin->id,
            ]
        );

        BudgetRelease::firstOrCreate(
            ['department_id' => $deptLogistics->id, 'quarter' => 'Q1 (Jul - Sep)'],
            [
                'financial_year' => '2026-2027',
                'amount' => 25000000.00,
                'release_date' => now()->subMonths(3),
                'reference_no' => 'FD-REL-LTF-2026-Q1',
                'remarks' => 'Q1 Fleet Maintenance & Fuel Allocation',
                'created_by' => $admin->id,
            ]
        );

        // Update or create tenders with departments
        $t1 = Tender::firstOrCreate(
            ['reference_no' => 'TND-2026-001'],
            [
                'title' => 'Supply & Deployment of Enterprise Cloud Data Center Hardware',
                'department_id' => $deptIT->id,
                'category' => 'IT & Software',
                'description' => 'Procurement, configuration, and 3-year SLA support for enterprise blade servers, SAN storage racks, high-speed optical switches, and redundant power infrastructure.',
                'requirements' => '1. Tier-3 ISO certified partner\n2. Minimum 5 years continuous vendor experience in government cloud infra\n3. Warranty: 3 years on-site 24/7 OEM support',
                'budget' => 25000000.00,
                'submission_deadline' => now()->addDays(25),
                'opening_date' => now()->addDays(26),
                'status' => 'published',
                'created_by' => $admin->id,
            ]
        );
        $t1->update(['department_id' => $deptIT->id]);

        $t2 = Tender::firstOrCreate(
            ['reference_no' => 'TND-2026-002'],
            [
                'title' => 'Construction of 4-Storey Healthcare & Diagnostic Center Facility',
                'department_id' => $deptCW->id,
                'category' => 'Construction & Civil Works',
                'description' => 'Turnkey civil construction including foundation, grey structure, HVAC installations, electrical MEP, interior fittings, and biomedical pipeline conduits.',
                'requirements' => '1. PEC Category C-3 or above\n2. Proven completion of at least 2 public hospital projects\n3. Completion timeline: 14 calendar months',
                'budget' => 85000000.00,
                'submission_deadline' => now()->addDays(15),
                'opening_date' => now()->addDays(16),
                'status' => 'published',
                'created_by' => $admin->id,
            ]
        );
        $t2->update(['department_id' => $deptCW->id]);

        $t3 = Tender::firstOrCreate(
            ['reference_no' => 'TND-2026-003'],
            [
                'title' => 'Annual Supply of Essential Medical Diagnostic Kits & Consumables',
                'department_id' => $deptHealth->id,
                'category' => 'Medical & Healthcare',
                'description' => 'Delivery of WHO pre-qualified reagent kits, blood analysis cassettes, disposable PPE, and automated pathology lab accessories on quarterly batch schedule.',
                'requirements' => '1. Drug Regulatory Authority (DRAP) valid registration\n2. Cold-chain logistics tracking certified\n3. Batch testing certificate with each dispatch',
                'budget' => 14500000.00,
                'submission_deadline' => now()->subDays(2),
                'opening_date' => now()->subDays(1),
                'status' => 'under_evaluation',
                'created_by' => $admin->id,
            ]
        );
        $t3->update(['department_id' => $deptHealth->id]);

        $t4 = Tender::firstOrCreate(
            ['reference_no' => 'TND-2026-004'],
            [
                'title' => 'Fleet Logistics & Transportation Services for Rural Distribution',
                'department_id' => $deptLogistics->id,
                'category' => 'Logistics & Supply Chain',
                'description' => 'Dedicated fleet of 30 GPS-equipped temperature-controlled container vehicles for inter-district supplies distribution with real-time telematics dashboard.',
                'requirements' => '1. Registered fleet with comprehensive insurance\n2. 99.5% delivery punctuality record\n3. 24/7 central dispatch monitoring center',
                'budget' => 18000000.00,
                'submission_deadline' => now()->subDays(20),
                'opening_date' => now()->subDays(18),
                'status' => 'awarded',
                'created_by' => $admin->id,
            ]
        );
        $t4->update(['department_id' => $deptLogistics->id]);

        // Sample Bids
        $bid1 = Bid::firstOrCreate(
            ['tender_id' => $t1->id, 'user_id' => $vendor1->id],
            [
                'bid_amount' => 23400000.00,
                'proposal_description' => 'Comprehensive enterprise server proposal with OEM partner authorization from Dell/HPE. Includes on-site spares inventory and certified engineers on standby.',
                'status' => 'submitted',
                'submitted_at' => now()->subDays(2),
            ]
        );

        $bid2 = Bid::firstOrCreate(
            ['tender_id' => $t3->id, 'user_id' => $vendor1->id],
            [
                'bid_amount' => 13800000.00,
                'proposal_description' => 'Authorized distributor quote with certified European cold-chain shipping and guaranteed 48-hour emergency batch turnaround.',
                'status' => 'under_review',
                'submitted_at' => now()->subDays(4),
            ]
        );

        $bid4 = Bid::firstOrCreate(
            ['tender_id' => $t4->id, 'user_id' => $vendor2->id],
            [
                'bid_amount' => 17200000.00,
                'proposal_description' => 'Complete fleet deployment with 30 refrigerated trucks, GPS telematics API, and 24/7 driver support team.',
                'status' => 'accepted',
                'submitted_at' => now()->subDays(18),
            ]
        );

        // Sample Bills against Awarded Tender T4
        $b1 = Bill::firstOrCreate(
            ['bill_number' => 'BILL-2026-001'],
            [
                'tender_id' => $t4->id,
                'vendor_id' => $vendor2->id,
                'department_id' => $deptLogistics->id,
                'gross_amount' => 5000000.00,
                'tax_deduction' => 250000.00, // 5% withholding tax
                'net_amount' => 4750000.00,
                'invoice_date' => now()->subDays(12),
                'due_date' => now()->subDays(2),
                'milestone_description' => 'Running Bill #1 - Initial Fleet Mobilization & GPS Integration (Phase 1 Complete)',
                'status' => 'paid',
                'payment_reference' => 'CHQ-HBL-9482014',
                'paid_at' => now()->subDays(5),
                'created_by' => $vendor2->id,
            ]
        );

        BillItem::firstOrCreate(
            ['bill_id' => $b1->id, 'item_name' => 'Heavy Container Fleet GPS Tracking Modules'],
            [
                'unit' => 'Units',
                'quantity' => 30,
                'unit_price' => 50000.00,
                'total_price' => 1500000.00,
            ]
        );

        BillItem::firstOrCreate(
            ['bill_id' => $b1->id, 'item_name' => 'Temperature Cold-Chain Sensor Probes'],
            [
                'unit' => 'Units',
                'quantity' => 60,
                'unit_price' => 25000.00,
                'total_price' => 1500000.00,
            ]
        );

        // Seed User's Exact Bill # 11709
        $demoBill = Bill::updateOrCreate(
            ['bill_number' => '11709'],
            [
                'vendor_id' => $vendor1->id,
                'department_id' => $deptHealth->id,
                'head_of_account' => 'Cost of Other Stores',
                'gross_amount' => 74925.00,
                'gst_rate' => 18.00,
                'gst_amount' => 13487.00,
                'tax_deduction' => 0.00,
                'net_amount' => 88412.00, // 74,925 + 13,487 GST
                'agpr_vendor_no' => '30368984',
                'strn_number' => '1700212811518',
                'ntn_number' => '2128115-7',
                'invoice_date' => now()->subDays(2),
                'due_date' => now()->addDays(20),
                'milestone_description' => 'Cost of Other Stores - Supply of Electrical, Safety and Maintenance Equipment for Office Premises',
                'status' => 'approved',
                'created_by' => $vendor1->id,
            ]
        );

        $demoItems = [
            ['item_name' => 'Led Tube Light Complete Set', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 2500, 'total_price' => 2500],
            ['item_name' => 'Led Bulb 24 Watts Phillips', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 495, 'total_price' => 495],
            ['item_name' => '6 Kg Dry Chemical Powder Fire Extinguisher', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 7000, 'total_price' => 7000],
            ['item_name' => 'Electric Switch Button Sheet 9+1', 'unit' => 'Nos', 'quantity' => 2, 'unit_price' => 1430, 'total_price' => 2860],
            ['item_name' => 'Electric Kettle', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 12530, 'total_price' => 12530],
            ['item_name' => 'Multi Board', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 1150, 'total_price' => 1150],
            ['item_name' => 'Network Point Sheet', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 950, 'total_price' => 950],
            ['item_name' => 'Power Plug Ac', 'unit' => 'Nos', 'quantity' => 4, 'unit_price' => 1200, 'total_price' => 4800],
            ['item_name' => 'Exaust Fan 6"', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 8500, 'total_price' => 8500],
            ['item_name' => 'Exaust Fan 12"', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 12000, 'total_price' => 12000],
            ['item_name' => 'Purchase Plastic Plant Pot', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 2000, 'total_price' => 2000],
            ['item_name' => 'Water Thermose', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 3500, 'total_price' => 3500],
            ['item_name' => 'Iron Pad Lock (Small)', 'unit' => 'Nos', 'quantity' => 3, 'unit_price' => 600, 'total_price' => 1800],
            ['item_name' => 'Breaker 20 Amp', 'unit' => 'Nos', 'quantity' => 2, 'unit_price' => 2250, 'total_price' => 4500],
            ['item_name' => 'Breaker 30 Amp', 'unit' => 'Nos', 'quantity' => 3, 'unit_price' => 3000, 'total_price' => 9000],
            ['item_name' => 'Electric Switch Button Sheet 7+1', 'unit' => 'Nos', 'quantity' => 1, 'unit_price' => 980, 'total_price' => 980],
            ['item_name' => 'Tape', 'unit' => 'Nos', 'quantity' => 3, 'unit_price' => 120, 'total_price' => 360],
        ];

        $demoBill->items()->delete();
        foreach ($demoItems as $it) {
            $demoBill->items()->create($it);
        }

        Bill::firstOrCreate(
            ['bill_number' => 'BILL-2026-002'],
            [
                'tender_id' => $t4->id,
                'vendor_id' => $vendor2->id,
                'department_id' => $deptLogistics->id,
                'gross_amount' => 6000000.00,
                'tax_deduction' => 300000.00,
                'net_amount' => 5700000.00,
                'invoice_date' => now()->subDays(3),
                'due_date' => now()->addDays(14),
                'milestone_description' => 'Running Bill #2 - First Quarter Inter-District Transport Operations & Telematics Verification',
                'status' => 'approved',
                'created_by' => $vendor2->id,
            ]
        );

        Bill::firstOrCreate(
            ['bill_number' => 'BILL-2026-003'],
            [
                'tender_id' => $t1->id,
                'vendor_id' => $vendor1->id,
                'department_id' => $deptIT->id,
                'gross_amount' => 8000000.00,
                'tax_deduction' => 400000.00,
                'net_amount' => 7600000.00,
                'invoice_date' => now()->subDays(1),
                'due_date' => now()->addDays(20),
                'milestone_description' => 'Advance Milestone Invoice - OEM Hardware Shipment & Customs Clearance',
                'status' => 'pending',
                'created_by' => $vendor1->id,
            ]
        );
    }
}
