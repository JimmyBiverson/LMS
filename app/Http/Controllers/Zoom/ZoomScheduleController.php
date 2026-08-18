<?php

namespace App\Http\Controllers\Zoom;

use App\Exceptions\ZoomNotConfiguredException;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\ZoomMeeting;
use App\Services\Zoom\ZoomCalendarService;
use App\Services\Zoom\ZoomMeetingService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ZoomScheduleController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected ZoomMeetingService $service,
        protected ZoomCalendarService $calendar
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $upcoming = ZoomMeeting::with(['course', 'lesson'])
            ->visibleTo($user)
            ->whereIn('status', [ZoomMeeting::STATUS_SCHEDULED, ZoomMeeting::STATUS_STARTING_SOON, ZoomMeeting::STATUS_LIVE])
            ->orderBy('start_time')
            ->get();

        $past = ZoomMeeting::with(['course', 'lesson', 'attendance'])
            ->visibleTo($user)
            ->past()
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        $liveNow = $upcoming->filter(fn (ZoomMeeting $m) => $m->computeStatus() === ZoomMeeting::STATUS_LIVE)->values();

        $calendarUrl = $user->isAdmin() || $user->isStaff()
            ? route('zoom.admin.calendar')
            : route('zoom.instructor.calendar');

        return view('instructor.zoom.index', compact('upcoming', 'past', 'liveNow', 'calendarUrl'));
    }

    /**
     * Month / week / day calendar for instructors and admins.
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
            'timezone' => $this->defaultTimezone(),
        ];

        return view('instructor.zoom.calendar', compact('calendar'));
    }

    public function calendarIcs(Request $request)
    {
        $from = Carbon::parse($request->get('start', now()->subDays(7)))->startOfDay();
        $to = Carbon::parse($request->get('end', now()->addDays(60)))->endOfDay();

        $ics = $this->calendar->icsForRange($request->user(), $from, $to);

        return response($ics)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="zoom-classes.ics"');
    }

    public function create(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin() || $user->isStaff()) {
            $courses = Course::where('status', '!=', 'draft')->orderBy('title')->get(['id', 'title']);
        } else {
            $courses = Course::where(fn ($q) => $q->where('user_id', $user->id)->orWhere('instructor_id', $user->id))
                ->where('status', '!=', 'draft')
                ->orderBy('title')
                ->get(['id', 'title']);
        }

        $lessonsByCourse = Lesson::whereIn('course_id', $courses->pluck('id'))
            ->get(['id', 'course_id', 'title'])
            ->groupBy('course_id');

        $timezone = $this->defaultTimezone();
        $isAdmin = $user->isAdmin() || $user->isStaff();
        $submitUrl = $isAdmin ? route('zoom.admin.store') : route('zoom.instructor.store');
        $method = 'POST';
        $cancelUrl = $isAdmin ? route('zoom.admin.index') : route('zoom.instructor.index');

        return view('instructor.zoom.create', compact('courses', 'lessonsByCourse', 'timezone', 'isAdmin', 'submitUrl', 'method', 'cancelUrl'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', ZoomMeeting::class);

        $data = $this->validateSchedule($request);

        $this->authorizeCourse($request->user(), $data);

        try {
            $meeting = $this->service->schedule($data, $request->user());
        } catch (ZoomNotConfiguredException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            logger()->error('Zoom meeting creation failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'The class could not be created. Please try again ('.$e->getMessage().').');
        }

        return redirect()->route($this->showRouteName($request->user()), $meeting)
            ->with('success', 'Zoom class scheduled successfully. Students have been notified.');
    }

    public function show(ZoomMeeting $meeting): View
    {
        $this->authorize('manage', $meeting);

        $meeting->load(['course', 'lesson', 'instructor', 'attendance.student']);

        return view('instructor.zoom.show', compact('meeting'));
    }

    public function edit(ZoomMeeting $meeting): View
    {
        $this->authorize('update', $meeting);

        $user = auth()->user();

        if ($user->isAdmin() || $user->isStaff()) {
            $courses = Course::where('status', '!=', 'draft')->orderBy('title')->get(['id', 'title']);
        } else {
            $courses = Course::where(fn ($q) => $q->where('user_id', $user->id)->orWhere('instructor_id', $user->id))
                ->where('status', '!=', 'draft')
                ->orderBy('title')
                ->get(['id', 'title']);
        }

        $lessonsByCourse = Lesson::whereIn('course_id', $courses->pluck('id'))
            ->get(['id', 'course_id', 'title'])
            ->groupBy('course_id');

        $timezone = $meeting->timezone;
        $isAdmin = $user->isAdmin() || $user->isStaff();
        $submitUrl = $isAdmin ? route('zoom.admin.update', $meeting) : route('zoom.instructor.update', $meeting);
        $method = 'PUT';
        $cancelUrl = $isAdmin ? route('zoom.admin.show', $meeting) : route('zoom.instructor.show', $meeting);

        return view('instructor.zoom.edit', compact('meeting', 'courses', 'lessonsByCourse', 'timezone', 'isAdmin', 'submitUrl', 'method', 'cancelUrl'));
    }

    public function update(Request $request, ZoomMeeting $meeting): RedirectResponse
    {
        $this->authorize('update', $meeting);

        $data = $this->validateSchedule($request);

        $this->authorizeCourse($request->user(), $data);

        try {
            $this->service->reschedule($meeting, $data);
        } catch (ZoomNotConfiguredException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            logger()->error('Zoom meeting update failed', ['meeting' => $meeting->id, 'error' => $e->getMessage()]);

            return back()->with('error', 'The class could not be updated ('.$e->getMessage().').');
        }

        return redirect()->route($this->showRouteName($request->user()), $meeting)
            ->with('success', 'Class rescheduled. Students have been notified.');
    }

    public function cancel(Request $request, ZoomMeeting $meeting): RedirectResponse
    {
        $this->authorize('cancel', $meeting);

        $this->service->cancel($meeting);

        return redirect()->route($this->indexRouteName($request->user()))
            ->with('success', 'Class cancelled. Students have been notified.');
    }

    /**
     * Start the class: mark live and launch the host into Zoom externally.
     */
    public function start(Request $request, ZoomMeeting $meeting): RedirectResponse
    {
        $this->authorize('start', $meeting);

        $this->service->start($meeting);

        if (! $meeting->start_url) {
            return back()->with('error', 'No host start URL is available for this meeting.');
        }

        return redirect()->away($meeting->start_url);
    }

    public function toggleRecording(Request $request, ZoomMeeting $meeting): RedirectResponse
    {
        $this->authorize('publishRecording', $meeting);

        $meeting->update(['recording_published' => ! $meeting->recording_published]);

        return back()->with('success', $meeting->recording_published
            ? 'Recording is now visible to enrolled students.'
            : 'Recording hidden from students.');
    }

    public function notify(Request $request, ZoomMeeting $meeting): RedirectResponse
    {
        $this->authorize('announce', $meeting);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $count = $this->service->notifyStudents($meeting, 'reminder', $validated['message']);

        return back()->with('success', "Announcement sent to {$count} enrolled students.");
    }

    protected function validateSchedule(Request $request): array
    {
        return $request->validate([
            'scope_type' => ['required', 'in:course,lesson,institution'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'topic' => ['required', 'string', 'max:255'],
            'agenda' => ['nullable', 'string', 'max:2000'],
            'start_time' => ['required', 'date'],
            'timezone' => ['required', 'string', 'max:64'],
            'duration_minutes' => ['required', 'integer', 'between:15,480'],
            'password' => ['nullable', 'string', 'max:32'],
            'is_recurring' => ['nullable', 'boolean'],
            'auto_recording' => ['nullable', 'boolean'],
            'waiting_room' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Scope enforcement: an institution meeting needs no course, everything else
     * must belong to a course, and instructors may only use their own courses.
     */
    protected function authorizeCourse(\App\Models\User $user, array $data): void
    {
        if (($data['scope_type'] ?? ZoomMeeting::SCOPE_COURSE) === ZoomMeeting::SCOPE_INSTITUTION) {
            abort_unless($user->isAdmin() || $user->isStaff(), 403, 'Only administrators can schedule institution-wide classes.');

            return;
        }

        abort_unless(isset($data['course_id']) && $data['course_id'], 422, 'A course is required for this class.');

        if ($user->isAdmin() || $user->isStaff()) {
            return;
        }

        $course = Course::find($data['course_id']);
        abort_unless($course, 403);

        $owns = $course->user_id === $user->id || $course->instructor_id === $user->id;
        abort_unless($owns, 403, 'You can only schedule classes for courses you teach.');

        if (isset($data['instructor_id']) && $data['instructor_id'] !== $user->id) {
            abort_unless($course->user_id === $user->id || $user->isAdmin() || $user->isStaff(), 403);
        }
    }

    protected function defaultTimezone(): string
    {
        return \App\Models\SchoolSetting::getValue('timezone') ?: config('zoom.default_timezone', 'UTC');
    }

    protected function showRouteName(\App\Models\User $user): string
    {
        return $user->isAdmin() || $user->isStaff() ? 'zoom.admin.show' : 'zoom.instructor.show';
    }

    protected function indexRouteName(\App\Models\User $user): string
    {
        return $user->isAdmin() || $user->isStaff() ? 'zoom.admin.index' : 'zoom.instructor.index';
    }
}
