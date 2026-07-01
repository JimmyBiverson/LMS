<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->nullable();
            $table->string('school_email')->nullable();
            $table->string('school_phone')->nullable();
            $table->text('school_address')->nullable();
            $table->string('currency_symbol')->default('$');
            $table->string('currency_code')->default('USD');
            $table->string('currency_position')->default('left');
            $table->string('timezone')->default('UTC');
            $table->string('language')->default('en');
            $table->string('favicon')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('primary_color')->default('#5F3EED');
            $table->string('secondary_color')->default('#F4B826');
            $table->string('accent_color')->default('#1AEBC5');
            $table->text('custom_css')->nullable();
            $table->string('slider_video')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
