<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseNote;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CourseNoteAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_cannot_access_notes_for_courses_they_are_not_enrolled_in(): void
    {
        $instructor = User::factory()->create(['role' => User::ROLE_INSTRUCTOR]);
        $course = Course::factory()->create(['user_id' => $instructor->id, 'status' => 'Active']);
        $note = CourseNote::factory()->create([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
            'status' => 'published',
        ]);

        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $this->actingAs($student)
            ->get(route('dashboard.course-notes.show', $note))
            ->assertForbidden();
    }

    public function test_enrolled_students_can_view_published_course_notes(): void
    {
        $instructor = User::factory()->create(['role' => User::ROLE_INSTRUCTOR]);
        $course = Course::factory()->create(['user_id' => $instructor->id, 'status' => 'Active']);
        $note = CourseNote::factory()->create([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
            'status' => 'published',
        ]);

        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
            'payment_status' => 'approved',
            'amount_paid' => 0,
        ]);

        $this->actingAs($student)
            ->get(route('dashboard.course-notes.show', $note))
            ->assertOk()
            ->assertSee($note->title);
    }

    public function test_enrolled_students_can_see_course_notes_in_materials_and_download_the_attachment(): void
    {
        $instructor = User::factory()->create(['role' => User::ROLE_INSTRUCTOR]);
        $course = Course::factory()->create(['user_id' => $instructor->id, 'status' => 'Active']);
        $note = CourseNote::factory()->create([
            'course_id' => $course->id,
            'user_id' => $instructor->id,
            'status' => 'published',
            'allow_download' => true,
        ]);

        Storage::fake('public');
        $path = 'course-notes/sample.pdf';
        Storage::disk('public')->put($path, 'sample content');
        $note->update(['attachment_path' => $path]);

        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
            'payment_status' => 'approved',
            'amount_paid' => 0,
        ]);

        $note->refresh();

        $this->actingAs($student)
            ->get('/courses/' . $course->slug . '/materials')
            ->assertOk()
            ->assertSee($note->title)
            ->assertSee(route('dashboard.course-notes.show', $note));

        $response = $this->actingAs($student)->get(route('dashboard.course-notes.download', $note));
        $response->assertHeader('Content-Disposition', 'attachment; filename=sample.pdf');
    }
}
