<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Notifications\AssignmentGraded;
use App\Services\AssignmentEnhancementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function __construct(
        private readonly AssignmentEnhancementService $assignmentService,
    ) {}

    // ─── Instructor: Manage Assignments ────────────────────────────
    public function index(Course $course): View
    {
        $assignments = Assignment::withCount('submissions')->where('course_id', $course->id)->latest()->get();
        return view('assignments.index', compact('course', 'assignments'));
    }

    public function create(Course $course): View
    {
        return view('assignments.create', compact('course'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'instructions_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'due_date' => 'nullable|date',
            'total_marks' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'max_file_size_mb' => 'nullable|integer|min:1|max:50',
            'allowed_file_types' => 'nullable|array',
            'late_submission_allowed' => 'boolean',
            'late_penalty_percent' => 'nullable|numeric|min:0|max:100',
            'available_from' => 'nullable|date',
        ]);
        
        $validated['course_id'] = $course->id;
        $validated['user_id'] = auth()->id();
        $validated['late_submission_allowed'] = $request->has('late_submission_allowed');
        
        if (isset($validated['allowed_file_types'])) {
            $validated['allowed_file_types'] = json_encode($validated['allowed_file_types']);
        }
        
        if ($request->hasFile('instructions_file')) {
            $validated['instructions_file'] = $request->file('instructions_file')->store('assignments/instructions', 'public');
        }
        
        $assignment = Assignment::create($validated);

        if ($assignment->status === 'published') {
            \App\Notifications\AssignmentNotification::sendPublishedToEnrolled($assignment);
        } elseif ($assignment->available_from) {
            \App\Notifications\AssignmentNotification::sendScheduledToEnrolled($assignment);
        }

        return redirect("/instructor/courses/{$course->id}/assignments")->with('success', 'Assignment created!');
    }

    public function show(Assignment $assignment): View
    {
        $assignment->load('course', 'submissions.user');
        return view('assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment): View
    {
        return view('assignments.edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'instructions_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'due_date' => 'nullable|date',
            'total_marks' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'max_file_size_mb' => 'nullable|integer|min:1|max:50',
            'allowed_file_types' => 'nullable|array',
            'late_submission_allowed' => 'boolean',
            'late_penalty_percent' => 'nullable|numeric|min:0|max:100',
            'available_from' => 'nullable|date',
        ]);
        
        $validated['late_submission_allowed'] = $request->has('late_submission_allowed');
        
        if (isset($validated['allowed_file_types'])) {
            $validated['allowed_file_types'] = json_encode($validated['allowed_file_types']);
        }
        
        if ($request->hasFile('instructions_file')) {
            // Delete old file if exists
            if ($assignment->instructions_file && Storage::disk('public')->exists($assignment->instructions_file)) {
                Storage::disk('public')->delete($assignment->instructions_file);
            }
            $validated['instructions_file'] = $request->file('instructions_file')->store('assignments/instructions', 'public');
        }
        
        $wasDraft = $assignment->status === 'draft';
        $hadNoSchedule = $assignment->available_from === null;

        $assignment->update($validated);

        if ($wasDraft && $assignment->status === 'published') {
            \App\Notifications\AssignmentNotification::sendPublishedToEnrolled($assignment);
        } elseif ($hadNoSchedule && $assignment->available_from !== null) {
            \App\Notifications\AssignmentNotification::sendScheduledToEnrolled($assignment);
        }

        return back()->with('success', 'Assignment updated!');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $courseId = $assignment->course_id;
        $assignment->delete();
        return redirect("/instructor/courses/{$courseId}/assignments")->with('success', 'Assignment deleted!');
    }

    // ─── Student: Submit ──────────────────────────────────────────
    public function submitForm(Assignment $assignment): View
    {
        // Check if student is enrolled in the course
        $isEnrolled = \App\Models\Enrollment::where('user_id', auth()->id())
            ->where('course_id', $assignment->course_id)
            ->exists();
        
        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }

        if (!$assignment->isAvailable()) {
            return view('quizzes.locked', ['quiz' => null, 'assignment' => $assignment]);
        }
        
        $assignment->load('course');
        return view('assignments.submit', compact('assignment'));
    }

    public function submit(Request $request, Assignment $assignment): RedirectResponse
    {
        // Check if student is enrolled in the course
        $isEnrolled = \App\Models\Enrollment::where('user_id', auth()->id())
            ->where('course_id', $assignment->course_id)
            ->exists();
        
        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }

        if (!$assignment->isAvailable()) {
            abort(403, 'This assignment is not yet available.');
        }
        
        $validated = $request->validate([
            'submission_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip|max:10240',
        ]);

        if (empty($validated['submission_text']) && !$request->hasFile('file')) {
            return back()->withErrors(['submission_text' => 'Please provide submission text or upload a file.'])->withInput();
        }

        $this->assignmentService->submitAssignment(
            $assignment,
            auth()->user(),
            $request->hasFile('file') ? [$request->file('file')] : [],
            $validated['submission_text'] ?? null
        );

        return back()->with('success', 'Assignment submitted successfully!');
    }

    // ─── Instructor: Grade ─────────────────────────────────────────
    public function grade(Request $request, AssignmentSubmission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0',
            'feedback' => 'nullable|string',
        ]);
        
        $this->assignmentService->gradeSubmission(
            $submission,
            (float) $validated['score'],
            $validated['feedback'] ?? '',
        );

        AssignmentGraded::send($submission->user, $submission);
        
        return back()->with('success', 'Submission graded!');
    }

    // ─── Student: My Assignments ───────────────────────────────────
    public function myAssignments(): View
    {
        $submissions = AssignmentSubmission::with('assignment.course')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('dashboard.assignments', compact('submissions'));
    }
}
