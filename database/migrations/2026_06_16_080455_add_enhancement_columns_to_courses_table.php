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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('preview_video')->nullable()->after('thumbnail');
            $table->integer('preview_video_duration')->nullable()->after('preview_video');
            $table->timestamp('thumbnail_updated_at')->nullable()->after('preview_video_duration');
            $table->integer('enrollment_count')->default(0)->after('thumbnail_updated_at');
            $table->decimal('average_rating', 3, 2)->nullable()->after('enrollment_count');
            $table->decimal('completion_rate', 5, 2)->nullable()->after('average_rating');
            $table->boolean('is_featured')->default(false)->after('completion_rate');
            $table->json('metadata')->nullable()->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'preview_video',
                'preview_video_duration',
                'thumbnail_updated_at',
                'enrollment_count',
                'average_rating',
                'completion_rate',
                'is_featured',
                'metadata'
            ]);
        });
    }
};
