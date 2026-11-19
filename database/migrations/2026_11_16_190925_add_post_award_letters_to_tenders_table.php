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
        Schema::table('tenders', function (Blueprint $table) {
            // Letter of Intent (LOI)
            $table->boolean('has_letter_of_intent')->default(false)->after('tender_outcome');
            $table->string('letter_of_intent_ref')->nullable()->after('has_letter_of_intent');
            $table->date('letter_of_intent_date')->nullable()->after('letter_of_intent_ref');
            $table->string('letter_of_intent_file')->nullable()->after('letter_of_intent_date');

            // Award Letter / Work Order
            $table->boolean('has_award_letter')->default(false)->after('letter_of_intent_file');
            $table->string('award_letter_ref')->nullable()->after('has_award_letter');
            $table->date('award_letter_date')->nullable()->after('award_letter_ref');
            $table->string('award_letter_file')->nullable()->after('award_letter_date');

            // Overall post-award documents status
            $table->string('documents_status')->default('pending')->after('award_letter_file'); // pending, completed
            $table->dateTime('documents_completed_at')->nullable()->after('documents_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn([
                'has_letter_of_intent',
                'letter_of_intent_ref',
                'letter_of_intent_date',
                'letter_of_intent_file',
                'has_award_letter',
                'award_letter_ref',
                'award_letter_date',
                'award_letter_file',
                'documents_status',
                'documents_completed_at',
            ]);
        });
    }
};
