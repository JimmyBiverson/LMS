@extends('layouts.dashboard')
@section('title', 'My Assignments')
@section('page-title', 'Assignment')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">My Assignments</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Assignment</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Deadline</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-right py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=4;$i++)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">Project Milestone {{ $i }}</td>
                    <td class="py-4 px-6 text-heading/70">Full-Stack Web Development</td>
                    <td class="py-4 px-6 text-heading/70">2024-12-{{ str_pad(10+$i,2,'0',STR_PAD_LEFT) }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $i <= 2 ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $i <= 2 ? 'Submitted' : 'Pending' }}</span></td>
                    <td class="py-4 px-6 text-right"><a href="#" class="text-primary text-sm font-semibold hover:underline">View</a></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection