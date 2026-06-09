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
            'title'        => ['required', 'string', 'max:255'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'level_id'     => ['nullable', 'exists:levels,id'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['exists:tags,id'],
            'description'  => ['required', 'string'],
            'outcomes'     => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'payment_type' => ['required', 'in:free,paid'],
            'price'        => ['required_if:payment_type,paid', 'numeric', 'min:0'],
            'sale_price'   => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'duration'     => ['nullable', 'string', 'max:255'],
            'status'       => ['required', 'string', 'in:Active,Draft,Pending'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['user_id'] = auth()->id();

        if ($validated['payment_type'] === 'free') {
            $validated['price']      = 0;
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
        $course     = Course::where('user_id', auth()->id())->findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        $levels     = Level::orderBy('order')->get();
        $tags       = Tag::orderBy('name')->get();
        return view('instructor.course-edit', compact('course', 'categories', 'levels', 'tags'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'level_id'     => ['nullable', 'exists:levels,id'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['exists:tags,id'],
            'description'  => ['required', 'string'],
            'outcomes'     => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'payment_type' => ['required', 'in:free,paid'],
            'price'        => ['required_if:payment_type,paid', 'numeric', 'min:0'],
            'sale_price'   => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'duration'     => ['nullable', 'string', 'max:255'],
            'status'       => ['required', 'string', 'in:Active,Draft,Pending'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($validated['payment_type'] === 'free') {
            $validated['price']      = 0;
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
            'title'           => ['required', 'string', 'max:255'],
            'content'         => ['nullable', 'string'],
            'video_url'       => ['nullable', 'url', 'max:500'],
            'video_file'      => ['nullable', 'file', 'mimes:mp4,mov,avi,webm,ogg', 'max:512000'], // max 500 MB
            'document_file'   => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx', 'max:51200'], // max 50 MB
            'duration'        => ['nullable', 'string', 'max:50'],
            'order'           => ['nullable', 'integer', 'min:0'],
            'is_free_preview' => ['nullable', 'boolean'],
            'status'          => ['nullable', 'string', 'in:draft,published'],
        ]);

        // Require at least one media source
        $hasMedia = !empty($validated['video_url'])
            || $request->hasFile('video_file')
            || $request->hasFile('document_file');

        if (!$hasMedia) {
            return back()
                ->withErrors(['video_url' => 'Please provide at least one media source: a Video URL, a Video File, or a Document.'])
                ->withInput();
        }

        $validated['course_id']      = $course->id;
        $validated['order']          = $validated['order'] ?? ($course->lessons()->max('order') + 1);
        $validated['is_free_preview'] = $request->boolean('is_free_preview');
        $validated['status']         = $validated['status'] ?? 'published';

        // Store uploaded video file
        if ($request->hasFile('video_file')) {
            $validated['video_file'] = $request->file('video_file')->store('lessons/videos', 'public');
        }

        // Store uploaded document file
        if ($request->hasFile('document_file')) {
            $validated['document_file'] = $request->file('document_file')->store('lessons/documents', 'public');
        }

        Lesson::create($validated);

        return redirect()->route('instructor.dashboard.courses.lessons', $course->id)
            ->with('success', 'Lesson added successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($id);
        $course->delete();

        return redirect()->route('instructor.dashboard.courses')
            ->with('success', 'Course deleted successfully!');
    }

    public function destroyLesson(int $courseId, int $lessonId): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($courseId);
        $lesson = $course->lessons()->findOrFail($lessonId);
        $lesson->delete();

        return redirect()->route('instructor.dashboard.courses.lessons', $course->id)
            ->with('success', 'Lesson deleted successfully!');
    }

    public function updateLessonOrder(Request $request, int $courseId): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($courseId);

        $request->validate([
            'lessons'          => 'required|array',
            'lessons.*.id'    => 'required|integer|exists:lessons,id',
            'lessons.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->lessons as $item) {
            Lesson::where('id', $item['id'])->where('course_id', $course->id)
                ->update(['order' => $item['order']]);
        }

        return back()->with('success', 'Lesson order updated!');
    }
}
