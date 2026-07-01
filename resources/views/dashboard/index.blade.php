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

{{-- My Tasks Widget --}}
@if($pendingAssignments->isNotEmpty() || $pendingQuizzes->isNotEmpty() || $pendingExams->isNotEmpty() || $recentGraded->isNotEmpty())
<div class="bg-white rounded-xl shadow-sm mb-8">
    <div class="p-5 border-b border-emerald-100">
        <h3 class="font-bold text-heading flex items-center gap-2">
            <span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span>
            <i class="ri-task-line text-emerald-500"></i> My Tasks
        </h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-100">
        {{-- Pending Assignments --}}
        <div class="p-5">
            <p class="text-xs font-semibold text-heading/50 uppercase tracking-wider mb-3 flex items-center gap-1">
                <i class="ri-file-list-3-line text-amber-500"></i> Pending Assignments
            </p>
            @forelse($pendingAssignments as $pa)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-heading truncate">{{ $pa->title }}</p>
                    <p class="text-xs text-heading/50 truncate">{{ $pa->course?->title }}</p>
                </div>
                <a href="/dashboard/assignments/{{ $pa->id }}/submit" class="text-xs text-primary font-semibold hover:underline ml-2 shrink-0">
                    Submit <i class="ri-arrow-right-s-line"></i>
                </a>
            </div>
            @empty
            <p class="text-sm text-heading/40 py-4 text-center">
                <i class="ri-check-double-line block text-lg mb-1"></i>
                All caught up!
            </p>
            @endforelse
        </div>

        {{-- Available Exams --}}
        <div class="p-5">
            <p class="text-xs font-semibold text-heading/50 uppercase tracking-wider mb-3 flex items-center gap-1">
                <i class="ri-edit-box-line text-purple-600"></i> Pending Exams
            </p>
            @forelse($pendingExams as $pe)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-heading truncate">{{ $pe->title }}</p>
                    <p class="text-xs text-heading/50 truncate">{{ $pe->course?->title }}</p>
                </div>
                @if(!$pe->isAvailable())
                {!! $pe->availabilityBadge() !!}
                @else
                <a href="/dashboard/exams/{{ $pe->id }}/instructions" class="text-xs text-purple-600 font-semibold hover:underline ml-2 shrink-0">
                    Start <i class="ri-arrow-right-s-line"></i>
                </a>
                @endif
            </div>
            @empty
            <p class="text-sm text-heading/40 py-4 text-center">
                <i class="ri-check-double-line block text-lg mb-1"></i>
                No pending exams
            </p>
            @endforelse
        </div>

        {{-- Available Quizzes --}}
        <div class="p-5">
            <p class="text-xs font-semibold text-heading/50 uppercase tracking-wider mb-3 flex items-center gap-1">
                <i class="ri-questionnaire-line text-primary"></i> Available Quizzes
            </p>
            @forelse($pendingQuizzes as $pq)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-heading truncate">{{ $pq->title }}</p>
                    <p class="text-xs text-heading/50 truncate">{{ $pq->course?->title }}</p>
                </div>
                @if(!$pq->isAvailable())
                {!! $pq->availabilityBadge() !!}
                @else
                <a href="/dashboard/quizzes/{{ $pq->id }}/instructions" class="text-xs text-primary font-semibold hover:underline ml-2 shrink-0">
                    Take <i class="ri-arrow-right-s-line"></i>
                </a>
                @endif
            </div>
            @empty
            <p class="text-sm text-heading/40 py-4 text-center">
                <i class="ri-check-double-line block text-lg mb-1"></i>
                No pending quizzes
            </p>
            @endforelse
        </div>

        {{-- Recently Graded --}}
        <div class="p-5">
            <p class="text-xs font-semibold text-heading/50 uppercase tracking-wider mb-3 flex items-center gap-1">
                <i class="ri-award-line text-green-500"></i> Recent Grades
            </p>
            @forelse($recentGraded as $rg)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-heading truncate">{{ $rg->assignment?->title }}</p>
                    <p class="text-xs text-heading/50">Score: <span class="font-bold text-green-600">{{ $rg->score }}/{{ $rg->assignment?->total_marks }}</span></p>
                </div>
            </div>
            @empty
            <p class="text-sm text-heading/40 py-4 text-center">
                <i class="ri-inbox-line block text-lg mb-1"></i>
                No graded items yet
            </p>
            @endforelse
        </div>
    </div>
