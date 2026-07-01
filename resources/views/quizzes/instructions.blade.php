@extends('layouts.app')
@section('title', $quiz->title . ' - Instructions')
@section('content')
<section class="py-12 lg:py-16">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm p-8">
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-xl {{ $quiz->is_exam ? 'bg-purple-100' : 'bg-blue-100' }} flex items-center justify-center shrink-0">
                    <i class="ri-{{ $quiz->is_exam ? 'edit-box' : 'questionnaire' }}-line text-2xl {{ $quiz->is_exam ? 'text-purple-600' : 'text-blue-600' }}"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-heading">{{ $quiz->title }}</h1>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold {{ $quiz->is_exam ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">{{ $quiz->is_exam ? 'Exam' : 'Quiz' }}</span>
                </div>
            </div>

            {{-- Instructions --}}
            @if($quiz->instructions)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h4 class="text-sm font-bold text-heading mb-2 flex items-center gap-2">
                    <i class="ri-information-line text-primary"></i> Instructions
                </h4>
                <p class="text-sm text-heading/80 whitespace-pre-wrap">{{ $quiz->instructions }}</p>
            </div>
            @endif

            @if($quiz->instructions_file)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="text-sm font-bold text-heading mb-2 flex items-center gap-2">
                    <i class="ri-file-pdf-line text-primary"></i> Instructions Document
                </h4>
                <a href="{{ asset('storage/' . $quiz->instructions_file) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-300 rounded-lg text-sm font-semibold text-primary hover:bg-blue-100 transition-colors">
                    <i class="ri-download-line"></i> Download Instructions File
                </a>
            </div>
            @endif

            {{-- Meta Information --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <i class="ri-time-line text-lg text-heading/40 block mb-1"></i>
                    <p class="text-lg font-bold text-heading">{{ $quiz->time_limit ?? '--' }}</p>
                    <p class="text-xs text-heading/50">Time Limit (min)</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <i class="ri-question-line text-lg text-heading/40 block mb-1"></i>
                    <p class="text-lg font-bold text-heading">{{ $quiz->questions->count() }}</p>
                    <p class="text-xs text-heading/50">Questions</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <i class="ri-award-line text-lg text-heading/40 block mb-1"></i>
                    <p class="text-lg font-bold text-heading">{{ $quiz->passing_score }}%</p>
                    <p class="text-xs text-heading/50">Passing Score</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <i class="ri-refresh-line text-lg text-heading/40 block mb-1"></i>
                    <p class="text-lg font-bold text-heading">{{ $attemptsInfo['remaining'] }}</p>
                    <p class="text-xs text-heading/50">Attempt{{ $attemptsInfo['limit'] !== 1 ? 's' : '' }} Remaining</p>
                </div>
            </div>

            {{-- Attempts Warning --}}
            @if($attemptsInfo['limit'] !== null && $attemptsInfo['remaining'] === 0)
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-700 font-semibold flex items-center gap-2">
                    <i class="ri-error-warning-line"></i>
                    You have used all {{ $attemptsInfo['limit'] }} allowed attempt{{ $attemptsInfo['limit'] !== 1 ? 's' : '' }} for this {{ $quiz->is_exam ? 'exam' : 'quiz' }}.
                </p>
            </div>
            @endif

            {{-- Start Button --}}
            @if($attemptsInfo['remaining'] > 0)
            <form method="GET" action="/dashboard/{{ $quiz->is_exam ? 'exams' : 'quizzes' }}/{{ $quiz->id }}/take">
                @csrf
                <button type="submit" class="w-full px-8 py-4 {{ $quiz->is_exam ? 'bg-purple-600 hover:bg-purple-700' : 'bg-primary hover:opacity-90' }} text-white font-bold rounded-full transition-all duration-300 text-center flex items-center justify-center gap-2 text-lg">
                    <i class="ri-play-circle-line"></i>
                    Start {{ $quiz->is_exam ? 'Exam' : 'Quiz' }}
                </button>
            </form>
            <p class="text-xs text-heading/40 text-center mt-2">Once started, the timer will begin and cannot be paused.</p>
            @endif

            {{-- Back Link --}}
            <div class="mt-6 text-center">
                <a href="{{ url()->previous() }}" class="text-sm text-heading/50 hover:text-primary transition-colors">
                    <i class="ri-arrow-left-line mr-1"></i> Go Back
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
