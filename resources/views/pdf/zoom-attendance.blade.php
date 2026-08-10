<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Attendance Report - {{ $meeting->topic }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1a202c; margin: 0; padding: 32px; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        .muted { color: #718096; font-size: 12px; }
        .meta { margin: 18px 0; }
        .meta p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #718096; border-bottom: 2px solid #e2e8f0; padding: 8px 10px; }
        td { padding: 8px 10px; border-bottom: 1px solid #edf2f7; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: bold; }
        .present { background: #d1fae5; color: #065f46; }
        .late { background: #fef3c7; color: #92400e; }
        .left_early { background: #ffedd5; color: #9a3412; }
        .absent { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 32px; font-size: 10px; color: #a0aec0; }
        .summary { margin-top: 18px; }
        .summary .box { display: inline-block; margin-right: 18px; }
        .summary .num { font-size: 18px; font-weight: bold; }
        .summary .lbl { font-size: 10px; color: #718096; }
    </style>
</head>
<body>
    <h1>Attendance Report</h1>
    <p class="muted">{{ $meeting->topic }} · {{ $meeting->course?->title ?: 'Whole Institution' }}</p>

    <div class="meta">
        <p><strong>Date:</strong> {{ $meeting->start_time->setTimezone($meeting->timezone)->format('D, M j, Y') }}</p>
        <p><strong>Time:</strong> {{ $meeting->start_time->setTimezone($meeting->timezone)->format('g:i A') }} - {{ $meeting->endTime()->setTimezone($meeting->timezone)->format('g:i A') }} ({{ $meeting->timezone }})</p>
        <p><strong>Instructor:</strong> {{ $meeting->instructor?->full_name ?: '—' }}</p>
        <p><strong>Generated:</strong> {{ now()->setTimezone($meeting->timezone)->format('Y-m-d H:i') }}</p>
    </div>

    <div class="summary">
        @php
            $counts = $rows->groupBy('status');
            $attended = ($counts->get('present')?->count() ?? 0) + ($counts->get('late')?->count() ?? 0) + ($counts->get('left_early')?->count() ?? 0);
            $total = $rows->count();
        @endphp
        <div class="box"><span class="num">{{ $attended }}</span><br><span class="lbl">Attended</span></div>
        <div class="box"><span class="num">{{ $counts->get('present')?->count() ?? 0 }}</span><br><span class="lbl">Present</span></div>
        <div class="box"><span class="num">{{ $counts->get('late')?->count() ?? 0 }}</span><br><span class="lbl">Late</span></div>
        <div class="box"><span class="num">{{ $counts->get('left_early')?->count() ?? 0 }}</span><br><span class="lbl">Left Early</span></div>
        <div class="box"><span class="num">{{ $counts->get('absent')?->count() ?? 0 }}</span><br><span class="lbl">Absent</span></div>
        <div class="box"><span class="num">{{ $total ? round(($attended / $total) * 100, 1) : 0 }}%</span><br><span class="lbl">Rate</span></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Email</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Left</th>
                <th>Duration (min)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td><strong>{{ $row['name'] }}</strong></td>
                    <td>{{ $row['email'] }}</td>
                    <td><span class="badge {{ strtolower(str_replace(' ', '_', $row['status'])) }}">{{ $row['status'] }}</span></td>
                    <td>{{ $row['joined'] }}</td>
                    <td>{{ $row['left'] }}</td>
                    <td>{{ $row['duration_min'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="footer">Zoom Classroom · {{ config('app.name') }}</p>
</body>
</html>
