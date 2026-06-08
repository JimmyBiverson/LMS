<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\NotificationLog;
use App\Models\User;

class CourseCompleted
{
    public static function send(User $user, Course $course): void
    {
        NotificationLog::create([
            'user_id' => $user->id,
            'type' => 'in_app',
            'subject' => 'Course Completed: ' . $course->title,
            'body' => 'Congratulations! You have completed "' . $course->title . '". Download your certificate now!',
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);
    }
}
