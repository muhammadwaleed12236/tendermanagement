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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('letterhead_type')->default('system_default')->after('invoice_template'); // system_default, header_footer, full_page
            $table->string('header_image')->nullable()->after('letterhead_type');
            $table->string('footer_image')->nullable()->after('header_image');
            $table->string('full_letterhead_image')->nullable()->after('footer_image');
            $table->integer('header_height')->default(140)->after('full_letterhead_image'); // px
            $table->integer('footer_height')->default(80)->after('header_height'); // px
            $table->integer('body_top_margin')->default(150)->after('footer_height'); // px
            $table->integer('body_bottom_margin')->default(90)->after('body_top_margin'); // px
            $table->integer('body_side_margin')->default(45)->after('body_bottom_margin'); // px
            $table->boolean('show_on_print')->default(true)->after('body_side_margin'); // whether to print graphics or blank for stationary
            $table->string('default_subject')->nullable()->after('show_on_print');
            $table->text('default_terms')->nullable()->after('default_subject');
            $table->string('signatory_title')->nullable()->after('default_terms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'letterhead_type',
                'header_image',
                'footer_image',
                'full_letterhead_image',
                'header_height',
                'footer_height',
                'body_top_margin',
                'body_bottom_margin',
                'body_side_margin',
                'show_on_print',
                'default_subject',
                'default_terms',
                'signatory_title',
            ]);
        });
    }
};
