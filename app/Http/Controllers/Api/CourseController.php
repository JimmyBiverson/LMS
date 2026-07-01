<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
        $courses = Course::with('instructor', 'level', 'tags', 'categoryRelation')
            ->withCount('lessons', 'enrollments', 'reviews')
            ->where('status', 'Active')
            ->latest()
            ->paginate(12);

        return response()->json($courses);
    }

    public function show(Course $course): JsonResponse
    {
        if ($course->status !== 'Active') {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $course->load('instructor', 'level', 'tags', 'categoryRelation', 'lessons');
        $course->loadCount('enrollments', 'reviews');

        return response()->json($course);
    }

    public function lessons(Course $course): JsonResponse
    {
        if ($course->status !== 'Active') {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $lessons = $course->lessons()->orderBy('order')->get();
        return response()->json($lessons);
    }

    public function featured(): JsonResponse
    {
        $courses = Course::with('instructor')
            ->withCount('enrollments', 'lessons')
            ->where('status', 'Active')
            ->latest()
            ->take(6)
            ->get();

        return response()->json($courses);
    }
}
