<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        $enrollments = Enrollment::with('course.instructor', 'course.lessons')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($enrollments);
    }

    public function enroll(Request $request, Course $course): JsonResponse
    {
        if ($course->status !== 'Active') {
            return response()->json(['message' => 'Course not available.'], 404);
        }

        $exists = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)->exists();

        if ($exists) {
            return response()->json(['message' => 'Already enrolled.'], 409);
        }

        $enrollment = Enrollment::create([
            'user_id' => $request->user()->id,
            'course_id' => $course->id,
            'amount_paid' => $course->payment_type === 'free' ? 0 : ($course->sale_price ?? $course->price),
            'status' => 'in_progress',
        ]);

        \App\Notifications\CourseEnrolled::send($request->user(), $course);

        return response()->json($enrollment->load('course'), 201);
    }

    public function progress(Request $request, Course $course): JsonResponse
    {
        $total = $course->lessons()->count();
        $completed = LessonCompletion::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->count();

        $completions = LessonCompletion::with('lesson')
            ->where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->get();

        return response()->json([
            'total_lessons' => $total,
            'completed_lessons' => $completed,
            'percentage' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
            'completions' => $completions,
        ]);
    }

    public function completeLesson(Request $request, \App\Models\Lesson $lesson): JsonResponse
    {
        $course = $lesson->course;

        $enrolled = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->exists();

        if (!$enrolled) {
            return response()->json(['message' => 'Not enrolled.'], 403);
        }

        $completion = LessonCompletion::firstOrCreate([
            'user_id' => $request->user()->id,
            'lesson_id' => $lesson->id,
            'course_id' => $course->id,
        ], ['completed_at' => now()]);

        \App\Notifications\LessonCompleted::send($request->user(), $lesson, $course);

        return response()->json($completion);
    }
}
