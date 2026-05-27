@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Students</p><p class="text-2xl font-bold text-heading mt-1">{{ $totalStudents ?? \App\Models\User::where('role','student')->count() }}</p></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Courses</p><p class="text-2xl font-bold text-heading mt-1">{{ $totalCourses ?? \App\Models\Course::count() }}</p></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Instructors</p><p class="text-2xl font-bold text-heading mt-1">{{ $totalInstructors ?? \App\Models\User::where('role','instructor')->count() }}</p></div></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><div><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Enrollments</p><p class="text-2xl font-bold text-heading mt-1">{{ $totalEnrollments ?? \App\Models\Enrollment::count() }}</p></div></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-bold text-heading mb-4">Recent Enrollments</h3><div class="space-y-3">@php($recent = \App\Models\Enrollment::with('user','course')->latest()->take(5)->get()) @forelse($recent as $e)<div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0"><div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center"><i class="ri-user-smile-line text-primary"></i></div><div class="flex-1"><p class="font-semibold text-heading text-sm">{{ $e->user?->name ?? 'User' }}</p><p class="text-xs text-heading/50">{{ $e->course?->title ?? 'Course' }}</p></div><span class="text-xs text-heading/40">{{ $e->created_at->diffForHumans() }}</span></div>@empty <p class="text-sm text-heading/50">No enrollments yet.</p> @endforelse</div></div>
        <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-bold text-heading mb-4">Recent Certificates</h3><div class="space-y-3">@php($recentCert = \App\Models\Certificate::with('course')->latest()->take(5)->get()) @forelse($recentCert as $c)<div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0"><div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center"><i class="ri-award-line text-amber-500"></i></div><div class="flex-1"><p class="font-semibold text-heading text-sm">{{ $c->title }}</p><p class="text-xs text-heading/50">{{ $c->course?->title ?? 'Course' }}</p></div><span class="text-xs text-heading/40">{{ $c->created_at->diffForHumans() }}</span></div>@empty <p class="text-sm text-heading/50">No certificates yet.</p> @endforelse</div></div>
    </div>
</div>
@endsection
