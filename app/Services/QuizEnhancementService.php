<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Quiz Enhancement Service
 * 
 * Manages enhanced quiz features including attempt tracking with timing,
 * time limit enforcement, question randomization, and auto-grading for
 * objective questions.
 */
class QuizEnhancementService
{
    /**
     * Create a new quiz with enhanced features.
     * 
     * @param array $data Quiz data
     * @return Quiz The created quiz
     * @throws Exception If creation fails
     */
    public function createQuiz(array $data): Quiz
    {
        try {
            DB::beginTransaction();

            // Validate required fields
            $this->validateQuizData($data);

            // Create the quiz
            $quiz = Quiz::create($data);

            DB::commit();

            Log::info("Quiz created successfully", [
                'quiz_id' => $quiz->id,
                'course_id' => $quiz->course_id,
                'title' => $quiz->title,
            ]);

            return $quiz;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to create quiz", [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Start a quiz attempt for a student.
     * 
     * Creates a new quiz attempt record, sets start time, and calculates
     * expiration time if quiz has a time limit.
     * 
     * @param Quiz $quiz The quiz to start
     * @param User $student The student taking the quiz
     * @return QuizAttempt The created quiz attempt
     * @throws Exception If student cannot attempt or creation fails
     */
    public function startQuizAttempt(Quiz $quiz, User $student): QuizAttempt
    {
        try {
            DB::beginTransaction();

            // Check if the user can attempt the quiz
            $attemptCheck = $quiz->canUserAttempt($student->id);
            if (!$attemptCheck['can_attempt']) {
                throw new Exception($attemptCheck['reason']);
            }

            // Calculate attempt number
            $attemptNumber = $quiz->getUserAttemptCount($student->id) + 1;

            // Calculate expiration time if quiz has time limit
            $expiresAt = null;
            if ($quiz->time_limit && $quiz->time_limit > 0) {
                $expiresAt = Carbon::now()->addMinutes($quiz->time_limit);
            }

            // Create quiz attempt
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'user_id' => $student->id,
                'started_at' => Carbon::now(),
                'expires_at' => $expiresAt,
                'answers' => [],
                'is_completed' => false,
                'attempt_number' => $attemptNumber,
            ]);

            DB::commit();

            Log::info("Quiz attempt started", [
                'attempt_id' => $attempt->id,
                'quiz_id' => $quiz->id,
                'user_id' => $student->id,
                'attempt_number' => $attemptNumber,
                'expires_at' => $expiresAt?->toIso8601String(),
            ]);

            return $attempt;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to start quiz attempt", [
                'quiz_id' => $quiz->id,
                'user_id' => $student->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Submit an answer for a quiz question.
     * 
     * Validates that the attempt is active and stores the answer.
     * Does not grade the question yet.
     * 
     * @param QuizAttempt $attempt The quiz attempt
     * @param int $questionId The question being answered
     * @param mixed $answer The student's answer
     * @return void
     * @throws Exception If attempt is invalid or expired
     */
    public function submitQuizAnswer(QuizAttempt $attempt, int $questionId, mixed $answer): void
    {
        try {
            // Check if attempt is completed
            if ($attempt->is_completed) {
                throw new Exception("This quiz attempt has already been completed");
            }

            // Check if attempt has expired
            if (!$this->checkTimeLimit($attempt)) {
                throw new Exception("This quiz attempt has expired");
            }

            // Verify question belongs to this quiz
            $question = QuizQuestion::where('quiz_id', $attempt->quiz_id)
                ->where('id', $questionId)
                ->firstOrFail();

            // Get current answers array
            $answers = $attempt->answers ?? [];

            // Store the answer
            $answers[$questionId] = [
                'question_id' => $questionId,
                'answer' => $answer,
                'answered_at' => Carbon::now()->toIso8601String(),
            ];

            // Update attempt with new answers
            $attempt->update([
                'answers' => $answers,
            ]);

            Log::info("Quiz answer submitted", [
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
            ]);
        } catch (Exception $e) {
            Log::error("Failed to submit quiz answer", [
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Finalize a quiz attempt and create quiz result.
     * 
     * Marks the attempt as completed, calculates score via auto-grading,
     * and creates a QuizResult record.
     * 
     * @param QuizAttempt $attempt The quiz attempt to finalize
     * @return QuizResult The created quiz result
     * @throws Exception If finalization fails
     */
    public function finalizeQuiz(QuizAttempt $attempt): QuizResult
    {
        try {
            DB::beginTransaction();

            // Check if already completed
            if ($attempt->is_completed) {
                throw new Exception("This quiz attempt has already been completed");
            }

            // Calculate score
            $score = $this->autoGradeQuiz($attempt);

            // Mark attempt as completed
            $attempt->update([
                'submitted_at' => Carbon::now(),
                'score' => $score,
                'is_completed' => true,
            ]);

            // Get quiz for passing status calculation
            $quiz = $attempt->quiz;

            // Determine if student passed
            $passed = $quiz->isPassing($score);

            // Create quiz result
            $result = QuizResult::create([
                'quiz_id' => $attempt->quiz_id,
                'user_id' => $attempt->user_id,
                'score' => $score,
                'total_marks' => $quiz->total_marks,
                'answers' => $attempt->answers,
                'started_at' => $attempt->started_at,
                'completed_at' => Carbon::now(),
                'passed' => $passed,
            ]);

            DB::commit();

            Log::info("Quiz attempt finalized", [
                'attempt_id' => $attempt->id,
                'result_id' => $result->id,
                'score' => $score,
                'total_marks' => $quiz->total_marks,
                'passed' => $passed,
            ]);

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to finalize quiz", [
                'attempt_id' => $attempt->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Auto-grade a quiz attempt for objective questions.
     * 
     * Calculates score by comparing student answers with correct answers.
     * Supports multiple choice, true/false, and other objective question types.
     * 
     * @param QuizAttempt $attempt The quiz attempt to grade
     * @return float The calculated score
     */
    public function autoGradeQuiz(QuizAttempt $attempt): float
    {
        $totalScore = 0.0;
        $answers = $attempt->answers ?? [];

        // Load all quiz questions
        $questions = QuizQuestion::where('quiz_id', $attempt->quiz_id)->get();

        foreach ($questions as $question) {
            // Check if student answered this question
            if (!isset($answers[$question->id])) {
                continue;
            }

            $studentAnswer = $answers[$question->id]['answer'] ?? null;
            $correctAnswer = $question->correct_answer;

            // Grade based on question type
            $isCorrect = $this->evaluateAnswer($question->type, $studentAnswer, $correctAnswer);

            if ($isCorrect) {
                $totalScore += (float) $question->marks;
            }
        }

        Log::info("Quiz auto-graded", [
            'attempt_id' => $attempt->id,
            'total_score' => $totalScore,
            'questions_count' => $questions->count(),
        ]);

        return round($totalScore, 2);
    }

    /**
     * Randomize quiz questions for a quiz.
     * 
     * Returns questions in random order. If randomize_options is enabled,
     * also randomizes the options for each question.
     * 
     * @param Quiz $quiz The quiz to randomize questions for
     * @return Collection Collection of randomized questions
     */
    public function randomizeQuestions(Quiz $quiz): Collection
    {
        return $quiz->randomizeQuestions();
    }

    /**
     * Check if a quiz attempt is still within time limit.
     * 
     * Returns true if attempt has no expiration or hasn't expired yet.
     * Returns false if attempt has expired.
     * 
     * @param QuizAttempt $attempt The quiz attempt to check
     * @return bool True if attempt is valid, false if expired
     */
    public function checkTimeLimit(QuizAttempt $attempt): bool
    {
        // If no expiration time set, always valid
        if (!$attempt->expires_at) {
            return true;
        }

        // Check if current time is before expiration
        return Carbon::now()->lessThan($attempt->expires_at);
    }

    /**
     * Evaluate if a student's answer is correct.
     * 
     * Compares student answer with correct answer based on question type.
     * Supports: multiple_choice, true_false, single_choice
     * 
     * @param string $questionType The type of question
     * @param mixed $studentAnswer The student's answer
     * @param mixed $correctAnswer The correct answer
     * @return bool True if answer is correct
     */
    private function evaluateAnswer(string $questionType, mixed $studentAnswer, mixed $correctAnswer): bool
    {
        // Handle null or empty answers
        if ($studentAnswer === null || $studentAnswer === '') {
            return false;
        }

        // Normalize question type
        $questionType = strtolower($questionType);

        switch ($questionType) {
            case 'multiple_choice':
            case 'single_choice':
            case 'mcq':
                // For multiple choice, compare string values (case-insensitive)
                return strcasecmp(trim((string)$studentAnswer), trim((string)$correctAnswer)) === 0;

            case 'true_false':
            case 'boolean':
                // For true/false, convert to boolean and compare
                return $this->normalizeBoolean($studentAnswer) === $this->normalizeBoolean($correctAnswer);

            case 'multiple_select':
            case 'checkbox':
                // For multiple select, compare arrays
                if (!is_array($studentAnswer) || !is_array($correctAnswer)) {
                    return false;
                }
                sort($studentAnswer);
                sort($correctAnswer);
                return $studentAnswer === $correctAnswer;

            case 'short_answer':
            case 'text':
                // For short answers, do case-insensitive comparison after trimming
                return strcasecmp(trim((string)$studentAnswer), trim((string)$correctAnswer)) === 0;

            default:
                // For unknown types, do direct comparison
                Log::warning("Unknown question type in auto-grading", [
                    'question_type' => $questionType,
                ]);
                return $studentAnswer === $correctAnswer;
        }
    }

    /**
     * Normalize a value to boolean.
     * 
     * Handles various representations of true/false.
     * 
     * @param mixed $value The value to normalize
     * @return bool The normalized boolean value
     */
    private function normalizeBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['true', '1', 'yes', 'y']);
        }

        return (bool) $value;
    }

    /**
     * Validate quiz data before creation.
     * 
     * @param array $data The quiz data to validate
     * @return void
     * @throws Exception If validation fails
     */
    private function validateQuizData(array $data): void
    {
        if (empty($data['title'])) {
            throw new Exception('Quiz title is required');
        }

        if (empty($data['course_id'])) {
            throw new Exception('Course ID is required');
        }

        if (isset($data['time_limit']) && $data['time_limit'] < 0) {
            throw new Exception('Time limit cannot be negative');
        }

        if (isset($data['passing_score']) && $data['passing_score'] < 0) {
            throw new Exception('Passing score cannot be negative');
        }

        if (isset($data['total_marks']) && $data['total_marks'] <= 0) {
            throw new Exception('Total marks must be greater than 0');
        }

        if (isset($data['attempts_limit']) && $data['attempts_limit'] < 0) {
            throw new Exception('Attempts limit cannot be negative');
        }
    }
}
