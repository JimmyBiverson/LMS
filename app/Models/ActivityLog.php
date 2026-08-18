<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Activity Log Model
 * 
 * Tracks user activities for admin monitoring. This model logs all significant
 * user actions within the LMS system, including course interactions, assignment
 * submissions, quiz attempts, and other user activities.
 * 
 * @property int $id
 * @property int $user_id
 * @property string $action Action performed (e.g., "course.viewed", "assignment.submitted")
 * @property string|null $subject_type Type of the subject (polymorphic)
 * @property int|null $subject_id ID of the subject (polymorphic)
 * @property array|null $metadata Additional context data stored as JSON
 * @property string|null $ip_address IP address of the user
 * @property string|null $user_agent User agent string
 * @property \Illuminate\Support\Carbon $created_at
 * 
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Model $subject
 */
class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    /**
     * Indicates if the model should be timestamped.
     * 
     * We only track created_at, not updated_at
     *
     * @var bool
     */
    public const UPDATED_AT = null;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the user that performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity (polymorphic relationship).
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
