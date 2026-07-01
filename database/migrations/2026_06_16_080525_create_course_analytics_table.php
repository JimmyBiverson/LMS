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
        Schema::create('course_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->date('date');
            $table->integer('views_count')->default(0);
            $table->integer('enrollments_count')->default(0);
            $table->integer('completions_count')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->timestamps();
            
            // Indexes for filtering and aggregation queries
            $table->index(['course_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_analytics');
    }
};
