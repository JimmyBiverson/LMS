<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('course_tag')) {
            $tagsExists = Schema::hasTable('tags');

            Schema::create('course_tag', function (Blueprint $table) use ($tagsExists) {
                $table->foreignId('course_id')->constrained()->cascadeOnDelete();
                if ($tagsExists) {
                    $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
                } else {
                    $table->unsignedBigInteger('tag_id');
                }
                $table->primary(['course_id', 'tag_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_tag');
    }
};
