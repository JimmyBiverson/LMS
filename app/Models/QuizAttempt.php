<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Quiz Attempt Model
 * 
 * Tracks individual quiz attempts with timing. This model manages the state
 * of a student's quiz attempt, including start/end times, expiration, scoring,
 * and the answers provided.
 * 
 * @property int $id
 * @property int $quiz_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $started_at
 * @property \Illuminate\Support\Carbon|null $submitted_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property float|null $score
 * @property array $answers User answers stored as JSON
 * @property bool $is_completed
 * @property int $attempt_number
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * 
 * @property-read \App\Models\Quiz $quiz
 * @property-read \App\Models\User $user
 */
class QuizAttempt extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_id',
        'user_id',
        'started_at',
        'submitted_at',
        'expires_at',
        'score',
        'answers',
        'is_completed',
        'attempt_number',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'expires_at' => 'datetime',
            'score' => 'decimal:2',
            'answers' => 'array',
            'is_completed' => 'boolean',
        ];
    }

    /**
     * Get the quiz that this attempt belongs to.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the user who made this attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to retrieve active attempts (not completed and not expired).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_completed', false)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope to retrieve expired attempts (expired and not completed).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('is_completed', false)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now());
    }
}
