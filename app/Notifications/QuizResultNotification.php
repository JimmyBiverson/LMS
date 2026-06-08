<?php

namespace App\Notifications;

use App\Models\NotificationLog;
use App\Models\Quiz;
use App\Models\User;

class QuizResultNotification
{
    public static function send(User $user, Quiz $quiz, int $score, int $total, bool $passed): void
    {
        NotificationLog::create([
            'user_id' => $user->id,
            'type' => 'in_app',
            'subject' => 'Quiz Result: ' . $quiz->title,
            'body' => ($passed ? 'You passed' : 'You scored') . ' ' . $score . '/' . $total . ' on "' . $quiz->title . '".',
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);
    }
}
