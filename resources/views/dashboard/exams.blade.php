@extends('layouts.dashboard')
@section('title', 'My Exams')
@section('page-title', 'Exams')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl p-6 text-white shadow-sm">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-blue-100">Assessment</p>
                <h3 class="mt-2 text-2xl font-extrabold">Your exams</h3>
            </div>
            <span class="px-3 py-1.5 rounded-full bg-white/15 text-sm font-semibold">{{ count($exams) }} available</span>
        </div>
    </div>

    @forelse($exams as $exam)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
            <div class="grid lg:grid-cols-[1fr_0.4fr] gap-6 p-6">
                <div>
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                            <i class="ri-file-exam-line text-2xl text-blue-600"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs uppercase tracking-[0.25em] text-blue-600 font-bold">Exam</p>
                            <h4 class="mt-1 text-lg font-extrabold text-heading">{{ $exam->title }}</h4>
                            <p class="text-sm text-heading/70 mt-1">{{ $exam->course->title }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 mt-4 text-sm">
                        <span class="inline-flex items-center gap-1.5 text-heading/60">
                            <i class="ri-time-line text-blue-500"></i>
                            {{ $exam->time_limit ? $exam->time_limit . ' min' : 'No time limit' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-heading/60">
                            <i class="ri-question-line text-purple-500"></i>
                            {{ $exam->questions_count ?? $exam->questions()->count() }} questions
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-heading/60">
                            <i class="ri-target-line text-emerald-500"></i>
                            {{ $exam->passing_score }}% to pass
                        </span>
                    </div>
                </div>
                <div class="flex flex-col justify-center gap-3">
                    @if(!$exam->isAvailable())
                        <div class="px-4 py-3 rounded-lg bg-gray-50 text-center">
                            {!! $exam->availabilityBadge() !!}
                        </div>
                    @else
                        <a href="/dashboard/exams/{{ $exam->id }}/instructions" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="ri-play-circle-line"></i> Start Exam
                        </a>
                        <a href="/dashboard/exams/{{ $exam->id }}/instructions" class="inline-flex items-center justify-center gap-2 px-5 py-3 border border-blue-200 text-blue-700 bg-blue-50 font-semibold rounded-xl hover:bg-blue-100 transition-colors text-sm">
                            <i class="ri-information-line"></i> Instructions
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <i class="ri-file-exam-line text-4xl text-gray-400"></i>
            </div>
            <h4 class="text-xl font-bold text-heading">No exams available</h4>
            <p class="mt-2 text-sm text-heading/60">When instructors publish exams for your enrolled courses, they will appear here.</p>
        </div>
    @endforelse
</div>
@endsection
