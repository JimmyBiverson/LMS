@extends('layouts.dashboard')
@section('title', 'Edit Quiz')
@section('page-title', 'Edit Quiz: ' . $quiz->title)
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="space-y-6">
    {{-- Quiz Settings --}}
    <div class="bg-white rounded-xl shadow-sm max-w-4xl">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-heading">{{ $quiz->is_exam ? 'Exam' : 'Quiz' }} Settings</h3>
                <p class="text-sm text-heading/60 mt-1">Total Marks: <span class="font-semibold text-primary">{{ $quiz->total_marks }}</span></p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $quiz->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($quiz->status) }}</span>
        </div>
        <div class="p-6">
            <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Title</label>
                        <input type="text" name="title" value="{{ $quiz->title }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                            <option value="draft" {{ $quiz->status=='draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $quiz->status=='published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Instructions (Text)</label>
                    <textarea name="instructions" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">{{ $quiz->instructions }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Instructions File (PDF, Word, etc.)</label>
                    @if($quiz->instructions_file)
                        <p class="text-xs text-heading/60 mb-2">Current: <a href="{{ asset('storage/' . $quiz->instructions_file) }}" target="_blank" class="text-primary hover:underline">Download</a></p>
                    @endif
                    <input type="file" name="instructions_file" accept=".pdf,.doc,.docx,.txt" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Time Limit (minutes)</label>
                        <input type="number" name="time_limit" value="{{ $quiz->time_limit }}" min="1" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" placeholder="No limit">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Passing Score (%)</label>
                        <input type="number" name="passing_score" value="{{ $quiz->passing_score }}" min="0" max="100" step="0.01" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Attempts Limit</label>
                        <input type="number" name="attempts_limit" value="{{ $quiz->attempts_limit }}" min="1" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" placeholder="Unlimited">
                    </div>
                </div>
                <div class="flex flex-wrap gap-4 pt-2" x-data="{ isExam: {{ $quiz->is_exam ? 'true' : 'false' }} }">
                    <label class="relative flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all duration-200 min-w-[140px]"
                        :class="!isExam ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                        <input type="radio" name="is_exam" value="0" class="sr-only" x-on:change="isExam = false" {{ $quiz->is_exam ? '' : 'checked' }}>
                        <i class="ri-questionnaire-line text-lg" :class="!isExam ? 'text-blue-600' : 'text-gray-400'"></i>
                        <span class="text-sm font-bold" :class="!isExam ? 'text-blue-700' : 'text-heading'">Quiz</span>
                    </label>
                    <label class="relative flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all duration-200 min-w-[140px]"
                        :class="isExam ? 'border-purple-500 bg-purple-50' : 'border-gray-200'">
                        <input type="radio" name="is_exam" value="1" class="sr-only" x-on:change="isExam = true" {{ $quiz->is_exam ? 'checked' : '' }}>
                        <i class="ri-edit-box-line text-lg" :class="isExam ? 'text-purple-600' : 'text-gray-400'"></i>
                        <span class="text-sm font-bold" :class="isExam ? 'text-purple-700' : 'text-heading'">Exam</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="randomize_options" value="1" {{ $quiz->randomize_options ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="text-sm font-semibold text-heading">Randomize Options</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="show_results_immediately" value="1" {{ $quiz->show_results_immediately ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="text-sm font-semibold text-heading">Show Results Immediately</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="certificate_on_pass" value="1" {{ $quiz->certificate_on_pass ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="text-sm font-semibold text-heading">Issue Certificate on Pass</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="proctoring_required" value="1" {{ $quiz->proctoring_required ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="text-sm font-semibold text-heading">Require Proctoring</span>
                    </label>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Save Settings</button>
                    <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}/delete" class="inline" onsubmit="return confirm('Delete this quiz and all questions?')">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-200 text-sm">Delete Quiz</button>
                    </form>
                </div>
            </form>
        </div>
    </div>

    {{-- Document Question Extraction --}}
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-heading flex items-center gap-2">
                <i class="ri-file-text-line text-primary text-xl"></i>
                Extract Questions from Document
            </h3>
            <p class="text-sm text-heading/60 mt-1">Upload a PDF, Word, or TXT file to automatically extract questions</p>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <input type="file" id="extractDocument" accept=".pdf,.doc,.docx,.txt" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                    <button type="button" onclick="extractQuestions()" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 text-sm flex items-center gap-2">
                        <i class="ri-upload-line"></i> Extract
                    </button>
                </div>
                <div id="extractionStatus" class="text-sm hidden"></div>
                <div id="extractedQuestions" class="hidden space-y-3"></div>
                <div id="bulkImportSection" class="hidden">
                    <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}/bulk-store-questions" id="bulkImportForm">
                        @csrf
                        <input type="hidden" name="questions" id="bulkQuestionsData">
                        <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-sm flex items-center gap-2">
                            <i class="ri-check-double-line"></i> Import All Extracted Questions
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Questions Builder --}}
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-heading flex items-center gap-2">
                <i class="ri-questionnaire-line text-primary text-xl"></i> 
                Add Questions ({{ $quiz->questions()->count() }} added)
            </h3>
            <p class="text-sm text-heading/60 mt-1">Add various types of questions to build your quiz</p>
        </div>

        {{-- Question Form --}}
        <div class="p-6 border-b border-gray-100 bg-blue-50/50">
            <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}/questions" id="questionForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Question Text</label>
                    <textarea name="question" rows="2" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Enter your question here..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Question Type</label>
                        <select name="type" id="questionType" onchange="updateQuestionTypeUI()" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                            <option value="multiple_choice">Multiple Choice (Single Answer)</option>
                            <option value="multiple_select">Multiple Select (Multiple Answers)</option>
                            <option value="true_false">True/False</option>
                            <option value="short_answer">Short Answer</option>
                            <option value="essay">Essay</option>
                            <option value="fill_in_blank">Fill in the Blank</option>
                            <option value="matching">Matching</option>
                            <option value="ordering">Ordering/Sequence</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Marks</label>
                        <input type="number" name="marks" value="1" min="1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                    </div>
                </div>

                {{-- Multiple Choice / Multiple Select Options --}}
                <div id="optionsDiv" class="space-y-2">
                    <label class="block text-sm font-semibold text-heading mb-1">Options (one per line)</label>
                    <textarea name="options" rows="4" placeholder="Option A&#10;Option B&#10;Option C&#10;Option D" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"></textarea>
                </div>

                {{-- Fill in the Blank --}}
                <div id="blanksDiv" class="hidden space-y-2">
                    <label class="block text-sm font-semibold text-heading mb-1">Blanks (correct answers, one per line)</label>
                    <textarea name="blanks" rows="3" placeholder="Blank 1 answer&#10;Blank 2 answer" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
                </div>

                {{-- Ordering Items --}}
                <div id="itemsDiv" class="hidden space-y-2">
                    <label class="block text-sm font-semibold text-heading mb-1">Items to Order (one per line, in correct order)</label>
                    <textarea name="items" rows="4" placeholder="First item&#10;Second item&#10;Third item" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
                </div>

                {{-- Matching Pairs --}}
                <div id="matchingDiv" class="hidden space-y-3">
                    <label class="block text-sm font-semibold text-heading mb-1">Matching Pairs (minimum 2)</label>
                    <div id="pairsContainer" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="pairs[0][key]" placeholder="Left item" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                            <input type="text" name="pairs[0][value]" placeholder="Right item" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                            <button type="button" onclick="removePair(this)" class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg"><i class="ri-delete-bin-line"></i></button>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" name="pairs[1][key]" placeholder="Left item" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                            <input type="text" name="pairs[1][value]" placeholder="Right item" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                            <button type="button" onclick="removePair(this)" class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg"><i class="ri-delete-bin-line"></i></button>
                        </div>
                    </div>
                    <button type="button" onclick="addPair()" class="text-sm text-primary font-semibold hover:underline">+ Add Pair</button>
                </div>

                {{-- Correct Answer --}}
                <div id="correctAnswerDiv" class="space-y-2">
                    <label class="block text-sm font-semibold text-heading mb-1">Correct Answer</label>
                    <input type="text" name="correct_answer" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" placeholder="Must match one option exactly">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm flex items-center gap-2">
                        <i class="ri-add-line"></i> Add Question
                    </button>
                    <button type="reset" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 text-sm">Clear</button>
                </div>
            </form>
        </div>

        {{-- Questions List --}}
        <div class="p-6">
            @if($quiz->questions->count() > 0)
                <div class="space-y-3">
                    @foreach($quiz->questions as $index => $q)
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary/50 transition-all group"
                        data-question-id="{{ $q->id }}"
                        data-question="{{ $q->question }}"
                        data-type="{{ $q->type }}"
                        data-marks="{{ $q->marks }}"
                        data-correct-answer="{{ $q->correct_answer }}"
                        data-options='{{ json_encode($q->options ?? []) }}'>
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-primary/10 text-primary text-xs font-bold rounded">Q{{ $index + 1 }}</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">{{ str_replace('_', ' ', ucfirst($q->type)) }}</span>
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded">{{ $q->marks }} pts</span>
                                </div>
                                <p class="font-semibold text-heading text-sm mb-2">{{ $q->question }}</p>
                                @if($q->type == 'multiple_choice' || $q->type == 'multiple_select')
                                    <p class="text-xs text-heading/60"><strong>Options:</strong> {{ implode(', ', $q->options ?? []) }}</p>
                                @elseif($q->type == 'true_false')
                                    <p class="text-xs text-heading/60"><strong>Correct:</strong> {{ $q->correct_answer }}</p>
                                @elseif($q->type == 'matching')
                                    <p class="text-xs text-heading/60"><strong>Pairs:</strong> {{ count($q->options ?? []) }} matching pairs</p>
                                @elseif($q->type == 'fill_in_blank')
                                    <p class="text-xs text-heading/60"><strong>Blanks:</strong> {{ implode(', ', $q->options ?? []) }}</p>
                                @elseif($q->type == 'ordering')
                                    <p class="text-xs text-heading/60"><strong>Items:</strong> {{ count($q->options ?? []) }} items to order</p>
                                @elseif($q->type == 'short_answer' || $q->type == 'essay')
                                    <p class="text-xs text-heading/60"><strong>Type:</strong> Free response</p>
                                @endif
                            </div>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="editQuestion({{ $q->id }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg" title="Edit">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <form method="POST" action="/instructor/quizzes/questions/{{ $q->id }}/delete" class="inline" onsubmit="return confirm('Delete this question?')">
                                    @csrf
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="ri-questionnaire-line text-4xl text-gray-300 mb-2 block"></i>
                    <p class="text-sm text-heading/50">No questions yet. Add your first question above to get started.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Schedule & Release Section --}}
    <x-schedule-section
        :model="$quiz"
        action="/instructor/quizzes/{{ $quiz->id }}"
        :label="$quiz->is_exam ? 'exam' : 'quiz'"
        :showResults="true"
        :fields="[
            'title' => $quiz->title,
            'instructions' => $quiz->instructions,
            'time_limit' => $quiz->time_limit,
            'passing_score' => $quiz->passing_score,
            'attempts_limit' => $quiz->attempts_limit,
            'status' => $quiz->status,
            'randomize_options' => $quiz->randomize_options ? '1' : null,
            'show_results_immediately' => $quiz->show_results_immediately ? '1' : null,
            'certificate_on_pass' => $quiz->certificate_on_pass ? '1' : null,
            'proctoring_required' => $quiz->proctoring_required ? '1' : null,
            'is_exam' => $quiz->is_exam ? '1' : null,
        ]"
    />

    {{-- Publish / Done Section --}}
    <div class="bg-white rounded-xl shadow-sm border-t-4 {{ $quiz->status === 'published' ? 'border-green-500' : 'border-amber-500' }}">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        @if($quiz->status === 'published')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Published</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Draft</span>
                        @endif
                        <span class="text-xs text-heading/50 bg-gray-100 px-2 py-1 rounded-full">{{ $quiz->questions->count() }} questions</span>
                        <span class="text-xs text-heading/50 bg-gray-100 px-2 py-1 rounded-full">{{ $quiz->total_marks }} total marks</span>
                    </div>
                    @if($quiz->status === 'published')
                        <p class="text-sm text-green-700 font-medium">
                            <i class="ri-check-double-line mr-1"></i>This {{ $quiz->is_exam ? 'exam' : 'quiz' }} is live and visible to students.
                        </p>
                    @else
                        <p class="text-sm text-amber-700 font-medium">
                            <i class="ri-eye-off-line mr-1"></i>Students cannot see this {{ $quiz->is_exam ? 'exam' : 'quiz' }} yet.
                        </p>
                        @if($quiz->questions->count() === 0)
                        <p class="text-xs text-heading/50 mt-1">Add at least one question before publishing.</p>
                        @endif
                    @endif
                </div>
                <div class="flex gap-3 shrink-0">
                    @if($quiz->status === 'draft' && $quiz->questions->count() > 0)
                    <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}" onsubmit="return confirm('Publish this {{ $quiz->is_exam ? 'exam' : 'quiz' }}? Students will be able to see and attempt it.');">
                        @csrf
                        <input type="hidden" name="title" value="{{ $quiz->title }}">
                        <input type="hidden" name="instructions" value="{{ $quiz->instructions }}">
                        <input type="hidden" name="time_limit" value="{{ $quiz->time_limit }}">
                        <input type="hidden" name="passing_score" value="{{ $quiz->passing_score }}">
                        <input type="hidden" name="attempts_limit" value="{{ $quiz->attempts_limit }}">
                        <input type="hidden" name="status" value="published">
                        @if($quiz->randomize_options) <input type="hidden" name="randomize_options" value="1"> @endif
                        @if($quiz->show_results_immediately) <input type="hidden" name="show_results_immediately" value="1"> @endif
                        @if($quiz->certificate_on_pass) <input type="hidden" name="certificate_on_pass" value="1"> @endif
                        @if($quiz->proctoring_required) <input type="hidden" name="proctoring_required" value="1"> @endif
                        @if($quiz->is_exam) <input type="hidden" name="is_exam" value="1"> @endif
                        <button type="submit" class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 text-base">
                            <i class="ri-rocket-line"></i> Publish {{ $quiz->is_exam ? 'Exam' : 'Quiz' }}
                        </button>
                    </form>
                    @endif
                    @if($quiz->status === 'published')
                    <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}" onsubmit="return confirm('Set this {{ $quiz->is_exam ? 'exam' : 'quiz' }} back to draft? Students will lose access.');">
                        @csrf
                        <input type="hidden" name="title" value="{{ $quiz->title }}">
                        <input type="hidden" name="instructions" value="{{ $quiz->instructions }}">
                        <input type="hidden" name="time_limit" value="{{ $quiz->time_limit }}">
                        <input type="hidden" name="passing_score" value="{{ $quiz->passing_score }}">
                        <input type="hidden" name="attempts_limit" value="{{ $quiz->attempts_limit }}">
                        <input type="hidden" name="status" value="draft">
                        @if($quiz->randomize_options) <input type="hidden" name="randomize_options" value="1"> @endif
                        @if($quiz->show_results_immediately) <input type="hidden" name="show_results_immediately" value="1"> @endif
                        @if($quiz->certificate_on_pass) <input type="hidden" name="certificate_on_pass" value="1"> @endif
                        @if($quiz->proctoring_required) <input type="hidden" name="proctoring_required" value="1"> @endif
                        @if($quiz->is_exam) <input type="hidden" name="is_exam" value="1"> @endif
                        <button type="submit" class="px-6 py-3 bg-amber-100 text-amber-800 font-bold rounded-lg hover:bg-amber-200 transition-colors flex items-center gap-2 text-sm">
                            <i class="ri-pencil-line"></i> Set to Draft
                        </button>
                    </form>
                    @endif
                    @if($quiz->status === 'draft' && $quiz->questions->count() > 0)
                    <form method="POST" action="/instructor/quizzes/{{ $quiz->id }}" onsubmit="return confirm('Save as draft and continue editing?');">
                        @csrf
                        <input type="hidden" name="title" value="{{ $quiz->title }}">
                        <input type="hidden" name="instructions" value="{{ $quiz->instructions }}">
                        <input type="hidden" name="time_limit" value="{{ $quiz->time_limit }}">
                        <input type="hidden" name="passing_score" value="{{ $quiz->passing_score }}">
                        <input type="hidden" name="attempts_limit" value="{{ $quiz->attempts_limit }}">
                        <input type="hidden" name="status" value="draft">
                        @if($quiz->randomize_options) <input type="hidden" name="randomize_options" value="1"> @endif
                        @if($quiz->show_results_immediately) <input type="hidden" name="show_results_immediately" value="1"> @endif
                        @if($quiz->certificate_on_pass) <input type="hidden" name="certificate_on_pass" value="1"> @endif
                        @if($quiz->proctoring_required) <input type="hidden" name="proctoring_required" value="1"> @endif
                        @if($quiz->is_exam) <input type="hidden" name="is_exam" value="1"> @endif
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

{{-- Edit Question Modal --}}
<div id="editQuestionModal" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center p-4" onclick="if(event.target===this)closeEditModal()">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading text-lg">Edit Question</h3>
            <button onclick="closeEditModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors"><i class="ri-close-line text-xl"></i></button>
        </div>
        <form method="POST" action="" id="editQuestionForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="editQuestionId" name="question_id">
            <div>
                <label class="block text-sm font-semibold text-heading mb-1">Question Text</label>
                <textarea id="editQuestionText" name="question" rows="2" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Type</label>
                    <select id="editQuestionType" name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-100" disabled>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="multiple_select">Multiple Select</option>
                        <option value="true_false">True/False</option>
                        <option value="short_answer">Short Answer</option>
                        <option value="essay">Essay</option>
                        <option value="fill_in_blank">Fill in the Blank</option>
                        <option value="matching">Matching</option>
                        <option value="ordering">Ordering</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Marks</label>
                    <input type="number" id="editQuestionMarks" name="marks" min="1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                </div>
            </div>
            <div id="editOptionsField" class="hidden space-y-2">
                <label class="block text-sm font-semibold text-heading mb-1">Options (one per line)</label>
                <textarea id="editOptions" name="options" rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            </div>
            <div id="editBlanksField" class="hidden space-y-2">
                <label class="block text-sm font-semibold text-heading mb-1">Blank Answers (one per line)</label>
                <textarea id="editBlanks" name="options" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            </div>
            <div id="editItemsField" class="hidden space-y-2">
                <label class="block text-sm font-semibold text-heading mb-1">Ordered Items (one per line, correct order)</label>
                <textarea id="editItems" name="options" rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            </div>
            <div id="editMatchingField" class="hidden space-y-2">
                <label class="block text-sm font-semibold text-heading mb-1">Matching Pairs (key=value per line)</label>
                <textarea id="editMatching" name="options" rows="4" placeholder="Left=Right&#10;Key=Value" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm"></textarea>
            </div>
            <div id="editCorrectAnswerDiv">
                <label class="block text-sm font-semibold text-heading mb-1">Correct Answer</label>
                <input type="text" id="editCorrectAnswer" name="correct_answer" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Update Question</button>
                <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('editQuestionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('editQuestionId').value;
    this.action = '/instructor/quizzes/questions/' + id + '/update';
    this.submit();
});
</script>
@endsection

