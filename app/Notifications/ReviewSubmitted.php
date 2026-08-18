<?php

namespace App\Notifications;

use App\Models\NotificationLog;
use App\Models\Review;
use App\Models\User;

class ReviewSubmitted
{
    public static function send(Review $review): void
    {
        $course = $review->course;
        $student = $review->user;

        if ($course && $course->instructor) {
            NotificationLog::create([
                'user_id' => $course->instructor->id,
                'type' => 'in_app',
                'subject' => 'New Review on Your Course',
                'link' => url('instructor/reviews'),
                'body' => $student->name . ' left a ' . $review->rating . ' star review on "' . $course->title . '"',
                'channel' => 'in_app',
                'sent_at' => now(),
            ]);
        }

        $admins = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF])->get();
        foreach ($admins as $admin) {
            NotificationLog::create([
                'user_id' => $admin->id,
                'type' => 'in_app',
                'subject' => 'New Review Submitted - Pending Approval',
                'link' => url('admin/review'),
                'body' => $student->name . ' submitted a ' . $review->rating . ' star review for "' . $course->title . '" — requires your approval.',
                'channel' => 'in_app',
                'sent_at' => now(),
            ]);
        }
    }
}
