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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('bill_id')->nullable()->constrained('bills')->nullOnDelete();
            $table->string('cost_center')->nullable();
            $table->string('department_name')->nullable();
            $table->string('company_name'); // Vendor / Contractor / Supplier
            $table->string('token_number'); // Received at cheque time (e.g. 023359, 123456)
            $table->string('document_number'); // Cheque # / Document # (e.g. 1500157809)
            $table->date('cheque_date');
            $table->decimal('cheque_amount', 15, 2)->default(0);
            $table->decimal('bill_amount', 15, 2)->default(0);
            
            // Deductions & Differences
            $table->decimal('gst_amount', 15, 2)->default(0); // 18% GST Amount
            $table->decimal('itax_amount', 15, 2)->default(0); // 5.5% or 15% Income Tax Amount
            $table->decimal('sst_amount', 15, 2)->default(0); // 15% SST Amount
            $table->decimal('sst_difference', 15, 2)->default(0); // SST Difference
            $table->decimal('vendor_gst_difference', 15, 2)->default(0); // Vendor GST Difference
            
            // Commission, Total Deductions & Net Payable
            $table->decimal('xyz_commission', 15, 2)->default(0); // XYZ Commission
            $table->decimal('total_deduction', 15, 2)->default(0); // Total Deduction Amount
            $table->decimal('amount_to_paid_department', 15, 2)->default(0); // Amount to be Paid Department
            $table->decimal('vendor_profit', 15, 2)->default(0); // Vendor Total Profit
            
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
