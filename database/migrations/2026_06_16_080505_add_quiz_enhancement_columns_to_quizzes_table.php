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
        Schema::table('quizzes', function (Blueprint $table) {
            // Add randomize_options
            $table->boolean('randomize_options')->default(false)->after('passing_score')->comment('Whether to randomize answer options');
            
            // Add show_results_immediately
            $table->boolean('show_results_immediately')->default(false)->after('randomize_options')->comment('Show results right after completion');
            
            // Add certificate_on_pass
            $table->boolean('certificate_on_pass')->default(false)->after('show_results_immediately')->comment('Generate certificate when student passes');
            
            // Add proctoring_required
            $table->boolean('proctoring_required')->default(false)->after('certificate_on_pass')->comment('Whether proctoring is required');
        });
        
        // Note: The passing_score column already exists as integer with default 50.
        // The design specifies it should be decimal and nullable.
        // To avoid breaking changes, we keep the existing passing_score column.
        // If conversion is needed in the future, a separate data migration should be created.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'randomize_options',
                'show_results_immediately',
                'certificate_on_pass',
                'proctoring_required',
            ]);
        });
    }
};
