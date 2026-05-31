@extends('layouts.dashboard')
@section('title', 'Students')
@section('page-title', 'My Students')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Enrolled Students</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Student</th>
                <th class="text-left py-4 px-6 font-semibold">Email</th>
                <th class="text-left py-4 px-6 font-semibold">Enrolled</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 flex items-center gap-3">
                        <img src="https://placehold.co/32x32/5F3EED/FFFFFF?text={{ substr($student->first_name, 0, 1) }}" class="w-8 h-8 rounded-full">
                        <span class="font-semibold text-heading">{{ $student->full_name }}</span>
                    </td>
                    <td class="py-4 px-6 text-heading/70">{{ $student->email }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $student->created_at->format("Y-m-d") }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-12 text-center text-heading/40 text-sm">No students enrolled yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
