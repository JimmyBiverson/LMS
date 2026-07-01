<?php

namespace App\Notifications;

use App\Models\Enrollment;
use App\Models\NotificationLog;
use App\Models\Quiz;

class QuizNotification
{
    public static function sendPublishedToEnrolled(Quiz $quiz): void
    {
        $label = $quiz->is_exam ? 'exam' : 'quiz';
        $enrollments = Enrollment::where('course_id', $quiz->course_id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->get();

        foreach ($enrollments as $enrollment) {
            NotificationLog::create([
                'user_id' => $enrollment->user_id,
                'type'    => 'in_app',
                'subject'  => 'New ' . ucfirst($label) . ': ' . $quiz->title,
                'body'     => 'A new ' . $label . ' "' . $quiz->title . '" has been published in your course.',
                'channel'  => 'in_app',
                'sent_at'  => now(),
            ]);
        }
    }

    public static function sendScheduledToEnrolled(Quiz $quiz): void
    {
        $label = $quiz->is_exam ? 'exam' : 'quiz';
        $date = $quiz->available_from ? $quiz->available_from->format('M j, Y g:i A') : 'TBD';
        $enrollments = Enrollment::where('course_id', $quiz->course_id)
            ->whereIn('status', ['in_progress', 'completed'])
            ->get();

        foreach ($enrollments as $enrollment) {
            NotificationLog::create([
                'user_id' => $enrollment->user_id,
                'type'    => 'in_app',
                'subject'  => 'Upcoming ' . ucfirst($label) . ': ' . $quiz->title,
                'body'     => 'The ' . $label . ' "' . $quiz->title . '" has been scheduled and will be available on ' . $date . '.',
                'channel'  => 'in_app',
                'sent_at'  => now(),
            ]);
        }
    }

    public static function sendResultsReleasedToEnrolled(Quiz $quiz): void
    {
        $label = $quiz->is_exam ? 'exam' : 'quiz';
        $results = \App\Models\QuizResult::where('quiz_id', $quiz->id)->get();

        foreach ($results as $result) {
            NotificationLog::create([
                'user_id' => $result->user_id,
                'type'    => 'in_app',
                'subject'  => 'Results Released: ' . $quiz->title,
                'body'     => 'Your results for the ' . $label . ' "' . $quiz->title . '" are now available.',
                'channel'  => 'in_app',
                'sent_at'  => now(),
            ]);
        }
    }
}
