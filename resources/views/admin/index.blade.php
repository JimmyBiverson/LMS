@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop

@section('content')
@php
$roleBg = 'bg-slate-50';
$roleSidebarBorder = 'border-slate-200';
$roleLogoBg = 'bg-slate-800';
$roleAccent = 'slate-700';
$roleHover = 'slate-100';
$roleHeaderBg = 'bg-white';
$roleAvatarBg = 'bg-slate-100';
$roleAvatarText = 'text-slate-700';
$notifUrl = url('admin/notification');
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl p-5 shadow-lg text-white">
        <div><p class="text-slate-300 text-xs font-semibold uppercase tracking-wider">Total Students</p><p class="text-3xl font-extrabold mt-1">{{ $totalStudents ?? \App\Models\User::where('role','student')->count() }}</p></div>
        <div class="mt-2 text-slate-400 text-xs flex items-center gap-1"><i class="ri-group-line"></i> platform learners</div>
    </div>
    <div class="bg-gradient-to-br from-slate-600 to-slate-800 rounded-xl p-5 shadow-lg text-white">
        <div><p class="text-slate-300 text-xs font-semibold uppercase tracking-wider">Total Courses</p><p class="text-3xl font-extrabold mt-1">{{ $totalCourses ?? \App\Models\Course::count() }}</p></div>
        <div class="mt-2 text-slate-400 text-xs flex items-center gap-1"><i class="ri-book-open-line"></i> published courses</div>
    </div>
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-5 shadow-lg text-white">
        <div><p class="text-blue-200 text-xs font-semibold uppercase tracking-wider">Total Instructors</p><p class="text-3xl font-extrabold mt-1">{{ $totalInstructors ?? \App\Models\User::where('role','instructor')->count() }}</p></div>
        <div class="mt-2 text-blue-300 text-xs flex items-center gap-1"><i class="ri-user-star-line"></i> content creators</div>
    </div>
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-xl p-5 shadow-lg text-white">
        <div><p class="text-emerald-200 text-xs font-semibold uppercase tracking-wider">Enrollments</p><p class="text-3xl font-extrabold mt-1">{{ $totalEnrollments ?? \App\Models\Enrollment::count() }}</p></div>
        <div class="mt-2 text-emerald-300 text-xs flex items-center gap-1"><i class="ri-user-add-line"></i> total enrollments</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-slate-700">
        <p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Organizations</p>
        <p class="text-2xl font-extrabold text-heading mt-1">{{ \App\Models\User::where('role','organization')->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
        <p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Active Courses</p>
        <p class="text-2xl font-extrabold text-heading mt-1">{{ \App\Models\Course::where('status','Active')->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
        <p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Certificates Issued</p>
        <p class="text-2xl font-extrabold text-heading mt-1">{{ \App\Models\Certificate::count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-500">
        <p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Pending Reviews</p>
        <p class="text-2xl font-extrabold text-heading mt-1">0</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-heading flex items-center gap-2"><span class="w-1.5 h-5 bg-slate-700 rounded-full"></span>Recent Enrollments</h3>
            <a href="/admin/enrollment/all" class="text-sm text-slate-600 font-semibold hover:underline">View All</a>
        </div>
        <div class="p-4 space-y-3">
            @php($recent = \App\Models\Enrollment::with('user','course')->latest()->take(5)->get())
            @forelse($recent as $e)
            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center"><i class="ri-user-smile-line text-slate-600"></i></div>
                <div class="flex-1"><p class="font-semibold text-heading text-sm">{{ $e->user?->name ?? 'User' }}</p><p class="text-xs text-heading/50">{{ $e->course?->title ?? 'Course' }}</p></div>
                <span class="text-xs text-heading/40">{{ $e->created_at->diffForHumans() }}</span>
            </div>
            @empty <p class="text-sm text-heading/50">No enrollments yet.</p> @endforelse
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-heading flex items-center gap-2"><span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>Recent Certificates</h3>
            <a href="/admin/certificate" class="text-sm text-slate-600 font-semibold hover:underline">View All</a>
        </div>
        <div class="p-4 space-y-3">
            @php($recentCert = \App\Models\Certificate::with('course')->latest()->take(5)->get())
            @forelse($recentCert as $c)
            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center"><i class="ri-award-line text-amber-500"></i></div>
                <div class="flex-1"><p class="font-semibold text-heading text-sm">{{ $c->title }}</p><p class="text-xs text-heading/50">{{ $c->course?->title ?? 'Course' }}</p></div>
                <span class="text-xs text-heading/40">{{ $c->created_at->diffForHumans() }}</span>
            </div>
            @empty <p class="text-sm text-heading/50">No certificates yet.</p> @endforelse
        </div>
    </div>
</div>

<div class="mt-6 bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><span class="w-1.5 h-5 bg-slate-700 rounded-full"></span>Platform Overview</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center p-4 rounded-lg bg-slate-50"><p class="text-2xl font-extrabold text-slate-700">{{ number_format(\App\Models\User::count()) }}</p><p class="text-xs text-heading/50 mt-1">Total Users</p></div>
        <div class="text-center p-4 rounded-lg bg-blue-50"><p class="text-2xl font-extrabold text-blue-700">{{ \App\Models\Category::where('status','active')->count() }}</p><p class="text-xs text-heading/50 mt-1">Categories</p></div>
        <div class="text-center p-4 rounded-lg bg-emerald-50"><p class="text-2xl font-extrabold text-emerald-700">{{ \App\Models\Blog::where('status','published')->count() }}</p><p class="text-xs text-heading/50 mt-1">Published Blogs</p></div>
        <div class="text-center p-4 rounded-lg bg-amber-50"><p class="text-2xl font-extrabold text-amber-700">${{ number_format(\App\Models\Enrollment::sum('amount_paid'), 0) }}</p><p class="text-xs text-heading/50 mt-1">Total Revenue</p></div>
    </div>
</div>
@endsection
