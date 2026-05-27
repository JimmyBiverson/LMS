@extends('layouts.dashboard')
@section('title', 'Instructor Assignments')
@section('page-title', 'Assignment')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <h3 class="font-bold text-heading">Assignments</h3>
        <a href="#" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300"><i class="ri-add-line mr-1"></i> Add Assignment</a>
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
                @for($i=1;$i<=4;$i++)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">Project {{ $i }}</td>
                    <td class="py-4 px-6 text-heading/70">Web Development</td>
                    <td class="py-4 px-6 text-heading/70">2024-12-{{ str_pad(10+$i,2,'0',STR_PAD_LEFT) }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ 15+$i*5 }}</td>
                    <td class="py-4 px-6 text-right"><a href="#" class="px-3 py-1 text-xs font-semibold rounded-full border border-heading/10 hover:bg-primary hover:text-white transition-all">Manage</a></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection