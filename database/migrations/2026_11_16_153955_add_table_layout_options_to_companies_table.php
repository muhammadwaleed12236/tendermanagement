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
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('show_unit_column')->default(true)->after('body_style');
            $table->boolean('show_sr_column')->default(true)->after('show_unit_column');
            $table->string('total_layout', 50)->default('auto')->after('show_sr_column'); // auto, card_box, in_table_footer, split_banner, wide_bar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['show_unit_column', 'show_sr_column', 'total_layout']);
        });
    }
};
