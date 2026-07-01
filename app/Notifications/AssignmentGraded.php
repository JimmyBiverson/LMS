<?php

namespace App\Notifications;

use App\Models\AssignmentSubmission;
use App\Models\NotificationLog;
use App\Models\User;

class AssignmentGraded
{
    public static function send(User $user, AssignmentSubmission $submission): void
    {
        NotificationLog::create([
            'user_id' => $user->id,
            'type' => 'in_app',
            'subject' => 'Assignment Graded: ' . ($submission->assignment->title ?? ''),
            'link' => url('dashboard/assignments'),
            'body' => 'Your assignment "' . ($submission->assignment->title ?? '') . '" has been graded. Score: ' . $submission->score . '/' . $submission->assignment->total_marks,
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);
    }
}
