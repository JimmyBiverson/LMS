<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoomAttendance extends Model
{
    protected $table = 'zoom_attendance';

    public const STATUS_PRESENT = 'present';
    public const STATUS_LATE = 'late';
    public const STATUS_LEFT_EARLY = 'left_early';
    public const STATUS_ABSENT = 'absent';

    public const SOURCE_MANUAL = 'manual';
    public const SOURCE_ZOOM = 'zoom';

    protected $fillable = [
        'meeting_id', 'student_id', 'join_time', 'leave_time',
        'duration_minutes', 'status', 'source',
    ];

    protected function casts(): array
    {
        return [
            'join_time' => 'datetime',
            'leave_time' => 'datetime',
            'duration_minutes' => 'integer',
        ];
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(ZoomMeeting::class, 'meeting_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }
}
