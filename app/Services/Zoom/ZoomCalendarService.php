<?php

namespace App\Services\Zoom;

use App\Models\User;
use App\Models\ZoomMeeting;
use Illuminate\Support\Carbon;

class ZoomCalendarService
{
    /**
     * Meetings visible to the given user within a UTC time range.
     */
    public function eventsForUser(User $user, Carbon $from, Carbon $to)
    {
        return ZoomMeeting::with(['course', 'lesson'])
            ->where('status', '!=', ZoomMeeting::STATUS_CANCELLED)
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start_time', [$from, $to])
                    ->orWhereRaw('DATE_ADD(start_time, INTERVAL duration_minutes MINUTE) >= ?', [$from])
                    ->whereRaw('DATE_ADD(start_time, INTERVAL duration_minutes MINUTE) <= ?', [$to]);
            })
            ->visibleTo($user)
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Array payload for the calendar UI (month/week/day views).
     */
    public function eventPayload(User $user, Carbon $from, Carbon $to): array
    {
        return $this->eventsForUser($user, $from, $to)
            ->map(fn (ZoomMeeting $m) => $this->toEvent($m, $user))
            ->values()
            ->all();
    }

    public function toEvent(ZoomMeeting $m, ?User $user = null): array
    {
        $start = $m->start_time;
        $end = $m->endTime();
        $status = $m->computeStatus();

        return [
            'id' => $m->id,
            'title' => $m->topic,
            'course' => $m->course?->title,
            'start' => $start->toIso8601String(),
            'end' => $end->toIso8601String(),
            'status' => $status,
            'live' => in_array($status, ['live', 'starting_soon'], true),
            'timezone' => $m->timezone,
            'url' => $user
                ? $this->showUrl($m, $user)
                : route('zoom.show', $m),
        ];
    }

    public function showUrl(ZoomMeeting $m, User $user): string
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return route('zoom.admin.show', $m);
        }

        if ($user->isInstructor()) {
            return route('zoom.instructor.show', $m);
        }

        return route('zoom.show', $m);
    }

    /**
     * A full calendar file for the visible meetings in a range.
     */
    public function icsForRange(User $user, Carbon $from, Carbon $to): string
    {
        $events = $this->eventsForUser($user, $from, $to)
            ->map(fn (ZoomMeeting $m) => $this->icsEvent($m))
            ->implode('');

        return $this->icsEnvelope($events);
    }

    /**
     * Single meeting ICS file.
     */
    public function icsForMeeting(ZoomMeeting $meeting): string
    {
        return $this->icsEnvelope($this->icsEvent($meeting));
    }

    protected function icsEvent(ZoomMeeting $m): string
    {
        $tz = $m->timezone ?: 'UTC';
        $start = $m->start_time->copy()->setTimezone($tz);
        $end = $m->endTime()->setTimezone($tz);

        $lines = [
            'BEGIN:VEVENT',
            'UID:zoom-'.$m->id.'@lms-sample.duckdns.org',
            'DTSTAMP:'.$start->format('Ymd\THis').'Z',
            'DTSTART;TZID='.$tz.':'.$start->format('Ymd\THis'),
            'DTEND;TZID='.$tz.':'.$end->format('Ymd\THis'),
            'SUMMARY:'.$this->escape($m->topic),
            'DESCRIPTION:'.$this->escape($m->agenda ?: 'Zoom classroom session'),
            'LOCATION:'.$this->escape($m->join_url ?: ''),
            'STATUS:'.($m->isCancelled() ? 'CANCELLED' : 'CONFIRMED'),
            'END:VEVENT',
        ];

        return implode("\r\n", $lines)."\r\n";
    }

    protected function icsEnvelope(string $body): string
    {
        return "BEGIN:VCALENDAR\r\n"
            ."VERSION:2.0\r\n"
            ."PRODID:-//LMS//Zoom Classroom//EN\r\n"
            ."CALSCALE:GREGORIAN\r\n"
            ."METHOD:PUBLISH\r\n"
            .$body
            ."END:VCALENDAR\r\n";
    }

    protected function escape(string $value): string
    {
        return str_replace(
            ["\\", ',', ';', "\n"],
            ["\\\\", '\\,', '\\;', '\\n'],
            $value
        );
    }

    /**
     * Google Calendar "add event" URL.
     */
    public function googleUrl(ZoomMeeting $m): string
    {
        $start = $m->start_time->copy()->utc()->format('Ymd\THis\Z');
        $end = $m->endTime()->utc()->format('Ymd\THis\Z');

        return 'https://calendar.google.com/calendar/render?action=TEMPLATE'
            .'&text='.urlencode($m->topic)
            .'&dates='.$start.'/'.$end
            .'&details='.urlencode($m->agenda ?: 'Zoom classroom session')
            .'&location='.urlencode($m->join_url ?: '');
    }

    /**
     * Outlook (web) "create event" URL.
     */
    public function outlookUrl(ZoomMeeting $m): string
    {
        $start = $m->start_time->toIso8601String();
        $end = $m->endTime()->toIso8601String();

        return 'https://outlook.live.com/calendar/0/deeplink/compose'
            .'?path=/calendar/action/compose'
            .'&rru=ad/create'
            .'&startdt='.urlencode($start)
            .'&enddt='.urlencode($end)
            .'&subject='.urlencode($m->topic)
            .'&body='.urlencode($m->agenda ?: 'Zoom classroom session')
            .'&location='.urlencode($m->join_url ?: '');
    }
}
