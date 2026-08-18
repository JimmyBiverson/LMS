@php $p = auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isStaff()) ? 'zoom.admin' : 'zoom.instructor'; @endphp
@extends('layouts.dashboard')

@section('title', 'Schedule a Zoom Class')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route($p.'.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-heading/50 hover:text-primary mb-4">
        <i class="ri-arrow-left-s-line"></i> Back to Classes
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h1 class="text-xl font-bold text-heading">Schedule a Zoom Class</h1>
            <p class="text-sm text-gray-500 mt-1">Students enrolled in the selected course will be notified.</p>
        </div>
        <div class="p-6">
            @include('components.zoom-meeting-form', [
                'meeting' => $meeting ?? null,
                'courses' => $courses,
                'lessonsByCourse' => $lessonsByCourse,
                'timezone' => $timezone,
                'isAdmin' => $isAdmin,
                'submitUrl' => $submitUrl,
                'method' => $method,
                'cancelUrl' => $cancelUrl,
            ])
        </div>
    </div>
</div>
@endsection
