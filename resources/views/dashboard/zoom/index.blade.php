@extends('layouts.dashboard')

@section('title', 'My Zoom Classes')
@section('page-title', 'Zoom Classes')
@section('user-name', auth()->user()->full_name ?? 'Student')
@section('sidebar')@include('components.student-sidebar')@stop

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-heading">My Zoom Classes</h1>
            <p class="text-sm text-gray-500 mt-1">Your scheduled live classes across all enrolled courses.</p>
        </div>
        <a href="{{ $calendarUrl }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">
            <i class="ri-calendar-line"></i> Class Calendar
        </a>
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
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <span class="text-xs font-bold text-red-600 uppercase tracking-wide">{{ $meeting->course->title }}</span>
                            <x-zoom-status-badge :status="$meeting->status" />
                        </div>
                        <h3 class="font-bold text-heading leading-snug">{{ $meeting->topic }}</h3>
                        @if($meeting->lesson)
                            <p class="text-xs text-gray-500 mt-0.5">Lesson: {{ $meeting->lesson->title }}</p>
                        @endif
                        <div class="mt-3">
                            <x-zoom-countdown :meeting="$meeting" />
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            <form method="POST" action="{{ route('zoom.join', $meeting) }}">
                                @csrf
                                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700">
                                    <i class="ri-vidicon-line"></i> Join Class
                                </button>
                            </form>
                            <a href="{{ route('zoom.show', $meeting) }}" class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($today->isNotEmpty())
        <div class="mb-6">
            <h2 class="font-bold text-heading mb-3 flex items-center gap-2"><i class="ri-sun-line text-amber-500"></i> Today's Classes</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($today as $meeting)
                    @include('dashboard.zoom._meeting_card', ['meeting' => $meeting])
                @endforeach
            </div>
        </div>
    @endif

    @if($future->isNotEmpty())
        <div class="mb-6">
            <h2 class="font-bold text-heading mb-3 flex items-center gap-2"><i class="ri-calendar-event-line text-blue-500"></i> Upcoming</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($future as $meeting)
                    @include('dashboard.zoom._meeting_card', ['meeting' => $meeting])
                @endforeach
            </div>
        </div>
    @endif

    @if($recordings->isNotEmpty())
        <div class="mb-6">
            <h2 class="font-bold text-heading mb-3 flex items-center gap-2"><i class="ri-play-circle-line text-emerald-500"></i> Class Recordings</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recordings as $recording)
                    <a href="{{ $recording->recording_url }}" target="_blank" rel="noopener" class="group bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 inline-flex items-center justify-center"><i class="ri-play-line"></i></span>
                            <span class="text-xs text-gray-400">{{ $recording->start_time->format('M d, Y') }}</span>
                        </div>
                        <h3 class="font-semibold text-heading group-hover:text-primary">{{ $recording->topic }}</h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $recording->course->title }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($pastMeetings->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-heading flex items-center gap-2"><i class="ri-history-line text-gray-400"></i> Past Classes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs font-bold uppercase tracking-wide text-heading/50 border-b border-gray-100">
                            <th class="px-5 py-3">Class</th>
                            <th class="px-5 py-3">Course</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">My Attendance</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pastMeetings as $meeting)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-5 py-3.5 font-semibold text-heading">{{ $meeting->topic }}</td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $meeting->course->title }}</td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $meeting->start_time->format('M d, Y h:i A') }}</td>
                                <td class="px-5 py-3.5"><x-zoom-status-badge :status="$meeting->status" /></td>
                                <td class="px-5 py-3.5">
                                    @php $record = $myAttendance->get($meeting->id); @endphp
                                    @if($record)
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold
                                            @if($record->status === 'present') text-emerald-600
                                            @elseif($record->status === 'late') text-amber-600
                                            @elseif($record->status === 'left_early') text-orange-600
                                            @else text-red-500 @endif">
                                            <i class="ri-checkbox-circle-line"></i>{{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Not marked</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('zoom.show', $meeting) }}" class="text-primary hover:underline text-xs font-semibold">Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($pastMeetings->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">{{ $pastMeetings->links() }}</div>
            @endif
        </div>
    @else
        <div class="text-center py-16">
            <i class="ri-video-on-line text-5xl text-gray-300"></i>
            <h3 class="mt-3 font-bold text-heading">No classes yet</h3>
            <p class="text-sm text-gray-500 mt-1">When your instructors schedule Zoom classes you'll see them here.</p>
        </div>
    @endif
</div>
@endsection
