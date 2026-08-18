@extends('layouts.app')
@section('title', 'Submit Assignment')
@section('content')
<section class="py-12 lg:py-16">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-extrabold text-heading mb-2">{{ $assignment->title }}</h1>
            <p class="text-heading/70 mb-4">{{ $assignment->description }}</p>
            <div class="flex gap-4 text-sm text-heading/60">
                <span>Due: {{ $assignment->due_date?->format('Y-m-d') ?? 'No deadline' }}</span>
                <span>Marks: {{ $assignment->total_marks }}</span>
            </div>
            @if($assignment->instructions)
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-heading text-sm mb-2">Instructions</h4>
                <p class="text-sm text-heading/70">{{ $assignment->instructions }}</p>
            </div>
            @endif
            @if($assignment->instructions_file)
            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-heading text-sm mb-2 flex items-center gap-2">
                    <i class="ri-file-pdf-line text-primary"></i> Assignment Instructions Document
                </h4>
                <a href="{{ asset('storage/' . $assignment->instructions_file) }}" target="_blank" class="text-primary hover:underline text-sm font-semibold">Download Instructions</a>
            </div>
            @endif
        </div>
        @php
            $deadlinePassed = $assignment->due_date && $assignment->isDeadlinePassed();
        @endphp
        <form method="POST" action="/dashboard/assignments/{{ $assignment->id }}/submit" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            <div class="space-y-4">
                @if($deadlinePassed && !$assignment->late_submission_allowed)
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <div class="flex items-center gap-2 font-semibold"><i class="ri-time-line"></i> Deadline expired</div>
                        <p class="mt-1">This assignment closed on {{ $assignment->due_date->format('F d, Y h:i A') }} and late submissions are not allowed.</p>
                    </div>
                @elseif($deadlinePassed && $assignment->late_submission_allowed)
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                        <div class="flex items-center gap-2 font-semibold"><i class="ri-alert-line"></i> Late submission allowed</div>
                        <p class="mt-1">The deadline has passed, but this assignment accepts late submissions with the configured penalty.</p>
                    </div>
                @endif
                <div><label class="block text-sm font-semibold text-heading mb-1">Submission Text</label><textarea name="submission_text" rows="8" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" placeholder="Write your assignment content here..." {{ $deadlinePassed && !$assignment->late_submission_allowed ? 'disabled' : '' }}></textarea></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Upload File</label><input type="file" name="file" accept=".pdf,.doc,.docx,.txt,.zip" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" {{ $deadlinePassed && !$assignment->late_submission_allowed ? 'disabled' : '' }}></div>
                <p class="text-sm text-heading/60">Provide either a typed submission or upload a file. At least one is required.</p>
                <button type="submit" {{ $deadlinePassed && !$assignment->late_submission_allowed ? 'disabled' : '' }} class="w-full px-8 py-4 {{ $deadlinePassed && !$assignment->late_submission_allowed ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-primary text-white hover:opacity-90' }} font-bold rounded-full transition-all duration-300">
                    {{ $deadlinePassed && !$assignment->late_submission_allowed ? 'Submission Closed' : 'Submit Assignment' }}
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
