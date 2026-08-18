<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->boolean('is_exam')->default(false)->after('certificate_on_pass');
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete()->after('is_exam');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['is_exam', 'class_id']);
        });
    }
};
