<?php

namespace App\Notifications;

use App\Models\NotificationLog;
use App\Models\User;

class InstructorRejected
{
    public static function send(User $instructor): void
    {
        NotificationLog::create([
            'user_id' => $instructor->id,
            'type' => 'in_app',
            'subject' => 'Instructor Account Disapproved',
            'body' => 'Your instructor account has been disapproved. Please contact support for more information.',
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);
    }
}
