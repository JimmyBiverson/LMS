@extends('layouts.dashboard')
@section('title', 'Instructor Assignments')
@section('page-title', 'Assignment')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <h3 class="font-bold text-heading">Assignments</h3>
        @if($courses->count() > 0)
        <form method="GET" action="/instructor/courses/{{ $courses->first()->id }}/assignments/create" class="flex items-center gap-2" id="assignmentCourseForm">
            <select name="course_id" onchange="document.getElementById('assignmentCourseForm').action='/instructor/courses/'+this.value+'/assignments/create'" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary">
                @foreach($courses as $c)
                <option value="{{ $c->id }}">{{ $c->title }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300 whitespace-nowrap"><i class="ri-add-line mr-1"></i> Add Assignment</button>
        </form>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Assignment</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Deadline</th>
                <th class="text-left py-4 px-6 font-semibold">Submissions</th>
                <th class="text-right py-4 px-6 font-semibold">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assignments as $assignment)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $assignment->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $assignment->course->title ?? "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $assignment->due_date ? $assignment->due_date->format("Y-m-d") : "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $assignment->submissions_count }}</td>
                    <td class="py-4 px-6 text-right"><a href="{{ route("instructor.dashboard.assignments.edit", $assignment) }}" class="px-3 py-1 text-xs font-semibold rounded-full border border-heading/10 hover:bg-primary hover:text-white transition-all">Manage</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-heading/40 text-sm">No assignments created yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
