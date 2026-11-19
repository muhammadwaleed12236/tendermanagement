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
            $table->string('tender_no')->nullable()->after('reference_no');
            $table->string('bidding_procedure')->default('single_stage_one_envelope')->after('tender_no'); // single_stage_one_envelope, single_stage_two_envelope
            $table->date('collection_start_date')->nullable()->after('bidding_procedure');
            $table->string('collection_start_notes')->nullable()->default('During working hours')->after('collection_start_date');
            $table->date('collection_end_date')->nullable()->after('collection_start_notes');
            $table->string('collection_end_notes')->nullable()->default('During working hours')->after('collection_end_date');
            $table->dateTime('technical_opening_date')->nullable()->after('submission_deadline');
            $table->dateTime('financial_opening_date')->nullable()->after('technical_opening_date');
            
            // Workflow stage management
            $table->string('current_stage')->default('stage_1')->after('financial_opening_date'); // stage_1, stage_2, completed
            $table->string('stage_1_status')->default('pending')->after('current_stage'); // pending, qualified, disqualified
            $table->text('stage_1_notes')->nullable()->after('stage_1_status');
            $table->dateTime('stage_1_completed_at')->nullable()->after('stage_1_notes');
            
            $table->string('stage_2_status')->nullable()->after('stage_1_completed_at'); // pending, won, lost
            $table->text('stage_2_notes')->nullable()->after('stage_2_status');
            $table->dateTime('stage_2_completed_at')->nullable()->after('stage_2_notes');
            
            $table->string('tender_outcome')->default('in_progress')->after('stage_2_completed_at'); // in_progress, won, lost, cancelled
            $table->foreignId('company_id')->nullable()->after('department_id')->constrained('companies')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn([
                'tender_no',
                'bidding_procedure',
                'collection_start_date',
                'collection_start_notes',
                'collection_end_date',
                'collection_end_notes',
                'technical_opening_date',
                'financial_opening_date',
                'current_stage',
                'stage_1_status',
                'stage_1_notes',
                'stage_1_completed_at',
                'stage_2_status',
                'stage_2_notes',
                'stage_2_completed_at',
                'tender_outcome',
                'company_id',
            ]);
        });
    }
};