@push('scripts')
<script>
function escapeHtml(text) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(text));
    return d.innerHTML;
}

let extractedQuestions = [];

function extractQuestions() {
    const fileInput = document.getElementById('extractDocument');
    const status = document.getElementById('extractionStatus');

    if (!fileInput.files.length) {
        status.className = 'text-sm text-red-600';
        status.textContent = 'Please select a file first.';
        status.classList.remove('hidden');
        return;
    }

    const formData = new FormData();
    formData.append('document', fileInput.files[0]);
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    formData.append('_token', token || '{{ csrf_token() }}');

    status.className = 'text-sm text-blue-600';
    status.textContent = 'Extracting questions...';
    status.classList.remove('hidden');

    fetch('/instructor/quizzes/{{ $quiz->id }}/extract-questions', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': token || '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            status.className = 'text-sm text-red-600';
            status.textContent = data.error || 'Extraction failed.';
            return;
        }
        status.className = 'text-sm text-green-600';
        status.textContent = data.count + ' questions extracted! Review them below.';
        extractedQuestions = data.questions;

        const container = document.getElementById('extractedQuestions');
        container.innerHTML = '';
        container.classList.remove('hidden');

        data.questions.forEach((q, i) => {
            const div = document.createElement('div');
            div.className = 'p-4 bg-green-50 rounded-lg border border-green-200';
            const typeLabel = q.type.replace(/_/g, ' ');
            const optionsText = q.options && q.options.length ? q.options.join(', ') : '';
            const answerText = q.correct_answer ? escapeHtml(q.correct_answer) : '';
            div.innerHTML = `
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">Q${i+1}</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">${typeLabel}</span>
                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded">${q.marks} pt${q.marks > 1 ? 's' : ''}</span>
                </div>
                <p class="font-semibold text-heading text-sm">${escapeHtml(q.question)}</p>
                ${optionsText ? `<p class="text-xs text-heading/60 mt-1"><strong>Options:</strong> ${escapeHtml(optionsText)}</p>` : ''}
                ${answerText ? `<p class="text-xs text-green-700 mt-1"><strong>Answer:</strong> ${answerText}</p>` : ''}
            `;
            container.appendChild(div);
        });

        document.getElementById('bulkImportSection').classList.remove('hidden');
    })
    .catch(() => {
        status.className = 'text-sm text-red-600';
        status.textContent = 'Network error during extraction.';
    });
}

