<?php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\Controller;
use App\Models\ZoomAttendance;
use App\Models\ZoomMeeting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ZoomAttendanceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Attendance rows for every enrolled student plus summary stats.
     */
    public function show(ZoomMeeting $meeting): View
    {
        $this->authorize('manageAttendance', $meeting);

        $meeting->load(['course', 'instructor']);

        $records = ZoomAttendance::with('student')
            ->where('meeting_id', $meeting->id)
            ->get()
            ->keyBy('student_id');

        $enrolled = $this->enrolledStudents($meeting);

        $rows = $enrolled->map(function ($student) use ($records) {
            $record = $records->get($student->id);

            return (object) [
                'student' => $student,
                'record' => $record,
                'status' => $record?->status ?? ZoomAttendance::STATUS_ABSENT,
            ];
        })->values();

        $summary = $this->summary($rows);

        return view('instructor.zoom.attendance', compact('meeting', 'rows', 'summary'));
    }

    public function export(Request $request, ZoomMeeting $meeting)
    {
        $this->authorize('manageAttendance', $meeting);

        $format = $request->query('format', 'csv');

        abort_unless(in_array($format, ['csv', 'pdf'], true), 422);

        $records = ZoomAttendance::with('student')
            ->where('meeting_id', $meeting->id)
            ->get()
            ->keyBy('student_id');

        $rows = $this->enrolledStudents($meeting)->map(function ($student) use ($records) {
            $record = $records->get($student->id);

            return [
                'name' => $student->full_name ?: $student->name,
                'email' => $student->email,
                'status' => ucwords(str_replace('_', ' ', $record?->status ?? ZoomAttendance::STATUS_ABSENT)),
                'joined' => $record?->join_time?->setTimezone($meeting->timezone)->format('Y-m-d H:i:s') ?? '—',
                'left' => $record?->leave_time?->setTimezone($meeting->timezone)->format('Y-m-d H:i:s') ?? '—',
                'duration_min' => $record?->duration_minutes ?? 0,
            ];
        })->values();

        $filename = 'attendance-zoom-'.$meeting->id.'-'.now()->format('YmdHis');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('pdf.zoom-attendance', [
                'meeting' => $meeting,
                'rows' => $rows,
            ]);

            return $pdf->download($filename.'.pdf');
        }

        $csv = $this->toCsv($rows);

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'.csv"',
        ]);
    }

    protected function enrolledStudents(ZoomMeeting $meeting): Collection
    {
        $ids = $meeting->enrolledStudentIds();

        if (empty($ids)) {
            return collect();
        }

        return \App\Models\User::whereIn('id', $ids)
            ->orderBy('name')
            ->get(['id', 'name', 'first_name', 'last_name', 'email']);
    }

    protected function summary(Collection $rows): array
    {
        $counts = [
            'present' => 0,
            'late' => 0,
            'left_early' => 0,
            'absent' => 0,
        ];

        foreach ($rows as $row) {
            $counts[$row->status] = ($counts[$row->status] ?? 0) + 1;
        }

        $attended = $counts['present'] + $counts['late'] + $counts['left_early'];
        $total = max(1, $rows->count());

        $counts['attended'] = $attended;
        $counts['rate'] = round(($attended / $total) * 100, 1);

        return $counts;
    }

    protected function toCsv(Collection $rows): string
    {
        $out = fopen('php://temp', 'r+');

        fputcsv($out, ['Student', 'Email', 'Status', 'Joined', 'Left', 'Duration (min)']);

        foreach ($rows as $row) {
            fputcsv($out, [
                $row['name'],
                $row['email'],
                $row['status'],
                $row['joined'],
                $row['left'],
                $row['duration_min'],
            ]);
        }

        rewind($out);

        return stream_get_contents($out);
    }
}
