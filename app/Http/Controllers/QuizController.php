<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Services\QuestionExtractionService;
use App\Services\QuizEnhancementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct(
        private readonly QuizEnhancementService $quizService,
    ) {}
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
            'passing_score' => 'required|numeric|min:0|max:100',
            'attempts_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
            'randomize_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'certificate_on_pass' => 'boolean',
            'proctoring_required' => 'boolean',
            'is_exam' => 'boolean',
            'class_id' => 'nullable|exists:classes,id',
            'available_from' => 'nullable|date',
            'results_released_at' => 'nullable|date',
        ]);
        
        $validated['course_id'] = $course->id;
        $validated['user_id'] = auth()->id();
        $validated['randomize_options'] = $request->has('randomize_options');
        $validated['show_results_immediately'] = $request->has('show_results_immediately');
        $validated['certificate_on_pass'] = $request->has('certificate_on_pass');
        $validated['proctoring_required'] = $request->has('proctoring_required');
        $validated['is_exam'] = $request->input('is_exam') === '1';
        
        if ($request->hasFile('instructions_file')) {
            $validated['instructions_file'] = $request->file('instructions_file')->store('quizzes/instructions', 'public');
        }
        
        $quiz = Quiz::create($validated);

        if ($quiz->status === 'published') {
            \App\Notifications\QuizNotification::sendPublishedToEnrolled($quiz);
        } elseif ($quiz->available_from) {
            \App\Notifications\QuizNotification::sendScheduledToEnrolled($quiz);
        }

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
            'passing_score' => 'required|numeric|min:0|max:100',
            'attempts_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
            'randomize_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'certificate_on_pass' => 'boolean',
            'proctoring_required' => 'boolean',
            'is_exam' => 'boolean',
            'class_id' => 'nullable|exists:classes,id',
            'available_from' => 'nullable|date',
            'results_released_at' => 'nullable|date',
        ]);
        
        $validated['randomize_options'] = $request->has('randomize_options');
        $validated['show_results_immediately'] = $request->has('show_results_immediately');
        $validated['certificate_on_pass'] = $request->has('certificate_on_pass');
        $validated['proctoring_required'] = $request->has('proctoring_required');
        $validated['is_exam'] = $request->input('is_exam') === '1';
        
        if ($request->hasFile('instructions_file')) {
            // Delete old file if exists
            if ($quiz->instructions_file && Storage::disk('public')->exists($quiz->instructions_file)) {
                Storage::disk('public')->delete($quiz->instructions_file);
            }
            $validated['instructions_file'] = $request->file('instructions_file')->store('quizzes/instructions', 'public');
        }
        
        $wasDraft = $quiz->status === 'draft';
        $hadNoSchedule = $quiz->available_from === null;

        $quiz->update($validated);

        if ($wasDraft && $quiz->status === 'published') {
            \App\Notifications\QuizNotification::sendPublishedToEnrolled($quiz);
        } elseif ($hadNoSchedule && $quiz->available_from !== null) {
            \App\Notifications\QuizNotification::sendScheduledToEnrolled($quiz);
        }

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

    public function destroyQuestion(QuizQuestion $question): RedirectResponse
    {
        $quiz = $question->quiz;
        if ($this->isScorableQuestion($question->type)) {
            $quiz->decrement('total_marks', $question->marks);
        }
        $question->delete();
        return back()->with('success', 'Question deleted!');
    }

    public function updateQuestion(Request $request, QuizQuestion $question): RedirectResponse
    {
        $quiz = $question->quiz;
        $oldMarks = $question->marks;

        $validated = $request->validate([
            'question' => ['required', 'string', 'max:5000'],
            'type' => ['required', 'string', 'in:multiple_choice,multiple_select,true_false,short_answer,essay,fill_in_blank,matching,ordering'],
            'marks' => ['required', 'integer', 'min:0', 'max:999'],
            'correct_answer' => ['nullable', 'string', 'max:1000'],
            'options' => ['nullable', 'string', 'max:10000'],
        ]);

        $validated['options'] = $this->processQuestionOptions($validated);

        DB::transaction(function () use ($quiz, $question, $validated, $oldMarks) {
            $question->update($validated);
            if ($this->isScorableQuestion($question->type)) {
                $quiz->increment('total_marks', $validated['marks'] - $oldMarks);
            }
        });

        return back()->with('success', 'Question updated successfully!');
    }

    protected function processQuestionOptions(array &$validated): ?array
    {
        $options = null;
        switch ($validated['type']) {
            case 'multiple_choice':
            case 'multiple_select':
                $options = $this->normalizeLines($validated['options'] ?? '');
                if ($validated['type'] === 'multiple_choice') {
                    $validated['correct_answer'] = $this->normalizeSingleAnswer($validated['correct_answer'] ?? '', $options);
                } else {
                    $answers = $this->normalizeMultipleAnswer($validated['correct_answer'] ?? '');
                    $validated['correct_answer'] = implode(',', array_values(array_intersect($answers, $options)));
                }
                break;
            case 'true_false':
                $options = ['True', 'False'];
                break;
            case 'fill_in_blank':
                $options = $this->normalizeLines($validated['options'] ?? '');
                break;
            case 'matching':
            case 'ordering':
                $options = $this->normalizeLines($validated['options'] ?? '');
                break;
        }
        return $options;
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
        $lines = preg_split('/\r\n|\r|\n/', $value);
        if ($lines === false) {
            return [];
        }
        return array_values(array_filter(array_map('trim', $lines), 'strlen'));
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

    // ─── Student: Pre-Attempt Instructions ──────────────────────
    public function instructions(Quiz $quiz): View
    {
        if ($quiz->status !== 'published') {
            abort(404);
        }
        if (!$quiz->isAvailable()) {
            return view('quizzes.locked', compact('quiz'));
        }
        $isEnrolled = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $quiz->course_id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->exists();
        if (!$isEnrolled) {
            abort(403, 'You must be enrolled in the course to view this.');
        }

        $quiz->load('questions');
        $attemptsInfo = $quiz->canUserAttempt(auth()->id());

        return view('quizzes.instructions', compact('quiz', 'attemptsInfo'));
    }

    // ─── Student: Take Quiz ───────────────────────────────────────
    public function take(Quiz $quiz): View
    {
        if ($quiz->status !== 'published') {
            abort(404);
        }
        if (!$quiz->isAvailable()) {
            return view('quizzes.locked', compact('quiz'));
        }
        $isEnrolled = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $quiz->course_id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->exists();
        if (!$isEnrolled) {
            abort(403, 'You must be enrolled in the course to take this quiz.');
        }

        // Check attempt limits
        $attemptLimit = $quiz->attempts_limit;
        if ($attemptLimit) {
            $attemptsCount = QuizResult::where('quiz_id', $quiz->id)
                ->where('user_id', auth()->id())
                ->count();
            if ($attemptsCount >= $attemptLimit) {
                abort(403, 'You have reached the maximum number of attempts for this quiz.');
            }
        }

        // Start server-enforced timer via QuizEnhancementService
        $attempt = $this->quizService->startQuizAttempt($quiz, auth()->user());

        $quiz->load('questions');
        return view('quizzes.take', compact('quiz', 'attempt'));
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

        // Load attempt for server timer enforcement
        $attemptId = $request->input('attempt_id');
        $attempt = null;
        if ($attemptId) {
            $attempt = QuizAttempt::where('id', $attemptId)
                ->where('quiz_id', $quiz->id)
                ->where('user_id', auth()->id())
                ->where('is_completed', false)
                ->first();
        }

        // If no attempt found, create one (backward compat for tests or direct submissions)
        if (!$attempt) {
            try {
                $attempt = $this->quizService->startQuizAttempt($quiz, auth()->user());
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        // Server-side time limit check — strictly enforced
        if ($attempt->expires_at && now()->greaterThan($attempt->expires_at)) {
            $attempt->update(['is_completed' => true, 'submitted_at' => now()]);
            return redirect("/dashboard/quizzes/my-result")->with('error', 'Time limit exceeded. Your attempt has been auto-submitted.');
        }

        // Store answers in the attempt
        $answers = $request->input('answers', []);
        $attempt->update(['answers' => $answers]);

        // Finalize via service (auto-grades and creates QuizResult)
        $result = $this->quizService->finalizeQuiz($attempt);

        $passed = $result->passed;
        $score = $result->score;
        $totalMarks = $quiz->total_marks ?? $quiz->questions->sum('marks');

        if ($quiz->show_results_immediately) {
            $quiz->update(['results_released_at' => now()]);
        }

        \App\Notifications\QuizResultNotification::send(auth()->user(), $quiz, $score, $totalMarks, $passed);
        $msg = $quiz->show_results_immediately
            ? 'Quiz submitted! Score: ' . $score . '/' . $totalMarks
            : 'Your ' . ($quiz->is_exam ? 'exam' : 'quiz') . ' has been submitted. Your instructor will review and release results soon.';
        return redirect("/dashboard/quizzes/my-result")->with('success', $msg);
    }

    public function myResults(): View
    {
        $results = QuizResult::with('quiz.course')->where('user_id', auth()->id())->latest()->get();
        return view('dashboard.quizzes.my-result', compact('results'));
    }

    // ─── Document Question Extraction ────────────────────────────
    public function extractQuestions(Request $request, Quiz $quiz): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,txt|max:20480',
        ]);

        try {
            $service = app(QuestionExtractionService::class);
            $questions = $service->extract($request->file('document'));

            return response()->json([
                'success' => true,
                'questions' => $questions,
                'count' => count($questions),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function bulkStoreQuestions(Request $request, Quiz $quiz): RedirectResponse
    {
        $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.marks' => 'required|integer|min:1',
        ]);

        $questions = $request->input('questions');
        $order = $quiz->questions()->max('order') ?? 0;

        foreach ($questions as $qData) {
            $order++;
            $question = [
                'quiz_id' => $quiz->id,
                'question' => $qData['question'],
                'type' => $qData['type'] ?? 'multiple_choice',
                'options' => $qData['options'] ?? null,
                'correct_answer' => $qData['correct_answer'] ?? null,
                'marks' => $qData['marks'] ?? 1,
                'order' => $order,
            ];

            if (is_string($question['options'])) {
                $question['options'] = array_values(array_filter(array_map('trim', explode("\n", $question['options'])), 'strlen'));
            }

            $q = QuizQuestion::create($question);

            if ($this->isScorableQuestion($q->type)) {
                $quiz->increment('total_marks', $q->marks);
            }
        }

        return back()->with('success', count($questions) . ' questions imported successfully!');
    }

    public function releaseResults(Quiz $quiz): RedirectResponse
    {
        if ($quiz->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $quiz->update(['results_released_at' => now()]);

        $students = QuizResult::with('user')
            ->where('quiz_id', $quiz->id)
            ->get()
            ->pluck('user')
            ->unique('id');

        foreach ($students as $student) {
            \App\Notifications\QuizResultNotification::sendResultsReleased($student, $quiz);
        }

        $label = $quiz->is_exam ? 'exam' : 'quiz';
        return back()->with('success', "Results for this {$label} have been released to all students.");
    }
}
