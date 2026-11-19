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
        Schema::create('department_budget_heads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('year')->default('2026');
            $table->string('head_code')->nullable(); // e.g. A03302
            $table->string('head_name'); // e.g. A03302-WATER or WATER
            $table->decimal('original_budget', 15, 2)->default(0);
            $table->decimal('expenditure', 15, 2)->default(0);
            $table->decimal('released_budget', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_budget_heads');
    }
};
