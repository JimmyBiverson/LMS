<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        foreach (DB::table('courses')->whereNull('slug')->cursor() as $course) {
            $base = Str::slug($course->title);
            $slug = $base;
            $i = 1;
            while (DB::table('courses')->where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('courses')->where('id', $course->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
