@extends('layouts.dashboard')
@section('title', 'Assignments')
@section('page-title', 'Assignments for ' . $course->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading">Assignments</h3>
        <a href="/instructor/courses/{{ $course->id }}/assignments/create" class="px-4 py-2 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">+ Add Assignment</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Submissions</th>
                <th class="text-left py-4 px-6 font-semibold">Due Date</th>
                <th class="text-left py-4 px-6 font-semibold">Total Marks</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-right py-4 px-6 font-semibold">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assignments as $i=>$a)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $a->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $a->submissions_count }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $a->due_date ? $a->due_date->format('Y-m-d') : '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $a->total_marks }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $a->status=='published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($a->status) }}</span></td>
                    <td class="py-4 px-6 text-right">
                        <a href="/instructor/assignments/{{ $a->id }}" class="text-primary hover:underline text-xs font-semibold mr-2">View</a>
                        <a href="/instructor/assignments/{{ $a->id }}/edit" class="text-primary hover:underline text-xs font-semibold mr-2">Edit</a>
                        <form method="POST" action="/instructor/assignments/{{ $a->id }}/delete" class="inline" onsubmit="return confirm('Delete this assignment?')">
                            @csrf
                            <button type="submit" class="text-red-500 hover:underline text-xs font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/50 text-sm">No assignments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
