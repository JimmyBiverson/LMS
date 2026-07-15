<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bundles')) {
            Schema::create('bundles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->decimal('sale_price', 10, 2)->nullable();
                $table->string('level')->nullable();
                $table->string('thumbnail')->nullable();
                $table->string('status')->default('active');
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('bundle_course')) {
            Schema::create('bundle_course', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
                $table->foreignId('course_id')->constrained()->cascadeOnDelete();
                $table->unique(['bundle_id', 'course_id']);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Intentionally left empty to avoid destructive rollback.
    }
};
