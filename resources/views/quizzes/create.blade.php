@extends('layouts.dashboard')
@section('title', 'Create Quiz')
@section('page-title', 'Create Quiz for ' . $course->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm max-w-2xl">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">New Quiz</h3></div>
    <div class="p-6">
        <form method="POST" action="/instructor/courses/{{ $course->id }}/quizzes">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-semibold text-heading mb-1">Title</label><input type="text" name="title" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Instructions</label><textarea name="instructions" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-heading mb-1">Time Limit (minutes)</label><input type="number" name="time_limit" min="1" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Passing Score (%)</label><input type="number" name="passing_score" value="50" min="0" max="100" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                </div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Create Quiz</button>
            </div>
        </form>
    </div>
</div>
@endsection
