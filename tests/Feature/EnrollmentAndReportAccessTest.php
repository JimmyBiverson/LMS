<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\StudentTermReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EnrollmentAndReportAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser(string $role, array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'role' => $role,
            'password' => Hash::make('password123'),
            'status' => 'active',
            'is_approved' => true,
            'class_id' => null,
        ], $overrides));
    }

    public function test_paid_course_enrollment_requires_instructor_approval(): void
    {
        $instructor = $this->createUser('instructor', ['is_approved' => true]);
        $student = $this->createUser('student');
        $course = Course::create([
            'user_id' => $instructor->id,
            'title' => 'Advanced Laravel',
            'description' => 'Paid course',
            'status' => 'Active',
            'payment_type' => 'paid',
            'price' => 120,
            'slug' => 'advanced-laravel-' . uniqid(),
        ]);

        $this->actingAs($student)->post('/enroll/' . $course->id);

        $enrollment = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        $this->assertNotNull($enrollment);
        $this->assertSame('pending', $enrollment->approval_status);
        $this->assertFalse($enrollment->isApprovedForAccess());

        $enrollment->update(['approval_status' => 'approved', 'payment_status' => 'approved']);

        $this->assertTrue($enrollment->fresh()->isApprovedForAccess());
    }

    public function test_student_term_report_is_hidden_until_fees_are_cleared_and_authorized_for_student(): void
    {
        $admin = $this->createUser('admin');
        $instructor = $this->createUser('instructor', ['is_approved' => true, 'can_upload_reports' => true]);
        $student = $this->createUser('student', ['school_fees_paid' => false]);

        $report = StudentTermReport::create([
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'term' => 'Term 1',
            'academic_year' => '2026',
            'status' => 'published',
            'visible_to_student' => false,
            'school_fees_paid' => false,
            'remarks' => 'Fee balance outstanding',
        ]);

        $this->assertFalse($report->isVisibleToStudent());

        $student->update(['school_fees_paid' => true]);
        $report->update(['visible_to_student' => true, 'school_fees_paid' => true]);

        $this->assertTrue($report->fresh()->isVisibleToStudent());
        $this->assertTrue($report->fresh()->canStudentSee());
    }

    public function test_student_dashboard_only_lists_reports_the_student_can_access(): void
    {
        $instructor = $this->createUser('instructor', ['is_approved' => true, 'can_upload_reports' => true]);
        $student = $this->createUser('student', ['school_fees_paid' => true]);

        $visible = StudentTermReport::create([
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'term' => 'Term 1',
            'academic_year' => '2026',
            'status' => 'published',
            'visible_to_student' => true,
            'school_fees_paid' => true,
            'remarks' => 'Visible report',
        ]);

        $hidden = StudentTermReport::create([
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'term' => 'Term 2',
            'academic_year' => '2026',
            'status' => 'published',
            'visible_to_student' => false,
            'school_fees_paid' => true,
            'remarks' => 'Hidden report',
        ]);

        $this->actingAs($student)
            ->get('/dashboard/term-reports')
            ->assertOk()
            ->assertViewHas('reports', function ($reports) use ($visible, $hidden) {
                return $reports->contains(fn ($report) => $report->id === $visible->id)
                    && ! $reports->contains(fn ($report) => $report->id === $hidden->id);
            });
    }

    public function test_admin_can_toggle_super_instructor_reporting_permission(): void
    {
        $admin = $this->createUser('admin');
        $instructor = $this->createUser('instructor', ['is_approved' => true, 'can_upload_reports' => false]);

        $this->actingAs($admin)
            ->post('/admin/settings/instructors/' . $instructor->id . '/toggle-super')
            ->assertRedirect();

        $this->assertTrue($instructor->fresh()->can_upload_reports);
        $this->assertTrue($instructor->fresh()->canManageStudentReports());
    }
}
