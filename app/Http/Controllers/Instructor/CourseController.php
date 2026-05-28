<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Tag;
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
        $categories = Category::where('status', 'active')->get();
        $levels = Level::orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        return view('instructor.course-create', compact('categories', 'levels', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'level_id' => ['nullable', 'exists:levels,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'description' => ['required', 'string'],
            'outcomes' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
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

        if (!empty($validated['category_id'])) {
            $category = Category::find($validated['category_id']);
            $validated['category'] = $category ? $category->name : null;
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course = Course::create($validated);

        if (!empty($validated['tags'])) {
            $course->tags()->sync($validated['tags']);
        }

        return redirect()->route('instructor.dashboard.courses.edit', $course->id)
            ->with('success', 'Course created successfully! Now add lessons.');
    }

    public function edit(int $id): View
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        $levels = Level::orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        return view('instructor.course-edit', compact('course', 'categories', 'levels', 'tags'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'level_id' => ['nullable', 'exists:levels,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'description' => ['required', 'string'],
            'outcomes' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
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

        if (!empty($validated['category_id'])) {
            $category = Category::find($validated['category_id']);
            $validated['category'] = $category ? $category->name : null;
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course->update($validated);

        if ($request->has('tags')) {
            $course->tags()->sync($validated['tags'] ?? []);
        }

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
            'status' => ['nullable', 'string', 'in:draft,published'],
        ]);

        $validated['course_id'] = $course->id;
        $validated['order'] = $validated['order'] ?? ($course->lessons()->max('order') + 1);
        $validated['is_free_preview'] = $request->boolean('is_free_preview');
        $validated['status'] = $validated['status'] ?? 'published';

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
