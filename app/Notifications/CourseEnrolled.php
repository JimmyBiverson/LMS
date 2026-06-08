<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\NotificationLog;
use App\Models\User;

class CourseEnrolled
{
    public static function send(User $user, Course $course): void
    {
        NotificationLog::create([
            'user_id' => $user->id,
            'type' => 'in_app',
            'subject' => 'Enrolled in ' . $course->title,
            'body' => 'You have successfully enrolled in "' . $course->title . '". Start learning today!',
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);
    }
}
