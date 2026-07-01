<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Tag;
use App\Models\User;
use App\Traits\HandleUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CourseController extends Controller
{
    use HandleUploads;
    public function index(): View
    {
        $courses = Course::with('lessons')->where('user_id', auth()->id())->get();
        return view('org.courses', compact('courses'));
    }

    public function create(): View
    {
        $instructors = User::where('organization_id', auth()->id())
            ->where('role', User::ROLE_INSTRUCTOR)
            ->get();
        $categories = Category::where('status', 'active')->get();
        $levels = Level::orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        return view('org.course-create', compact('instructors', 'categories', 'levels', 'tags'));
    }

    public function storeInstructor(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'designation' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_INSTRUCTOR,
            'designation' => $validated['designation'] ?? null,
            'organization_id' => auth()->id(),
        ]);

        return redirect()->route('org.dashboard.instructors.create')
            ->with('success', "Instructor {$user->name} created successfully!");
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'level_id' => ['nullable', 'exists:levels,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'description' => ['nullable', 'string'],
            'outcomes' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'payment_type' => ['required', 'in:free,paid'],
            'price' => ['required_if:payment_type,paid', 'nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'string', 'in:Active,Draft'],
            'thumbnail' => ['nullable', 'image', 'max:20480'],
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
            try {
                $validated['thumbnail'] = $this->storeThumbnail($request->file('thumbnail'));
            } catch (\Exception $e) {
                return back()->withErrors(['thumbnail' => $e->getMessage()])->withInput();
            }
        }

        $course = Course::create($validated);

        if (!empty($validated['tags'])) {
            $course->tags()->sync($validated['tags']);
        }

        return redirect()->route('org.dashboard.courses.edit', $course->id)
            ->with('success', 'Course created successfully!');
    }

    public function edit(int $id): View
    {
        $course = Course::with('tags')->where('user_id', auth()->id())->findOrFail($id);
        $instructors = User::where('organization_id', auth()->id())
            ->where('role', User::ROLE_INSTRUCTOR)
            ->get();
        $categories = Category::where('status', 'active')->get();
        $levels = Level::orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        return view('org.course-edit', compact('course', 'instructors', 'categories', 'levels', 'tags'));
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
            'description' => ['nullable', 'string'],
            'outcomes' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'payment_type' => ['required', 'in:free,paid'],
            'price' => ['required_if:payment_type,paid', 'nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'string', 'in:Active,Draft'],
            'thumbnail' => ['nullable', 'image', 'max:20480'],
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
            try {
                $validated['thumbnail'] = $this->storeThumbnail($request->file('thumbnail'));
            } catch (\Exception $e) {
                return back()->withErrors(['thumbnail' => $e->getMessage()])->withInput();
            }
        }

        $course->update($validated);

        if ($request->has('tags')) {
            $course->tags()->sync($validated['tags'] ?? []);
        }

        return redirect()->route('org.dashboard.courses.edit', $course->id)
            ->with('success', 'Course updated successfully!');
    }

    public function lessons(int $id): View
    {
        $course = Course::with('lessons')->where('user_id', auth()->id())->findOrFail($id);
        return view('org.lesson', compact('course'));
    }

    public function storeLesson(Request $request, int $courseId): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($courseId);

        $uploadErrors = [];
        if ($error = $this->getFileUploadErrorMessage('video_file', 'The video file')) {
            $uploadErrors['video_file'] = $error;
        }
        if ($error = $this->getFileUploadErrorMessage('document_file', 'The document file')) {
            $uploadErrors['document_file'] = $error;
        }

        if (!empty($uploadErrors)) {
            return back()->withErrors($uploadErrors)->withInput();
        }

        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'content'         => ['nullable', 'string'],
            'video_url'       => ['nullable', 'url', 'max:500'],
            'video_file'      => ['nullable', 'file', 'max:512000'],
            'document_file'   => ['nullable', 'file', 'max:51200'],
            'duration'        => ['nullable', 'string', 'max:50'],
            'order'           => ['nullable', 'integer', 'min:0'],
            'is_free_preview' => ['nullable', 'boolean'],
            'status'          => ['nullable', 'string', 'in:draft,published'],
        ], [
            'video_file.uploaded' => 'The video file failed to upload. It may be too large or the upload was interrupted.',
            'document_file.uploaded' => 'The document file failed to upload. It may be too large or the upload was interrupted.',
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

        $validated['course_id']       = $course->id;
        $validated['order']           = $validated['order'] ?? ($course->lessons()->max('order') + 1);
        $validated['is_free_preview'] = $request->boolean('is_free_preview');
        $validated['status']          = $validated['status'] ?? 'published';

        // Handle video file upload - non-blocking
        if ($request->hasFile('video_file')) {
            try {
                $validated['video_file'] = $this->storeVideo($request->file('video_file'));
            } catch (Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Video file upload failed', [
                    'course_id' => $course->id,
                    'file' => $request->file('video_file')->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]);
                unset($validated['video_file']);
            }
        }

        // Handle document file upload - non-blocking
        if ($request->hasFile('document_file')) {
            try {
                $validated['document_file'] = $this->storeDocument($request->file('document_file'));
            } catch (Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Document file upload failed', [
                    'course_id' => $course->id,
                    'file' => $request->file('document_file')->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]);
                unset($validated['document_file']);
            }
        }

        $hasStoredMedia = !empty($validated['video_url'])
            || !empty($validated['video_file'])
            || !empty($validated['document_file']);

        if (!$hasStoredMedia) {
            return back()
                ->withErrors(['video_url' => 'Unable to store media. Please verify the uploaded video and/or document files and try again.'])
                ->withInput();
        }

        Lesson::create($validated);

        return redirect()->route('org.dashboard.courses.lessons', $course->id)
            ->with('success', 'Lesson added successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($id);
        $course->delete();

        return redirect('/org/courses')->with('success', 'Course deleted successfully!');
    }

    public function destroyLesson(int $courseId, int $lessonId): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($courseId);
        $lesson = $course->lessons()->findOrFail($lessonId);
        $lesson->delete();

        return redirect()->route('org.dashboard.courses.lessons', $course->id)
            ->with('success', 'Lesson deleted successfully!');
    }

    public function updateLessonOrder(Request $request, int $courseId): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($courseId);

        $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|integer|exists:lessons,id',
            'lessons.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->lessons as $item) {
            Lesson::where('id', $item['id'])->where('course_id', $course->id)
                ->update(['order' => $item['order']]);
        }

        return back()->with('success', 'Lesson order updated!');
    }
}
