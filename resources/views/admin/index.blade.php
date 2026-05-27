@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Students</p><p class="text-2xl font-bold text-heading mt-1">4,568</p></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Courses</p><p class="text-2xl font-bold text-heading mt-1">128</p></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Instructors</p><p class="text-2xl font-bold text-heading mt-1">56</p></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Revenue</p><p class="text-2xl font-bold text-heading mt-1">$124,500</p></div></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-bold text-heading mb-4">Recent Enrollments</h3><div class="space-y-3">@for($i=1;$i<=4;$i++)<div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0"><img src="https://placehold.co/40x40/5F3EED/FFFFFF?text=U{{ $i }}" class="w-10 h-10 rounded-full"><div class="flex-1"><p class="font-semibold text-heading text-sm">User {{ $i }}</p><p class="text-xs text-heading/50">Enrolled in Course {{ $i }}</p></div><span class="text-xs text-heading/40">{{ $i }}d ago</span></div>@endfor</div></div>
        <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-bold text-heading mb-4">Monthly Revenue</h3><div class="flex items-end justify-between h-32 gap-2 pt-4">@for($i=1;$i<=6;$i++)<div class="flex-1 flex flex-col items-center gap-1"><div class="w-full bg-primary/20 rounded-t-lg" style="height: {{ 15+$i*10 }}px"></div><span class="text-xs text-heading/50">M{{ $i }}</span></div>@endfor</div></div>
    </div>
</div>
@endsection