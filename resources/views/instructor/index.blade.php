@extends('layouts.dashboard')
@section('title', 'Instructor Dashboard')
@section('page-title', 'Dashboard')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop

@section('content')
@php
$roleBg = 'bg-amber-50';
$roleSidebarBorder = 'border-amber-200';
$roleLogoBg = 'bg-amber-500';
$roleAccent = 'amber-600';
$roleHover = 'amber-50';
$roleHeaderBg = 'bg-white';
$roleAvatarBg = 'bg-amber-100';
$roleAvatarText = 'text-amber-600';
$notifUrl = url('instructor/notifications');
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between"><div><p class="text-amber-100 text-xs font-semibold uppercase tracking-wider">Total Students</p><p class="text-3xl font-extrabold mt-1">{{ $totalStudents }}</p></div><div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white"><i class="ri-group-line text-xl"></i></div></div>
        <div class="mt-2 text-amber-100 text-xs flex items-center gap-1"><i class="ri-arrow-up-line"></i> across all courses</div>
    </div>
    <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between"><div><p class="text-orange-100 text-xs font-semibold uppercase tracking-wider">Total Courses</p><p class="text-3xl font-extrabold mt-1">{{ $courses->count() }}</p></div><div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white"><i class="ri-book-open-line text-xl"></i></div></div>
        <div class="mt-2 text-orange-100 text-xs flex items-center gap-1"><i class="ri-check-line"></i> {{ $courses->where('status', 'Active')->count() }} active</div>
    </div>
    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between"><div><p class="text-yellow-100 text-xs font-semibold uppercase tracking-wider">Total Enrollments</p><p class="text-3xl font-extrabold mt-1">{{ $courses->sum('enrollments_count') }}</p></div><div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white"><i class="ri-user-add-line text-xl"></i></div></div>
        <div class="mt-2 text-yellow-100 text-xs flex items-center gap-1"><i class="ri-bar-chart-line"></i> student engagement</div>
    </div>
    <div class="bg-gradient-to-br from-amber-600 to-red-500 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between"><div><p class="text-amber-100 text-xs font-semibold uppercase tracking-wider">Est. Revenue</p><p class="text-3xl font-extrabold mt-1">{{ \App\Helpers\CurrencyHelper::format($courses->sum(fn($c)=>$c->enrollments_count * ($c->sale_price ?? $c->price))) }}</p></div><div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white"><i class="ri-money-dollar-circle-line text-xl"></i></div></div>
        <div class="mt-2 text-amber-100 text-xs flex items-center gap-1"><i class="ri-funds-line"></i> estimated earnings</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-amber-100 flex items-center justify-between">
            <h3 class="font-bold text-heading flex items-center gap-2"><span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>My Courses</h3>
            <a href="{{ url('instructor/courses') }}" class="text-sm text-amber-600 font-semibold hover:underline">Manage Courses</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[500px]">
                <thead><tr class="bg-amber-50/50 text-heading/60 text-xs uppercase tracking-wider">
                    <th class="text-left py-3 px-4 font-semibold">Course</th><th class="text-left py-3 px-4 font-semibold">Students</th><th class="text-left py-3 px-4 font-semibold">Price</th><th class="text-left py-3 px-4 font-semibold">Status</th><th class="text-left py-3 px-4 font-semibold">Action</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($courses as $c)
                    <tr class="hover:bg-amber-50/30 transition-colors">
                        <td class="py-3 px-4 font-semibold text-heading">{{ $c->title }}</td>
                        <td class="py-3 px-4 text-heading/70">{{ $c->enrollments_count }}</td>
                        <td class="py-3 px-4 text-heading/70">{{ $c->price > 0 ? \App\Helpers\CurrencyHelper::format((float)$c->price) : 'Free' }}</td>
                        <td class="py-3 px-4"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $c->status=='Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $c->status }}</span></td>
                        <td class="py-3 px-4"><a href="/instructor/courses/edit/{{ $c->id }}" class="text-amber-600 hover:text-amber-800 text-xs font-semibold"><i class="ri-edit-line mr-1"></i>Edit</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-8 text-center text-heading/50 text-sm">No courses yet. <a href="/instructor/courses/create" class="text-amber-600 font-semibold hover:underline">Create one</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ url('instructor/courses/create') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors"><i class="ri-add-circle-line text-lg"></i><span class="font-semibold text-sm">Create New Course</span></a>
                <a href="{{ url('instructor/quiz') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors"><i class="ri-questionnaire-line text-lg"></i><span class="font-semibold text-sm">Manage Quizzes</span></a>
                <a href="{{ url('instructor/assignments') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors"><i class="ri-file-list-3-line text-lg"></i><span class="font-semibold text-sm">Manage Assignments</span></a>
                <a href="/instructor/earnings" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors"><i class="ri-funds-line text-lg"></i><span class="font-semibold text-sm">View Earnings</span></a>
                <a href="/instructor/students" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors"><i class="ri-group-line text-lg"></i><span class="font-semibold text-sm">My Students</span></a>
                <a href="/instructor/reviews" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors"><i class="ri-star-line text-lg"></i><span class="font-semibold text-sm">Course Reviews</span></a>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>Course Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between"><span class="text-sm text-heading/70">Active</span><span class="text-sm font-bold text-emerald-600">{{ $courses->where('status', 'Active')->count() }}</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-emerald-500 rounded-full h-2" style="width: {{ $courses->count() > 0 ? ($courses->where('status','Active')->count()/$courses->count())*100 : 0 }}%"></div></div>
                <div class="flex items-center justify-between"><span class="text-sm text-heading/70">Draft</span><span class="text-sm font-bold text-amber-600">{{ $courses->where('status', 'Draft')->count() }}</span></div>
                <div class="flex items-center justify-between"><span class="text-sm text-heading/70">Pending</span><span class="text-sm font-bold text-yellow-600">{{ $courses->where('status', 'Pending')->count() }}</span></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><span class="w-1.5 h-5 bg-blue-500 rounded-full"></span>Recent Activity</h3>
            <div class="space-y-3">
                @forelse($recentNotifications as $note)
                <div class="flex items-start gap-3 py-2 border-b border-gray-50 last:border-0">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0"><i class="ri-notification-3-line text-blue-600 text-sm"></i></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-heading truncate">{{ $note->subject }}</p>
                        <p class="text-xs text-heading/60 truncate">{{ $note->body }}</p>
                        <p class="text-xs text-heading/40 mt-0.5">{{ $note->sent_at->diffForHumans() }}</p>
                    </div>
                    @if(!$note->is_read)
                    <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 mt-2"></span>
                    @endif
                </div>
                @empty
                <div class="text-center py-4"><p class="text-sm text-heading/50">No activity yet.</p></div>
                @endforelse
                <a href="{{ url('instructor/notifications') }}" class="block text-center text-xs font-semibold text-amber-600 hover:underline pt-2">View All Notifications</a>
            </div>
        </div>
    </div>
</div>
@endsection
