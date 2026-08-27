<?php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\Controller;
use App\Models\ZoomAttendance;
use App\Models\ZoomMeeting;
use App\Services\Zoom\ZoomCalendarService;
use App\Services\Zoom\ZoomMeetingService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ZoomMeetingController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected ZoomMeetingService $service,
        protected ZoomCalendarService $calendar
    ) {
    }

    /**
     * Student classroom hub: live, today, upcoming, recordings and history.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $upcoming = ZoomMeeting::with(['course', 'lesson', 'instructor'])
            ->visibleTo($user)
            ->whereIn('status', [ZoomMeeting::STATUS_SCHEDULED, ZoomMeeting::STATUS_STARTING_SOON, ZoomMeeting::STATUS_LIVE])
            ->orderBy('start_time')
            ->get();

        $liveNow = $upcoming->filter(fn (ZoomMeeting $m) => $m->computeStatus() === ZoomMeeting::STATUS_LIVE && $m->isJoinableNow())->values();
        $today = $upcoming->filter(fn (ZoomMeeting $m) => $m->start_time->isToday())->values();
        $future = $upcoming
            ->filter(fn (ZoomMeeting $m) => $m->endTime()->gt(now()))
            ->sortBy('start_time')
            ->take(15)
            ->values();

        $past = ZoomMeeting::with(['course', 'lesson'])
            ->visibleTo($user)
            ->past()
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        $pastMeetings = $past;

        $recordings = ZoomMeeting::with(['course'])
            ->visibleTo($user)
            ->past()
            ->where('recording_status', ZoomMeeting::RECORDING_AVAILABLE)
            ->where('recording_published', true)
            ->orderByDesc('start_time')
            ->take(8)
            ->get();

        $myAttendance = ZoomAttendance::where('student_id', $user->id)
            ->whereIn('meeting_id', $past->pluck('id')->merge($upcoming->pluck('id'))->unique())
            ->get()
            ->keyBy('meeting_id');

        $calendarUrl = route('zoom.calendar');

        return view('dashboard.zoom.index', compact('liveNow', 'today', 'future', 'pastMeetings', 'recordings', 'myAttendance', 'calendarUrl'));
    }

    public function show(ZoomMeeting $meeting): View
    {
        $this->authorize('view', $meeting);

        $meeting->load(['course', 'lesson', 'instructor', 'attendance.student']);

        $myAttendance = ZoomAttendance::where('meeting_id', $meeting->id)
            ->where('student_id', auth()->id())
            ->first();

        return view('dashboard.zoom.show', compact('meeting', 'myAttendance'));
    }

    /**
     * Authorized external launch. The meeting opens in the Zoom desktop/mobile
     * app (or browser client) — never embedded in the LMS.
     */
    public function join(ZoomMeeting $meeting): RedirectResponse
    {
        $this->authorize('join', $meeting);

        if (! $meeting->join_url || ! $this->isSafeZoomUrl($meeting->join_url)) {
            abort(422, 'This meeting has no join link yet.');
        }

        if (config('zoom.track_join_attendance')) {
            $this->service->recordJoin($meeting, auth()->user());
        }

        Cache::forget('zoom.user.'.auth()->id().'.upcoming');

        return redirect()->away($meeting->join_url);
    }

    /**
     * Calendar event feed (month/week/day views).
     */
    public function calendar(Request $request): View
    {
        $user = $request->user();
        $view = in_array($request->query('view', 'month'), ['month', 'week', 'day'], true) ? $request->query('view') : 'month';
        $anchor = $request->filled('date') ? Carbon::parse($request->query('date')) : Carbon::today();

        if ($view === 'week') {
            $from = $anchor->copy()->startOfWeek();
            $to = $anchor->copy()->endOfWeek();
        } elseif ($view === 'day') {
            $from = $anchor->copy()->startOfDay();
            $to = $anchor->copy()->endOfDay();
        } else {
            $from = $anchor->copy()->startOfMonth();
            $to = $anchor->copy()->endOfMonth();
        }

        $events = collect($this->calendar->eventPayload($user, $from, $to))->keyBy('id');

        $days = collect();
        for ($day = $from->copy(); $day->lte($to); $day->addDay()) {
            $days->push([
                'date' => $day->copy(),
                'in_month' => $day->month === $anchor->month,
                'events' => $events->filter(fn (array $e) => Carbon::parse($e['start'])->isSameDay($day))->values(),
            ]);
        }

        if ($view === 'week') {
            $days = $days->take(7);
        } elseif ($view === 'day') {
            $days = $days->take(1);
        }

        $calendar = [
            'view' => $view,
            'current' => $anchor,
            'prev' => $from->copy()->subDay()->toDateString(),
            'next' => $to->copy()->addDay()->toDateString(),
            'days' => $days,
            'timezone' => config('app.timezone', 'UTC'),
        ];

        return view('dashboard.zoom.calendar', compact('calendar'));
    }

    public function calendarIcs(Request $request)
    {
        $from = Carbon::parse($request->get('start', now()->subDays(7)))->startOfDay();
        $to = Carbon::parse($request->get('end', now()->addDays(60)))->endOfDay();

        $ics = $this->calendar->icsForRange(auth()->user(), $from, $to);

        return response($ics)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="zoom-classes.ics"');
    }

    public function ics(ZoomMeeting $meeting)
    {
        $this->authorize('view', $meeting);

        return response($this->calendar->icsForMeeting($meeting))
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="zoom-'.$meeting->id.'.ics"');
    }

    /**
     * Only allow launching into Zoom domains to prevent open-redirect abuse.
     */
    protected function isSafeZoomUrl(string $url): bool
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?: '');

        return $host === 'zoom.us'
            || str_ends_with($host, '.zoom.us')
            || $host === 'zoom.com'
            || str_ends_with($host, '.zoom.com');
    }
}
