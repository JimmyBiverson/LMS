<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'school_fees_paid')) {
                $table->boolean('school_fees_paid')->default(false)->after('class_id');
            }
            if (!Schema::hasColumn('users', 'can_upload_reports')) {
                $table->boolean('can_upload_reports')->default(false)->after('school_fees_paid');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'approval_status')) {
                $table->string('approval_status')->default('pending')->after('payment_status');
            }
            if (!Schema::hasColumn('enrollments', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('approval_status')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('enrollments', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });

        if (!Schema::hasTable('student_term_reports')) {
            Schema::create('student_term_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('authorized_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('term');
                $table->string('academic_year');
                $table->string('subject')->nullable();
                $table->decimal('marks', 8, 2)->nullable();
                $table->string('grade')->nullable();
                $table->text('remarks')->nullable();
                $table->string('status')->default('draft');
                $table->string('report_url')->nullable();
                $table->boolean('visible_to_student')->default(false);
                $table->boolean('school_fees_paid')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('student_term_reports');

        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('enrollments', 'approved_by')) {
                $table->dropConstrainedForeignId('approved_by');
            }
            if (Schema::hasColumn('enrollments', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'can_upload_reports')) {
                $table->dropColumn('can_upload_reports');
            }
            if (Schema::hasColumn('users', 'school_fees_paid')) {
                $table->dropColumn('school_fees_paid');
            }
        });
    }
};
