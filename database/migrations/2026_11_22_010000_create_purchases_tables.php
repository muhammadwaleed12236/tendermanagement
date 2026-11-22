<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add current_balance to vendors table if not exists
        if (!Schema::hasColumn('vendors', 'current_balance')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->decimal('current_balance', 15, 2)->default(0)->after('opening_balance');
            });
            DB::statement("UPDATE vendors SET current_balance = opening_balance");
        }

        // 2. Purchases Header Table
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_number')->unique(); // e.g. PUR-20261122-0001
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->date('purchase_date');
            $table->string('payment_status')->default('unpaid'); // 'paid', 'unpaid'
            $table->decimal('subtotal_amount', 15, 2)->default(0); // Base items sum
            $table->decimal('gst_amount', 15, 2)->default(0);      // Total GST
            $table->decimal('sst_amount', 15, 2)->default(0);      // Total SST
            $table->decimal('total_amount', 15, 2)->default(0);    // Net Total Payable
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Purchase Items Table
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('item_description');
            $table->string('unit')->default('Nos');
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('gst_percent', 5, 2)->default(0);  // Optional GST %
            $table->decimal('gst_amount', 15, 2)->default(0);   // Calculated GST
            $table->decimal('sst_percent', 5, 2)->default(0);  // Optional SST %
            $table->decimal('sst_amount', 15, 2)->default(0);   // Calculated SST
            $table->decimal('total_amount', 15, 2)->default(0); // (Qty * Price) + GST + SST
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');

        if (Schema::hasColumn('vendors', 'current_balance')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropColumn('current_balance');
            });
        }
    }
};
