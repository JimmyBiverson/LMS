@extends('layouts.dashboard')
@section('title', 'Quizzes')
@section('page-title', 'Quizzes for ' . $course->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading">Quizzes</h3>
        <a href="/instructor/courses/{{ $course->id }}/quizzes/create" class="px-4 py-2 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">+ Add Quiz</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Questions</th>
                <th class="text-left py-4 px-6 font-semibold">Time Limit</th>
                <th class="text-left py-4 px-6 font-semibold">Passing Score</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-right py-4 px-6 font-semibold">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($quizzes as $i=>$q)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $q->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $q->questions_count }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $q->time_limit ? $q->time_limit . ' min' : '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $q->passing_score }}%</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $q->status=='published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($q->status) }}</span></td>
                    <td class="py-4 px-6 text-right">
                        <a href="/instructor/quizzes/{{ $q->id }}/edit" class="text-primary hover:underline text-xs font-semibold mr-2">Edit</a>
                        <form method="POST" action="/instructor/quizzes/{{ $q->id }}/delete" class="inline" onsubmit="return confirm('Delete this quiz?')">
                            @csrf
                            <button type="submit" class="text-red-500 hover:underline text-xs font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/50 text-sm">No quizzes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
