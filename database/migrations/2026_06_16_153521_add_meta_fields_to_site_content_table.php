<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_content', function (Blueprint $table) {
            $table->string('page_section')->nullable()->after('category');
            $table->string('icon')->nullable()->after('page_section');
            $table->string('button_text')->nullable()->after('icon');
            $table->string('button_link')->nullable()->after('button_text');
            $table->integer('sort_order')->default(0)->after('display_order');
        });
    }

    public function down(): void
    {
        Schema::table('site_content', function (Blueprint $table) {
            $table->dropColumn(['page_section', 'icon', 'button_text', 'button_link', 'sort_order']);
        });
    }
};
