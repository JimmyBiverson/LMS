@extends('layouts.dashboard')
@section('title', 'Edit Quiz')
@section('page-title', 'Edit Quiz: ' . $quiz->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm max-w-2xl">
        <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Quiz Settings</h3></div>
        <div class="p-6">
            <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}">
                @csrf
                <div class="space-y-4">
                    <div><label class="block text-sm font-semibold text-heading mb-1">Title</label><input type="text" name="title" value="{{ $quiz->title }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Instructions</label><textarea name="instructions" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">{{ $quiz->instructions }}</textarea></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-semibold text-heading mb-1">Time Limit (minutes)</label><input type="number" name="time_limit" value="{{ $quiz->time_limit }}" min="1" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                        <div><label class="block text-sm font-semibold text-heading mb-1">Passing Score (%)</label><input type="number" name="passing_score" value="{{ $quiz->passing_score }}" min="0" max="100" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                    </div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                            <option value="draft" {{ $quiz->status=='draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $quiz->status=='published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Update Quiz</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading">Questions ({{ $quiz->total_marks }} total marks)</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}/questions" class="space-y-4 border-b border-gray-100 pb-6 mb-6">
                @csrf
                <div><label class="block text-sm font-semibold text-heading mb-1">Question</label><textarea name="question" rows="2" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-heading mb-1">Type</label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                        </select>
                    </div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Marks</label><input type="number" name="marks" value="1" min="1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                </div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Options (one per line)</label><textarea name="options[]" rows="4" required placeholder="Option A&#10;Option B&#10;Option C&#10;Option D" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Correct Answer</label><input type="text" name="correct_answer" required placeholder="Must match one option exactly" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></div>
                <button type="submit" class="px-4 py-2 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add Question</button>
            </form>
            <div class="space-y-4">
                @forelse($quiz->questions as $q)
                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <p class="font-semibold text-heading text-sm">{{ $q->question }}</p>
                        <p class="text-xs text-heading/60 mt-1">Type: {{ str_replace('_',' ',$q->type) }} | Marks: {{ $q->marks }} | Correct: {{ $q->correct_answer }}</p>
                    </div>
                    <form method="POST" action="/instructor/quizzes/questions/{{ $q->id }}/delete" onsubmit="return confirm('Delete this question?')">
                        @csrf
                        <button type="submit" class="text-red-500 hover:underline text-xs font-semibold">Delete</button>
                    </form>
                </div>
                @empty
                <p class="text-sm text-heading/50 text-center py-4">No questions yet. Add your first question above.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
