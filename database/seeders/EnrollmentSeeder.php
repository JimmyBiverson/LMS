<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('role', 'student')->first();
        $courses = Course::all();

        if (!$student) return;

        // Enroll student in Introduction to Web Development (in progress, 2 lessons completed)
        $webDev = $courses->firstWhere('title', 'Introduction to Web Development');
        if ($webDev) {
            Enrollment::updateOrCreate(
                ['user_id' => $student->id, 'course_id' => $webDev->id],
                [
                    'amount_paid' => 0,
                    'status' => 'in_progress',
                ]
            );

            // Complete first 2 lessons
            $lessons = $webDev->lessons()->orderBy('order')->take(2)->get();
            foreach ($lessons as $lesson) {
                LessonCompletion::updateOrCreate(
                    ['user_id' => $student->id, 'lesson_id' => $lesson->id],
                    [
                        'course_id' => $webDev->id,
                        'completed_at' => now(),
                    ]
                );
            }
        }

        // Enroll student in UI/UX Design Masterclass (completed)
        $uiux = $courses->firstWhere('title', 'UI/UX Design Masterclass');
        if ($uiux) {
            Enrollment::updateOrCreate(
                ['user_id' => $student->id, 'course_id' => $uiux->id],
                [
                    'amount_paid' => 39.99,
                    'status' => 'completed',
                    'completed_at' => now()->subDays(5),
                ]
            );

            // Complete all lessons
            $lessons = $uiux->lessons()->orderBy('order')->get();
            foreach ($lessons as $lesson) {
                LessonCompletion::updateOrCreate(
                    ['user_id' => $student->id, 'lesson_id' => $lesson->id],
                    [
                        'course_id' => $uiux->id,
                        'completed_at' => now()->subDays(rand(1, 30)),
                    ]
                );
            }
        }

        // Enroll student in Python for Data Science (in progress, no lessons completed)
        $python = $courses->firstWhere('title', 'Python for Data Science');
        if ($python) {
            Enrollment::updateOrCreate(
                ['user_id' => $student->id, 'course_id' => $python->id],
                [
                    'amount_paid' => 0,
                    'status' => 'in_progress',
                ]
            );
        }
    }
}
