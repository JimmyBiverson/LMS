@extends('layouts.dashboard')
@section('title', 'Course Notes')
@section('page-title', 'Course Notes')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">My Course Notes</h3>
        <p class="text-sm text-heading/60">Published materials for your enrolled courses</p>
    </div>
    <div class="p-6 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notes" class="border border-gray-200 rounded-full px-4 py-2 text-sm flex-1 min-w-[220px]">
            <button class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full">Search</button>
        </form>
    </div>
    <div class="p-6 space-y-4">
        @forelse($notes as $note)
            <div class="border border-gray-100 rounded-xl p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h4 class="font-semibold text-heading">{{ $note->title }}</h4>
                        <p class="text-sm text-heading/60">{{ $note->course?->title ?? 'Course note' }}</p>
                    </div>
                    <a href="{{ route('dashboard.course-notes.show', $note) }}" class="px-4 py-2 text-sm font-semibold rounded-full bg-primary text-white">View</a>
                </div>
                @if($note->summary)
                    <p class="text-sm text-heading/70 mt-3">{{ $note->summary }}</p>
                @endif
            </div>
        @empty
            <div class="text-center py-8 text-heading/50">No published notes are available for your enrolled courses yet.</div>
        @endforelse
    </div>
    <div class="p-6">{{ $notes->links() }}</div>
</div>
@endsection
