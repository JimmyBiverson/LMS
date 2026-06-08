<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LessonCompletionController extends Controller
{
    public function toggle(Request $request, Lesson $lesson): RedirectResponse
    {
        $course = $lesson->course;
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->firstOrFail();

        $completion = LessonCompletion::where('user_id', auth()->id())
            ->where('lesson_id', $lesson->id)
            ->first();

        if ($completion) {
            $completion->delete();
        } else {
            LessonCompletion::create([
                'user_id' => auth()->id(),
                'lesson_id' => $lesson->id,
                'course_id' => $course->id,
                'completed_at' => now(),
            ]);
            \App\Notifications\LessonCompleted::send(auth()->user(), $lesson, $course);
        }

        $totalLessons = $course->lessons()->count();
        $completedLessons = LessonCompletion::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->count();

        if ($totalLessons > 0 && $completedLessons >= $totalLessons && $enrollment->status !== 'completed') {
            $enrollment->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            Certificate::firstOrCreate([
                'user_id' => auth()->id(),
                'course_id' => $course->id,
            ], [
                'title' => 'Certificate of Completion: ' . $course->title,
                'description' => 'This certifies that the student has successfully completed the course.',
            ]);

            \App\Notifications\CourseCompleted::send(auth()->user(), $course);
        }

        if ($enrollment->status === 'completed' && $completedLessons < $totalLessons) {
            $enrollment->update([
                'status' => 'in_progress',
                'completed_at' => null,
            ]);
        }

        return back()->with('success', $completion ? 'Lesson marked as incomplete.' : 'Lesson marked as complete!');
    }
}
