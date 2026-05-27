@extends('layouts.dashboard')
@section('title', 'My Assignments')
@section('page-title', 'Assignments')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">My Assignments</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Assignment</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Score</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Submitted</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($submissions as $i=>$s)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $s->assignment?->title ?? 'Assignment' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $s->assignment?->course?->title ?? 'Course' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $s->score !== null ? $s->score . '/' . $s->assignment?->total_marks : '--' }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $s->status=='graded' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($s->status) }}</span></td>
                    <td class="py-4 px-6 text-heading/70">{{ $s->submitted_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No submissions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
