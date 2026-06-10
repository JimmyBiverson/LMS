<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuizController extends Controller
{
    // ─── Admin / Instructor: Manage Quizzes ─────────────────────────
    public function index(Course $course): View
    {
        $quizzes = Quiz::withCount('questions')->where('course_id', $course->id)->latest()->get();
        return view('quizzes.index', compact('course', 'quizzes'));
    }

    public function create(Course $course): View
    {
        return view('quizzes.create', compact('course'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'attempts_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);
        $validated['course_id'] = $course->id;
        $validated['user_id'] = auth()->id();
        Quiz::create($validated);
        return redirect("/instructor/courses/{$course->id}/quizzes")->with('success', 'Quiz created!');
    }

    public function edit(Quiz $quiz): View
    {
        $quiz->load('questions');
        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'attempts_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);
        $quiz->update($validated);
        return back()->with('success', 'Quiz updated!');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $courseId = $quiz->course_id;
        $quiz->delete();
        return redirect("/instructor/courses/{$courseId}/quizzes")->with('success', 'Quiz deleted!');
    }

    // ─── Quiz Questions ────────────────────────────────────────────
    public function storeQuestion(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,matching,short_answer',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_answer' => 'required|string',
            'marks' => 'required|integer|min:1',
        ]);
        if (in_array($validated['type'], ['multiple_choice', 'true_false'])) {
            $validated['options'] = array_values($validated['options'] ?? []);
        }
        if ($validated['type'] === 'short_answer') {
            $validated['options'] = null;
        }
        if ($validated['type'] === 'matching') {
            $request->validate(['pairs' => 'required|array|min:2', 'pairs.*.key' => 'required|string', 'pairs.*.value' => 'required|string']);
            $validated['options'] = $request->pairs;
        }
        $validated['quiz_id'] = $quiz->id;
        $validated['order'] = $quiz->questions()->count() + 1;
        QuizQuestion::create($validated);
        $quiz->increment('total_marks', $validated['marks']);
        return back()->with('success', 'Question added!');
    }

    public function destroyQuestion(QuizQuestion $question): RedirectResponse
    {
        $quiz = $question->quiz;
        $quiz->decrement('total_marks', $question->marks);
        $question->delete();
        return back()->with('success', 'Question deleted!');
    }

    // ─── Student: Take Quiz ───────────────────────────────────────
    public function take(Quiz $quiz): View
    {
        if ($quiz->status !== 'published') {
            abort(404);
        }
        $isEnrolled = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $quiz->course_id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->exists();
        if (!$isEnrolled) {
            abort(403, 'You must be enrolled in the course to take this quiz.');
        }
        if ($quiz->attempts_limit) {
            $attempts = QuizResult::where('quiz_id', $quiz->id)->where('user_id', auth()->id())->count();
            if ($attempts >= $quiz->attempts_limit) {
                abort(403, 'You have reached the maximum number of attempts for this quiz.');
            }
        }
        $quiz->load('questions');
        return view('quizzes.take', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $isEnrolled = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $quiz->course_id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->exists();
        if (!$isEnrolled) {
            abort(403, 'You must be enrolled in the course to submit this quiz.');
        }
        $quiz->load('questions');
        $answers = $request->input('answers', []);
        $score = 0;
        foreach ($quiz->questions as $q) {
            if (isset($answers[$q->id]) && $answers[$q->id] === $q->correct_answer) {
                $score += $q->marks;
            }
        }
        $totalMarks = $quiz->questions->sum('marks');
        $percentage = $totalMarks > 0 ? ($score / $totalMarks) * 100 : 0;
        $passed = $percentage >= $quiz->passing_score;
        QuizResult::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'score' => $score,
            'total_marks' => $totalMarks,
            'answers' => $answers,
            'completed_at' => now(),
            'passed' => $passed,
        ]);
        \App\Notifications\QuizResultNotification::send(auth()->user(), $quiz, $score, $totalMarks, $passed);
        return redirect("/dashboard/quizzes/my-result")->with('success', 'Quiz submitted! Score: ' . $score . '/' . $totalMarks);
    }

    public function myResults(): View
    {
        $results = QuizResult::with('quiz.course')->where('user_id', auth()->id())->latest()->get();
        return view('dashboard.quizzes.my-result', compact('results'));
    }
}
