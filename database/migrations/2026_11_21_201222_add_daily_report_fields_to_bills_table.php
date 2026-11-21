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
        Schema::table('bills', function (Blueprint $table) {
            if (!Schema::hasColumn('bills', 'quarter')) {
                $table->string('quarter')->nullable()->default('1st Qtr')->after('bill_number');
            }
            if (!Schema::hasColumn('bills', 'head_code')) {
                $table->string('head_code')->nullable()->after('head_of_account');
            }
            if (!Schema::hasColumn('bills', 'head_description')) {
                $table->string('head_description')->nullable()->after('head_code');
            }
            if (!Schema::hasColumn('bills', 'cost_center')) {
                $table->string('cost_center')->nullable()->after('head_description');
            }
            if (!Schema::hasColumn('bills', 'dispatch_date')) {
                $table->date('dispatch_date')->nullable()->after('due_date');
            }
            if (!Schema::hasColumn('bills', 'remarks')) {
                $table->string('remarks')->nullable()->after('dispatch_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn([
                'quarter',
                'head_code',
                'head_description',
                'cost_center',
                'dispatch_date',
                'remarks',
            ]);
        });
    }
};
