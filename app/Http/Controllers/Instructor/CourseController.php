<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Tag;
use App\Traits\HandleUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Exception;

class CourseController extends Controller
{
    use HandleUploads;
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
        if (!auth()->user()->isApproved()) {
            return back()->with('error', 'Your instructor account is pending approval. You cannot create courses until an admin approves your account.')->withInput();
        }
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
            'price'        => ['required_if:payment_type,paid', 'nullable', 'numeric', 'min:0'],
            'sale_price'   => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'duration'     => ['nullable', 'string', 'max:255'],
            'status'       => ['required', 'string', 'in:Active,Draft,Pending'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'preview_video' => ['nullable', 'file', 'mimes:mp4,mov,avi,webm,ogg', 'max:102400'],
        ]);

        try {
            $validated['user_id'] = auth()->id();

            if ($validated['payment_type'] === 'free') {
                $validated['price']      = 0;
                $validated['sale_price'] = null;
            }

            if (!empty($validated['category_id'])) {
                $category = Category::find($validated['category_id']);
                $validated['category'] = $category ? $category->name : null;
            }

            // Handle thumbnail upload with improved error handling
            if ($request->hasFile('thumbnail')) {
                try {
                    $validated['thumbnail'] = $this->storeThumbnail($request->file('thumbnail'));
                } catch (Exception $e) {
                    Log::warning('Thumbnail store failed during course creation: ' . $e->getMessage(), [
                        'course_title' => $validated['title'],
                        'file' => $request->file('thumbnail')->getClientOriginalName(),
                    ]);
                    $validated['thumbnail'] = null;
                }
            }

            // Handle preview video upload
            if ($request->hasFile('preview_video')) {
                try {
                    $validated['preview_video'] = $this->storeVideo($request->file('preview_video'), 'courses/preview-videos');
                } catch (Exception $e) {
                    Log::warning('Preview video store failed during course creation: ' . $e->getMessage(), [
                        'course_title' => $validated['title'],
                        'file' => $request->file('preview_video')->getClientOriginalName(),
                    ]);
                    unset($validated['preview_video']);
                }
            }

            $course = Course::create($validated);

            if (!empty($validated['tags'])) {
                $course->tags()->sync($validated['tags']);
            }

            return redirect()->route('instructor.dashboard.courses.edit', $course->id)
                ->with('success', 'Course created successfully! Now add lessons.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create course: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(int $id): View
    {
        $course     = Course::with('tags')->where('user_id', auth()->id())->findOrFail($id);
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
            'price'        => ['required_if:payment_type,paid', 'nullable', 'numeric', 'min:0'],
            'sale_price'   => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'duration'     => ['nullable', 'string', 'max:255'],
            'status'       => ['required', 'string', 'in:Active,Draft,Pending'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'preview_video' => ['nullable', 'file', 'mimes:mp4,mov,avi,webm,ogg', 'max:102400'],
        ]);

        try {
            if ($validated['payment_type'] === 'free') {
                $validated['price']      = 0;
                $validated['sale_price'] = null;
            }

            if (!empty($validated['category_id'])) {
                $category = Category::find($validated['category_id']);
                $validated['category'] = $category ? $category->name : null;
            }

            // Handle thumbnail upload with improved error handling
            if ($request->hasFile('thumbnail')) {
                try {
                    // Delete old thumbnail if it exists
                    if ($course->thumbnail && $course->thumbnail !== 'N/A') {
                        $this->deleteFile($course->thumbnail);
                    }

                    $validated['thumbnail'] = $this->storeThumbnail($request->file('thumbnail'));
                } catch (Exception $e) {
                    Log::warning('Thumbnail store failed during course update: ' . $e->getMessage(), [
                        'course_id' => $course->id,
                        'file' => $request->file('thumbnail')->getClientOriginalName(),
                    ]);
                    unset($validated['thumbnail']);
                }
            }

            // Handle preview video upload
            if ($request->hasFile('preview_video')) {
                try {
                    if ($course->preview_video) {
                        $this->deleteFile($course->preview_video);
                    }
                    $validated['preview_video'] = $this->storeVideo($request->file('preview_video'), 'courses/preview-videos');
                } catch (Exception $e) {
                    Log::warning('Preview video store failed during course update: ' . $e->getMessage(), [
                        'course_id' => $course->id,
                        'file' => $request->file('preview_video')->getClientOriginalName(),
                    ]);
                    unset($validated['preview_video']);
                }
            }

            // Handle preview video removal indicator
            if ($request->input('remove_preview_video') === '1' && $course->preview_video) {
                $this->deleteFile($course->preview_video);
                $validated['preview_video'] = null;
            }

            $course->update($validated);

            if ($request->has('tags')) {
                $course->tags()->sync($validated['tags'] ?? []);
            }

            return redirect()->route('instructor.dashboard.courses.edit', $course->id)
                ->with('success', 'Course updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update course: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function lessons(int $id): View
    {
        $course = Course::with('lessons')->where('user_id', auth()->id())->findOrFail($id);
        return view('instructor.lesson', compact('course'));
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
            'video_file'      => ['nullable', 'file', 'max:512000'], // max 500 MB - removed strict MIME check
            'document_file'   => ['nullable', 'file', 'max:51200'], // max 50 MB - removed strict MIME check
            'duration'        => ['nullable', 'string', 'max:50'],
            'order'           => ['nullable', 'integer', 'min:0'],
            'is_free_preview' => ['nullable', 'boolean'],
            'status'          => ['nullable', 'string', 'in:draft,published'],
        ], [
            'video_file.uploaded' => 'The video file failed to upload. It may be too large or the upload was interrupted.',
            'document_file.uploaded' => 'The document file failed to upload. It may be too large or the upload was interrupted.',
        ]);

        try {
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

            // Handle video file upload - non-blocking
            if ($request->hasFile('video_file')) {
                try {
                    $validated['video_file'] = $this->storeVideo($request->file('video_file'));
                } catch (Exception $e) {
                    Log::warning('Video file upload failed during lesson creation', [
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
                    Log::warning('Document file upload failed during lesson creation', [
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

            return redirect()->route('instructor.dashboard.courses.lessons', $course->id)
                ->with('success', 'Lesson added successfully!');
        } catch (Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to add lesson: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $course = Course::where('user_id', auth()->id())->findOrFail($id);
        $course->delete();

        return redirect()->route('instructor.dashboard.courses.index')
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
