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
            // TENDER FORM / DOCUMENT FEE
            $table->decimal('tender_fee_amount', 15, 2)->nullable()->after('budget');
            $table->boolean('is_tender_fee_paid')->default(false)->after('tender_fee_amount');
            $table->foreignId('tender_fee_bank_account_id')->nullable()->after('is_tender_fee_paid')->constrained('bank_accounts')->nullOnDelete();
            $table->string('tender_fee_payment_mode')->nullable()->after('tender_fee_bank_account_id'); // cash, pay_order, cheque, online_transfer
            $table->string('tender_fee_ref_no')->nullable()->after('tender_fee_payment_mode'); // PO / Cheque / Slip #
            $table->date('tender_fee_date')->nullable()->after('tender_fee_ref_no');
            $table->string('tender_fee_receipt_file')->nullable()->after('tender_fee_date');

            // BID SECURITY / EARNEST MONEY (CDR / PAY ORDER)
            $table->decimal('bid_security_amount', 15, 2)->nullable()->after('tender_fee_receipt_file');
            $table->decimal('bid_security_percentage', 5, 2)->nullable()->after('bid_security_amount'); // e.g. 2.00%
            $table->string('bid_security_type')->default('pay_order')->after('bid_security_percentage'); // pay_order, cdr, bank_guarantee, cheque
            $table->foreignId('bid_security_bank_account_id')->nullable()->after('bid_security_type')->constrained('bank_accounts')->nullOnDelete();
            $table->string('bid_security_instrument_no')->nullable()->after('bid_security_bank_account_id'); // CDR / PO Number
            $table->date('bid_security_date')->nullable()->after('bid_security_instrument_no');
            $table->date('bid_security_expiry_date')->nullable()->after('bid_security_date');
            $table->string('bid_security_status')->default('submitted')->after('bid_security_expiry_date'); // submitted, released_returned, forfeited, adjusted
            $table->dateTime('bid_security_released_at')->nullable()->after('bid_security_status');
            $table->string('bid_security_file')->nullable()->after('bid_security_released_at');

            // CONTRACT AWARD & WORK ORDER DETAILS (FOR WON TENDERS REPORTING)
            $table->decimal('awarded_amount', 15, 2)->nullable()->after('bid_security_file');
            $table->string('work_order_no')->nullable()->after('awarded_amount');
            $table->date('work_order_date')->nullable()->after('work_order_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropForeign(['tender_fee_bank_account_id']);
            $table->dropForeign(['bid_security_bank_account_id']);
            $table->dropColumn([
                'tender_fee_amount',
                'is_tender_fee_paid',
                'tender_fee_bank_account_id',
                'tender_fee_payment_mode',
                'tender_fee_ref_no',
                'tender_fee_date',
                'tender_fee_receipt_file',
                'bid_security_amount',
                'bid_security_percentage',
                'bid_security_type',
                'bid_security_bank_account_id',
                'bid_security_instrument_no',
                'bid_security_date',
                'bid_security_expiry_date',
                'bid_security_status',
                'bid_security_released_at',
                'bid_security_file',
                'awarded_amount',
                'work_order_no',
                'work_order_date',
            ]);
        });
    }
};
