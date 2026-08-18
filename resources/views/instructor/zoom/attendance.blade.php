@php
$p = auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isStaff()) ? 'zoom.admin' : 'zoom.instructor';
@endphp
@extends('layouts.dashboard')

@section('title', 'Attendance - ' . $meeting->topic)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route($p.'.show', $meeting) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-heading/50 hover:text-primary mb-4">
        <i class="ri-arrow-left-s-line"></i> Back to Class
    </a>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-heading">Attendance Report</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $meeting->topic }} · {{ $meeting->start_time->setTimezone($meeting->timezone)->format('D, M j, Y h:i A') }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route($p.'.attendance.export', [$meeting, 'format' => 'csv']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">
                <i class="ri-file-excel-2-line"></i> CSV
            </a>
            <a href="{{ route($p.'.attendance.export', [$meeting, 'format' => 'pdf']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-bold hover:opacity-90">
                <i class="ri-file-pdf-2-line"></i> PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-2xl font-extrabold text-heading">{{ $summary['attended'] }}</p>
            <p class="text-xs font-semibold text-heading/50 mt-0.5">Attended</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-2xl font-extrabold text-emerald-600">{{ $summary['present'] }}</p>
            <p class="text-xs font-semibold text-emerald-700/70 mt-0.5">Present</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-2xl font-extrabold text-amber-600">{{ $summary['late'] }}</p>
            <p class="text-xs font-semibold text-amber-700/70 mt-0.5">Late</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-2xl font-extrabold text-orange-600">{{ $summary['left_early'] }}</p>
            <p class="text-xs font-semibold text-orange-700/70 mt-0.5">Left Early</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-2xl font-extrabold text-red-500">{{ $summary['absent'] }}</p>
            <p class="text-xs font-semibold text-red-700/70 mt-0.5">Absent</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-bold text-heading">Student Roster <span class="text-sm font-semibold text-gray-400">({{ $rows->count() }} students)</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-bold uppercase tracking-wide text-heading/50 border-b border-gray-100">
                        <th class="px-5 py-3">Student</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Joined</th>
                        <th class="px-5 py-3">Left</th>
                        <th class="px-5 py-3">Duration</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($rows as $row)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-5 py-3 font-semibold text-heading">{{ $row->student->full_name ?: $row->student->name }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $row->student->email }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                                    @if($row->status === 'present') bg-emerald-100 text-emerald-700
                                    @elseif($row->status === 'late') bg-amber-100 text-amber-700
                                    @elseif($row->status === 'left_early') bg-orange-100 text-orange-700
                                    @else bg-red-100 text-red-500 @endif">
                                    <i class="ri-user-follow-line"></i>{{ ucfirst(str_replace('_', ' ', $row->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $row->record?->join_time ? $row->record->join_time->setTimezone($meeting->timezone)->format('h:i A') : '—' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $row->record?->leave_time ? $row->record->leave_time->setTimezone($meeting->timezone)->format('h:i A') : '—' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $row->record?->duration_minutes ? $row->record->duration_minutes . ' min' : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($rows->isEmpty())
            <div class="px-5 py-12 text-center">
                <i class="ri-user-star-line text-3xl text-gray-300"></i>
                <p class="mt-2 text-sm text-gray-400">No enrolled students for this meeting yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
