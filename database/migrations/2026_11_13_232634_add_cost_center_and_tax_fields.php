<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string('cost_center_description')->nullable(); // e.g. "Office of the XYZ", "Cost Center Karachi"
            $table->string('city')->nullable()->default('Karachi.');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('strn_number')->nullable(); // Sales Tax Registration No
            $table->string('agpr_vendor_no')->nullable(); // AGPR Vendor # e.g. 30368984
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->string('head_of_account')->nullable()->default('Cost of Other Stores'); // Bill subhead
            $table->decimal('gst_rate', 5, 2)->default(18.00); // 18% GST
            $table->decimal('gst_amount', 15, 2)->default(0);
            $table->string('agpr_vendor_no')->nullable();
            $table->string('strn_number')->nullable();
            $table->string('ntn_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn(['head_of_account', 'gst_rate', 'gst_amount', 'agpr_vendor_no', 'strn_number', 'ntn_number']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['strn_number', 'agpr_vendor_no']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['cost_center_description', 'city']);
        });
    }
};
