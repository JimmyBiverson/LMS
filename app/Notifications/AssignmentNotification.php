<?php

namespace App\Notifications;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\NotificationLog;

class AssignmentNotification
{
    public static function sendPublishedToEnrolled(Assignment $assignment): void
    {
        $enrollments = Enrollment::where('course_id', $assignment->course_id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->get();

        foreach ($enrollments as $enrollment) {
            NotificationLog::create([
                'user_id' => $enrollment->user_id,
                'type'    => 'in_app',
                'subject'  => 'New Assignment: ' . $assignment->title,
                'body'     => 'A new assignment "' . $assignment->title . '" has been published in your course.',
                'channel'  => 'in_app',
                'sent_at'  => now(),
            ]);
        }
    }

    public static function sendScheduledToEnrolled(Assignment $assignment): void
    {
        $date = $assignment->available_from ? $assignment->available_from->format('M j, Y g:i A') : 'TBD';
        $enrollments = Enrollment::where('course_id', $assignment->course_id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->get();

        foreach ($enrollments as $enrollment) {
            NotificationLog::create([
                'user_id' => $enrollment->user_id,
                'type'    => 'in_app',
                'subject'  => 'Upcoming Assignment: ' . $assignment->title,
                'body'     => 'The assignment "' . $assignment->title . '" is scheduled and will be available on ' . $date . '.',
                'channel'  => 'in_app',
                'sent_at'  => now(),
            ]);
        }
    }
}
