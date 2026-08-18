@extends('layouts.app')
@section('title', $quiz->title)
@section('content')
<section class="py-12 lg:py-16">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-extrabold text-heading mb-2">{{ $quiz->title }}</h1>
            @if($quiz->instructions)<p class="text-heading/70 mb-4">{{ $quiz->instructions }}</p>@endif
            @if($quiz->instructions_file)
            <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-heading text-sm mb-2 flex items-center gap-2">
                    <i class="ri-file-pdf-line text-primary"></i> Quiz Instructions Document
                </h4>
                <a href="{{ asset('storage/' . $quiz->instructions_file) }}" target="_blank" class="text-primary hover:underline text-sm font-semibold">Download Instructions</a>
            </div>
            @endif
            <div class="flex gap-4 text-sm text-heading/60" x-data="{ timeLeft: {{ $quiz->time_limit ? $quiz->time_limit * 60 : 0 }}, timerRunning: {{ $quiz->time_limit ? 'true' : 'false' }} }" x-init="if (timerRunning) { setInterval(() => { if (timeLeft > 0) timeLeft--; if (timeLeft <= 0 && timerRunning) { timerRunning = false; document.getElementById('quiz-form').requestSubmit(); } }, 1000); }">
                <span x-show="!timerRunning"><i class="ri-time-line mr-1"></i>No limit</span>
                <span x-show="timerRunning"><i class="ri-time-line mr-1"></i><span x-text="Math.floor(timeLeft / 60) + ':' + (timeLeft % 60).toString().padStart(2, '0')"></span> remaining</span>
                <span><i class="ri-question-line mr-1"></i>{{ $quiz->questions->count() }} questions</span>
                <span><i class="ri-award-line mr-1"></i>Pass: {{ $quiz->passing_score }}%</span>
            </div>
        </div>
        <form id="quiz-form" method="POST" action="/dashboard/quizzes/{{ $quiz->id }}/submit">
            @csrf
            <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
            
            {{-- Server-side expires_at for real-time client timer --}}
            <div class="hidden" x-init="
                if (typeof timeLeft !== 'undefined' && timerRunning) {
                    const serverExpires = '{{ $attempt->expires_at?->timestamp ?? 0 }}';
                    if (serverExpires > 0) {
                        const now = Math.floor(Date.now() / 1000);
                        const serverTimeLeft = Math.max(0, Math.floor((parseInt(serverExpires) - now) / 1000));
                        if (serverTimeLeft < timeLeft) timeLeft = serverTimeLeft;
                    }
                }
            "></div>
            @foreach($quiz->questions as $i => $q)
            @php($questionOptions = is_array($q->options) ? $q->options : [])
            <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
                <h3 class="font-bold text-heading mb-3">{{ $i+1 }}. {{ $q->question }} <span class="text-xs text-heading/50 font-normal">({{ $q->marks }} mark{{ $q->marks>1?'s':'' }})</span></h3>
                <div class="space-y-3">
                    @if(in_array($q->type, ['multiple_choice', 'true_false']))
                        @foreach($questionOptions as $opt)
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-primary cursor-pointer transition-colors">
                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="text-primary focus:ring-primary">
                            <span class="text-sm text-heading/80">{{ $opt }}</span>
                        </label>
                        @endforeach
                    @elseif($q->type === 'multiple_select')
                        @foreach($questionOptions as $opt)
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-primary cursor-pointer transition-colors">
                            <input type="checkbox" name="answers[{{ $q->id }}][]" value="{{ $opt }}" class="text-primary focus:ring-primary">
                            <span class="text-sm text-heading/80">{{ $opt }}</span>
                        </label>
                        @endforeach
                    @elseif($q->type === 'short_answer')
                        <input type="text" name="answers[{{ $q->id }}]" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Your answer">
                    @elseif($q->type === 'essay')
                        <textarea name="answers[{{ $q->id }}]" rows="5" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Write your essay response here..."></textarea>
                    @elseif($q->type === 'fill_in_blank')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($questionOptions as $index => $blank)
                            <label class="space-y-1 text-sm text-heading/80">
                                Blank {{ $index + 1 }}
                                <input type="text" name="answers[{{ $q->id }}][]" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Answer for blank {{ $index + 1 }}">
                            </label>
                            @endforeach
                        </div>
                    @elseif($q->type === 'matching')
                        @foreach($questionOptions as $index => $pair)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-end">
                            <div>
                                <label class="block text-sm font-semibold text-heading mb-1">Match for: {{ is_array($pair) ? ($pair['key'] ?? '') : $pair }}</label>
                                <input type="text" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm bg-gray-50" value="{{ is_array($pair) ? ($pair['key'] ?? '') : $pair }}" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-heading mb-1">Choose match</label>
                                <select name="answers[{{ $q->id }}][{{ $index }}]" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <option value="">Select answer</option>
                                    @foreach($questionOptions as $matchOption)
                                    @php($matchValue = is_array($matchOption) ? ($matchOption['value'] ?? '') : $matchOption)
                                    <option value="{{ $matchValue }}">{{ $matchValue }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach
                    @elseif($q->type === 'ordering')
                        <label class="block text-sm font-semibold text-heading mb-1">Enter the items in correct order, one item per line</label>
                        <textarea name="answers[{{ $q->id }}]" rows="{{ max(3, min(8, count($questionOptions))) }}" class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="{{ implode('\n', $questionOptions) }}"></textarea>
                    @else
                        <p class="text-sm text-heading/70">This question type cannot be answered online.</p>
                    @endif
                </div>
            </div>
            @endforeach
            <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center">Submit Quiz</button>
        </form>
    </div>
</section>
@endsection
