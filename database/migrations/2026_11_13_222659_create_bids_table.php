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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tenders')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // vendor
            $table->decimal('bid_amount', 15, 2);
            $table->text('proposal_description');
            $table->string('document_path')->nullable();
            $table->string('status')->default('submitted'); // submitted, under_review, accepted, rejected
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            // A vendor can only submit one bid per tender
            $table->unique(['tender_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
