@extends('layouts.app')
@section('title', 'My Exams')
@section('content')
<section class="py-12 lg:py-16">
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-3xl font-extrabold text-heading mb-8">My Exams</h1>
        @forelse($exams as $exam)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-4 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-heading">{{ $exam->title }}</h3>
                <p class="text-sm text-heading/60 mt-1">{{ $exam->course->title }}</p>
                <div class="flex gap-3 mt-2 text-xs text-heading/50">
                    <span><i class="ri-time-line mr-1"></i>{{ $exam->time_limit ? $exam->time_limit . ' min' : 'No limit' }}</span>
                    <span><i class="ri-question-line mr-1"></i>{{ $exam->questions_count ?? $exam->questions()->count() }} questions</span>
                    <span><i class="ri-award-line mr-1"></i>{{ $exam->passing_score }}% to pass</span>
                </div>
            </div>
            @if(!$exam->isAvailable())
            {!! $exam->availabilityBadge() !!}
            @else
            <a href="/dashboard/exams/{{ $exam->id }}/instructions" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Start Exam</a>
            @endif
        </div>
        @empty
        <div class="text-center py-12">
            <i class="ri-edit-box-line text-5xl text-gray-300 mb-4 block"></i>
            <p class="text-heading/50 text-sm">No exams available for your enrolled courses.</p>
        </div>
        @endforelse
    </div>
</section>
@endsection
