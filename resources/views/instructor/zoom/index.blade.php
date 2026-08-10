@php
$p = auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isStaff()) ? 'zoom.admin' : 'zoom.instructor';
$startingSoon = $upcoming->filter(fn ($m) => $m->status === 'starting_soon')->values();
@endphp
@extends('layouts.dashboard')

@section('title', 'My Zoom Classes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-heading">My Zoom Classes</h1>
            <p class="text-sm text-gray-500 mt-1">Schedule and manage live classes for your courses.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ $calendarUrl }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">
                <i class="ri-calendar-line"></i> Calendar
            </a>
            <a href="{{ route($p.'.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-bold hover:opacity-90">
                <i class="ri-add-line"></i> Schedule Class
            </a>
        </div>
    </div>

    @if($liveNow->isNotEmpty())
        <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-2xl p-5 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                </span>
                <h2 class="font-bold text-red-700">Live Right Now</h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($liveNow as $meeting)
                    <div class="bg-white rounded-xl border border-red-100 shadow-sm p-5 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <span class="text-xs font-bold text-heading/50 uppercase tracking-wide">{{ $meeting->course->title }}</span>
                            <x-zoom-status-badge :status="$meeting->status" />
                        </div>
                        <h3 class="font-bold text-heading">{{ $meeting->topic }}</h3>
                        <div class="mt-2"><x-zoom-countdown :meeting="$meeting" /></div>
                        <div class="mt-4 flex items-center gap-2">
                            <form method="POST" action="{{ route($p.'.start', $meeting) }}">
                                @csrf
                                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700">
                                    <i class="ri-vidicon-line"></i> Start Class
                                </button>
                            </form>
                            <a href="{{ route($p.'.show', $meeting) }}" class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">Manage</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($startingSoon->isNotEmpty())
        <div class="mb-6">
            <h2 class="font-bold text-heading mb-3 flex items-center gap-2"><i class="ri-time-line text-amber-500"></i> Starting Soon</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($startingSoon as $meeting)
                    <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-5 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <span class="text-xs font-bold text-heading/50 uppercase tracking-wide">{{ $meeting->course->title }}</span>
                            <x-zoom-status-badge :status="$meeting->status" />
                        </div>
                        <h3 class="font-bold text-heading">{{ $meeting->topic }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $meeting->start_time->setTimezone($meeting->timezone)->format('D, M j h:i A') }}</p>
                        <div class="mt-2"><x-zoom-countdown :meeting="$meeting" /></div>
                        <div class="mt-4 flex items-center gap-2">
                            <form method="POST" action="{{ route($p.'.start', $meeting) }}">
                                @csrf
                                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-sm font-bold hover:opacity-90"><i class="ri-vidicon-line"></i> Start</button>
                            </form>
                            <a href="{{ route($p.'.show', $meeting) }}" class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">Manage</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($upcoming->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-bold text-heading flex items-center gap-2"><i class="ri-calendar-event-line text-blue-500"></i> Upcoming Classes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs font-bold uppercase tracking-wide text-heading/50 border-b border-gray-100">
                            <th class="px-5 py-3">Class</th>
                            <th class="px-5 py-3">Course</th>
                            <th class="px-5 py-3">Date &amp; Time</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($upcoming as $meeting)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-5 py-3.5 font-semibold text-heading">{{ $meeting->topic }}</td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $meeting->course->title }}</td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $meeting->start_time->setTimezone($meeting->timezone)->format('D, M j, Y h:i A') }}</td>
                                <td class="px-5 py-3.5"><x-zoom-status-badge :status="$meeting->status" /></td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <form method="POST" action="{{ route($p.'.start', $meeting) }}" class="inline">
                                        @csrf
                                        <button class="text-primary hover:underline text-xs font-semibold mr-3">Start</button>
                                    </form>
                                    <a href="{{ route($p.'.show', $meeting) }}" class="text-heading/50 hover:text-primary text-xs font-semibold">Manage</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($past->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-bold text-heading flex items-center gap-2"><i class="ri-history-line text-gray-400"></i> Past Classes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs font-bold uppercase tracking-wide text-heading/50 border-b border-gray-100">
                            <th class="px-5 py-3">Class</th>
                            <th class="px-5 py-3">Course</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Attendance</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($past as $meeting)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-5 py-3.5 font-semibold text-heading">{{ $meeting->topic }}</td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $meeting->course->title }}</td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $meeting->start_time->setTimezone($meeting->timezone)->format('M j, Y h:i A') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                                        @if($meeting->attendanceRate() >= 80) bg-emerald-100 text-emerald-700
                                        @elseif($meeting->attendanceRate() >= 50) bg-amber-100 text-amber-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        <i class="ri-user-follow-line"></i>{{ $meeting->attendanceRate() }}%
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route($p.'.show', $meeting) }}" class="text-heading/50 hover:text-primary text-xs font-semibold">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($past->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">{{ $past->links() }}</div>
            @endif
        </div>
    @endif
</div>
@endsection
