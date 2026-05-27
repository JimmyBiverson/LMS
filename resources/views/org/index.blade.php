@extends('layouts.dashboard')
@section('title', 'Organization Dashboard')
@section('page-title', 'Dashboard')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Students</p><p class="text-2xl font-bold text-heading mt-1">{{ $totalStudents }}</p></div><div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary"><i class="ri-group-line text-xl"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Courses</p><p class="text-2xl font-bold text-heading mt-1">{{ $courses->count() }}</p></div><div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary"><i class="ri-book-open-line text-xl"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Enrollments</p><p class="text-2xl font-bold text-heading mt-1">{{ $courses->sum('enrollments_count') }}</p></div><div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-green-600"><i class="ri-user-star-line text-xl"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Active Courses</p><p class="text-2xl font-bold text-heading mt-1">{{ $courses->where('status', 'Active')->count() }}</p></div><div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center text-red-500"><i class="ri-money-dollar-circle-line text-xl"></i></div></div></div>
    </div>
</div>
@endsection
