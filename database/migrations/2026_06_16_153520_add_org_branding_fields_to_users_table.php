<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('profile_image');
            $table->string('primary_color')->nullable()->default('#5F3EED')->after('logo');
            $table->string('secondary_color')->nullable()->default('#F4B826')->after('primary_color');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['logo', 'primary_color', 'secondary_color']);
        });
    }
};
