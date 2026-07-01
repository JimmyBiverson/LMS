@extends('layouts.dashboard')
@section('title', 'Organization Dashboard')
@section('page-title', 'Dashboard')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop

@section('content')
@php
$roleBg = 'bg-violet-50';
$roleSidebarBorder = 'border-violet-200';
$roleLogoBg = 'bg-violet-500';
$roleAccent = 'violet-600';
$roleHover = 'violet-50';
$roleHeaderBg = 'bg-white';
$roleAvatarBg = 'bg-violet-100';
$roleAvatarText = 'text-violet-600';
$notifUrl = url('org/notifications');
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-gradient-to-br from-violet-500 to-purple-700 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between"><div><p class="text-violet-200 text-xs font-semibold uppercase tracking-wider">Total Students</p><p class="text-3xl font-extrabold mt-1">{{ $totalStudents }}</p></div><div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white"><i class="ri-group-line text-xl"></i></div></div>
        <div class="mt-2 text-violet-200 text-xs flex items-center gap-1"><i class="ri-user-star-line"></i> enrolled learners</div>
    </div>
    <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between"><div><p class="text-purple-100 text-xs font-semibold uppercase tracking-wider">Total Courses</p><p class="text-3xl font-extrabold mt-1">{{ $courses->count() }}</p></div><div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white"><i class="ri-book-open-line text-xl"></i></div></div>
        <div class="mt-2 text-purple-100 text-xs flex items-center gap-1"><i class="ri-check-line"></i> {{ $courses->where('status', 'Active')->count() }} active courses</div>
    </div>
    <div class="bg-gradient-to-br from-fuchsia-400 to-fuchsia-600 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between"><div><p class="text-fuchsia-100 text-xs font-semibold uppercase tracking-wider">Enrollments</p><p class="text-3xl font-extrabold mt-1">{{ $courses->sum('enrollments_count') }}</p></div><div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white"><i class="ri-user-add-line text-xl"></i></div></div>
        <div class="mt-2 text-fuchsia-100 text-xs flex items-center gap-1"><i class="ri-line-chart-line"></i> total enrollments</div>
    </div>
    <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between"><div><p class="text-indigo-100 text-xs font-semibold uppercase tracking-wider">Instructors</p>        <p class="text-3xl font-extrabold mt-1">{{ \App\Models\User::where('role', 'instructor')->where('organization_id', auth()->id())->count() }}</p></div><div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white"><i class="ri-team-line text-xl"></i></div></div>
        <div class="mt-2 text-indigo-100 text-xs flex items-center gap-1"><i class="ri-arrow-up-line"></i> team members</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-violet-100 flex items-center justify-between">
            <h3 class="font-bold text-heading flex items-center gap-2"><span class="w-1.5 h-5 bg-violet-500 rounded-full"></span>Organization Courses</h3>
            <a href="/org/courses" class="text-sm text-violet-600 font-semibold hover:underline">Manage All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-violet-50/50 text-heading/60 text-xs uppercase tracking-wider">
                    <th class="text-left py-3 px-4 font-semibold">Course</th><th class="text-left py-3 px-4 font-semibold">Students</th><th class="text-left py-3 px-4 font-semibold">Status</th><th class="text-left py-3 px-4 font-semibold">Action</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($courses as $c)
                    <tr class="hover:bg-violet-50/30 transition-colors">
                        <td class="py-3 px-4 font-semibold text-heading">{{ $c->title }}</td>
                        <td class="py-3 px-4 text-heading/70">{{ $c->enrollments_count }}</td>
                        <td class="py-3 px-4"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $c->status=='Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $c->status }}</span></td>
                        <td class="py-3 px-4"><a href="/org/courses/edit/{{ $c->id }}" class="text-violet-600 hover:text-violet-800 text-xs font-semibold"><i class="ri-edit-line mr-1"></i>Edit</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-heading/50 text-sm">No courses yet. <a href="/org/courses/create" class="text-violet-600 font-semibold hover:underline">Create one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><span class="w-1.5 h-5 bg-violet-500 rounded-full"></span>Quick Actions</h3>
            <div class="space-y-3">
                <a href="/org/courses/create" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-violet-50 text-violet-700 hover:bg-violet-100 transition-colors"><i class="ri-add-circle-line text-lg"></i><span class="font-semibold text-sm">Create New Course</span></a>
                <a href="/org/instructors" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-violet-50 text-violet-700 hover:bg-violet-100 transition-colors"><i class="ri-team-line text-lg"></i><span class="font-semibold text-sm">Manage Instructors</span></a>
                <a href="/org/financial" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-violet-50 text-violet-700 hover:bg-violet-100 transition-colors"><i class="ri-funds-line text-lg"></i><span class="font-semibold text-sm">Financial Report</span></a>
                <a href="/org/students" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-violet-50 text-violet-700 hover:bg-violet-100 transition-colors"><i class="ri-group-line text-lg"></i><span class="font-semibold text-sm">All Students</span></a>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><span class="w-1.5 h-5 bg-violet-500 rounded-full"></span>Organization Overview</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-lg bg-violet-50"><span class="text-sm text-violet-700 font-semibold">Courses</span><span class="text-sm font-bold text-violet-900">{{ $courses->count() }}</span></div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-purple-50"><span class="text-sm text-purple-700 font-semibold">Active Courses</span><span class="text-sm font-bold text-purple-900">{{ $courses->where('status', 'Active')->count() }}</span></div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-fuchsia-50"><span class="text-sm text-fuchsia-700 font-semibold">Total Revenue</span><span class="text-sm font-bold text-fuchsia-900">{{ \App\Helpers\CurrencyHelper::format($courses->sum(fn($c)=>$c->enrollments_count * ($c->sale_price ?? $c->price))) }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
