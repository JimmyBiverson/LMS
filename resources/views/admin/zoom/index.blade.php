@extends('layouts.dashboard')

@section('title', 'Zoom Classroom')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-heading">Zoom Classroom</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor and manage every class across the institution.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('zoom.admin.settings') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">
                <i class="ri-settings-4-line"></i> Settings
            </a>
            <a href="{{ route('zoom.admin.calendar') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">
                <i class="ri-calendar-line"></i> Calendar
            </a>
            <a href="{{ route('zoom.admin.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-bold hover:opacity-90">
                <i class="ri-add-line"></i> Schedule Class
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wide text-heading/50">Total</p>
                <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 inline-flex items-center justify-center"><i class="ri-video-on-line"></i></span>
            </div>
            <p class="text-3xl font-extrabold text-heading mt-2">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wide text-heading/50">Live Now</p>
                <span class="w-8 h-8 rounded-lg bg-red-100 text-red-600 inline-flex items-center justify-center"><i class="ri-vidicon-line"></i></span>
            </div>
            <p class="text-3xl font-extrabold text-red-600 mt-2">{{ $stats['live'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wide text-heading/50">Starting Soon</p>
                <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 inline-flex items-center justify-center"><i class="ri-time-line"></i></span>
            </div>
            <p class="text-3xl font-extrabold text-amber-600 mt-2">{{ $stats['starting_soon'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wide text-heading/50">Active Hosts</p>
                <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 inline-flex items-center justify-center"><i class="ri-user-star-line"></i></span>
            </div>
            <p class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $stats['active_hosts'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-2xl font-extrabold text-heading">{{ $stats['today'] }}</p>
            <p class="text-xs font-semibold text-heading/50 mt-0.5">Today</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-2xl font-extrabold text-heading">{{ $stats['upcoming'] }}</p>
            <p class="text-xs font-semibold text-heading/50 mt-0.5">Upcoming</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-2xl font-extrabold text-heading">{{ $stats['ended'] }}</p>
            <p class="text-xs font-semibold text-heading/50 mt-0.5">Ended</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-2xl font-extrabold text-emerald-600">{{ $attendanceRate['rate'] }}%</p>
            <p class="text-xs font-semibold text-heading/50 mt-0.5">Attendance (30 days)</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-bold text-heading">All Classes</h2>
            <form method="GET" action="{{ route('zoom.admin.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="status" class="rounded-lg border-gray-200 text-xs font-semibold text-heading/70">
                    <option value="">All statuses</option>
                    @foreach(['scheduled' => 'Scheduled', 'starting_soon' => 'Starting Soon', 'live' => 'Live', 'ended' => 'Ended', 'cancelled' => 'Cancelled'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="course_id" class="rounded-lg border-gray-200 text-xs font-semibold text-heading/70">
                    <option value="">All courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>{{ $course->title }}</option>
                    @endforeach
                </select>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search topic..." class="rounded-lg border-gray-200 text-xs">
                <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gray-100 text-heading/70 text-xs font-bold hover:bg-gray-200"><i class="ri-search-line"></i> Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-bold uppercase tracking-wide text-heading/50 border-b border-gray-100">
                        <th class="px-5 py-3">Class</th>
                        <th class="px-5 py-3">Course</th>
                        <th class="px-5 py-3">Host</th>
                        <th class="px-5 py-3">When</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($meetings as $meeting)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-heading">{{ $meeting->topic }}</p>
                                @if($meeting->scope_type === 'institution')
                                    <p class="text-xs text-primary font-semibold">Institution-wide</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $meeting->course?->title ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $meeting->instructor?->full_name ?: $meeting->creator?->full_name ?: '—' }}</td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $meeting->start_time->setTimezone($meeting->timezone)->format('M j, Y h:i A') }}</td>
                            <td class="px-5 py-3.5"><x-zoom-status-badge :status="$meeting->status" /></td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('zoom.admin.show', $meeting) }}" class="text-primary hover:underline text-xs font-semibold mr-3">Manage</a>
                                @if($meeting->status === 'ended')
                                    <a href="{{ route('zoom.admin.attendance', $meeting) }}" class="text-heading/50 hover:text-primary text-xs font-semibold">Attendance</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <i class="ri-video-off-line text-3xl text-gray-300"></i>
                                <p class="mt-2 text-sm text-gray-400">No classes match your filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($meetings->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $meetings->links() }}</div>
        @endif
    </div>
</div>
@endsection
