@extends('layouts.dashboard')
@section('title', 'My Quizzes')
@section('page-title', 'My Result')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Quiz Results</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Quiz</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Score</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($results as $i=>$r)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $r->quiz?->title ?? 'Quiz' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $r->quiz?->course?->title ?? 'Course' }}</td>
                    <td class="py-4 px-6 text-heading/70">
                        @if($r->quiz && !$r->quiz->areResultsReleased())
                            <span class="text-amber-600 font-semibold">Awaiting review</span>
                        @else
                            {{ $r->score }}/{{ $r->total_marks }}
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        @if($r->quiz && !$r->quiz->areResultsReleased())
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">Pending</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $r->passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $r->passed ? 'Passed' : 'Failed' }}</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-heading/70">{{ $r->completed_at?->format('Y-m-d') ?? '--' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No quiz results yet. Take a quiz to see your results here.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
