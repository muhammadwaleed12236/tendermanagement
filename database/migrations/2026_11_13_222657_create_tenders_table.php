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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('reference_no')->unique();
            $table->string('category')->default('General');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->dateTime('submission_deadline');
            $table->dateTime('opening_date')->nullable();
            $table->string('document_path')->nullable();
            $table->string('status')->default('published'); // draft, published, under_evaluation, awarded, closed, cancelled
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
