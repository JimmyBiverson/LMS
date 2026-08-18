<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoomNotification extends Model
{
    public const TYPE_SCHEDULED = 'scheduled';
    public const TYPE_RESCHEDULED = 'rescheduled';
    public const TYPE_CANCELLED = 'cancelled';
    public const TYPE_STARTING_SOON = 'starting_soon';
    public const TYPE_RECORDING = 'recording_available';
    public const TYPE_REMINDER = 'reminder';

    public const CHANNEL_IN_APP = 'in_app';
    public const CHANNEL_EMAIL = 'email';

    protected $fillable = [
        'meeting_id', 'user_id', 'type', 'channel',
        'subject', 'body', 'link', 'is_sent', 'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_sent' => 'boolean',
            'sent_at' => 'datetime',
        ];
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(ZoomMeeting::class, 'meeting_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