document.getElementById('bulkImportForm')?.addEventListener('submit', function() {
    document.getElementById('bulkQuestionsData').value = JSON.stringify(extractedQuestions);
});

function updateQuestionTypeUI() {
    const type = document.getElementById('questionType').value;
    const optionsDiv = document.getElementById('optionsDiv');
    const blanksDiv = document.getElementById('blanksDiv');
    const itemsDiv = document.getElementById('itemsDiv');
    const matchingDiv = document.getElementById('matchingDiv');
    const correctAnswerDiv = document.getElementById('correctAnswerDiv');

    optionsDiv.classList.add('hidden');
    blanksDiv.classList.add('hidden');
    itemsDiv.classList.add('hidden');
    matchingDiv.classList.add('hidden');
    correctAnswerDiv.classList.add('hidden');

    switch(type) {
        case 'multiple_choice':
        case 'multiple_select':
            optionsDiv.classList.remove('hidden');
            correctAnswerDiv.classList.remove('hidden');
            break;
        case 'true_false':
            correctAnswerDiv.classList.remove('hidden');
            break;
        case 'short_answer':
            correctAnswerDiv.classList.remove('hidden');
            break;
        case 'essay':
            break;
        case 'fill_in_blank':
            blanksDiv.classList.remove('hidden');
            correctAnswerDiv.classList.remove('hidden');
            break;
        case 'matching':
            matchingDiv.classList.remove('hidden');
            break;
        case 'ordering':
            itemsDiv.classList.remove('hidden');
            break;
    }
}

