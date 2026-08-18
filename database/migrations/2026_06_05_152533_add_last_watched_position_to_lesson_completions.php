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
        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->integer('last_watched_position')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->dropColumn('last_watched_position');
        });
    }
};
