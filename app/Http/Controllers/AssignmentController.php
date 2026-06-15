<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AssignmentController extends Controller
{
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
        ]);
        $validated['course_id'] = $course->id;
        $validated['user_id'] = auth()->id();
        
        if ($request->hasFile('instructions_file')) {
            $validated['instructions_file'] = $request->file('instructions_file')->store('assignments/instructions', 'public');
        }
        
        Assignment::create($validated);
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
        ]);
        
        if ($request->hasFile('instructions_file')) {
            // Delete old file if exists
            if ($assignment->instructions_file && Storage::disk('public')->exists($assignment->instructions_file)) {
                Storage::disk('public')->delete($assignment->instructions_file);
            }
            $validated['instructions_file'] = $request->file('instructions_file')->store('assignments/instructions', 'public');
        }
        
        $assignment->update($validated);
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
        
        $validated = $request->validate([
            'submission_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip|max:10240',
        ]);

        if (empty($validated['submission_text']) && !$request->hasFile('file')) {
            return back()->withErrors(['submission_text' => 'Please provide submission text or upload a file.'])->withInput();
        }

        $data = [
            'assignment_id' => $assignment->id,
            'user_id' => auth()->id(),
            'submission_text' => $validated['submission_text'] ?? null,
        ];

        if ($request->hasFile('file')) {
            $data['file_url'] = $request->file('file')->store('submissions', 'public');
        }

        AssignmentSubmission::create($data);
        return back()->with('success', 'Assignment submitted successfully!');
    }

    // ─── Instructor: Grade ─────────────────────────────────────────
    public function grade(Request $request, AssignmentSubmission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0',
            'feedback' => 'nullable|string',
        ]);
        $validated['status'] = 'graded';
        $validated['graded_at'] = now();
        $submission->update($validated);
        \App\Notifications\AssignmentGraded::send($submission->user, $submission);
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
