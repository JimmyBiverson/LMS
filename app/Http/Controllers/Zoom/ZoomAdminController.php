<?php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ZoomAttendance;
use App\Models\ZoomMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ZoomAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = ZoomMeeting::with(['course', 'lesson', 'instructor', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->query('course_id'));
        }

        if ($request->filled('search')) {
            $query->where('topic', 'like', '%'.$request->query('search').'%');
        }

        $meetings = $query->orderByDesc('start_time')->paginate(20)->withQueryString();

        $stats = [
            'total' => ZoomMeeting::count(),
            'live' => ZoomMeeting::where('status', ZoomMeeting::STATUS_LIVE)->count(),
            'starting_soon' => ZoomMeeting::where('status', ZoomMeeting::STATUS_STARTING_SOON)->count(),
            'today' => ZoomMeeting::whereDate('start_time', now()->toDateString())->count(),
            'upcoming' => ZoomMeeting::whereIn('status', [ZoomMeeting::STATUS_SCHEDULED, ZoomMeeting::STATUS_STARTING_SOON, ZoomMeeting::STATUS_LIVE])->count(),
            'ended' => ZoomMeeting::where('status', ZoomMeeting::STATUS_ENDED)->count(),
        ];

        $activeHosts = ZoomMeeting::whereIn('status', [ZoomMeeting::STATUS_LIVE, ZoomMeeting::STATUS_STARTING_SOON])
            ->whereNotNull('instructor_id')
            ->distinct('instructor_id')
            ->count('instructor_id');

        $stats['active_hosts'] = $activeHosts;

        $attendanceRate = $this->attendanceRate();

        $courses = Course::orderBy('title')->get(['id', 'title']);

        return view('admin.zoom.index', compact('meetings', 'stats', 'attendanceRate', 'courses'));
    }

    protected function attendanceRate(): array
    {
        $window = now()->subDays(30);

        $totalAttended = ZoomAttendance::whereHas('meeting', fn ($q) => $q->where('start_time', '>=', $window))
            ->whereIn('status', [ZoomAttendance::STATUS_PRESENT, ZoomAttendance::STATUS_LATE, ZoomAttendance::STATUS_LEFT_EARLY])
            ->count();

        $totalAbsent = ZoomAttendance::whereHas('meeting', fn ($q) => $q->where('start_time', '>=', $window))
            ->where('status', ZoomAttendance::STATUS_ABSENT)
            ->count();

        $denominator = max(1, $totalAttended + $totalAbsent);

        return [
            'attended' => $totalAttended,
            'absent' => $totalAbsent,
            'rate' => round(($totalAttended / $denominator) * 100, 1),
        ];
    }

    public function settings(): View
    {
        return app(ZoomSettingsController::class)->edit();
    }
}
