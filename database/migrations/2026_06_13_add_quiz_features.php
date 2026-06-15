<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Add new quiz configuration fields
            $table->boolean('shuffle_questions')->default(false)->after('passing_score')->comment('Randomize question order for each student');
            $table->boolean('shuffle_options')->default(false)->after('shuffle_questions')->comment('Randomize answer options for multiple choice');
            $table->boolean('show_answers_after')->default(true)->after('shuffle_options')->comment('Show correct answers after submission');
            $table->boolean('show_score_immediately')->default(true)->after('show_answers_after')->comment('Show score right after submission');
            $table->integer('question_pool')->nullable()->after('show_score_immediately')->comment('Total questions available for random selection');
            $table->integer('questions_per_attempt')->nullable()->after('question_pool')->comment('How many questions to show per attempt (if using pool)');
            $table->enum('grading_method', ['best_score', 'latest', 'average'])->default('best_score')->after('questions_per_attempt')->comment('How to grade multiple attempts');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'shuffle_questions',
                'shuffle_options',
                'show_answers_after',
                'show_score_immediately',
                'question_pool',
                'questions_per_attempt',
                'grading_method',
            ]);
        });
    }
};
