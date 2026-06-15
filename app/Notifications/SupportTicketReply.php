<?php

namespace App\Notifications;

use App\Models\NotificationLog;
use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketReply
{
    public static function send(SupportTicket $ticket, User $replier): void
    {
        // Notify the ticket creator if the replier is not them
        if ($replier->id !== $ticket->user_id) {
            NotificationLog::create([
                'user_id' => $ticket->user_id,
                'type' => 'in_app',
                'subject' => 'Reply to Your Support Ticket: ' . $ticket->subject,
                'body' => $replier->name . ' replied to your support ticket "' . $ticket->subject . '"',
                'channel' => 'in_app',
                'sent_at' => now(),
            ]);
        }

        // Notify the instructor if this is course-related and replier is not the instructor
        if ($ticket->course_id && $replier->id !== $ticket->course->user_id) {
            $instructor = User::find($ticket->course->user_id);
            if ($instructor) {
                NotificationLog::create([
                    'user_id' => $instructor->id,
                    'type' => 'in_app',
                    'subject' => 'Reply to Support Ticket: ' . $ticket->subject,
                    'body' => $replier->name . ' replied to support ticket "' . $ticket->subject . '"',
                    'channel' => 'in_app',
                    'sent_at' => now(),
                ]);
            }
        }

        // Notify admins if replier is not an admin
        if ($replier->role !== User::ROLE_ADMIN) {
            $admins = User::where('role', User::ROLE_ADMIN)->get();
            foreach ($admins as $admin) {
                NotificationLog::create([
                    'user_id' => $admin->id,
                    'type' => 'in_app',
                    'subject' => 'Reply to Support Ticket: ' . $ticket->subject,
                    'body' => $replier->name . ' replied to support ticket "' . $ticket->subject . '"',
                    'channel' => 'in_app',
                    'sent_at' => now(),
                ]);
            }
        }
    }
}
