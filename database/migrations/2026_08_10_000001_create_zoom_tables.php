<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('meet_provider_id')->nullable()->constrained('meet_providers')->nullOnDelete();
            $table->integer('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->integer('lesson_id')->nullable()->constrained('lessons')->nullOnDelete();
            $table->integer('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('scope_type', 20)->default('course')->index();
            $table->string('topic', 255);
            $table->text('agenda')->nullable();
            $table->dateTime('start_time');
            $table->string('timezone', 64)->default('UTC');
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->unsignedTinyInteger('meeting_type')->default(2);
            $table->string('zoom_meeting_id', 64)->nullable()->unique();
            $table->string('zoom_uuid', 64)->nullable();
            $table->text('join_url')->nullable();
            $table->text('start_url')->nullable();
            $table->string('password', 64)->nullable();
            $table->string('host_email', 191)->nullable();
            $table->string('status', 20)->default('scheduled');
            $table->boolean('is_recurring')->default(false);
            $table->json('recurring_settings')->nullable();
            $table->string('recording_status', 20)->default('none');
            $table->text('recording_url')->nullable();
            $table->string('recording_password', 64)->nullable();
            $table->json('recording_files')->nullable();
            $table->boolean('recording_published')->default(false);
            $table->boolean('has_attendance')->default(false);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->index(['course_id', 'status', 'start_time']);
            $table->index(['instructor_id', 'status', 'start_time']);
            $table->index('status');
            $table->index('start_time');
        });

        Schema::create('zoom_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('zoom_meetings')->cascadeOnDelete();
            $table->integer('student_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('join_time')->nullable();
            $table->dateTime('leave_time')->nullable();
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->string('status', 20)->default('absent');
            $table->string('source', 20)->default('manual');
            $table->timestamps();

            $table->unique(['meeting_id', 'student_id']);
            $table->index('student_id');
            $table->index('status');
        });

        Schema::create('zoom_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('zoom_meetings')->cascadeOnDelete();
            $table->integer('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 30)->index();
            $table->string('channel', 20)->default('in_app');
            $table->string('subject', 191)->nullable();
            $table->text('body')->nullable();
            $table->string('link', 500)->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_sent']);
            $table->index(['meeting_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zoom_notifications');
        Schema::dropIfExists('zoom_attendance');
        Schema::dropIfExists('zoom_meetings');
    }
};
