<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_content', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->enum('type', ['text', 'html', 'json', 'image', 'video'])->default('text');
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Add composite index on category, is_active, display_order
            $table->index(['category', 'is_active', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_content');
    }
};
