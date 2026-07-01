@extends('layouts.dashboard')
@section('title', 'Exam Management')
@section('page-title', 'Exams')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Add New Exam</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/school/exams" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @csrf
            <input type="text" name="title" placeholder="Exam Title" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="course_id" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Course</option> @foreach($courses as $c) <option value="{{ $c->id }}">{{ $c->title }}</option> @endforeach
            </select>
            <select name="class_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">All Classes</option> @foreach($classes as $cl) <option value="{{ $cl->id }}">{{ $cl->name }}</option> @endforeach
            </select>
            <input type="date" name="exam_date" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="time" name="start_time" placeholder="Start Time" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="time" name="end_time" placeholder="End Time" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="number" name="total_marks" placeholder="Total Marks" value="100" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="number" name="passing_marks" placeholder="Passing Marks" value="50" step="0.01" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="exam_type" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="midterm">Midterm</option><option value="final">Final</option><option value="quiz">Quiz</option><option value="assignment">Assignment</option>
            </select>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="scheduled">Scheduled</option><option value="ongoing">Ongoing</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option>
            </select>
            <textarea name="description" placeholder="Description" rows="2" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm md:col-span-2"></textarea>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Create Exam</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Exams</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th><th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th><th class="text-left py-4 px-6 font-semibold">Class</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th><th class="text-left py-4 px-6 font-semibold">Marks</th>
                <th class="text-left py-4 px-6 font-semibold">Type</th><th class="text-left py-4 px-6 font-semibold">Status</th><th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($exams as $i=>$e)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $e->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $e->course?->title ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $e->class?->name ?? 'All' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $e->exam_date->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $e->total_marks }} (pass: {{ $e->passing_marks }})</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">{{ ucfirst($e->exam_type) }}</span></td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $e->status == 'completed' ? 'bg-green-100 text-green-700' : ($e->status == 'ongoing' ? 'bg-yellow-100 text-yellow-700' : ($e->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">{{ ucfirst($e->status) }}</span></td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <form method="POST" action="/admin/school/exams/{{ $e->id }}/delete" x-data @submit.prevent="if(confirm('Delete this exam?')) $el.submit()">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty <tr><td colspan="9" class="py-8 text-center text-heading/50">No exams found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
