<?php

namespace App\Models;

use App\Models\Traits\Schedulable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;

class Assignment extends Model
{
    use HasFactory;
    use Schedulable;

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'description',
        'instructions',
        'instructions_file',
        'due_date',
        'total_marks',
        'status',
        'time_limit_minutes',
        'max_file_size_mb',
        'allowed_file_types',
        'late_submission_allowed',
        'late_penalty_percent',
        'grading_rubric',
        'available_from',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'allowed_file_types' => 'array',
            'grading_rubric' => 'array',
            'late_submission_allowed' => 'boolean',
            'late_penalty_percent'   => 'decimal:2',
            'available_from'         => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    /**
     * Validate if a file meets the upload requirements
     *
     * @param UploadedFile $file
     * @return array ['valid' => bool, 'error' => string|null]
     */
    public function validateFileUpload(UploadedFile $file): array
    {
        // Check file size
        $fileSizeMB = $file->getSize() / 1024 / 1024;
        if ($fileSizeMB > $this->max_file_size_mb) {
            return [
                'valid' => false,
                'error' => "File size ({$fileSizeMB}MB) exceeds maximum allowed size ({$this->max_file_size_mb}MB)"
            ];
        }

        // Check file type if restrictions are set
        if (!empty($this->allowed_file_types)) {
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = array_map('strtolower', $this->allowed_file_types);
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                return [
                    'valid' => false,
                    'error' => "File type '{$fileExtension}' is not allowed. Allowed types: " . implode(', ', $allowedExtensions)
                ];
            }
        }

        return ['valid' => true, 'error' => null];
    }

    /**
     * Check if the assignment deadline has passed
     *
     * @param Carbon|null $comparisonDate Date to compare against (defaults to now)
     * @return bool
     */
    public function isDeadlinePassed(?Carbon $comparisonDate = null): bool
    {
        if (!$this->due_date) {
            return false;
        }

        $compareDate = $comparisonDate ?? Carbon::now();
        return $compareDate->isAfter($this->due_date);
    }

    /**
     * Check if submission is considered late
     *
     * @param Carbon|null $submissionDate Date of submission (defaults to now)
     * @return bool
     */
    public function isSubmissionLate(?Carbon $submissionDate = null): bool
    {
        if (!$this->due_date) {
            return false;
        }

        $submitDate = $submissionDate ?? Carbon::now();
        return $submitDate->isAfter($this->due_date);
    }

    /**
     * Check if late submissions are accepted and calculate how late
     *
     * @param Carbon|null $submissionDate Date of submission (defaults to now)
     * @return array ['accepted' => bool, 'hours_late' => float|null, 'message' => string|null]
     */
    public function checkLateSubmission(?Carbon $submissionDate = null): array
    {
        if (!$this->due_date) {
            return [
                'accepted' => true,
                'hours_late' => null,
                'message' => 'No deadline set'
            ];
        }

        $submitDate = $submissionDate ?? Carbon::now();
        
        if (!$this->isSubmissionLate($submitDate)) {
            return [
                'accepted' => true,
                'hours_late' => null,
                'message' => 'Submitted on time'
            ];
        }

        $hoursLate = abs($this->due_date->diffInHours($submitDate, false));

        if (!$this->late_submission_allowed) {
            return [
                'accepted' => false,
                'hours_late' => $hoursLate,
                'message' => 'Late submissions are not allowed for this assignment'
            ];
        }

        return [
            'accepted' => true,
            'hours_late' => $hoursLate,
            'message' => "Submitted {$hoursLate} hours late"
        ];
    }

    /**
     * Calculate the late penalty to be applied to the score
     *
     * @param float $originalScore The original score before penalty
     * @param Carbon|null $submissionDate Date of submission (defaults to now)
     * @return array ['penalized_score' => float, 'penalty_applied' => float, 'is_late' => bool]
     */
    public function calculateLatePenalty(float $originalScore, ?Carbon $submissionDate = null): array
    {
        if (!$this->isSubmissionLate($submissionDate)) {
            return [
                'penalized_score' => $originalScore,
                'penalty_applied' => 0.0,
                'is_late' => false
            ];
        }

        if (!$this->late_submission_allowed || !$this->late_penalty_percent) {
            return [
                'penalized_score' => $originalScore,
                'penalty_applied' => 0.0,
                'is_late' => true
            ];
        }

        $penaltyAmount = $originalScore * ($this->late_penalty_percent / 100);
        $penalizedScore = max(0, $originalScore - $penaltyAmount);

        return [
            'penalized_score' => round($penalizedScore, 2),
            'penalty_applied' => round($penaltyAmount, 2),
            'is_late' => true
        ];
    }

    /**
     * Get time remaining until deadline
     *
     * @return array ['expired' => bool, 'days' => int, 'hours' => int, 'minutes' => int, 'human_readable' => string]
     */
    public function getTimeRemaining(): array
    {
        if (!$this->due_date) {
            return [
                'expired' => false,
                'days' => null,
                'hours' => null,
                'minutes' => null,
                'human_readable' => 'No deadline'
            ];
        }

        $now = Carbon::now();
        
        if ($now->isAfter($this->due_date)) {
            $diff = $now->diff($this->due_date);
            return [
                'expired' => true,
                'days' => $diff->days,
                'hours' => $diff->h,
                'minutes' => $diff->i,
                'human_readable' => 'Deadline passed ' . $this->due_date->diffForHumans()
            ];
        }

        $diff = $now->diff($this->due_date);
        
        return [
            'expired' => false,
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'human_readable' => $this->due_date->diffForHumans()
        ];
    }

    /**
     * Check if assignment accepts submissions
     *
     * @param Carbon|null $checkDate Date to check against (defaults to now)
     * @return array ['accepts_submissions' => bool, 'reason' => string|null]
     */
    public function acceptsSubmissions(?Carbon $checkDate = null): array
    {
        $date = $checkDate ?? Carbon::now();

        // Check if deadline passed
        if ($this->isDeadlinePassed($date)) {
            if ($this->late_submission_allowed) {
                return [
                    'accepts_submissions' => true,
                    'reason' => 'Late submission (penalty may apply)'
                ];
            }
            
            return [
                'accepts_submissions' => false,
                'reason' => 'Deadline has passed and late submissions are not allowed'
            ];
        }

        return [
            'accepts_submissions' => true,
            'reason' => null
        ];
    }

    /**
     * Get formatted allowed file types for display
     *
     * @return string
     */
    public function getAllowedFileTypesFormatted(): string
    {
        if (empty($this->allowed_file_types)) {
            return 'All file types';
        }

        return implode(', ', array_map('strtoupper', $this->allowed_file_types));
    }
}
