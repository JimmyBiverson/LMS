<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->index('status', 'courses_status_index');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('status', 'enrollments_status_index');
            $table->index('user_id', 'enrollments_user_id_index');
        });

        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->index('lesson_id', 'lesson_completions_lesson_id_index');
            $table->index('user_id', 'lesson_completions_user_id_index');
            $table->index('course_id', 'lesson_completions_course_id_index');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->index('status', 'lessons_status_index');
        });

        Schema::table('quiz_results', function (Blueprint $table) {
            $table->index(['quiz_id', 'user_id'], 'quiz_results_quiz_user_index');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_status_index');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_status_index');
            $table->dropIndex('enrollments_user_id_index');
        });

        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->dropIndex('lesson_completions_lesson_id_index');
            $table->dropIndex('lesson_completions_user_id_index');
            $table->dropIndex('lesson_completions_course_id_index');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex('lessons_status_index');
        });

        Schema::table('quiz_results', function (Blueprint $table) {
            $table->dropIndex('quiz_results_quiz_user_index');
        });
    }
};
