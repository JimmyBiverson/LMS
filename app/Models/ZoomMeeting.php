<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class ZoomMeeting extends Model
{
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_STARTING_SOON = 'starting_soon';
    public const STATUS_LIVE = 'live';
    public const STATUS_ENDED = 'ended';
    public const STATUS_CANCELLED = 'cancelled';

    public const SCOPE_COURSE = 'course';
    public const SCOPE_LESSON = 'lesson';
    public const SCOPE_INSTITUTION = 'institution';

    public const RECORDING_NONE = 'none';
    public const RECORDING_PROCESSING = 'processing';
    public const RECORDING_AVAILABLE = 'available';

    protected $fillable = [
        'meet_provider_id', 'course_id', 'lesson_id', 'instructor_id', 'created_by',
        'scope_type', 'topic', 'agenda', 'start_time', 'timezone', 'duration_minutes',
        'meeting_type', 'zoom_meeting_id', 'zoom_uuid', 'join_url', 'start_url',
        'password', 'host_email', 'status', 'is_recurring', 'recurring_settings',
        'recording_status', 'recording_url', 'recording_password', 'recording_files',
        'recording_published', 'has_attendance', 'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'is_recurring' => 'boolean',
            'recurring_settings' => 'array',
            'recording_files' => 'array',
            'recording_published' => 'boolean',
            'has_attendance' => 'boolean',
            'last_synced_at' => 'datetime',
            'start_url' => 'encrypted',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(MeetProvider::class, 'meet_provider_id');
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(ZoomAttendance::class, 'meeting_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(ZoomNotification::class, 'meeting_id');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_SCHEDULED, self::STATUS_STARTING_SOON, self::STATUS_LIVE]);
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ENDED);
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return $query;
        }

        if ($user->isInstructor()) {
            $courseIds = Course::where(fn ($q) => $q->where('user_id', $user->id)->orWhere('instructor_id', $user->id))
                ->pluck('id');

            return $query->where(function (Builder $q) use ($courseIds, $user) {
                $q->whereIn('course_id', $courseIds)
                    ->orWhere('instructor_id', $user->id)
                    ->orWhere('scope_type', self::SCOPE_INSTITUTION);
            });
        }

        if ($user->isStudent()) {
            $courseIds = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['in_progress', 'completed'])
                ->pluck('course_id');

            return $query->where(function (Builder $q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds)
                    ->orWhere('scope_type', self::SCOPE_INSTITUTION);
            });
        }

        return $query->whereRaw('1 = 0');
    }

    public function endTime(): Carbon
    {
        return $this->start_time->copy()->addMinutes($this->duration_minutes);
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }

    public function isEnded(): bool
    {
        return $this->status === self::STATUS_ENDED;
    }

    public function hasRecording(): bool
    {
        return $this->recording_status === self::RECORDING_AVAILABLE && $this->recording_url;
    }

    /**
     * Attendance rate for ended meetings (attended / marked students).
     */
    public function attendanceRate(): int
    {
        $records = $this->attendance()->count(['id']);

        if ($records === 0) {
            return 0;
        }

        $attended = $this->attendance()
            ->whereIn('status', [ZoomAttendance::STATUS_PRESENT, ZoomAttendance::STATUS_LATE, ZoomAttendance::STATUS_LEFT_EARLY])
            ->count(['id']);

        return (int) round(($attended / $records) * 100);
    }

    public function canStudentsJoin(): bool
    {
        return ! in_array($this->status, [self::STATUS_ENDED, self::STATUS_CANCELLED], true);
    }

    public function isJoinableNow(?Carbon $now = null): bool
    {
        $now = $now ?? now();

        return in_array($this->status, [self::STATUS_SCHEDULED, self::STATUS_STARTING_SOON, self::STATUS_LIVE], true)
            && $now->lte($this->endTime());
    }

    /**
     * Compute the current lifecycle status purely from the clock.
     * Used by the sync command and by views to avoid stale writes.
     */
    public function computeStatus(?Carbon $now = null): string
    {
        if ($this->isCancelled()) {
            return self::STATUS_CANCELLED;
        }

        if ($this->isLive()) {
            return self::STATUS_LIVE;
        }

        if ($this->isEnded()) {
            return self::STATUS_ENDED;
        }

        $now = $now ?? now();

        if ($now->gte($this->endTime())) {
            return self::STATUS_ENDED;
        }

        if ($now->gte($this->start_time)) {
            return self::STATUS_LIVE;
        }

        if ($now->gte($this->start_time->copy()->subMinutes((int) config('zoom.starting_soon_minutes', 15)))) {
            return self::STATUS_STARTING_SOON;
        }

        return self::STATUS_SCHEDULED;
    }

    /**
     * Ids of the students who are allowed to see/join this meeting.
     */
    public function enrolledStudentIds(): array
    {
        if ($this->scope_type === self::SCOPE_INSTITUTION) {
            return User::where('role', User::ROLE_STUDENT)
                ->where('status', User::STATUS_ACTIVE)
                ->pluck('id')
                ->all();
        }

        if (! $this->course_id) {
            return [];
        }

        return $this->course?->enrollments()
            ->whereIn('status', ['in_progress', 'completed'])
            ->pluck('enrollments.user_id')
            ->all() ?? [];
    }
}
