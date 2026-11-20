<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Sirf user accounts create kiye jaate hain.
     */
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
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
        User::firstOrCreate(
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
        User::firstOrCreate(
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

        // 4. Vendor 2
        User::firstOrCreate(
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
    }
}
