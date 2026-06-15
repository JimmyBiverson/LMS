@extends('layouts.dashboard')
@section('title', 'Create Quiz')
@section('page-title', 'Create Quiz for ' . $course->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-heading flex items-center gap-2">
                <i class="ri-file-quiz-line text-primary text-xl"></i>
                Create New Quiz
            </h3>
            <p class="text-sm text-heading/60 mt-1">{{ $course->title }}</p>
        </div>
        <div class="p-6">
            <form method="POST" action="/instructor/courses/{{ $course->id }}/quizzes" class="space-y-5" enctype="multipart/form-data">
                @csrf
                
                {{-- Basic Info --}}
                <div class="space-y-4">
                    <h4 class="font-semibold text-heading text-sm flex items-center gap-2">
                        <i class="ri-information-line text-blue-500"></i> Basic Information
                    </h4>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Quiz Title *</label>
                        <input type="text" name="title" required value="{{ old('title') }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="e.g., Chapter 5 Assessment">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Instructions (Text)</label>
                        <textarea name="instructions" rows="3" value="{{ old('instructions') }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Add instructions for students taking this quiz..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Instructions File (PDF, Word, etc.)</label>
                        <input type="file" name="instructions_file" accept=".pdf,.doc,.docx,.txt" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm">
                        <p class="text-xs text-heading/50 mt-1">Upload a document with quiz instructions or questions</p>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="border-t border-gray-100 pt-4 space-y-4">
                    <h4 class="font-semibold text-heading text-sm flex items-center gap-2">
                        <i class="ri-settings-line text-blue-500"></i> Quiz Settings
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Time Limit (minutes)</label>
                            <input type="number" name="time_limit" min="1" value="{{ old('time_limit') }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="e.g., 30">
                            <p class="text-xs text-heading/50 mt-1">Leave empty for no time limit</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Passing Score (%) *</label>
                            <input type="number" name="passing_score" value="{{ old('passing_score', '50') }}" min="0" max="100" required class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            @error('passing_score') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Attempts Limit</label>
                            <input type="number" name="attempts_limit" min="1" value="{{ old('attempts_limit') }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="e.g., 3">
                            <p class="text-xs text-heading/50 mt-1">Leave empty for unlimited</p>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="border-t border-gray-100 pt-4 space-y-4">
                    <h4 class="font-semibold text-heading text-sm flex items-center gap-2">
                        <i class="ri-checkbox-circle-line text-blue-500"></i> Status
                    </h4>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="radio" name="status" value="draft" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary rounded">
                            <span class="text-sm font-semibold text-heading">Draft</span>
                            <span class="text-xs text-heading/50">(Students cannot see)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="radio" name="status" value="published" {{ old('status') === 'published' ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary rounded">
                            <span class="text-sm font-semibold text-heading">Published</span>
                            <span class="text-xs text-heading/50">(Students can access)</span>
                        </label>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="border-t border-gray-100 pt-4 flex gap-3">
                    <button type="submit" class="px-6 py-3 bg-primary text-white font-bold rounded-lg hover:opacity-90 transition-all text-sm flex items-center gap-2">
                        <i class="ri-add-line"></i> Create Quiz
                    </button>
                    <a href="/instructor/courses/{{ $course->id }}/lessons" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all text-sm">Cancel</a>
                </div>

                @if($errors->any())
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h4 class="font-semibold text-blue-900 text-sm mb-2 flex items-center gap-2">
            <i class="ri-lightbulb-flash-line"></i> Tip
        </h4>
        <p class="text-sm text-blue-800">After creating the quiz, you'll be able to add various types of questions including multiple choice, true/false, essays, matching pairs, and more. You can edit the quiz settings and questions anytime before publishing.</p>
    </div>
</div>
@endsection
