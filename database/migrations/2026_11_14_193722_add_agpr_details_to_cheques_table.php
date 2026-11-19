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
        Schema::table('cheques', function (Blueprint $table) {
            $table->decimal('itax_5_5_amount', 15, 2)->default(0)->after('itax_amount');
            $table->decimal('itax_15_amount', 15, 2)->default(0)->after('itax_5_5_amount');
            $table->decimal('agpr_purchased_gst', 15, 2)->default(0)->after('vendor_gst_difference');
            $table->decimal('agpr_purchased_itax', 15, 2)->default(0)->after('agpr_purchased_gst');
            $table->decimal('agpr_repaired_sst', 15, 2)->default(0)->after('agpr_purchased_itax');
            $table->decimal('agpr_repaired_itax', 15, 2)->default(0)->after('agpr_repaired_sst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cheques', function (Blueprint $table) {
            $table->dropColumn([
                'itax_5_5_amount',
                'itax_15_amount',
                'agpr_purchased_gst',
                'agpr_purchased_itax',
                'agpr_repaired_sst',
                'agpr_repaired_itax',
            ]);
        });
    }
};
