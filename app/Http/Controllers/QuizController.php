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
            'instructions_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'attempts_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);
        $validated['course_id'] = $course->id;
        $validated['user_id'] = auth()->id();
        
        if ($request->hasFile('instructions_file')) {
            $validated['instructions_file'] = $request->file('instructions_file')->store('quizzes/instructions', 'public');
        }
        
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
            'instructions_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'attempts_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);
        
        if ($request->hasFile('instructions_file')) {
            // Delete old file if exists
            if ($quiz->instructions_file && \Storage::disk('public')->exists($quiz->instructions_file)) {
                \Storage::disk('public')->delete($quiz->instructions_file);
            }
            $validated['instructions_file'] = $request->file('instructions_file')->store('quizzes/instructions', 'public');
        }
        
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
            'type' => 'required|in:multiple_choice,multiple_select,true_false,matching,short_answer,essay,fill_in_blank,ordering',
            'options' => 'nullable',
            'correct_answer' => 'nullable',
            'marks' => 'required|integer|min:1',
        ]);

        if (in_array($validated['type'], ['multiple_choice', 'multiple_select'], true)) {
            $request->validate([
                'options' => 'required|string',
                'correct_answer' => 'required|string',
            ]);
        } elseif ($validated['type'] === 'true_false') {
            $request->validate(['correct_answer' => 'required|in:True,False,true,false']);
        } elseif ($validated['type'] === 'short_answer') {
            $request->validate(['correct_answer' => 'required|string']);
        } elseif ($validated['type'] === 'fill_in_blank') {
            $request->validate(['blanks' => 'required|array|min:1', 'blanks.*' => 'required|string']);
        } elseif ($validated['type'] === 'matching') {
            $request->validate(['pairs' => 'required|array|min:2', 'pairs.*.key' => 'required|string', 'pairs.*.value' => 'required|string']);
        } elseif ($validated['type'] === 'ordering') {
            $request->validate(['items' => 'required|array|min:2', 'items.*' => 'required|string']);
        }

        match ($validated['type']) {
            'multiple_choice' => $this->processMCQOptions($validated),
            'multiple_select' => $this->processMultipleSelectOptions($validated),
            'true_false' => $this->processTrueFalse($validated),
            'matching' => $this->processMatching($validated, $request),
            'short_answer' => $this->processShortAnswer($validated),
            'essay' => $this->processEssay($validated),
            'fill_in_blank' => $this->processFillInBlank($validated, $request),
            'ordering' => $this->processOrdering($validated, $request),
        };

        $validated['quiz_id'] = $quiz->id;
        $validated['order'] = ($quiz->questions()->max('order') ?? 0) + 1;
        QuizQuestion::create($validated);

        if ($this->isScorableQuestion($validated['type'])) {
            $quiz->increment('total_marks', $validated['marks']);
        }

        return back()->with('success', 'Question added successfully!');
    }

    protected function processMCQOptions(&$validated): void
    {
        $validated['options'] = $this->normalizeLines($validated['options'] ?? '');
        $validated['correct_answer'] = $this->normalizeSingleAnswer($validated['correct_answer'] ?? '', $validated['options']);
    }

    protected function processMultipleSelectOptions(&$validated): void
    {
        $validated['options'] = $this->normalizeLines($validated['options'] ?? '');
        $answers = $this->normalizeMultipleAnswer($validated['correct_answer'] ?? '');
        $validated['correct_answer'] = implode(',', array_values(array_intersect($answers, $validated['options'])));
    }

    protected function processShortAnswer(&$validated): void
    {
        $validated['options'] = null;
    }

    protected function processEssay(&$validated): void
    {
        $validated['options'] = null;
        $validated['correct_answer'] = null;
    }

    protected function processTrueFalse(&$validated): void
    {
        $validated['options'] = ['True', 'False'];
        $validated['correct_answer'] = in_array($validated['correct_answer'], ['True', 'False', 'true', 'false'], true)
            ? ucfirst(strtolower($validated['correct_answer']))
            : 'True';
    }

    protected function processMatching(&$validated, Request $request): void
    {
        $validated['options'] = array_values($request->input('pairs', []));
        $validated['correct_answer'] = null;
    }

    protected function processFillInBlank(&$validated, Request $request): void
    {
        $validated['options'] = array_values(array_filter(array_map('trim', $request->input('blanks', [])), 'strlen'));
        $validated['correct_answer'] = null;
    }

    protected function processOrdering(&$validated, Request $request): void
    {
        $validated['options'] = array_values(array_filter(array_map('trim', $request->input('items', [])), 'strlen'));
        $validated['correct_answer'] = null;
    }

    protected function normalizeLines(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: [], 'strlen')));
    }

    protected function normalizeSingleAnswer(string $answer, array $options): string
    {
        $answer = trim($answer);
        if ($answer === '' && count($options) > 0) {
            return $options[0];
        }
        return $answer;
    }

    protected function normalizeMultipleAnswer(string $answer): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/,|\r\n|\r|\n/', $answer) ?: []), 'strlen'));
    }

    protected function isScorableQuestion(string $type): bool
    {
        return in_array($type, ['multiple_choice', 'multiple_select', 'true_false', 'short_answer', 'fill_in_blank', 'matching', 'ordering'], true);
    }

    protected function isCorrectAnswer(QuizQuestion $question, mixed $submitted): bool
    {
        if (! $this->isScorableQuestion($question->type) || $question->type === 'essay') {
            return false;
        }

        switch ($question->type) {
            case 'multiple_choice':
            case 'true_false':
                return trim((string) $submitted) === trim((string) $question->correct_answer);
            case 'multiple_select':
                if (! is_array($submitted)) {
                    return false;
                }
                $expected = array_values(array_filter(array_map('trim', explode(',', (string) $question->correct_answer)), 'strlen'));
                $given = array_values(array_filter(array_map('trim', $submitted), 'strlen'));
                sort($expected);
                sort($given);
                return $expected === $given;
            case 'short_answer':
                return strcasecmp(trim((string) $submitted), trim((string) $question->correct_answer)) === 0;
            case 'fill_in_blank':
                if (! is_array($submitted)) {
                    $submitted = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', (string) $submitted) ?: []), 'strlen'));
                }
                $expected = array_values(array_map('trim', $question->options ?? []));
                if (count($submitted) !== count($expected)) {
                    return false;
                }
                foreach ($expected as $index => $value) {
                    if (strcasecmp($value, $submitted[$index]) !== 0) {
                        return false;
                    }
                }
                return true;
            case 'matching':
                if (! is_array($submitted)) {
                    return false;
                }
                $expected = array_values($question->options ?? []);
                foreach ($expected as $index => $pair) {
                    if (! array_key_exists($index, $submitted) || strcasecmp(trim((string) $submitted[$index]), trim((string) $pair['value'])) !== 0) {
                        return false;
                    }
                }
                return true;
            case 'ordering':
                if (! is_array($submitted)) {
                    $submitted = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $submitted) ?: []), 'strlen'));
                }
                $expected = array_values($question->options ?? []);
                $actual = array_values(array_map('trim', $submitted));
                return $expected === $actual;
        }

        return false;
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
            $submittedAnswer = $answers[$q->id] ?? null;
            if ($this->isCorrectAnswer($q, $submittedAnswer)) {
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
