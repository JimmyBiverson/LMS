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
                <h3 class="font-bold text-heading">Quiz Settings</h3>
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
                        <input type="number" name="passing_score" value="{{ $quiz->passing_score }}" min="0" max="100" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-1">Attempts Limit</label>
                        <input type="number" name="attempts_limit" value="{{ $quiz->attempts_limit }}" min="1" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm" placeholder="Unlimited">
                    </div>
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
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary/50 transition-all group">
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
</div>

@push('scripts')
<script>
function updateQuestionTypeUI() {
    const type = document.getElementById('questionType').value;
    const optionsDiv = document.getElementById('optionsDiv');
    const blanksDiv = document.getElementById('blanksDiv');
    const itemsDiv = document.getElementById('itemsDiv');
    const matchingDiv = document.getElementById('matchingDiv');
    const correctAnswerDiv = document.getElementById('correctAnswerDiv');

    // Hide all by default
    optionsDiv.classList.add('hidden');
    blanksDiv.classList.add('hidden');
    itemsDiv.classList.add('hidden');
    matchingDiv.classList.add('hidden');
    correctAnswerDiv.classList.add('hidden');

    // Show relevant fields based on type
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
            // No additional fields
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
    alert('Edit functionality coming soon!');
}

// Initialize UI
document.addEventListener('DOMContentLoaded', function() {
    updateQuestionTypeUI();
});
</script>
@endpush
@endsection
