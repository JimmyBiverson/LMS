<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentWorkflowFixTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_instruction_page_loads_when_attempts_limit_is_reached(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT, 'status' => User::STATUS_ACTIVE]);
        $instructor = User::factory()->create(['role' => User::ROLE_INSTRUCTOR, 'status' => User::STATUS_ACTIVE, 'is_approved' => true]);
        $course = Course::factory()->create([
            'user_id' => $instructor->id,
            'status' => 'Active',
        ]);

        Enrollment::factory()->create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'in_progress',
        ]);

        $quiz = Quiz::factory()->create([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
            'status' => 'published',
            'attempts_limit' => 1,
            'time_limit' => 15,
        ]);

        QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'type' => 'multiple_choice',
            'options' => ['A', 'B', 'C'],
            'correct_answer' => 'A',
            'marks' => 1,
        ]);

        QuizResult::factory()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $student->id,
            'score' => 1,
            'total_marks' => 1,
            'passed' => true,
        ]);

        $response = $this->actingAs($student)->get("/dashboard/quizzes/{$quiz->id}/instructions");

        $response->assertStatus(200);
        $response->assertSee('You have used all');
    }

    public function test_assignment_deadline_blocks_submission_after_due_date(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT, 'status' => User::STATUS_ACTIVE]);
        $instructor = User::factory()->create(['role' => User::ROLE_INSTRUCTOR, 'status' => User::STATUS_ACTIVE, 'is_approved' => true]);
        $course = Course::factory()->create([
            'user_id' => $instructor->id,
            'status' => 'Active',
        ]);

        Enrollment::factory()->create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'in_progress',
        ]);

        $assignment = Assignment::factory()->create([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
            'status' => 'published',
            'due_date' => now()->subDay(),
            'late_submission_allowed' => false,
            'total_marks' => 100,
        ]);

        $response = $this->actingAs($student)->get("/dashboard/assignments/{$assignment->id}/submit");

        $response->assertStatus(200);
        $response->assertSee('Submission time ran out');
    }

    public function test_certificate_preview_route_is_available(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT, 'status' => User::STATUS_ACTIVE]);
        $instructor = User::factory()->create(['role' => User::ROLE_INSTRUCTOR, 'status' => User::STATUS_ACTIVE, 'is_approved' => true]);
        $course = Course::factory()->create([
            'user_id' => $instructor->id,
            'status' => 'Active',
        ]);

        $certificate = Certificate::create([
            'course_id' => $course->id,
            'user_id' => $student->id,
            'title' => 'Completion certificate',
            'description' => 'Completed the course.',
        ]);

        $response = $this->actingAs($student)->get("/dashboard/certificate/{$certificate->id}/preview");

        $response->assertStatus(200);
        $response->assertSee('Certificate Preview');
    }
}