</div>
@endif

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
                <th class="text-left py-4 px-6 font-semibold">Action</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($latest as $e)
                    @php
                        $resumeL = $e->course?->getResumeLesson();
                    @endphp
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="py-4 px-6 font-semibold text-heading">{{ $e->course?->title ?? 'Deleted Course' }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $e->course?->instructor?->name ?? 'N/A' }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $e->amount_paid > 0 ? \App\Helpers\CurrencyHelper::format((float)$e->amount_paid) : 'Free' }}</td>
                        <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $e->status==='completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst(str_replace('_',' ',$e->status ?? 'in_progress')) }}</span></td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                @if($resumeL)
                                    <a href="/courses/{{ $e->course->slug }}/lessons/{{ $resumeL->id }}" class="text-sm font-semibold text-emerald-600 hover:underline flex items-center gap-1"><i class="ri-play-circle-line"></i> Continue</a>
                                @else
                                    <a href="/courses/{{ $e->course->slug }}" class="text-sm font-semibold text-primary hover:underline flex items-center gap-1"><i class="ri-eye-line"></i> View</a>
                                @endif
                                <a href="/courses/{{ $e->course->slug }}/materials" class="text-sm font-semibold text-primary hover:underline flex items-center gap-1"><i class="ri-tools-line"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-8 text-center text-heading/50 text-sm">You haven't enrolled in any courses yet. <a href="/courses" class="text-emerald-600 font-semibold hover:underline">Browse courses</a></td></tr>
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
            $completedLessons = $progressCounts[$e->course_id] ?? 0;
            $pct = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            $circleId = 'progress-' . $e->id;
            $circumference = 2 * pi() * 28;
            $offset = $circumference - ($pct / 100) * $circumference;
        @endphp
        <div class="mb-5 last:mb-0">
            <div x-data="{ show: false }" x-init="() => { setTimeout(() => { show = true }, 100) }">
                <div class="flex items-center gap-4">
                    {{-- Animated SVG Progress Ring --}}
                    <div class="relative shrink-0">
                        <svg width="68" height="68" viewBox="0 0 68 68">
                            <circle cx="34" cy="34" r="28" fill="none" stroke="#e5e7eb" stroke-width="5"/>
                            <circle cx="34" cy="34" r="28" fill="none" stroke="{{ $pct >= 80 ? '#10b981' : ($pct >= 40 ? '#f59e0b' : '#6b7280') }}" stroke-width="5"
                                stroke-dasharray="{{ $circumference }}"
                                :stroke-dashoffset="show ? {{ $offset }} : {{ $circumference }}"
                                stroke-linecap="round"
                                transform="rotate(-90 34 34)"
                                class="transition-all duration-1000 ease-out"
                            />
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-xs font-extrabold text-heading"
                              x-text="show ? '{{ $pct }}%' : '0%'">
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="/courses/{{ $e->course?->slug }}/materials" class="text-sm font-semibold text-heading hover:text-primary transition-colors truncate block">{{ $e->course?->title ?? 'Course' }}</a>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-heading/60">{{ $completedLessons }}/{{ $totalLessons }} lessons</span>
                            @if($e->course)
                                @php
                                    $qzCount = $e->course->quizzes_count ?? 0;
                                    $asCount = $e->course->assignments_count ?? 0;
                                @endphp
                                @if($qzCount > 0 || $asCount > 0)
                                <span class="text-xs text-heading/40">·</span>
                                @endif
                                @if($qzCount > 0)
                                <a href="/courses/{{ $e->course->slug }}/materials#quizzes" class="text-xs text-primary hover:underline">
                                    <i class="ri-questionnaire-line"></i> {{ $qzCount }}
                                </a>
                                @endif
                                @if($asCount > 0)
                                <a href="/courses/{{ $e->course->slug }}/materials#assignments" class="text-xs text-amber-600 hover:underline">
                                    <i class="ri-file-list-3-line"></i> {{ $asCount }}
                                </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-sm text-heading/50">No courses enrolled yet.</p>
        @endforelse
    </div>
</div>
@endsection