<?php

namespace App\Models;

use App\Models\Traits\Schedulable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Quiz extends Model
{
    use HasFactory;
    use Schedulable;

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'instructions',
        'instructions_file',
        'time_limit',
        'passing_score',
        'total_marks',
        'attempts_limit',
        'status',
        'randomize_options',
        'show_results_immediately',
        'certificate_on_pass',
        'proctoring_required',
        'is_exam',
        'class_id',
        'available_from',
        'results_released_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'passing_score' => 'decimal:2',
            'total_marks' => 'decimal:2',
            'randomize_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'certificate_on_pass' => 'boolean',
            'proctoring_required' => 'boolean',
            'is_exam' => 'boolean',
            'available_from' => 'datetime',
            'results_released_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function areResultsReleased(): bool
    {
        return $this->results_released_at !== null;
    }

    /**
     * Randomize quiz questions for display
     *
     * This method returns quiz questions in random order. If randomize_options
     * is enabled, it also shuffles the options for each question.
     *
     * @return Collection Collection of questions with potentially randomized order and options
     */
    public function randomizeQuestions(): Collection
    {
        $questions = $this->questions()->get();

        // Shuffle the questions themselves
        $questions = $questions->shuffle();

        // If randomize_options is enabled, shuffle the options for each question
        if ($this->randomize_options) {
            $questions = $questions->map(function ($question) {
                // Only randomize options if the question has options (multiple choice, etc.)
                if (!empty($question->options) && is_array($question->options)) {
                    // Create a copy to avoid modifying the original
                    $questionCopy = clone $question;
                    
                    // Shuffle the options array
                    $options = $question->options;
                    shuffle($options);
                    $questionCopy->options = $options;
                    
                    return $questionCopy;
                }
                
                return $question;
            });
        }

        return $questions;
    }

    /**
     * Calculate if a score passes the quiz
     *
     * @param float $score The score to check
     * @return array ['passed' => bool, 'score' => float, 'passing_score' => float|null, 'percentage' => float]
     */
    public function calculatePassingStatus(float $score): array
    {
        $percentage = $this->total_marks > 0 
            ? ($score / $this->total_marks) * 100 
            : 0;

        $passed = false;
        
        if ($this->passing_score !== null) {
            // If passing score is set, check if score meets or exceeds it
            $passed = $score >= $this->passing_score;
        }

        return [
            'passed' => $passed,
            'score' => round($score, 2),
            'passing_score' => $this->passing_score,
            'percentage' => round($percentage, 2),
        ];
    }

    /**
     * Check if a score passes the passing score threshold
     *
     * @param float $score The score to check
     * @return bool
     */
    public function isPassing(float $score): bool
    {
        if ($this->passing_score === null) {
            return false;
        }

        return $score >= $this->passing_score;
    }

    /**
     * Get the percentage score
     *
     * @param float $score The raw score
     * @return float The percentage score
     */
    public function getPercentageScore(float $score): float
    {
        if ($this->total_marks <= 0) {
            return 0.0;
        }

        return round(($score / $this->total_marks) * 100, 2);
    }

    /**
     * Check if the quiz requires a certificate on pass
     *
     * @return bool
     */
    public function requiresCertificate(): bool
    {
        return $this->certificate_on_pass === true;
    }

    /**
     * Check if the quiz should show results immediately after completion
     *
     * @return bool
     */
    public function showsResultsImmediately(): bool
    {
        return $this->show_results_immediately === true;
    }

    /**
     * Check if proctoring is required for this quiz
     *
     * @return bool
     */
    public function requiresProctoring(): bool
    {
        return $this->proctoring_required === true;
    }

    /**
     * Get user's quiz attempts for this quiz
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserAttempts(int $userId): Collection
    {
        return $this->quizAttempts()
            ->where('user_id', $userId)
            ->orderBy('attempt_number', 'desc')
            ->get();
    }

    /**
     * Get user's attempt count
     *
     * @param int $userId
     * @return int
     */
    public function getUserAttemptCount(int $userId): int
    {
        return $this->quizAttempts()
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Check if user can attempt the quiz
     *
     * @param int $userId
     * @return array ['can_attempt' => bool, 'reason' => string|null, 'attempts_used' => int, 'attempts_limit' => int|null]
     */
    public function canUserAttempt(int $userId): array
    {
        $attemptsUsed = $this->getUserAttemptCount($userId);

        // If no attempts limit is set, user can always attempt
        if ($this->attempts_limit === null || $this->attempts_limit <= 0) {
            return [
                'can_attempt' => true,
                'reason' => null,
                'attempts_used' => $attemptsUsed,
                'attempts_limit' => null,
            ];
        }

        // Check if user has attempts remaining
        if ($attemptsUsed >= $this->attempts_limit) {
            return [
                'can_attempt' => false,
                'reason' => "Maximum attempts ({$this->attempts_limit}) reached",
                'attempts_used' => $attemptsUsed,
                'attempts_limit' => $this->attempts_limit,
            ];
        }

        return [
            'can_attempt' => true,
            'reason' => null,
            'attempts_used' => $attemptsUsed,
            'attempts_limit' => $this->attempts_limit,
        ];
    }
}
