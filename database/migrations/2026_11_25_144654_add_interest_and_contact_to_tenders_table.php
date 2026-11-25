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
            $table->string('interest_status')->default('interested')->after('status');
            $table->string('contact_person')->nullable()->after('interest_status');
            $table->boolean('is_fully_processed')->default(false)->after('contact_person');
            $table->boolean('initial_entry_only')->default(false)->after('is_fully_processed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn(['interest_status', 'contact_person', 'is_fully_processed', 'initial_entry_only']);
        });
    }
};
