<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('reminder_type')->default('daily')->comment('daily, weekly, deadline, milestone');
            $table->timestamp('remind_at')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'remind_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_reminders');
    }
};
