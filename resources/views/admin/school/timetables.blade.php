@extends('layouts.dashboard')
@section('title', 'Timetable Management')
@section('page-title', 'Timetables')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Add Schedule</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/school/timetables" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @csrf
            <select name="class_id" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Class</option> @foreach($classes as $cl) <option value="{{ $cl->id }}">{{ $cl->name }}</option> @endforeach
            </select>
            <select name="course_id" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Course</option> @foreach($courses as $c) <option value="{{ $c->id }}">{{ $c->title }}</option> @endforeach
            </select>
            <select name="teacher_id" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Teacher</option> @foreach($teachers as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
            </select>
            <select name="day_of_week" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Day</option><option value="monday">Monday</option><option value="tuesday">Tuesday</option>
                <option value="wednesday">Wednesday</option><option value="thursday">Thursday</option>
                <option value="friday">Friday</option><option value="saturday">Saturday</option><option value="sunday">Sunday</option>
            </select>
            <input type="time" name="start_time" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="time" name="end_time" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="text" name="room" placeholder="Room" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add Schedule</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Weekly Timetables</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th><th class="text-left py-4 px-6 font-semibold">Class</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th><th class="text-left py-4 px-6 font-semibold">Teacher</th>
                <th class="text-left py-4 px-6 font-semibold">Day</th><th class="text-left py-4 px-6 font-semibold">Time</th>
                <th class="text-left py-4 px-6 font-semibold">Room</th><th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($timetables as $i=>$t)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $t->class?->name ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->course?->title ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->teacher?->name ?? '--' }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">{{ ucfirst($t->day_of_week) }}</span></td>
                    <td class="py-4 px-6 text-heading/70">{{ \Carbon\Carbon::parse($t->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($t->end_time)->format('h:i A') }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->room ?? '--' }}</td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/school/timetables/{{ $t->id }}/delete" x-data @submit.prevent="if(confirm('Delete this schedule?')) $el.submit()">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty <tr><td colspan="8" class="py-8 text-center text-heading/50">No schedules found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
