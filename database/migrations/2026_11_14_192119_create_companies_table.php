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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('vendor_no')->nullable(); // AGPR / Vendor No
            $table->string('ntn')->nullable();
            $table->string('gst')->nullable(); // GST / STRN
            $table->string('sst')->nullable(); // Sindh Sales Tax
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_default')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Add company_id and sst_number to bills if not exists
        Schema::table('bills', function (Blueprint $table) {
            if (!Schema::hasColumn('bills', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('vendor_id')->constrained('companies')->nullOnDelete();
            }
            if (!Schema::hasColumn('bills', 'sst_number')) {
                $table->string('sst_number')->nullable()->after('strn_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (Schema::hasColumn('bills', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }
            if (Schema::hasColumn('bills', 'sst_number')) {
                $table->dropColumn('sst_number');
            }
        });

        Schema::dropIfExists('companies');
    }
};
