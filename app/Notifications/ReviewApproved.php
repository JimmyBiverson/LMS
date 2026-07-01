<?php

namespace App\Notifications;

use App\Models\NotificationLog;
use App\Models\Review;
use App\Models\User;

class ReviewApproved
{
    public static function send(Review $review): void
    {
        $course = $review->course;
        $student = $review->user;

        NotificationLog::create([
            'user_id' => $student->id,
            'type' => 'in_app',
            'subject' => 'Your Review Has Been Approved',
            'link' => url('dashboard'),
            'body' => 'Your ' . $review->rating . ' star review for "' . $course->title . '" is now public!',
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);

        if ($course && $course->instructor) {
            NotificationLog::create([
                'user_id' => $course->instructor->id,
                'type' => 'in_app',
                'subject' => 'Review Approved on Your Course',
                'link' => url('instructor/reviews'),
                'body' => 'A ' . $review->rating . ' star review on "' . $course->title . '" has been approved and is now public.',
                'channel' => 'in_app',
                'sent_at' => now(),
            ]);
        }
    }
}
