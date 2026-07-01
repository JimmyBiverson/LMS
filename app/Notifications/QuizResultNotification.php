<?php

namespace App\Notifications;

use App\Models\NotificationLog;
use App\Models\Quiz;
use App\Models\User;

class QuizResultNotification
{
    public static function send(User $user, Quiz $quiz, int $score, int $total, bool $passed): void
    {
        if ($quiz->results_released_at) {
            $body = ($passed ? 'You passed' : 'You scored') . ' ' . $score . '/' . $total . ' on "' . $quiz->title . '".';
        } else {
            $label = $quiz->is_exam ? 'exam' : 'quiz';
            $body = 'Your ' . $label . ' "' . $quiz->title . '" has been submitted and is awaiting instructor review.';
        }

        NotificationLog::create([
            'user_id' => $user->id,
            'type'    => 'in_app',
            'subject'  => 'Quiz Result: ' . $quiz->title,
            'body'     => $body,
            'channel'  => 'in_app',
            'sent_at'  => now(),
        ]);
    }

    public static function sendResultsReleased(User $user, Quiz $quiz): void
    {
        $label = $quiz->is_exam ? 'exam' : 'quiz';
        NotificationLog::create([
            'user_id' => $user->id,
            'type'    => 'in_app',
            'subject'  => 'Results Released: ' . $quiz->title,
            'body'     => 'Your results for ' . $label . ' "' . $quiz->title . '" are now available.',
            'channel'  => 'in_app',
            'sent_at'  => now(),
        ]);
    }
}
