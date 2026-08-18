@extends('layouts.dashboard')
@section('title', 'Term Reports')
@section('page-title', 'Term Reports')
@section('user-name', auth()->user()->name ?? 'Student')
@section('sidebar')@include('components.student-sidebar')@stop

@section('content')
@if (session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm">
    <div class="border-b border-gray-100 p-6">
        <h3 class="font-bold text-heading">My Term Reports</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs uppercase tracking-wider text-heading/60">
                    <th class="px-6 py-4 text-left font-semibold">Term</th>
                    <th class="px-6 py-4 text-left font-semibold">Academic Year</th>
                    <th class="px-6 py-4 text-left font-semibold">Instructor</th>
                    <th class="px-6 py-4 text-left font-semibold">Marks</th>
                    <th class="px-6 py-4 text-left font-semibold">Grade</th>
                    <th class="px-6 py-4 text-left font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reports as $report)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-heading">{{ $report->term }}</td>
                        <td class="px-6 py-4 text-heading/70">{{ $report->academic_year }}</td>
                        <td class="px-6 py-4 text-heading/70">{{ $report->instructor?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-heading/70">
                            {{ $report->marks !== null ? number_format((float) $report->marks, 2) : '--' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($report->grade)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">{{ $report->grade }}</span>
                            @else
                                <span class="text-heading/50">--</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded-full {{ $report->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} px-3 py-1 text-xs font-bold">
                                {{ ucfirst($report->status ?? 'draft') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-heading/50">
                            No term reports are available yet. Reports become visible after fees are cleared and the instructor publishes them.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
