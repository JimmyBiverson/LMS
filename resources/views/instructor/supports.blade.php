@extends('layouts.dashboard')
@section('title', 'Instructor Supports')
@section('page-title', 'Support')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Support Tickets</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">From</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=3;$i++)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">Student {{ $i }}</td>
                    <td class="py-4 px-6 text-heading/70">Question about assignment {{ $i }}</td>
                    <td class="py-4 px-6 text-heading/70">Web Development</td>
                    <td class="py-4 px-6 text-heading/70">2024-12-0{{ $i }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $i == 1 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ $i == 1 ? 'Pending' : 'Resolved' }}</span></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection