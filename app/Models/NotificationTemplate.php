<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationTemplate extends Model
{
    protected $table = 'notification_templates';

    protected $fillable = [
        'type',
        'template_name',
        'subject',
        'body',
        'status',
    ];

    public function notificationLogs(): HasMany
    {
        return $this->hasMany(NotificationLog::class, 'notification_template_id');
    }
}
