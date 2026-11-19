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
        Schema::table('budget_releases', function (Blueprint $table) {
            $table->foreignId('budget_head_id')->nullable()->after('department_id')->constrained('department_budget_heads')->nullOnDelete();
            $table->json('head_allocations')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_releases', function (Blueprint $table) {
            $table->dropForeign(['budget_head_id']);
            $table->dropColumn(['budget_head_id', 'head_allocations']);
        });
    }
};
