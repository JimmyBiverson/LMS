<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_discussions', function (Blueprint $table) {
            $table->string('title')->nullable()->after('course_id');
            $table->boolean('is_solved')->default(false)->after('body');
            $table->integer('upvotes')->default(0)->after('is_solved');
        });
    }

    public function down(): void
    {
        Schema::table('course_discussions', function (Blueprint $table) {
            $table->dropColumn(['title', 'is_solved', 'upvotes']);
        });
    }
};
