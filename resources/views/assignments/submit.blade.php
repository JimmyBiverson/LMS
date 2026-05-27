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
        </div>
        <form method="POST" action="/dashboard/assignments/{{ $assignment->id }}/submit" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-semibold text-heading mb-1">Your Submission</label><textarea name="submission_text" rows="8" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" placeholder="Write your assignment content here..."></textarea></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">File URL (optional)</label><input type="url" name="file_url" placeholder="https://example.com/my-file.pdf" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Submit Assignment</button>
            </div>
        </form>
    </div>
</section>
@endsection
