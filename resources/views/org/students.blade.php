@extends('layouts.dashboard')
@section('title', 'Org Students')
@section('page-title', 'Students')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">All Students</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Student</th>
                <th class="text-left py-4 px-6 font-semibold">Email</th>
                <th class="text-left py-4 px-6 font-semibold">Courses</th>
                <th class="text-left py-4 px-6 font-semibold">Joined</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=5;$i++)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i }}</td>
                    <td class="py-4 px-6 flex items-center gap-3"><img src="https://placehold.co/32x32/5F3EED/FFFFFF?text=S{{ $i }}" class="w-8 h-8 rounded-full"><span class="font-semibold text-heading">Student {{ $i }}</span></td>
                    <td class="py-4 px-6 text-heading/70">student{{ $i }}@mail.com</td>
                    <td class="py-4 px-6 text-heading/70">{{ 1+$i }}</td>
                    <td class="py-4 px-6 text-heading/70">2024-12-0{{ $i }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection