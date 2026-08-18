@extends('layouts.dashboard')
@section('title', 'View Course Note')
@section('page-title', 'View Course Note')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="font-bold text-heading">{{ $courseNote->title }}</h3>
            <p class="text-sm text-heading/60">{{ $courseNote->course?->title ?? 'Course note' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('instructor.dashboard.course-notes.edit', $courseNote) }}" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full">Edit</a>
            <a href="{{ route('instructor.dashboard.course-notes.index') }}" class="px-4 py-2 border border-gray-200 rounded-full">Back</a>
        </div>
    </div>
    <div class="rounded-lg border border-gray-100 p-4 bg-gray-50">
        <p class="text-sm text-heading/60">{{ $courseNote->summary }}</p>
        <div class="mt-3 flex flex-wrap gap-3 text-sm text-heading/70">
            <span class="font-semibold">Status:</span> <span>{{ ucfirst($courseNote->status) }}</span>
            <span class="font-semibold">Downloads:</span> <span>{{ $courseNote->allow_download ? 'Enabled' : 'Disabled' }}</span>
            <span class="font-semibold">Created:</span> <span>{{ $courseNote->created_at->format('M d, Y') }}</span>
        </div>
    </div>
    <div class="prose max-w-none">{!! $courseNote->content !!}</div>
    @if($courseNote->attachment_path)
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="font-semibold text-heading">Attachment</p>
            <a href="{{ route('dashboard.course-notes.download', $courseNote) }}" class="text-primary hover:underline">Download {{ $courseNote->attachment_name }}</a>
        </div>
    @endif
    @if($courseNote->external_link)
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="font-semibold text-heading">External Resource</p>
            <a href="{{ $courseNote->external_link }}" target="_blank" rel="noopener" class="text-primary hover:underline">Open link</a>
        </div>
    @endif
</div>
@endsection
