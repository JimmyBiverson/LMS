@extends('layouts.app')
@section('title', $quiz->title)
@section('content')
<section class="py-12 lg:py-16">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-extrabold text-heading mb-2">{{ $quiz->title }}</h1>
            @if($quiz->instructions)<p class="text-heading/70 mb-4">{{ $quiz->instructions }}</p>@endif
            <div class="flex gap-4 text-sm text-heading/60">
                <span><i class="ri-time-line mr-1"></i>{{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'No limit' }}</span>
                <span><i class="ri-question-line mr-1"></i>{{ $quiz->questions->count() }} questions</span>
                <span><i class="ri-award-line mr-1"></i>Pass: {{ $quiz->passing_score }}%</span>
            </div>
        </div>
        <form method="POST" action="/dashboard/quizzes/{{ $quiz->id }}/submit">
            @csrf
            @foreach($quiz->questions as $i => $q)
            <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
                <h3 class="font-bold text-heading mb-3">{{ $i+1 }}. {{ $q->question }} <span class="text-xs text-heading/50 font-normal">({{ $q->marks }} mark{{ $q->marks>1?'s':'' }})</span></h3>
                <div class="space-y-2">
                    @foreach($q->options as $opt)
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-primary cursor-pointer transition-colors">
                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="text-primary focus:ring-primary">
                        <span class="text-sm text-heading/80">{{ $opt }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
            <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center">Submit Quiz</button>
        </form>
    </div>
</section>
@endsection
