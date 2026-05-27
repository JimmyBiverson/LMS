@extends('layouts.dashboard')
@section('title', 'Organization Dashboard')
@section('page-title', 'Dashboard')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Students</p><p class="text-2xl font-bold text-heading mt-1">2,450</p></div><div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary"><i class="ri-group-line text-xl"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Courses</p><p class="text-2xl font-bold text-heading mt-1">42</p></div><div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary"><i class="ri-book-open-line text-xl"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Instructors</p><p class="text-2xl font-bold text-heading mt-1">18</p></div><div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-green-600"><i class="ri-user-star-line text-xl"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Revenue</p><p class="text-2xl font-bold text-heading mt-1">$52,300</p></div><div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center text-red-500"><i class="ri-money-dollar-circle-line text-xl"></i></div></div></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-bold text-heading mb-4">Recent Activity</h3><div class="space-y-3">@for($i=1;$i<=4;$i++)<div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0"><div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs"><i class="ri-user-add-line"></i></div><div class="flex-1"><p class="font-semibold text-heading text-sm">New student enrolled</p><p class="text-xs text-heading/50">Course {{ $i }}</p></div><span class="text-xs text-heading/40">{{ $i }}d ago</span></div>@endfor</div></div>
        <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-bold text-heading mb-4">Revenue Chart</h3><div class="flex items-end justify-between h-32 gap-2 pt-4">@for($i=1;$i<=6;$i++)<div class="flex-1 flex flex-col items-center gap-1"><div class="w-full bg-secondary/30 rounded-t-lg" style="height: {{ 15+$i*12 }}px"></div><span class="text-xs text-heading/50">M{{ $i }}</span></div>@endfor</div></div>
    </div>
</div>
@endsection