<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSchemaRepairService
{
    public function ensureBundleTables(): void
    {
        if (!Schema::hasTable('bundles')) {
            Schema::create('bundles', function ($table) {
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
            Schema::create('bundle_course', function ($table) {
                $table->id();
                $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
                $table->foreignId('course_id')->constrained()->cascadeOnDelete();
                $table->unique(['bundle_id', 'course_id']);
                $table->timestamps();
            });
        }
    }

    public function ensureUserProfileColumns(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function ($table) {
                $table->string('role')->default('student')->after('email');
            });
        }

        if (!Schema::hasColumn('users', 'status')) {
            Schema::table('users', function ($table) {
                $table->string('status')->default('active')->after('role');
            });
        }
    }
}
