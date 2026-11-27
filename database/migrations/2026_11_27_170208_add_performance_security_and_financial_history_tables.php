<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->decimal('performance_security_amount', 15, 2)->nullable()->after('bid_security_file');
            $table->string('performance_security_status', 50)->default('no_fees')->after('performance_security_amount');
            $table->string('performance_certificate_no')->nullable()->after('performance_security_status');
            $table->date('performance_security_date')->nullable()->after('performance_certificate_no');
            $table->string('performance_security_file')->nullable()->after('performance_security_date');
            $table->decimal('bid_security_refund_amount', 15, 2)->nullable()->after('bid_security_released_at');
            $table->string('bid_security_refund_notes')->nullable()->after('bid_security_refund_amount');
        });

        Schema::create('tender_financial_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tenders')->cascadeOnDelete();
            $table->string('tender_reference')->nullable();
            $table->string('vendor_name')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamp('action_date')->nullable();
            $table->string('transaction_type', 60); // bid_security_collection, bid_security_refund, performance_security_collection
            $table->string('current_status', 50);
            $table->string('document_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_financial_histories');

        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn([
                'performance_security_amount',
                'performance_security_status',
                'performance_certificate_no',
                'performance_security_date',
                'performance_security_file',
                'bid_security_refund_amount',
                'bid_security_refund_notes',
            ]);
        });
    }
};
