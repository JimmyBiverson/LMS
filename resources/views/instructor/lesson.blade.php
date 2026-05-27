@extends('layouts.dashboard')
@section('title', 'Lessons')
@section('page-title', 'Lessons for: ' . $course->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="mb-6">
    <a href="{{ route('instructor.dashboard.courses.edit', $course->id) }}" class="text-sm text-primary hover:underline">&larr; Back to Course</a>
</div>

@if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
                <h3 class="font-bold text-heading">Lessons ({{ $course->lessons->count() }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                        <th class="text-left py-4 px-6 font-semibold">#</th>
                        <th class="text-left py-4 px-6 font-semibold">Lesson</th>
                        <th class="text-left py-4 px-6 font-semibold">Duration</th>
                        <th class="text-left py-4 px-6 font-semibold">Preview</th>
                        <th class="text-right py-4 px-6 font-semibold">Actions</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($course->lessons->sortBy('order') as $lesson)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 text-heading/70">{{ $lesson->order }}</td>
                            <td class="py-4 px-6 font-semibold text-heading">{{ $lesson->title }}</td>
                            <td class="py-4 px-6 text-heading/70">{{ $lesson->duration ?? '--' }}</td>
                            <td class="py-4 px-6">@if($lesson->is_free_preview)<span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">Free</span>@else<span class="text-xs text-heading/40">No</span>@endif</td>
                            <td class="py-4 px-6 text-right">
                                <form method="POST" action="{{ route('instructor.dashboard.courses.lessons.delete', [$course->id, $lesson->id]) }}" class="inline" onsubmit="return confirm('Delete this lesson?')">
                                    @csrf
                                    <button class="px-3 py-1 text-xs font-semibold rounded-full border border-red-200 text-red-500 hover:bg-red-50 transition-all">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-8 text-center text-heading/40 text-sm">No lessons yet. Add your first lesson!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-heading mb-4">Add Lesson</h3>
            <form method="POST" action="{{ route('instructor.dashboard.courses.lessons', $course->id) }}" class="space-y-4">
                @csrf
                <div><label class="block text-sm font-semibold text-heading mb-1">Title *</label><input name="title" type="text" value="{{ old('title') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Content</label><textarea name="content" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">{{ old('content') }}</textarea></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Video URL</label><input name="video_url" type="url" value="{{ old('video_url') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="https://..."></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Duration</label><input name="duration" type="text" value="{{ old('duration') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="e.g. 15 min"></div>
                <div><label class="flex items-center gap-2"><input name="is_free_preview" type="checkbox" value="1" class="w-5 h-5 rounded border-heading/20 text-primary" @checked(old('is_free_preview'))><span class="text-sm text-heading font-semibold">Free preview</span></label></div>
                <button type="submit" class="w-full px-6 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Add Lesson</button>
            </form>
        </div>
    </div>
</div>
@endsection
