@extends('layouts.dashboard')
@section('title', 'Create Assignment')
@section('page-title', 'Create Assignment for ' . $course->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm max-w-2xl">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">New Assignment</h3></div>
    <div class="p-6">
        <form method="POST" action="/instructor/courses/{{ $course->id }}/assignments" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-semibold text-heading mb-1">Title</label><input type="text" name="title" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Description</label><textarea name="description" rows="4" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Instructions (Text)</label><textarea name="instructions" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Instructions File (PDF, Word, etc.)</label><input type="file" name="instructions_file" accept=".pdf,.doc,.docx,.txt" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-heading mb-1">Due Date</label><input type="date" name="due_date" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Total Marks</label><input type="number" name="total_marks" value="100" min="1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                </div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Create Assignment</button>
            </div>
        </form>
    </div>
</div>
@endsection
