<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\NotificationLog;
use App\Models\User;

class LessonCompleted
{
    public static function send(User $user, Lesson $lesson, Course $course): void
    {
        NotificationLog::create([
            'user_id' => $user->id,
            'type' => 'in_app',
            'subject' => 'Lesson Completed: ' . $lesson->title,
            'body' => 'Great job completing "' . $lesson->title . '" in "' . $course->title . '". Keep going!',
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);
    }
}
