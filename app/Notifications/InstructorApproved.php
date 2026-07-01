<?php

namespace App\Notifications;

use App\Models\NotificationLog;
use App\Models\User;

class InstructorApproved
{
    public static function send(User $instructor): void
    {
        NotificationLog::create([
            'user_id' => $instructor->id,
            'type' => 'in_app',
            'subject' => 'Instructor Account Approved',
            'link' => url('instructor'),
            'body' => 'Congratulations ' . $instructor->name . '! Your instructor account has been approved. You can now create courses and manage students.',
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);
    }
}
