@extends('layouts.dashboard')
@section('title', 'Student Dashboard')
@section('page-title', 'Dashboard')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop

@section('content')
@php
$roleBg = 'bg-emerald-50';
$roleSidebarBorder = 'border-emerald-100';
$roleLogoBg = 'bg-emerald-500';
$roleAccent = 'emerald-600';
$roleHover = 'emerald-50';
$roleAvatarBg = 'bg-emerald-100';
$roleAvatarText = 'text-emerald-600';
$notifUrl = url('dashboard/notifications');
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl p-5 shadow-lg text-white">
        <p class="text-emerald-100 text-sm font-medium">Enrolled Courses</p>
        <p class="text-3xl font-extrabold mt-1">{{ $totalEnrolled }}</p>
        <div class="mt-2 w-full bg-emerald-400/30 rounded-full h-1.5"><div class="bg-white rounded-full h-1.5" style="width: {{ $totalEnrolled > 0 ? min(100, ($completed/$totalEnrolled)*100) : 0 }}%"></div></div>
    </div>
    <div class="bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl p-5 shadow-lg text-white">
        <p class="text-teal-100 text-sm font-medium">In Progress</p>
        <p class="text-3xl font-extrabold mt-1">{{ $inProgress }}</p>
        <div class="mt-2 flex items-center gap-1 text-teal-100 text-xs"><i class="ri-time-line"></i> Keep going!</div>
    </div>
    <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl p-5 shadow-lg text-white">
        <p class="text-green-100 text-sm font-medium">Completed</p>
        <p class="text-3xl font-extrabold mt-1">{{ $completed }}</p>
        <div class="mt-2 flex items-center gap-1 text-green-100 text-xs"><i class="ri-check-double-line"></i> {{ $totalEnrolled > 0 ? round(($completed/$totalEnrolled)*100) : 0 }}% completion rate</div>
    </div>
    <div class="bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-xl p-5 shadow-lg text-white">
        <p class="text-cyan-100 text-sm font-medium">Certificates</p>
        <p class="text-3xl font-extrabold mt-1">{{ $certificateCount }}</p>
        <div class="mt-2 flex items-center gap-1 text-cyan-100 text-xs"><i class="ri-award-line"></i> Earned</div>
    </div>
    <div class="bg-gradient-to-br from-sky-400 to-sky-600 rounded-xl p-5 shadow-lg text-white">
        <p class="text-sky-100 text-sm font-medium">Wishlist</p>
        <p class="text-3xl font-extrabold mt-1">{{ \App\Models\Wishlist::where('user_id', auth()->id())->count() }}</p>
        <div class="mt-2 flex items-center gap-1 text-sky-100 text-xs"><i class="ri-heart-line"></i> Saved courses</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-emerald-100 flex items-center justify-between">
            <h3 class="font-bold text-heading flex items-center gap-2"><span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span>Latest Enrolled Courses</h3>
            <a href="/dashboard/my-enrolled-course" class="text-sm text-emerald-600 font-semibold hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-emerald-50/50 text-heading/60 text-xs uppercase tracking-wider">
                    <th class="text-left py-4 px-6 font-semibold">Course Name</th>
                    <th class="text-left py-4 px-6 font-semibold">Author</th>
                    <th class="text-left py-4 px-6 font-semibold">Price</th>
                    <th class="text-left py-4 px-6 font-semibold">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($latest as $e)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="py-4 px-6 font-semibold text-heading">{{ $e->course?->title ?? 'Deleted Course' }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $e->course?->instructor?->name ?? 'N/A' }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $e->amount_paid > 0 ? '$'.number_format((float)$e->amount_paid,2) : 'Free' }}</td>
                        <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $e->status==='completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst(str_replace('_',' ',$e->status ?? 'in_progress')) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-heading/50 text-sm">You haven't enrolled in any courses yet. <a href="/courses" class="text-emerald-600 font-semibold hover:underline">Browse courses</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span>Learning Progress</h3>
        @forelse($enrollments->take(4) as $e)
        @php
            $totalLessons = $e->course?->lessons->count() ?? 0;
            $completedLessons = $totalLessons > 0 ? \App\Models\LessonCompletion::where('user_id', auth()->id())->where('course_id', $e->course_id)->count() : 0;
            $pct = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        @endphp
        <div class="mb-4 last:mb-0">
            <div class="flex items-center justify-between mb-1">
                <span class="text-sm font-semibold text-heading truncate">{{ $e->course?->title ?? 'Course' }}</span>
                <span class="text-xs font-bold text-emerald-600">{{ $pct }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-emerald-500 rounded-full h-2 transition-all duration-500" style="width: {{ $pct }}%"></div></div>
        </div>
        @empty
        <p class="text-sm text-heading/50">No courses enrolled yet.</p>
        @endforelse
    </div>
</div>
@endsection
