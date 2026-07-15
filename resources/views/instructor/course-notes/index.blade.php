@extends('layouts.dashboard')
@section('title', 'Course Notes')
@section('page-title', 'Course Notes')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="font-bold text-heading">Course Notes</h3>
            <p class="text-sm text-heading/60">Manage study materials for your courses</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('instructor.dashboard.course-notes.create') }}" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300"><i class="ri-add-line mr-1"></i> Add New Note</a>
        </div>
    </div>
    <div class="p-6 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notes" class="border border-gray-200 rounded-full px-4 py-2 text-sm flex-1 min-w-[220px]">
            <button class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full">Search</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                    <th class="text-left py-4 px-6 font-semibold">Title</th>
                    <th class="text-left py-4 px-6 font-semibold">Course</th>
                    <th class="text-left py-4 px-6 font-semibold">Status</th>
                    <th class="text-left py-4 px-6 font-semibold">Created</th>
                    <th class="text-right py-4 px-6 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($notes as $note)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-semibold text-heading">{{ $note->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $note->course?->title ?? '—' }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $note->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($note->status) }}</span></td>
                    <td class="py-4 px-6 text-heading/70">{{ $note->created_at->format('M d, Y') }}</td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('instructor.dashboard.course-notes.show', $note) }}" class="px-3 py-1 text-xs font-semibold rounded-full border border-heading/10 hover:bg-primary hover:text-white transition-all">View</a>
                            <a href="{{ route('instructor.dashboard.course-notes.edit', $note) }}" class="px-3 py-1 text-xs font-semibold rounded-full border border-heading/10 hover:bg-primary hover:text-white transition-all">Edit</a>
                            <form action="{{ route('instructor.dashboard.course-notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Delete this note?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 text-xs font-semibold rounded-full border border-red-200 text-red-600 hover:bg-red-600 hover:text-white transition-all">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-heading/40 text-sm">No notes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $notes->links() }}</div>
</div>
@endsection
