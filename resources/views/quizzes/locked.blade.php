@extends('layouts.dashboard')
@section('title', 'Not Yet Available')
@section('page-title', 'Not Yet Available')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
@php
    $item = $quiz ?? $assignment ?? null;
    $label = 'item';
    if ($quiz) $label = $quiz->is_exam ? 'exam' : 'quiz';
    elseif ($assignment) $label = 'assignment';
@endphp
<div class="max-w-lg mx-auto mt-12">
    <div class="bg-white rounded-xl shadow-sm p-8 text-center">
        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ri-lock-line text-2xl text-amber-600"></i>
        </div>
        <h3 class="text-xl font-bold text-heading mb-2">Not Yet Available</h3>
        @if($item && $item->available_from)
            <p class="text-heading/60 text-sm mb-4">
                This {{ $label }} will be available on
                <span class="font-semibold text-heading">{{ $item->available_from->format('F j, Y \a\t g:i A') }}</span>.
            </p>
            @if($item->available_from->diffInDays(now()) <= 7 && $item->available_from->isFuture())
            <div x-data="{
                target: new Date('{{ $item->available_from->toIso8601String() }}').getTime(),
                now: new Date().getTime(),
                get diff() { return Math.max(0, this.target - this.now); },
                get days() { return Math.floor(this.diff / 86400000); },
                get hours() { return Math.floor((this.diff % 86400000) / 3600000); },
                get minutes() { return Math.floor((this.diff % 3600000) / 60000); },
                get seconds() { return Math.floor((this.diff % 60000) / 1000); },
                init() {
                    setInterval(() => { this.now = new Date().getTime(); }, 1000);
                }
            }" class="flex justify-center gap-4 mb-4">
                <div class="text-center">
                    <span class="text-2xl font-bold text-primary" x-text="days"></span>
                    <p class="text-xs text-heading/50">Days</p>
                </div>
                <div class="text-center">
                    <span class="text-2xl font-bold text-primary" x-text="hours.toString().padStart(2, '0')"></span>
                    <p class="text-xs text-heading/50">Hours</p>
                </div>
                <div class="text-center">
                    <span class="text-2xl font-bold text-primary" x-text="minutes.toString().padStart(2, '0')"></span>
                    <p class="text-xs text-heading/50">Minutes</p>
                </div>
                <div class="text-center">
                    <span class="text-2xl font-bold text-primary" x-text="seconds.toString().padStart(2, '0')"></span>
                    <p class="text-xs text-heading/50">Seconds</p>
                </div>
            </div>
            @endif
        @else
            <p class="text-heading/60 text-sm mb-4">This {{ $label }} is not yet available.</p>
        @endif
        <a href="{{ url()->previous() }}" class="inline-block px-6 py-2.5 bg-gray-100 text-heading font-semibold rounded-lg hover:bg-gray-200 text-sm transition-colors">
            <i class="ri-arrow-left-line mr-1"></i> Go Back
        </a>
    </div>
</div>
@endsection