function addPair() {
    const container = document.getElementById('pairsContainer');
    const index = container.children.length;
    const pairHTML = `
        <div class="flex gap-2">
            <input type="text" name="pairs[${index}][key]" placeholder="Left item" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <input type="text" name="pairs[${index}][value]" placeholder="Right item" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
            <button type="button" onclick="removePair(this)" class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg"><i class="ri-delete-bin-line"></i></button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', pairHTML);
}

function removePair(btn) {
    btn.closest('.flex').remove();
}

function editQuestion(id) {
    const container = document.querySelector(`[data-question-id="${id}"]`);
    if (!container) return;

    const questionText = container.dataset.question;
    const type = container.dataset.type;
    const marks = container.dataset.marks;
    const correctAnswer = container.dataset.correctAnswer;
    const optionsRaw = container.dataset.options;

    document.getElementById('editQuestionId').value = id;
    document.getElementById('editQuestionText').value = questionText;
    document.getElementById('editQuestionType').value = type;
    document.getElementById('editQuestionMarks').value = marks;
    document.getElementById('editCorrectAnswer').value = correctAnswer;

    const optionsField = document.getElementById('editOptionsField');
    const blanksField = document.getElementById('editBlanksField');
    const itemsField = document.getElementById('editItemsField');
    const matchingField = document.getElementById('editMatchingField');
    const editCorrectDiv = document.getElementById('editCorrectAnswerDiv');

    optionsField.classList.add('hidden');
    blanksField.classList.add('hidden');
    itemsField.classList.add('hidden');
    matchingField.classList.add('hidden');
    editCorrectDiv.classList.remove('hidden');

    const options = optionsRaw ? (typeof optionsRaw === 'string' ? optionsRaw.split(',') : optionsRaw) : [];

    switch(type) {
        case 'multiple_choice':
        case 'multiple_select':
            optionsField.classList.remove('hidden');
            document.getElementById('editOptions').value = options.join('\n');
            break;
        case 'true_false':
            break;
        case 'short_answer':
            break;
        case 'essay':
            editCorrectDiv.classList.add('hidden');
            break;
        case 'fill_in_blank':
            blanksField.classList.remove('hidden');
            document.getElementById('editBlanks').value = options.join('\n');
            break;
        case 'matching':
            matchingField.classList.remove('hidden');
            break;
        case 'ordering':
            itemsField.classList.remove('hidden');
            document.getElementById('editItems').value = options.join('\n');
            break;
    }

    document.getElementById('editQuestionModal').classList.remove('hidden');
    document.getElementById('editQuestionModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editQuestionModal').classList.add('hidden');
    document.getElementById('editQuestionModal').classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', function() {
    updateQuestionTypeUI();
});
</script>
@endpush
