@php
$p = auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isStaff()) ? 'zoom.admin' : 'zoom.instructor';
$tz = $meeting->timezone;
$start = $meeting->start_time->setTimezone($tz);
$end = $meeting->endTime()->setTimezone($tz);
$summary = [
    'present' => $meeting->attendance->where('status', 'present')->count(),
    'late' => $meeting->attendance->where('status', 'late')->count(),
    'left_early' => $meeting->attendance->where('status', 'left_early')->count(),
    'absent' => $meeting->attendance->where('status', 'absent')->count(),
];
$attended = $summary['present'] + $summary['late'] + $summary['left_early'];
$files = collect($meeting->recording_files ?? []);
@endphp
@extends('layouts.dashboard')

@section('title', $meeting->topic)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route($p.'.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-heading/50 hover:text-primary mb-4">
        <i class="ri-arrow-left-s-line"></i> Back to Classes
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-primary to-primary/80 px-6 py-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/20 text-white text-xs font-bold">{{ $meeting->course ? $meeting->course->title : 'Whole Institution' }}</span>
                        <x-zoom-status-badge :status="$meeting->status" />
                    </div>
                    <h1 class="text-2xl font-bold text-white">{{ $meeting->topic }}</h1>
                    @if($meeting->lesson)
                        <p class="text-white/80 text-sm mt-1">Lesson: {{ $meeting->lesson->title }}</p>
                    @endif
                </div>
                <div class="bg-white/95 rounded-xl px-5 py-4">
                    <x-zoom-countdown :meeting="$meeting" />
                </div>
            </div>
        </div>

        <div class="p-6 grid md:grid-cols-3 gap-4 border-b border-gray-100">
            <div class="flex items-start gap-3">
                <span class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 inline-flex items-center justify-center shrink-0"><i class="ri-calendar-line"></i></span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-heading/50">When</p>
                    <p class="text-sm font-semibold text-heading mt-0.5">{{ $start->format('D, M j, Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $start->format('h:i A') }} - {{ $end->format('h:i A') }} ({{ $tz }})</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 inline-flex items-center justify-center shrink-0"><i class="ri-timer-line"></i></span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-heading/50">Details</p>
                    <p class="text-sm font-semibold text-heading mt-0.5">{{ $meeting->duration_minutes }} minutes</p>
                    <p class="text-sm text-gray-500">{{ $meeting->instructor ? $meeting->instructor->full_name : 'You' }} · {{ $meeting->is_recurring ? 'Recurring' : 'One-time' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 inline-flex items-center justify-center shrink-0"><i class="ri-group-line"></i></span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-heading/50">Students</p>
                    <p class="text-sm font-semibold text-heading mt-0.5">{{ $meeting->scope_type === 'institution' ? 'All active students' : ($meeting->course ? $meeting->course->enrollments()->count() : 0) . ' enrolled' }}</p>
                    <p class="text-sm text-gray-500">Passcode: {{ $meeting->password ?: 'None' }}</p>
                </div>
            </div>
        </div>

        @if($meeting->agenda)
            <div class="px-6 py-5 border-b border-gray-100">
                <p class="text-xs font-bold uppercase tracking-wide text-heading/50 mb-1.5">About this class</p>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $meeting->agenda }}</p>
            </div>
        @endif

        <div class="p-6">
            <p class="text-xs font-bold uppercase tracking-wide text-heading/50 mb-3">Actions</p>
            <div class="flex flex-wrap gap-2">
                @if(in_array($meeting->status, ['scheduled', 'starting_soon', 'live']))
                    <form method="POST" action="{{ route($p.'.start', $meeting) }}">
                        @csrf
                        <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700">
                            <i class="ri-vidicon-line"></i> Start Class in Zoom
                        </button>
                    </form>
                    <a href="{{ route($p.'.edit', $meeting) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">
                        <i class="ri-pencil-line"></i> Edit
                    </a>
                    <form method="POST" action="{{ route($p.'.cancel', $meeting) }}" onsubmit="return confirm('Cancel this class? Students will be notified.')">
                        @csrf
                        <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-rose-200 text-rose-600 text-sm font-semibold hover:bg-rose-50">
                            <i class="ri-close-circle-line"></i> Cancel Class
                        </button>
                    </form>
                @endif
                @if($meeting->status === 'ended')
                    <a href="{{ route($p.'.attendance', $meeting) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700">
                        <i class="ri-user-follow-line"></i> Attendance Report
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($meeting->hasRecording())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <h2 class="font-bold text-heading flex items-center gap-2"><i class="ri-play-circle-line text-emerald-500"></i> Recording</h2>
                <form method="POST" action="{{ route($p.'.recording', $meeting) }}">
                    @csrf
                    <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border {{ $meeting->recording_published ? 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' : 'border-gray-200 text-heading/60 hover:bg-gray-50' }} text-xs font-bold">
                        <i class="ri-eye-line"></i>{{ $meeting->recording_published ? 'Visible to students' : 'Hidden from students' }}
                    </button>
                </form>
            </div>
            <div class="space-y-3">
                @forelse($files as $file)
                    <div class="flex items-center gap-4 p-3 rounded-xl border border-gray-100">
                        <span class="w-11 h-11 rounded-lg bg-emerald-50 text-emerald-600 inline-flex items-center justify-center shrink-0"><i class="ri-video-line"></i></span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-heading truncate">{{ $file['recording_type'] ?? 'Recording' }}</p>
                            <p class="text-xs text-gray-500">{{ isset($file['file_size']) ? round($file['file_size'] / 1048576, 1) . ' MB' : '' }}</p>
                        </div>
                        <a href="{{ $file['play_url'] ?? $file['download_url'] ?? $meeting->recording_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold hover:opacity-90"><i class="ri-play-line"></i> View</a>
                    </div>
                @empty
                    <a href="{{ $meeting->recording_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline"><i class="ri-play-line"></i> Open recording in Zoom</a>
                @endforelse
            </div>
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6 mb-6">
        @if($meeting->status === 'ended')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-heading flex items-center gap-2"><i class="ri-user-follow-line text-primary"></i> Attendance</h2>
                    <a href="{{ route($p.'.attendance', $meeting) }}" class="text-xs font-bold text-primary hover:underline">Full report</a>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-lg bg-emerald-50 p-3 text-center">
                        <p class="text-2xl font-extrabold text-emerald-600">{{ $summary['present'] }}</p>
                        <p class="text-xs font-semibold text-emerald-700/70">Present</p>
                    </div>
                    <div class="rounded-lg bg-amber-50 p-3 text-center">
                        <p class="text-2xl font-extrabold text-amber-600">{{ $summary['late'] }}</p>
                        <p class="text-xs font-semibold text-amber-700/70">Late</p>
                    </div>
                    <div class="rounded-lg bg-orange-50 p-3 text-center">
                        <p class="text-2xl font-extrabold text-orange-600">{{ $summary['left_early'] }}</p>
                        <p class="text-xs font-semibold text-orange-700/70">Left Early</p>
                    </div>
                    <div class="rounded-lg bg-red-50 p-3 text-center">
                        <p class="text-2xl font-extrabold text-red-500">{{ $summary['absent'] }}</p>
                        <p class="text-xs font-semibold text-red-700/70">Absent</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-heading/50">Attendance rate</span>
                    <span class="text-lg font-extrabold text-heading">{{ $meeting->attendanceRate() }}%</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 {{ $meeting->status === 'ended' ? '' : 'md:col-span-2' }}">
            <h2 class="font-bold text-heading flex items-center gap-2 mb-4"><i class="ri-notification-3-line text-amber-500"></i> Send Announcement</h2>
            @if($meeting->status === 'cancelled')
                <p class="text-sm text-gray-400">This class is cancelled, announcements are disabled.</p>
            @else
                <form method="POST" action="{{ route($p.'.notify', $meeting) }}" class="space-y-3">
                    @csrf
                    <textarea name="message" rows="3" required maxlength="1000" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm" placeholder="e.g. Don't forget to prepare chapter 4 before class..."></textarea>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-400">An in-app notification plus email goes to every enrolled student.</p>
                        <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold hover:opacity-90">
                            <i class="ri-send-plane-line"></i> Send
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
