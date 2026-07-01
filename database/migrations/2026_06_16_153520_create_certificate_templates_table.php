<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('background_image')->nullable();
            $table->string('logo_position')->default('top-center');
            $table->string('title_font')->default('sans-serif');
            $table->string('title_color')->default('#111827');
            $table->string('body_color')->default('#374151');
            $table->json('layout')->nullable();
            $table->boolean('include_qr')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
