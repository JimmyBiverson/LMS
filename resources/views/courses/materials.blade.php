@extends('layouts.app')

@section('title', 'Course Materials - ' . $course->title)

@section('content')
<section class="bg-gradient-to-br from-primary/5 to-secondary/5 py-10 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-2 text-sm text-heading/60 mb-3">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/courses" class="hover:text-primary transition-colors">Courses</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/courses/{{ $course->slug }}" class="hover:text-primary transition-colors">{{ $course->title }}</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Materials</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-heading">{{ $course->title }}</h1>
                <p class="text-heading/60 mt-1">Course Materials Hub</p>
            </div>
        </div>
    </div>
</section>

<section class="py-10">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Progress & Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-10">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-heading/60">Progress</span>
                    <span class="text-2xl font-extrabold text-primary">{{ $progressPercent }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="bg-primary h-full rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                </div>
                <p class="text-xs text-heading/50 mt-2">{{ $completedLessons }} of {{ $totalLessons }} lessons completed</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                        <i class="ri-edit-box-line text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-heading">{{ $exams->count() }}</p>
                        <p class="text-xs text-heading/60">Exams</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i class="ri-questionnaire-line text-primary"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-heading">{{ $quizzes->count() }}</p>
                        <p class="text-xs text-heading/60">Quizzes</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i class="ri-file-list-3-line text-primary"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-heading">{{ $assignments->count() }}</p>
                        <p class="text-xs text-heading/60">Assignments</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                        <i class="ri-award-line text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-heading">{{ $certificate ? 1 : 0 }}</p>
                        <p class="text-xs text-heading/60">Certificates Earned</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Announcements Banner --}}
        @if($announcements->isNotEmpty())
        <div class="mb-8">
            @foreach($announcements as $ann)
            <div class="bg-gradient-to-r from-primary-50 to-secondary-50 rounded-xl border border-primary-100 p-5 mb-3">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-white shadow-sm flex items-center justify-center shrink-0 mt-0.5">
                        <i class="ri-megaphone-line text-primary"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-bold text-heading text-sm">{{ $ann->title }}</p>
                            <span class="text-xs text-heading/50">{{ $ann->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-heading/70">{{ $ann->body }}</p>
                        <p class="text-xs text-heading/40 mt-1">— {{ $ann->user?->name ?? 'Instructor' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                {{-- Quizzes Section --}}
                    {{-- Exams Section --}}
                @if($exams->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-heading flex items-center gap-2">
                            <i class="ri-edit-box-line text-purple-600"></i> Exams
                        </h3>
                        <span class="text-xs text-heading/60 bg-gray-100 px-2 py-1 rounded-full">{{ $exams->count() }} total</span>
                    </div>
                    <div class="p-5">
                        @foreach($exams as $exam)
                        @php
                            $examResult = \App\Models\QuizResult::where('quiz_id', $exam->id)
                                ->where('user_id', auth()->id())->first();
                            $eStatusColor = 'bg-gray-50 border-gray-200';
                            $eIconColor = 'text-gray-400';
                            $eBtnHtml = '<a href="/dashboard/exams/' . $exam->id . '/instructions" class="px-4 py-2 text-sm font-semibold rounded-lg bg-purple-600 text-white hover:opacity-90 transition-opacity"><i class="ri-play-fill"></i> Start Exam</a>';
                            if ($examResult) {
                                $eStatusColor = $examResult->passed ? 'bg-green-50 border-green-200' : 'bg-amber-50 border-amber-200';
                                $eIconColor = $examResult->passed ? 'text-green-600' : 'text-amber-600';
                                $eBtnHtml = '<a href="/dashboard/quizzes/my-result" class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-200 text-heading/70 hover:border-primary hover:text-primary transition-colors"><i class="ri-history-line"></i> View Result (' . $examResult->score . '/' . $examResult->total_marks . ')</a>';
                            } elseif (!$exam->isAvailable()) {
                                $eBtnHtml = $exam->availabilityBadge();
                            }
                        @endphp
                        <div class="rounded-xl border p-4 mb-3 flex items-center justify-between {{ $eStatusColor }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm">
                                    <i class="ri-edit-box-line text-lg {{ $eIconColor }}"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-heading text-sm">{{ $exam->title }}</p>
                                    <p class="text-xs text-heading/60">
                                        {{ $exam->questions_count }} questions
                                        @if($exam->time_limit) &middot; {{ $exam->time_limit }} min @endif
                                        &middot; Pass: {{ $exam->passing_score ?? 'N/A' }}%
                                    </p>
                                </div>
                            </div>
                            <div>{!! $eBtnHtml !!}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Quizzes Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-heading flex items-center gap-2">
                            <i class="ri-questionnaire-line text-primary"></i> Course Quizzes
                        </h3>
                        <span class="text-xs text-heading/60 bg-gray-100 px-2 py-1 rounded-full">{{ $quizzes->count() }} total</span>
                    </div>
                    <div class="p-5">
                        @forelse($quizzes as $quiz)
                        @php
                            $result = $quizResults->get($quiz->id);
                            $statusColor = 'bg-gray-50 border-gray-200';
                            $iconColor = 'text-gray-400';
                            $btnHtml = '<a href="/dashboard/quizzes/' . $quiz->id . '/instructions" class="px-4 py-2 text-sm font-semibold rounded-lg bg-primary text-white hover:opacity-90 transition-opacity"><i class="ri-play-fill"></i> Take Quiz</a>';
                            if ($result) {
                                $statusColor = $result->passed ? 'bg-green-50 border-green-200' : 'bg-amber-50 border-amber-200';
                                $iconColor = $result->passed ? 'text-green-600' : 'text-amber-600';
                                $btnHtml = '<a href="/dashboard/quizzes/my-result" class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-200 text-heading/70 hover:border-primary hover:text-primary transition-colors"><i class="ri-history-line"></i> View Result (' . $result->score . '/' . $result->total_marks . ')</a>';
                            } elseif (!$quiz->isAvailable()) {
                                $btnHtml = $quiz->availabilityBadge();
                            }
                        @endphp
                        <div class="rounded-xl border p-4 mb-3 flex items-center justify-between {{ $statusColor }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm">
                                    <i class="ri-questionnaire-line text-lg {{ $iconColor }}"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-heading text-sm">{{ $quiz->title }}</p>
                                    <p class="text-xs text-heading/60">
                                        {{ $quiz->questions_count }} questions
                                        @if($quiz->time_limit) &middot; {{ $quiz->time_limit }} min @endif
                                        &middot; Pass: {{ $quiz->passing_score ?? 'N/A' }}%
                                    </p>
                                </div>
                            </div>
                            <div>{!! $btnHtml !!}</div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-heading/40">
                            <i class="ri-questionnaire-line text-3xl block mb-2"></i>
                            <p class="font-semibold">No quizzes yet</p>
                            <p class="text-sm mt-1">Quizzes will appear here once the instructor publishes them.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Assignments Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-heading flex items-center gap-2">
                            <i class="ri-file-list-3-line text-primary"></i> Assignments
                        </h3>
                        <span class="text-xs text-heading/60 bg-gray-100 px-2 py-1 rounded-full">{{ $assignments->count() }} total</span>
                    </div>
                    <div class="p-5">
                        @forelse($assignments as $assignment)
                        @php
                            $submission = $submissions->get($assignment->id);
                            $statusColor = 'bg-gray-50 border-gray-200';
                            $iconColor = 'text-gray-400';
                            $btnHtml = '<a href="/dashboard/assignments/' . $assignment->id . '/submit" class="px-4 py-2 text-sm font-semibold rounded-lg bg-primary text-white hover:opacity-90 transition-opacity"><i class="ri-upload-line"></i> Submit</a>';
                            $dueLabel = '';
                            if (!$submission && !$assignment->isAvailable()) {
                                $btnHtml = $assignment->availabilityBadge();
                            } elseif ($submission) {
                                $isGraded = $submission->score !== null;
                                $statusColor = $isGraded ? 'bg-green-50 border-green-200' : 'bg-amber-50 border-amber-200';
                                $iconColor = $isGraded ? 'text-green-600' : 'text-amber-600';
                                $btnHtml = $isGraded
                                    ? '<span class="px-4 py-2 text-sm font-semibold rounded-lg bg-green-100 text-green-700"><i class="ri-check-line"></i> Graded: ' . $submission->score . '/' . $assignment->total_marks . '</span>'
                                    : '<span class="px-4 py-2 text-sm font-semibold rounded-lg bg-amber-100 text-amber-700"><i class="ri-time-line"></i> Awaiting Grade</span>';
                            } elseif ($assignment->due_date) {
                                $dueLabel = $assignment->due_date->isPast() ? ' <span class="text-red-500">(Overdue!)</span>' : ' <span class="text-heading/50">(Due: ' . $assignment->due_date->format('M d, Y') . ')</span>';
                            }
                        @endphp
                        <div class="rounded-xl border p-4 mb-3 flex items-center justify-between {{ $statusColor }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm">
                                    <i class="ri-file-list-3-line text-lg {{ $iconColor }}"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-heading text-sm">{{ $assignment->title }}</p>
                                    <p class="text-xs text-heading/60">
                                        {{ $assignment->total_marks }} marks{!! $dueLabel !!}
                                    </p>
                                </div>
                            </div>
                            <div>{!! $btnHtml !!}</div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-heading/40">
                            <i class="ri-file-list-3-line text-3xl block mb-2"></i>
                            <p class="font-semibold">No assignments yet</p>
                            <p class="text-sm mt-1">Assignments will appear here once the instructor publishes them.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Certificate Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="font-bold text-heading flex items-center gap-2">
                            <i class="ri-award-line text-primary"></i> Certificate
                        </h3>
                    </div>
                    <div class="p-5">
                        @if($certificate)
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-3">
                                <i class="ri-award-fill text-3xl text-green-600"></i>
                            </div>
                            <p class="font-bold text-heading mb-1">{{ $certificate->title ?? 'Certificate of Completion' }}</p>
                            <p class="text-xs text-heading/60 mb-4">Congratulations on completing this course!</p>
                            <a href="/dashboard/certificate/{{ $certificate->id }}/download" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors w-full justify-center">
                                <i class="ri-download-line"></i> Download PDF
                            </a>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                <i class="ri-award-line text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-sm text-heading/60">Complete the course requirements to earn your certificate.</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="font-bold text-heading flex items-center gap-2">
                            <i class="ri-links-line text-primary"></i> Quick Links
                        </h3>
                    </div>
                    <div class="p-5 space-y-2">
                        <a href="/courses/{{ $course->slug }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-primary-50 transition-colors text-sm font-semibold text-heading/70 hover:text-primary">
                            <i class="ri-book-open-line text-primary"></i> Course Overview
                        </a>
                        @if($resumeLesson = $course->getResumeLesson())
                        <a href="/courses/{{ $course->slug }}/lessons/{{ $resumeLesson->id }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition-colors text-sm font-semibold text-heading/70 hover:text-green-600">
                            <i class="ri-play-circle-line text-green-500"></i> Resume Learning
                        </a>
                        @endif
                        <a href="/courses/{{ $course->slug }}/discussions" class="flex items-center gap-3 p-3 rounded-lg hover:bg-primary-50 transition-colors text-sm font-semibold text-heading/70 hover:text-primary">
                            <i class="ri-question-answer-line text-primary"></i> Discussions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection