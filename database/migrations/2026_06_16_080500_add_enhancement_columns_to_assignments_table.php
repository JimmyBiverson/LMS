<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Time limit for completing the assignment (in minutes)
            $table->integer('time_limit_minutes')->nullable()->after('due_date');
            
            // Maximum file size allowed for uploads (in megabytes)
            $table->integer('max_file_size_mb')->default(10)->after('time_limit_minutes');
            
            // Array of allowed file types (e.g., ['pdf', 'docx', 'zip'])
            $table->json('allowed_file_types')->nullable()->after('max_file_size_mb');
            
            // Whether late submissions are accepted
            $table->boolean('late_submission_allowed')->default(false)->after('allowed_file_types');
            
            // Percentage penalty for late submissions
            $table->decimal('late_penalty_percent', 5, 2)->nullable()->after('late_submission_allowed');
            
            // JSON structure defining grading criteria
            $table->json('grading_rubric')->nullable()->after('late_penalty_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn([
                'time_limit_minutes',
                'max_file_size_mb',
                'allowed_file_types',
                'late_submission_allowed',
                'late_penalty_percent',
                'grading_rubric',
            ]);
        });
    }
};
