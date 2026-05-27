<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::with('lessons')->where('user_id', auth()->id())->get();
        return view('instructor.courses', compact('courses'));
    }

    public function create(): View
    {
        return view('instructor.course-create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'payment_type' => ['required', 'in:free,paid'],
            'price' => ['required_if:payment_type,paid', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'duration' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Active,Draft,Pending'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['user_id'] = auth()->id();

        if ($validated['payment_type'] === 'free') {
            $validated['price'] = 0;
            $validated['sale_price'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course = Course::create($validated);

        return redirect()->route('instructor.dashboard.courses.edit', $course->id)
            ->with('success', 'Course created successfully! Now add lessons.');
    }

    public function edit(int $id): View
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($id);
        return view('instructor.course-edit', compact('course'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'payment_type' => ['required', 'in:free,paid'],
            'price' => ['required_if:payment_type,paid', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'duration' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Active,Draft,Pending'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($validated['payment_type'] === 'free') {
            $validated['price'] = 0;
            $validated['sale_price'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course->update($validated);

        return redirect()->route('instructor.dashboard.courses.edit', $course->id)
            ->with('success', 'Course updated successfully!');
    }

    public function lessons(int $id): View
    {
        $course = Course::with('lessons')->where('user_id', auth()->id())->findOrFail($id);
        return view('instructor.lesson', compact('course'));
    }

    public function storeLesson(Request $request, int $courseId): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($courseId);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'duration' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_free_preview' => ['nullable', 'boolean'],
        ]);

        $validated['course_id'] = $course->id;
        $validated['order'] = $validated['order'] ?? ($course->lessons()->max('order') + 1);
        $validated['is_free_preview'] = $request->boolean('is_free_preview');

        Lesson::create($validated);

        return redirect()->route('instructor.dashboard.courses.lessons', $course->id)
            ->with('success', 'Lesson added successfully!');
    }

    public function destroyLesson(int $courseId, int $lessonId): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($courseId);
        $lesson = $course->lessons()->findOrFail($lessonId);
        $lesson->delete();

        return redirect()->route('instructor.dashboard.courses.lessons', $course->id)
            ->with('success', 'Lesson deleted successfully!');
    }
}
