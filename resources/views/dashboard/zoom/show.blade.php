@php
$tz = $meeting->timezone;
$start = $meeting->start_time->setTimezone($tz);
$end = $meeting->endTime()->setTimezone($tz);
$uid = 'zoom-' . $meeting->id . '@' . parse_url(config('app.url'), PHP_URL_HOST);
$google = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' . urlencode($meeting->topic) .
    '&dates=' . $start->format('Ymd\THis') . '/' . $end->format('Ymd\THis') .
    '&details=' . urlencode($meeting->agenda ?? '') .
    '&location=' . urlencode('Zoom: ' . ($meeting->join_url ?? '')) .
    '&ctz=' . $tz;
$outlook = 'https://outlook.live.com/calendar/0/action/compose?subject=' . urlencode($meeting->topic) .
    '&startdt=' . $start->toIso8601String() . '&enddt=' . $end->toIso8601String() .
    '&body=' . urlencode($meeting->agenda ?? '') .
    '&location=' . urlencode('Zoom: ' . ($meeting->join_url ?? '')) .
    '&path=/calendar/action/compose&rru=addevent';
$myStatus = $myAttendance ? $myAttendance->status : null;
$attendance = $myAttendance;
@endphp
@extends('layouts.dashboard')

@section('title', $meeting->topic)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('zoom.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-heading/50 hover:text-primary mb-4">
        <i class="ri-arrow-left-s-line"></i> Back to My Classes
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-primary to-primary/80 px-6 py-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/20 text-white text-xs font-bold">{{ $meeting->course->title }}</span>
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

        <div class="p-6 grid md:grid-cols-3 gap-4">
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
                    <p class="text-xs font-bold uppercase tracking-wide text-heading/50">Duration</p>
                    <p class="text-sm font-semibold text-heading mt-0.5">{{ $meeting->duration_minutes }} minutes</p>
                    <p class="text-sm text-gray-500">{{ $meeting->course->instructor ? $meeting->course->instructor->full_name : 'Your instructor' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 inline-flex items-center justify-center shrink-0"><i class="ri-key-2-line"></i></span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-heading/50">Passcode</p>
                    <p class="text-sm font-semibold text-heading mt-0.5">{{ $meeting->password ?: 'None' }}</p>
                    <p class="text-sm text-gray-500">{{ $meeting->is_recurring ? 'Recurring series' : 'One-time class' }}</p>
                </div>
            </div>
        </div>

        @if($meeting->agenda)
            <div class="px-6 pb-6">
                <p class="text-xs font-bold uppercase tracking-wide text-heading/50 mb-1.5">About this class</p>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $meeting->agenda }}</p>
            </div>
        @endif
    </div>

    <div class="grid md:grid-cols-3 gap-4 mb-6">
        @if($meeting->isJoinableNow())
            <form method="POST" action="{{ route('zoom.join', $meeting) }}" class="md:col-span-2">
                @csrf
                <button class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700">
                    <i class="ri-vidicon-line text-lg"></i> Join Class Now
                    <span class="text-white/70 text-xs font-semibold">(opens in the Zoom app)</span>
                </button>
            </form>
        @else
            <div class="md:col-span-2 rounded-xl border-2 border-dashed border-gray-200 p-5 text-center">
                <p class="text-sm font-semibold text-heading/60">The join button unlocks when the class is live.</p>
                <p class="text-xs text-gray-400 mt-1">You can join a few minutes before the scheduled start time.</p>
            </div>
        @endif

        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('zoom.ics', $meeting) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-heading/70 hover:bg-gray-50"><i class="ri-file-download-line"></i> iCal</a>
                <a href="{{ $google }}" target="_blank" rel="noopener" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-heading/70 hover:bg-gray-50"><i class="ri-google-line"></i> Google</a>
                <a href="{{ $outlook }}" target="_blank" rel="noopener" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-heading/70 hover:bg-gray-50"><i class="ri-microsoft-line"></i> Outlook</a>
            </div>
        </div>
    </div>

    @if($meeting->hasRecording() && $meeting->recording_published)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="font-bold text-heading mb-4 flex items-center gap-2"><i class="ri-play-circle-line text-emerald-500"></i> Recordings</h2>
            <div class="space-y-3">
                @php $files = collect($meeting->recording_files ?? []); @endphp
                @forelse($files as $file)
                    <div class="flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:bg-gray-50/70">
                        <span class="w-11 h-11 rounded-lg bg-emerald-50 text-emerald-600 inline-flex items-center justify-center shrink-0"><i class="ri-video-line"></i></span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-heading truncate">{{ $file['recording_type'] ?? 'Recording' }}</p>
                            <p class="text-xs text-gray-500">{{ isset($file['recording_start']) ? \Illuminate\Support\Carbon::parse($file['recording_start'])->format('M d, Y h:i A') : $meeting->start_time->format('M d, Y h:i A') }}</p>
                        </div>
                        <a href="{{ $file['play_url'] ?? $file['download_url'] ?? $meeting->recording_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold hover:opacity-90"><i class="ri-play-line"></i> Watch</a>
                    </div>
                @empty
                    <div class="flex items-center gap-4 p-3 rounded-xl border border-gray-100">
                        <span class="w-11 h-11 rounded-lg bg-emerald-50 text-emerald-600 inline-flex items-center justify-center shrink-0"><i class="ri-video-line"></i></span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-heading truncate">Class recording</p>
                            <p class="text-xs text-gray-500">{{ $meeting->start_time->format('M d, Y h:i A') }}</p>
                        </div>
                        <a href="{{ $meeting->recording_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold hover:opacity-90"><i class="ri-play-line"></i> Watch</a>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    @if($myStatus)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="font-bold text-heading mb-3 flex items-center gap-2"><i class="ri-user-check-line text-primary"></i> My Attendance</h2>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-bold
                @if($myStatus === 'present') bg-emerald-100 text-emerald-700
                @elseif($myStatus === 'late') bg-amber-100 text-amber-700
                @elseif($myStatus === 'left_early') bg-orange-100 text-orange-700
                @else bg-red-100 text-red-700 @endif">
                <i class="ri-checkbox-circle-line"></i>{{ ucfirst(str_replace('_', ' ', $myStatus)) }}
            </span>
            @if($attendance->joined_at)
                <p class="text-xs text-gray-500 mt-3">
                    Joined {{ $attendance->joined_at->format('h:i A') }}
                    @if($attendance->left_at) · Left {{ $attendance->left_at->format('h:i A') }} @endif
                    · Present for {{ gmdate('i', $attendance->duration_seconds ?: 0) }} min
                </p>
            @endif
        </div>
    @endif
</div>
@endsection
