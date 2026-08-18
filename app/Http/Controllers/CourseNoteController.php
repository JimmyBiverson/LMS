<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseNote;
use App\Models\Enrollment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseNoteController extends Controller
{
    use AuthorizesRequests;
    public function instructorIndex(Request $request): View
    {
        $this->authorize('viewAny', CourseNote::class);

        $query = CourseNote::with('course')->whereHas('course', function ($q) {
            $q->where('user_id', auth()->id());
        });

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%" );
            });
        }

        $notes = $query->latest()->paginate(10)->withQueryString();
        $courses = Course::where('user_id', auth()->id())->orderBy('title')->get();

        return view('instructor.course-notes.index', compact('notes', 'courses'));
    }

    public function instructorCreate(): View
    {
        $this->authorize('create', CourseNote::class);

        $courses = Course::where('user_id', auth()->id())->orderBy('title')->get();

        return view('instructor.course-notes.create', compact('courses'));
    }

    public function instructorStore(Request $request): RedirectResponse
    {
        $this->authorize('create', CourseNote::class);

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png,webp,gif,bmp', 'max:20480'],
            'external_link' => ['nullable', 'url', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,published'],
            'allow_download' => ['nullable', 'boolean'],
            'created_at' => ['nullable', 'date'],
        ]);

        $course = Course::where('user_id', auth()->id())->findOrFail($validated['course_id']);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('course-notes', 'public');
        }

        CourseNote::create([
            ...$validated,
            'course_id' => $course->id,
            'user_id' => auth()->id(),
            'attachment_path' => $attachmentPath,
            'allow_download' => $request->boolean('allow_download'),
            'created_at' => $validated['created_at'] ?? now(),
        ]);

        return redirect()->route('instructor.dashboard.course-notes.index')->with('success', 'Course note created successfully.');
    }

    public function instructorShow(CourseNote $courseNote): View
    {
        $this->authorize('view', $courseNote);

        return view('instructor.course-notes.show', compact('courseNote'));
    }

    public function instructorEdit(CourseNote $courseNote): View
    {
        $this->authorize('update', $courseNote);

        $courses = Course::where('user_id', auth()->id())->orderBy('title')->get();

        return view('instructor.course-notes.edit', compact('courseNote', 'courses'));
    }

    public function instructorUpdate(Request $request, CourseNote $courseNote): RedirectResponse
    {
        $this->authorize('update', $courseNote);

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png,webp,gif,bmp', 'max:20480'],
            'external_link' => ['nullable', 'url', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,published'],
            'allow_download' => ['nullable', 'boolean'],
            'created_at' => ['nullable', 'date'],
        ]);

        $course = Course::where('user_id', auth()->id())->findOrFail($validated['course_id']);

        if ($request->hasFile('attachment')) {
            if ($courseNote->attachment_path && Storage::disk('public')->exists($courseNote->attachment_path)) {
                Storage::disk('public')->delete($courseNote->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('course-notes', 'public');
        }

        $courseNote->update([
            ...$validated,
            'course_id' => $course->id,
            'user_id' => auth()->id(),
            'allow_download' => $request->boolean('allow_download'),
            'created_at' => $validated['created_at'] ?? $courseNote->created_at,
        ]);

        return redirect()->route('instructor.dashboard.course-notes.index')->with('success', 'Course note updated successfully.');
    }

    public function instructorDestroy(CourseNote $courseNote): RedirectResponse
    {
        $this->authorize('delete', $courseNote);

        if ($courseNote->attachment_path && Storage::disk('public')->exists($courseNote->attachment_path)) {
            Storage::disk('public')->delete($courseNote->attachment_path);
        }

        $courseNote->delete();

        return back()->with('success', 'Course note deleted.');
    }

    public function studentIndex(Request $request): View
    {
        $this->authorize('viewAny', CourseNote::class);

        $courseIds = Enrollment::where('user_id', auth()->id())->pluck('course_id');

        $query = CourseNote::with('course')->whereIn('course_id', $courseIds)->where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $notes = $query->orderBy('display_order')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('dashboard.course-notes.index', compact('notes'));
    }

    public function studentShow(CourseNote $courseNote): View
    {
        $this->authorize('view', $courseNote);

        return view('dashboard.course-notes.show', compact('courseNote'));
    }

    public function download(CourseNote $courseNote): RedirectResponse
    {
        $this->authorize('download', $courseNote);

        if (!$courseNote->attachment_path || !Storage::disk('public')->exists($courseNote->attachment_path)) {
            return back()->with('error', 'The attachment is not available.');
        }

        return response()->download(Storage::disk('public')->path($courseNote->attachment_path));
    }
}
