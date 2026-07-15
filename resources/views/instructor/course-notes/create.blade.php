@extends('layouts.dashboard')
@section('title', 'Add Course Note')
@section('page-title', 'Add Course Note')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('instructor.dashboard.course-notes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Course</label>
                <select name="course_id" class="w-full border border-gray-200 rounded-lg px-4 py-3" required>
                    <option value="">Select a course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Note Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-200 rounded-lg px-4 py-3" required>
                @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Description / Summary</label>
                <textarea name="summary" rows="4" class="w-full border border-gray-200 rounded-lg px-4 py-3">{{ old('summary') }}</textarea>
                @error('summary')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Display Order</label>
                <input type="number" name="display_order" value="{{ old('display_order', 1) }}" min="1" class="w-full border border-gray-200 rounded-lg px-4 py-3">
                @error('display_order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Status</label>
                <select name="status" class="w-full border border-gray-200 rounded-lg px-4 py-3">
                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Created Date</label>
                <input type="date" name="created_at" value="{{ old('created_at', now()->format('Y-m-d')) }}" class="w-full border border-gray-200 rounded-lg px-4 py-3">
                @error('created_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Attachment</label>
                <input type="file" name="attachment" class="w-full border border-gray-200 rounded-lg px-4 py-3">
                @error('attachment')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">External Resource Link</label>
                <input type="url" name="external_link" value="{{ old('external_link') }}" class="w-full border border-gray-200 rounded-lg px-4 py-3" placeholder="https://example.com">
                @error('external_link')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Allow Downloads</label>
                <select name="allow_download" class="w-full border border-gray-200 rounded-lg px-4 py-3">
                    <option value="1" {{ old('allow_download', 1) == 1 ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('allow_download') == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-heading mb-2">Rich Content</label>
            <textarea name="content" id="content" rows="10" class="w-full border border-gray-200 rounded-lg px-4 py-3">{{ old('content') }}</textarea>
            @error('content')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-5 py-3 bg-primary text-white font-semibold rounded-full">Save Note</button>
            <a href="{{ route('instructor.dashboard.course-notes.index') }}" class="px-5 py-3 border border-gray-200 rounded-full">Cancel</a>
        </div>
    </form>
</div>
@endsection
