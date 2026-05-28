<?php

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained('categories')->nullOnDelete();
        });

        Course::whereNotNull('category')->chunkById(100, function ($courses) {
            foreach ($courses as $course) {
                $category = Category::where('name', $course->category)->first();
                if ($category) {
                    $course->updateQuietly(['category_id' => $category->id]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
