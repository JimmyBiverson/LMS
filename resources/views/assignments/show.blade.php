@extends('layouts.dashboard')
@section('title', $assignment->title)
@section('page-title', $assignment->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-2">{{ $assignment->title }}</h3>
        <p class="text-heading/70 mb-4">{{ $assignment->description }}</p>
        <div class="flex gap-4 text-sm text-heading/60">
            <span>Due: {{ $assignment->due_date?->format('Y-m-d') ?? 'No deadline' }}</span>
            <span>Total Marks: {{ $assignment->total_marks }}</span>
            <span>Submissions: {{ $assignment->submissions->count() }}</span>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Submissions</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                    <th class="text-left py-4 px-6 font-semibold">#</th>
                    <th class="text-left py-4 px-6 font-semibold">Student</th>
                    <th class="text-left py-4 px-6 font-semibold">Submitted At</th>
                    <th class="text-left py-4 px-6 font-semibold">Score</th>
                    <th class="text-left py-4 px-6 font-semibold">Status</th>
                    <th class="text-right py-4 px-6 font-semibold">Action</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($assignment->submissions as $i=>$s)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                        <td class="py-4 px-6 font-semibold text-heading">{{ $s->user?->name ?? 'Unknown' }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $s->submitted_at->format('Y-m-d H:i') }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $s->score !== null ? $s->score . '/' . $assignment->total_marks : '--' }}</td>
                        <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $s->status=='graded' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($s->status) }}</span></td>
                        <td class="py-4 px-6 text-right">
                            @if($s->status !== 'graded')
                            <form method="POST" action="/instructor/submissions/{{ $s->id }}/grade" class="flex items-center gap-2 justify-end">
                                @csrf
                                <input type="number" name="score" placeholder="Score" min="0" max="{{ $assignment->total_marks }}" required class="w-20 px-2 py-1 border border-gray-200 rounded text-xs">
                                <input type="text" name="feedback" placeholder="Feedback" class="w-32 px-2 py-1 border border-gray-200 rounded text-xs">
                                <button type="submit" class="px-3 py-1 bg-primary text-white text-xs font-semibold rounded hover:opacity-90">Grade</button>
                            </form>
                            @else
                            <span class="text-xs text-green-600 font-semibold">Graded</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No submissions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
