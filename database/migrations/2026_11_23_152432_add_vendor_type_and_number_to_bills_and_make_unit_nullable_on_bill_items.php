<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add vendor_type and vendor_number to bills table
        Schema::table('bills', function (Blueprint $table) {
            if (!Schema::hasColumn('bills', 'vendor_type')) {
                $table->string('vendor_type')->nullable()->default('AGPR');
            }
            if (!Schema::hasColumn('bills', 'vendor_number')) {
                $table->string('vendor_number')->nullable();
            }
        });

        // Backfill existing bills with vendor_type 'AGPR' and vendor_number from agpr_vendor_no
        DB::table('bills')->whereNull('vendor_type')->update(['vendor_type' => 'AGPR']);
        DB::table('bills')->whereNull('vendor_number')->whereNotNull('agpr_vendor_no')->update([
            'vendor_number' => DB::raw('agpr_vendor_no'),
        ]);

        // 2. Make unit column nullable on bill_items table
        Schema::table('bill_items', function (Blueprint $table) {
            $table->string('unit')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->string('unit')->default('Nos')->change();
        });

        Schema::table('bills', function (Blueprint $table) {
            if (Schema::hasColumn('bills', 'vendor_number')) {
                $table->dropColumn('vendor_number');
            }
            if (Schema::hasColumn('bills', 'vendor_type')) {
                $table->dropColumn('vendor_type');
            }
        });
    }
};
