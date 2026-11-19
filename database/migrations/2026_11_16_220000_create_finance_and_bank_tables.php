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
        // 1. Bank Accounts & Cash in Hand Table
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name'); // e.g. Meezan Bank, HBL, Cash in Hand
            $table->string('account_title'); // e.g. ProWave Technologies
            $table->string('account_number'); // e.g. 0102-0100234567 or CASH-01
            $table->string('iban')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('account_type')->default('current'); // current, savings, escrow, petty_cash
            $table->string('currency', 10)->default('PKR');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->string('status', 20)->default('active'); // active, inactive, closed
            $table->boolean('is_default')->default(false);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Expense Categories Table
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Office Rent, Utilities, Fuel & Transport
            $table->string('code')->nullable(); // e.g. EXP-RENT
            $table->string('color', 20)->default('#4f46e5'); // Hex color for UI badges
            $table->text('description')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        // 3. Expenses Table
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique(); // e.g. EXP-2026-0001
            $table->foreignId('expense_category_id')->constrained('expense_categories')->cascadeOnDelete();
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('title'); // e.g. Generator Fuel & Mobil Oil
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->string('payment_method', 30)->default('cash'); // cash, bank_transfer, cheque, online
            $table->string('cheque_no')->nullable();
            $table->string('payee_name')->nullable(); // Paid to person/vendor
            $table->string('receipt_file')->nullable(); // Attachment
            $table->text('description')->nullable();
            $table->string('status', 20)->default('paid'); // paid, pending, cancelled
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Bank Transactions & Cashbook Table
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no')->unique(); // e.g. TXN-2026-0001
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->string('type', 10); // 'credit' (deposit/inflow), 'debit' (withdrawal/outflow)
            $table->string('category', 40); // expense, client_payment, cheque_clearance, transfer_in, transfer_out, deposit, withdrawal, opening_balance
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->date('transaction_date');
            $table->string('reference_no')->nullable(); // Cheque #, Receipt #, Voucher #
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->nullOnDelete();
            $table->foreignId('cheque_id')->nullable()->constrained('cheques')->nullOnDelete();
            $table->foreignId('transfer_to_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('bank_accounts');
    }
};
