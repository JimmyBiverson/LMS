@extends('layouts.dashboard')
@section('title', 'Edit Assignment')
@section('page-title', 'Edit Assignment')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm max-w-2xl">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Edit Assignment</h3></div>
    <div class="p-6">
        <form method="POST" action="/instructor/assignments/{{ $assignment->id }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-sm font-semibold text-heading mb-1">Title</label><input type="text" name="title" value="{{ $assignment->title }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Description</label><textarea name="description" rows="4" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">{{ $assignment->description }}</textarea></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Instructions (Text)</label><textarea name="instructions" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">{{ $assignment->instructions }}</textarea></div>
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Instructions File (PDF, Word, etc.)</label>
                    @if($assignment->instructions_file)
                        <p class="text-xs text-heading/60 mb-2">Current: <a href="{{ asset('storage/' . $assignment->instructions_file) }}" target="_blank" class="text-primary hover:underline">Download</a></p>
                    @endif
                    <input type="file" name="instructions_file" accept=".pdf,.doc,.docx,.txt" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-heading mb-1">Due Date</label><input type="date" name="due_date" value="{{ $assignment->due_date?->format('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Total Marks</label><input type="number" name="total_marks" value="{{ $assignment->total_marks }}" min="1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                </div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                        <option value="draft" {{ $assignment->status=='draft'?'selected':'' }}>Draft</option>
                        <option value="published" {{ $assignment->status=='published'?'selected':'' }}>Published</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
