@extends('layouts.dashboard')
@section('title', 'Course Support')
@section('page-title', 'Course Support')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Course Support Messages</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">From</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=3;$i++)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">Instructor Name</td>
                    <td class="py-4 px-6 text-heading/70">Full-Stack Web Development</td>
                    <td class="py-4 px-6 text-heading/70">Regarding your assignment submission</td>
                    <td class="py-4 px-6 text-heading/70">2024-12-0{{ $i }}</td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection