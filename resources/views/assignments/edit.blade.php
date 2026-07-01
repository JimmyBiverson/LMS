@extends('layouts.dashboard')
@section('title', 'Edit Assignment')
@section('page-title', 'Edit Assignment')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="space-y-6 max-w-2xl">
    {{-- Settings Form --}}
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-heading">Edit Assignment</h3>
        </div>
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
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Schedule & Release --}}
    <x-schedule-section
        :model="$assignment"
        action="/instructor/assignments/{{ $assignment->id }}"
        label="assignment"
        :fields="[
            'title' => $assignment->title,
            'description' => $assignment->description,
            'instructions' => $assignment->instructions,
            'due_date' => $assignment->due_date?->format('Y-m-d'),
            'total_marks' => $assignment->total_marks,
            'status' => $assignment->status,
            'time_limit_minutes' => $assignment->time_limit_minutes,
            'max_file_size_mb' => $assignment->max_file_size_mb,
            'late_submission_allowed' => $assignment->late_submission_allowed ? '1' : '0',
            'late_penalty_percent' => $assignment->late_penalty_percent,
        ]"
    />

    {{-- Publish / Done Section --}}
    <div class="bg-white rounded-xl shadow-sm border-t-4 {{ $assignment->status === 'published' ? 'border-green-500' : 'border-amber-500' }}">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        @if($assignment->status === 'published')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Published</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Draft</span>
                        @endif
                        <span class="text-xs text-heading/50 bg-gray-100 px-2 py-1 rounded-full">{{ $assignment->submissions_count ?? 0 }} submissions</span>
                        <span class="text-xs text-heading/50 bg-gray-100 px-2 py-1 rounded-full">{{ $assignment->total_marks }} total marks</span>
                    </div>
                    @if($assignment->status === 'published')
                        <p class="text-sm text-green-700 font-medium">
                            <i class="ri-check-double-line mr-1"></i>This assignment is live and visible to students.
                        </p>
                    @else
                        <p class="text-sm text-amber-700 font-medium">
                            <i class="ri-eye-off-line mr-1"></i>Students cannot see this assignment yet.
                        </p>
                    @endif
                </div>
                <div class="flex gap-3 shrink-0">
                    @if($assignment->status === 'draft')
                    <form method="POST" action="/instructor/assignments/{{ $assignment->id }}" onsubmit="return confirm('Publish this assignment? Students will be able to see and submit it.');">
                        @csrf
                        <input type="hidden" name="title" value="{{ $assignment->title }}">
                        <input type="hidden" name="description" value="{{ $assignment->description }}">
                        <input type="hidden" name="instructions" value="{{ $assignment->instructions }}">
                        <input type="hidden" name="due_date" value="{{ $assignment->due_date?->format('Y-m-d') }}">
                        <input type="hidden" name="total_marks" value="{{ $assignment->total_marks }}">
                        <input type="hidden" name="status" value="published">
                        @if($assignment->time_limit_minutes) <input type="hidden" name="time_limit_minutes" value="{{ $assignment->time_limit_minutes }}"> @endif
                        @if($assignment->max_file_size_mb) <input type="hidden" name="max_file_size_mb" value="{{ $assignment->max_file_size_mb }}"> @endif
                        @if($assignment->late_submission_allowed) <input type="hidden" name="late_submission_allowed" value="1"> @endif
                        @if($assignment->late_penalty_percent) <input type="hidden" name="late_penalty_percent" value="{{ $assignment->late_penalty_percent }}"> @endif
                        <button type="submit" class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 text-base">
                            <i class="ri-rocket-line"></i> Publish Assignment
                        </button>
                    </form>
                    @endif
                    @if($assignment->status === 'published')
                    <form method="POST" action="/instructor/assignments/{{ $assignment->id }}" onsubmit="return confirm('Set this assignment back to draft? Students will lose access.');">
                        @csrf
                        <input type="hidden" name="title" value="{{ $assignment->title }}">
                        <input type="hidden" name="description" value="{{ $assignment->description }}">
                        <input type="hidden" name="instructions" value="{{ $assignment->instructions }}">
                        <input type="hidden" name="due_date" value="{{ $assignment->due_date?->format('Y-m-d') }}">
                        <input type="hidden" name="total_marks" value="{{ $assignment->total_marks }}">
                        <input type="hidden" name="status" value="draft">
                        @if($assignment->time_limit_minutes) <input type="hidden" name="time_limit_minutes" value="{{ $assignment->time_limit_minutes }}"> @endif
                        @if($assignment->max_file_size_mb) <input type="hidden" name="max_file_size_mb" value="{{ $assignment->max_file_size_mb }}"> @endif
                        @if($assignment->late_submission_allowed) <input type="hidden" name="late_submission_allowed" value="1"> @endif
                        @if($assignment->late_penalty_percent) <input type="hidden" name="late_penalty_percent" value="{{ $assignment->late_penalty_percent }}"> @endif
                        <button type="submit" class="px-6 py-3 bg-amber-100 text-amber-800 font-bold rounded-lg hover:bg-amber-200 transition-colors flex items-center gap-2 text-sm">
                            <i class="ri-pencil-line"></i> Set to Draft
                        </button>
                    </form>
                    @endif
                    @if($assignment->status === 'draft')
                    <form method="POST" action="/instructor/assignments/{{ $assignment->id }}" onsubmit="return confirm('Save as draft and continue editing?');">
                        @csrf
                        <input type="hidden" name="title" value="{{ $assignment->title }}">
                        <input type="hidden" name="description" value="{{ $assignment->description }}">
                        <input type="hidden" name="instructions" value="{{ $assignment->instructions }}">
                        <input type="hidden" name="due_date" value="{{ $assignment->due_date?->format('Y-m-d') }}">
                        <input type="hidden" name="total_marks" value="{{ $assignment->total_marks }}">
                        <input type="hidden" name="status" value="draft">
                        @if($assignment->time_limit_minutes) <input type="hidden" name="time_limit_minutes" value="{{ $assignment->time_limit_minutes }}"> @endif
                        @if($assignment->max_file_size_mb) <input type="hidden" name="max_file_size_mb" value="{{ $assignment->max_file_size_mb }}"> @endif
                        @if($assignment->late_submission_allowed) <input type="hidden" name="late_submission_allowed" value="1"> @endif
                        @if($assignment->late_penalty_percent) <input type="hidden" name="late_penalty_percent" value="{{ $assignment->late_penalty_percent }}"> @endif
                        <button type="submit" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2 text-sm">
                            <i class="ri-save-line"></i> Save Draft
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
