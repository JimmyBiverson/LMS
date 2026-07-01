@extends('layouts.dashboard')
@section('title', 'Notification Preferences')
@section('page-title', 'Notification Preferences')
@section('user-name', auth()->user()->name ?? 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6">Notification Preferences</h3>
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
        @endif
        <form method="POST" action="/notifications/preferences" class="space-y-4">
            @csrf
            @php
                $types = [
                    'course_enrolled' => 'Course Enrollment',
                    'lesson_completed' => 'Lesson Completed',
                    'course_completed' => 'Course Completed',
                    'quiz_result' => 'Quiz Result',
                    'assignment_graded' => 'Assignment Graded',
                    'discussion_reply' => 'Discussion Reply',
                ];
            @endphp
            @foreach ($types as $key => $label)
            @php $pref = $preferences->get($key); @endphp
            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-lg">
                <span class="font-semibold text-heading text-sm">{{ $label }}</span>
                <select name="{{ $key }}" class="px-3 py-2 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                    <option value="in_app" {{ $pref && $pref->channel === 'in_app' ? 'selected' : '' }}>In-App</option>
                    <option value="email" {{ $pref && $pref->channel === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="both" {{ $pref && $pref->channel === 'both' ? 'selected' : '' }}>Both</option>
                    <option value="none" {{ !$pref || !$pref->enabled ? 'selected' : '' }}>None</option>
                </select>
            </div>
            @endforeach
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Save Preferences</button>
        </form>
    </div>
</div>
@endsection