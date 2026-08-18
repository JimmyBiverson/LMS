@extends('layouts.dashboard')
@section('title', 'Attendance Management')
@section('page-title', 'Attendance')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Mark Attendance</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/school/attendances" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @csrf
            <select name="class_id" id="att-class" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Class</option> @foreach($classes as $cl) <option value="{{ $cl->id }}">{{ $cl->name }}</option> @endforeach
            </select>
            <select name="course_id" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Course</option> @foreach($courses as $c) <option value="{{ $c->id }}">{{ $c->title }}</option> @endforeach
            </select>
            <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <select name="student_id" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="">Select Student</option> @foreach($students as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
            </select>
            <select name="status" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                <option value="present">Present</option><option value="absent">Absent</option><option value="late">Late</option><option value="excused">Excused</option>
            </select>
            <textarea name="remarks" placeholder="Remarks" rows="1" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Mark</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading">Attendance Records</h3>
        <form method="GET" action="/admin/school/attendances" class="flex gap-2">
            <select name="class_id" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs">
                <option value="">All Classes</option>
                @foreach($classes as $cl) <option value="{{ $cl->id }}" {{ request('class_id')==$cl->id ? 'selected' : '' }}>{{ $cl->name }}</option> @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs">
            <button type="submit" class="px-3 py-1.5 bg-primary text-white text-xs rounded-lg">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th><th class="text-left py-4 px-6 font-semibold">Student</th>
                <th class="text-left py-4 px-6 font-semibold">Class</th><th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th><th class="text-left py-4 px-6 font-semibold">Status</th><th class="text-left py-4 px-6 font-semibold">Remarks</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($attendances as $i=>$a)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $attendances->firstItem()+$i }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $a->student?->name ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $a->class?->name ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $a->course?->title ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $a->date->format('d M Y') }}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $a->status=='present' ? 'bg-green-100 text-green-700' : ($a->status=='late' ? 'bg-yellow-100 text-yellow-700' : ($a->status=='excused' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')) }}">{{ ucfirst($a->status) }}</span>
                    </td>
                    <td class="py-4 px-6 text-heading/70">{{ $a->remarks ?? '--' }}</td>
                </tr>
                @empty <tr><td colspan="7" class="py-8 text-center text-heading/50">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($attendances->hasPages())<div class="p-4">{{ $attendances->links() }}</div>@endif
    </div>
</div>
@endsection
