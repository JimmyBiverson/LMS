<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('quizzes', 'course_id')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->foreignId('course_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('quizzes', 'user_id')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('course_id')->constrained('users')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('assignments', 'course_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->foreignId('course_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('assignments', 'user_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('course_id')->constrained('users')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('quizzes', 'instructions')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->text('instructions')->nullable();
            });
        }

        if (!Schema::hasColumn('quizzes', 'time_limit')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->integer('time_limit')->nullable();
            });
        }

        if (!Schema::hasColumn('quizzes', 'passing_score')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->integer('passing_score')->default(50);
            });
        }

        if (!Schema::hasColumn('quizzes', 'total_marks')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->integer('total_marks')->default(0);
            });
        }

        if (!Schema::hasColumn('quizzes', 'status')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->string('status')->default('draft');
            });
        }

        if (!Schema::hasColumn('quizzes', 'is_exam')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->boolean('is_exam')->default(false);
            });
        }

        if (!Schema::hasColumn('assignments', 'instructions')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->text('instructions')->nullable();
            });
        }

        if (!Schema::hasColumn('assignments', 'due_date')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->date('due_date')->nullable();
            });
        }

        if (!Schema::hasColumn('assignments', 'total_marks')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->integer('total_marks')->default(100);
            });
        }

        if (!Schema::hasColumn('assignments', 'status')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->string('status')->default('draft');
            });
        }
    }

    public function down(): void
    {
        // no-op
    }
};
