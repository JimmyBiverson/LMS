@extends('layouts.dashboard')
@section('title', 'Quiz Manage')
@section('page-title', 'Quiz')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <h3 class="font-bold text-heading">Quizzes &amp; Exams</h3>
        @if($courses->count() > 0)
        <form method="GET" action="/instructor/courses/{{ $courses->first()->id }}/quizzes/create" class="flex items-center gap-2" id="quizCourseForm">
            <select name="course_id" onchange="document.getElementById('quizCourseForm').action='/instructor/courses/'+this.value+'/quizzes/create'" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary">
                @foreach($courses as $c)
                <option value="{{ $c->id }}">{{ $c->title }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300 whitespace-nowrap"><i class="ri-add-line mr-1"></i> Add Quiz / Exam</button>
        </form>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Quiz / Exam</th>
                <th class="text-left py-4 px-6 font-semibold">Type</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Questions</th>
                <th class="text-left py-4 px-6 font-semibold">Duration</th>
                <th class="text-left py-4 px-6 font-semibold">Availability</th>
                <th class="text-left py-4 px-6 font-semibold">Results</th>
                <th class="text-right py-4 px-6 font-semibold">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($quizzes as $quiz)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $quiz->title }}</td>
                    <td class="py-4 px-6">
                        @if($quiz->is_exam)
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">Exam</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Quiz</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-heading/70">{{ $quiz->course->title ?? "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $quiz->questions_count ?? $quiz->questions()->count() }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $quiz->time_limit }} min</td>
                    <td class="py-4 px-6">
                        @if($quiz->status === 'draft')
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">Draft</span>
                        @elseif($quiz->available_from && $quiz->available_from->isFuture())
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Scheduled</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        @if($quiz->status === 'published' && $quiz->results_released_at)
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Released</span>
                        @elseif($quiz->status === 'published')
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">--</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right flex items-center justify-end gap-2">
                        <a href="{{ route("instructor.dashboard.quizzes.edit", $quiz) }}" class="px-3 py-1 text-xs font-semibold rounded-full border border-heading/10 hover:bg-primary hover:text-white transition-all">Edit</a>
                        @if($quiz->status === 'published' && !$quiz->results_released_at)
                        <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}/release-results" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 hover:bg-green-200 transition-all">Release</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-12 text-center text-heading/40 text-sm">No quizzes created yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
