@extends('layouts.dashboard')
@section('title', 'Quizzes')
@section('page-title', 'Quizzes for ' . $course->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading">Quizzes &amp; Exams</h3>
        <a href="/instructor/courses/{{ $course->id }}/quizzes/create" class="px-4 py-2 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">+ Add Quiz / Exam</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                    <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Questions</th>
                <th class="text-left py-4 px-6 font-semibold">Time Limit</th>
                <th class="text-left py-4 px-6 font-semibold">Passing Score</th>
                <th class="text-left py-4 px-6 font-semibold">Type</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-right py-4 px-6 font-semibold">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($quizzes as $i=>$q)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $q->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $q->questions_count }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $q->time_limit ? $q->time_limit . ' min' : '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $q->passing_score }}%</td>
                    <td class="py-4 px-6">
                        @if($q->is_exam)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">Exam</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Quiz</span>
                        @endif
                    </td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $q->status=='published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($q->status) }}</span></td>
                    <td class="py-4 px-6 text-right">
                        <a href="/instructor/quizzes/{{ $q->id }}/edit" class="text-primary hover:underline text-xs font-semibold mr-2">Edit</a>
                        <form method="POST" action="/instructor/quizzes/{{ $q->id }}/delete" class="inline" onsubmit="return confirm('Delete this quiz?')">
                            @csrf
                            <button type="submit" class="text-red-500 hover:underline text-xs font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-8 text-center text-heading/50 text-sm">No quizzes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Announcements --}}
<div class="bg-white rounded-xl shadow-sm mt-6">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading flex items-center gap-2"><i class="ri-megaphone-line text-primary"></i> Course Announcements</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="/instructor/courses/{{ $course->id }}/announcements" class="mb-6">
            @csrf
            <input name="title" placeholder="Announcement title..." required maxlength="255"
                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-primary mb-3">
            <textarea name="body" rows="3" placeholder="Write your announcement..." required
                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-primary mb-3"></textarea>
            <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 transition-opacity text-sm">
                <i class="ri-send-plane-line mr-1"></i> Post Announcement
            </button>
        </form>

        @php
            $announcements = \App\Models\Announcement::with('user')->where('course_id', $course->id)->latest()->get();
        @endphp
        @forelse($announcements as $ann)
        <div class="flex items-start gap-3 py-3 border-b border-gray-50 last:border-0">
            <div class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                <i class="ri-megaphone-line text-primary text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-heading text-sm">{{ $ann->title }}</p>
                    <span class="text-xs text-heading/40">{{ $ann->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-heading/70">{{ $ann->body }}</p>
            </div>
            <form method="POST" action="/instructor/announcements/{{ $ann->id }}/delete" onsubmit="return confirm('Delete this announcement?')">
                @csrf
                <button type="submit" class="text-xs text-red-400 hover:text-red-600"><i class="ri-delete-bin-line"></i></button>
            </form>
        </div>
        @empty
        <p class="text-sm text-heading/40 text-center py-4">No announcements yet. Post one above to notify your students!</p>
        @endforelse
    </div>
</div>
@endsection
