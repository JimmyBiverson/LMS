<?php

namespace App\Notifications;

use App\Models\NotificationLog;
use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketCreated
{
    public static function send(SupportTicket $ticket): void
    {
        // Notify the course instructor if this is course-related
        if ($ticket->course_id) {
            $instructor = User::find($ticket->course->user_id);
            if ($instructor) {
                NotificationLog::create([
                    'user_id' => $instructor->id,
                    'type' => 'in_app',
                    'subject' => 'New Support Ticket: ' . $ticket->subject,
                    'body' => 'Student ' . $ticket->user->name . ' has created a support ticket: "' . $ticket->subject . '" (Priority: ' . $ticket->priority . ')',
                    'channel' => 'in_app',
                    'sent_at' => now(),
                ]);
            }
        }

        // Always notify admins about support tickets
        $admins = User::where('role', User::ROLE_ADMIN)->get();
        foreach ($admins as $admin) {
            NotificationLog::create([
                'user_id' => $admin->id,
                'type' => 'in_app',
                'subject' => 'New Support Ticket: ' . $ticket->subject,
                'body' => 'Student ' . $ticket->user->name . ' has created a support ticket: "' . $ticket->subject . '" (Priority: ' . $ticket->priority . ')',
                'channel' => 'in_app',
                'sent_at' => now(),
            ]);
        }
    }
}
