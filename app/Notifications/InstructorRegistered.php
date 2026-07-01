<?php

namespace App\Notifications;

use App\Models\NotificationLog;
use App\Models\User;

class InstructorRegistered
{
    public static function send(User $instructor): void
    {
        $admins = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF])->get();
        foreach ($admins as $admin) {
            NotificationLog::create([
                'user_id' => $admin->id,
                'type' => 'in_app',
                'subject' => 'New Instructor Registration',
                'link' => url('admin/settings/approve-instructors'),
                'body' => $instructor->name . ' (' . $instructor->email . ') has registered as an instructor and is pending your approval.',
                'channel' => 'in_app',
                'sent_at' => now(),
            ]);
        }
    }
}
