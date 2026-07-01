@extends('layouts.app')

@section('title', 'Course Details')

@section('content')
{{-- Breadcrumb --}}
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">{{ $course->title }}</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/courses" class="hover:text-primary transition-colors">Courses</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">{{ $course->title }}</span>
        </div>
    </div>
</section>

{{-- Course Detail --}}
<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-10">
            {{-- Main Content --}}
            <div class="flex-1">
                <div class="flex items-center gap-1 text-amber-400 text-sm mb-3">
                    @for($s=1;$s<=5;$s++)<i class="{{ $s <= round($courseAvgRating) ? 'ri-star-fill' : 'ri-star-line' }}"></i>@endfor
                    <span class="text-heading/60 ml-1">({{ $reviewCount }} {{ $reviewCount === 1 ? 'Review' : 'Reviews' }})</span>
                </div>
                <h1 class="text-3xl lg:text-4xl font-extrabold text-heading mb-4">{{ $course->title }}</h1>
                <p class="text-heading/70 leading-relaxed mb-6">
                    {{ $course->description }}
                </p>

                <div class="flex items-center gap-4 mb-8">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center">
                                <i class="ri-user-smile-line text-primary"></i>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-heading">{{ $course->instructor?->name ?? 'Instructor' }}</span>
                            </div>
                        </div>
                    <span class="px-3 py-1 rounded-full bg-primary-50 text-primary text-xs font-bold">{{ $course->category }}</span>
                </div>

                {{-- Tabs --}}
                <div x-data="{ activeTab: 'overview' }">
                    <div class="border-b border-gray-200 mb-8">
                        <nav class="flex gap-8 overflow-x-auto flex-nowrap">
                            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-primary text-primary' : 'border-transparent text-heading/60'" class="pb-3 border-b-2 font-bold text-sm whitespace-nowrap hover:text-primary transition-colors">Course Overview</button>
                            <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'border-primary text-primary' : 'border-transparent text-heading/60'" class="pb-3 border-b-2 font-semibold text-sm whitespace-nowrap hover:text-primary transition-colors">Curriculum</button>
                            <button @click="activeTab = 'instructor'" :class="activeTab === 'instructor' ? 'border-primary text-primary' : 'border-transparent text-heading/60'" class="pb-3 border-b-2 font-semibold text-sm whitespace-nowrap hover:text-primary transition-colors">Instructor</button>
                            <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'border-primary text-primary' : 'border-transparent text-heading/60'" class="pb-3 border-b-2 font-semibold text-sm whitespace-nowrap hover:text-primary transition-colors">Reviews</button>
                            @auth @if($isEnrolled)
                            <button @click="activeTab = 'materials'" :class="activeTab === 'materials' ? 'border-primary text-primary' : 'border-transparent text-heading/60'" class="pb-3 border-b-2 font-semibold text-sm whitespace-nowrap hover:text-primary transition-colors flex items-center gap-1">
                                <i class="ri-tools-line"></i> Course Materials
                            </button>
                            @endif @endauth
                        </nav>
                    </div>

                    <div>
                        {{-- Overview Tab --}}
                        <div x-show="activeTab === 'overview'" x-cloak>
                            <h2 class="text-xl font-bold text-heading mb-4">Course Overview</h2>
                            <p class="text-heading/70 leading-relaxed mb-6">
                                {{ $course->description }}
                            </p>

                            @if($course->outcomes)
                            <h3 class="text-lg font-bold text-heading mb-3">Learning Outcomes</h3>
                            <ul class="space-y-2 mb-6">
                                @foreach(explode("\n", $course->outcomes) as $outcome)
                                @continue(trim($outcome) === '')
                                <li class="flex items-start gap-2 text-heading/70">
                                    <i class="ri-checkbox-circle-fill text-primary mt-1"></i>
                                    {{ trim($outcome) }}
                                </li>
                                @endforeach
                            </ul>
                            @endif

                            @if($course->requirements)
                            <h3 class="text-lg font-bold text-heading mb-3">Course Requirements</h3>
                            <ul class="space-y-2 mb-6">
                                @foreach(explode("\n", $course->requirements) as $req)
                                @continue(trim($req) === '')
                                <li class="flex items-start gap-2 text-heading/70">
                                    <i class="ri-checkbox-circle-fill text-primary mt-1"></i>
                                    {{ trim($req) }}
                                </li>
                                @endforeach
                            </ul>
                            @endif

                            <h3 class="text-lg font-bold text-heading mb-3">Course FAQs</h3>
                            <div class="space-y-3 mb-8">
                                @forelse($faqs as $i => $faq)
                                <details class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                                    <summary class="font-bold text-heading cursor-pointer flex items-center justify-between">
                                        {{ $i + 1 }}. {{ $faq->question }}
                                        <i class="ri-arrow-down-s-line text-primary"></i>
                                    </summary>
                                    <p class="mt-3 text-heading/70">{{ $faq->answer }}</p>
                                </details>
                                @empty
                                <p class="text-sm text-heading/60">No FAQs available for this course.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Curriculum Tab --}}
                        <div x-show="activeTab === 'curriculum'" x-cloak>
                            <h3 class="text-lg font-bold text-heading mb-4">Course Curriculum</h3>
                            @forelse($course->lessons->sortBy('order') as $lesson)
                            @php $lessonCompleted = $lessonCompletionMap[$lesson->id] ?? false; @endphp
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-3 overflow-hidden">
                                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @auth
                                        @if($isEnrolled)
                                        <form method="POST" action="/lessons/{{ $lesson->id }}/toggle-completion" class="inline">
                                            @csrf
                                            <button type="submit" class="flex items-center justify-center w-5 h-5 rounded border-2 {{ $lessonCompleted ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 hover:border-primary' }} transition-colors">
                                                @if($lessonCompleted)<i class="ri-check-line text-xs font-bold"></i>@endif
                                            </button>
                                        </form>
                                        @endif
                                        @endauth
                                        <a href="/courses/{{ $course->slug }}/lessons/{{ $lesson->id }}" class="font-bold text-heading text-sm hover:text-primary transition-colors flex items-center gap-2 {{ $lessonCompleted ? 'text-green-600' : '' }}">
                                            @if($lesson->video_file || $lesson->video_url)
                                                <i class="ri-play-circle-line text-lg text-primary"></i>
                                            @elseif($lesson->document_file)
                                                <i class="ri-file-text-line text-lg text-primary flex-shrink-0"></i>
                                            @else
                                                <i class="ri-book-open-line text-lg text-primary"></i>
                                            @endif
                                            {{ $lesson->title }}
                                        </a>
                                    </div>
                                    <span class="text-xs text-heading/60">{{ $lesson->duration ?? '--' }}</span>
                                </div>
                                @if($lesson->content)
                                <div class="p-4">
                                    <p class="text-sm text-heading/70">{{ $lesson->content }}</p>
                                </div>
                                @endif
                                @if($lesson->is_free_preview)
                                <div class="p-4 bg-green-50 border-t border-green-100 flex items-center justify-between">
                                    <a href="/courses/{{ $course->slug }}/lessons/{{ $lesson->id }}" class="text-sm text-primary font-semibold hover:underline flex items-center gap-2">
                                        <i class="ri-play-circle-fill text-lg"></i> Free Preview Lesson
                                    </a>
                                    <span class="text-xs bg-green-100 text-green-800 px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">Preview</span>
                                </div>
                                @endif
                            </div>
                            @empty
                            <p class="text-sm text-heading/60">Curriculum is being developed.</p>
                            @endforelse
                        </div>

                        {{-- Instructor Tab --}}
                        <div x-show="activeTab === 'instructor'" x-cloak>
                            <h3 class="text-lg font-bold text-heading mb-4">Course Instructor</h3>
                            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-8">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-16 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                                        <i class="ri-user-smile-line text-2xl text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-heading">{{ $course->instructor?->name ?? 'Instructor' }}</h4>
                                        <p class="text-sm text-primary font-semibold mb-2">{{ $course->instructor?->designation ?? 'Instructor' }}</p>
                                        <p class="text-sm text-heading/60">{{ $course->instructor?->bio ?? 'No biography available.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Course Materials Tab --}}
                        <div x-show="activeTab === 'materials'" x-cloak>
                            <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2">
                                <i class="ri-tools-line text-primary"></i> Course Materials
                            </h3>

                            {{-- Announcements --}}
                            @if($announcements->isNotEmpty())
                            <div class="mb-6">
                                @foreach($announcements as $ann)
                                <div class="bg-gradient-to-r from-primary-50 to-secondary-50 rounded-xl border border-primary-100 p-4 mb-2">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center shrink-0">
                                            <i class="ri-megaphone-line text-primary text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-heading text-sm">{{ $ann->title }}</p>
                                                <span class="text-xs text-heading/50">{{ $ann->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-heading/70 mt-0.5">{{ $ann->body }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Exams Section --}}
                            @if($courseExams->isNotEmpty())
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-heading mb-4 flex items-center gap-2">
                                    <i class="ri-edit-box-line text-purple-600"></i> Exams
                                </h4>
                                @foreach($courseExams as $exam)
                                @php
                                    $examAttempted = $exam->user_result_exists > 0;
                                    $examUserResult = $examAttempted ? \App\Models\QuizResult::where('quiz_id', $exam->id)->where('user_id', auth()->id())->first() : null;
                                @endphp
                                <div class="bg-white rounded-xl border border-gray-100 p-4 mb-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg {{ $examAttempted ? ($examUserResult && $examUserResult->passed ? 'bg-green-100' : 'bg-amber-100') : 'bg-purple-50' }} flex items-center justify-center">
                                            <i class="ri-edit-box-line text-lg {{ $examAttempted ? ($examUserResult && $examUserResult->passed ? 'text-green-600' : 'text-amber-600') : 'text-purple-600' }}"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-heading text-sm">{{ $exam->title }}</p>
                                                @if(!$examAttempted && $exam->created_at && $exam->created_at->gt(now()->subDays(7)))
                                                <span class="px-1.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 animate-pulse">New</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-heading/60">
                                                {{ $exam->questions->count() }} questions &middot; {{ $exam->time_limit ? $exam->time_limit . ' min' : 'No time limit' }} &middot; Pass: {{ $exam->passing_score ?? 'N/A' }}%
                                                @if($examAttempted && $examUserResult)
                                                &middot; Score: <span class="{{ $examUserResult->passed ? 'text-green-600' : 'text-red-500' }} font-bold">{{ $examUserResult->score }}/{{ $examUserResult->total_marks }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($examAttempted)
                                            <a href="/dashboard/quizzes/my-result" class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-200 text-heading/70 hover:border-primary hover:text-primary transition-colors">
                                                <i class="ri-history-line"></i> View Result
                                            </a>
                                        @elseif(!$exam->isAvailable())
                                            {!! $exam->availabilityBadge() !!}
                                        @else
                                            <a href="/dashboard/exams/{{ $exam->id }}/instructions" class="px-4 py-2 text-sm font-semibold rounded-lg bg-purple-600 text-white hover:opacity-90 transition-opacity">
                                                <i class="ri-play-fill"></i> Start Exam
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Quizzes Section --}}
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-heading mb-4 flex items-center gap-2">
                                    <i class="ri-questionnaire-line text-primary"></i> Quizzes
                                </h4>
                                @forelse($courseQuizzes as $quiz)
                                @php
                                    $attempted = $quiz->user_result_exists > 0;
                                    $userResult = $attempted ? \App\Models\QuizResult::where('quiz_id', $quiz->id)->where('user_id', auth()->id())->first() : null;
                                @endphp
                                <div class="bg-white rounded-xl border border-gray-100 p-4 mb-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg {{ $attempted ? ($userResult && $userResult->passed ? 'bg-green-100' : 'bg-amber-100') : 'bg-primary-50' }} flex items-center justify-center">
                                            <i class="ri-questionnaire-line text-lg {{ $attempted ? ($userResult && $userResult->passed ? 'text-green-600' : 'text-amber-600') : 'text-primary' }}"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-heading text-sm">{{ $quiz->title }}</p>
                                                @if(!$attempted && $quiz->created_at && $quiz->created_at->gt(now()->subDays(7)))
                                                <span class="px-1.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 animate-pulse">New</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-heading/60">
                                                {{ $quiz->questions->count() }} questions &middot; {{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'No time limit' }} &middot; Pass: {{ $quiz->passing_score ?? 'N/A' }}%
                                                @if($attempted && $userResult)
                                                &middot; Score: <span class="{{ $userResult->passed ? 'text-green-600' : 'text-red-500' }} font-bold">{{ $userResult->score }}/{{ $userResult->total_marks }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($attempted)
                                            <a href="/dashboard/quizzes/my-result" class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-200 text-heading/70 hover:border-primary hover:text-primary transition-colors">
                                                <i class="ri-history-line"></i> View Result
                                            </a>
                                        @elseif(!$quiz->isAvailable())
                                            {!! $quiz->availabilityBadge() !!}
                                        @else
                                            <a href="/dashboard/quizzes/{{ $quiz->id }}/instructions" class="px-4 py-2 text-sm font-semibold rounded-lg bg-primary text-white hover:opacity-90 transition-opacity">
                                                <i class="ri-play-fill"></i> Take Quiz
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <p class="text-sm text-heading/60 mb-6">No quizzes available for this course yet.</p>
                                @endforelse
                            </div>

                            {{-- Assignments Section --}}
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-heading mb-4 flex items-center gap-2">
                                    <i class="ri-file-list-3-line text-primary"></i> Assignments
                                </h4>
                                @forelse($courseAssignments as $assignment)
                                @php
                                    $submitted = $assignment->user_submission_exists > 0;
                                    $submission = $submitted ? \App\Models\AssignmentSubmission::where('assignment_id', $assignment->id)->where('user_id', auth()->id())->first() : null;
                                @endphp
                                <div class="bg-white rounded-xl border border-gray-100 p-4 mb-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg {{ $submitted ? ($submission && $submission->score !== null ? 'bg-green-100' : 'bg-amber-100') : 'bg-primary-50' }} flex items-center justify-center">
                                            <i class="ri-file-list-3-line text-lg {{ $submitted ? ($submission && $submission->score !== null ? 'text-green-600' : 'text-amber-600') : 'text-primary' }}"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-heading text-sm">{{ $assignment->title }}</p>
                                                @if(!$submitted && $assignment->created_at && $assignment->created_at->gt(now()->subDays(7)))
                                                <span class="px-1.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 animate-pulse">New</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-heading/60">
                                                {{ $assignment->total_marks }} marks
                                                @if($assignment->due_date)
                                                &middot; Due: {{ $assignment->due_date->format('M d, Y') }}
                                                @endif
                                                @if($submitted && $submission->score !== null)
                                                &middot; Score: <span class="text-green-600 font-bold">{{ $submission->score }}/{{ $assignment->total_marks }}</span>
                                                @elseif($submitted)
                                                &middot; <span class="text-amber-600">Submitted, awaiting grade</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($submitted)
                                            <a href="/dashboard/assignments" class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-200 text-heading/70 hover:border-primary hover:text-primary transition-colors">
                                                <i class="ri-eye-line"></i> View Submission
                                            </a>
                                        @elseif(!$assignment->isAvailable())
                                            {!! $assignment->availabilityBadge() !!}
                                        @else
                                            <a href="/dashboard/assignments/{{ $assignment->id }}/submit" class="px-4 py-2 text-sm font-semibold rounded-lg bg-primary text-white hover:opacity-90 transition-opacity">
                                                <i class="ri-upload-line"></i> Submit
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <p class="text-sm text-heading/60 mb-6">No assignments for this course yet.</p>
                                @endforelse
                            </div>

                            {{-- Certificates Section --}}
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-heading mb-4 flex items-center gap-2">
                                    <i class="ri-award-line text-primary"></i> Certificate
                                </h4>
                                @if($courseCertificate)
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200 p-6 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="ri-award-fill text-2xl text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-heading">{{ $courseCertificate->title ?? 'Certificate of Completion' }}</p>
                                            <p class="text-sm text-heading/60">Congratulations! You earned this certificate.</p>
                                        </div>
                                    </div>
                                    <a href="/dashboard/certificate/{{ $courseCertificate->id }}/download" class="px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                                        <i class="ri-download-line"></i> Download
                                    </a>
                                </div>
                                @else
                                <div class="bg-gray-50 rounded-xl p-6 text-center">
                                    <i class="ri-award-line text-3xl text-heading/30 block mb-2"></i>
                                    <p class="text-sm text-heading/60">Complete all lessons and pass the required quizzes to earn your certificate.</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Reviews Tab --}}
                        <div x-show="activeTab === 'reviews'" x-cloak>
                            <h3 class="text-lg font-bold text-heading mb-4">Course Reviews</h3>

                            @if(auth()->check() && $isEnrolled && $hasCompletedCourse)
                            <div class="mb-6 p-6 bg-gray-50 rounded-xl">
                                <h4 class="font-bold text-heading mb-3">{{ $userReview ? 'Your Review' : 'Write a Review' }}</h4>
                                @if(session('success'))<div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>@endif
                                @if($errors->any())<div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"><ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                                <form method="POST" action="/dashboard/course-review/{{ $course->id }}" x-data="{ rating: {{ $userReview->rating ?? 0 }} }">
                                    @csrf
                                    <div class="flex items-center gap-1 mb-3">
                                        <span class="text-sm text-heading/60 mr-2">Rating:</span>
                                        <template x-for="star in 5" :key="star">
                                            <button type="button" @click="rating = star" class="text-xl transition-colors" :class="star <= rating ? 'text-amber-400' : 'text-gray-200'">
                                                <i :class="star <= rating ? 'ri-star-fill' : 'ri-star-line'"></i>
                                            </button>
                                        </template>
                                        <input type="hidden" name="rating" :value="rating">
                                    </div>
                                    <textarea name="review" rows="3" placeholder="Share your thoughts about this course..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">{{ $userReview->review ?? '' }}</textarea>
                                    <button type="submit" class="mt-3 px-6 py-2 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">{{ $userReview ? 'Update Review' : 'Submit Review' }}</button>
                                </form>
                            </div>
                            @endif

                            @if($reviewCount)
                            <div class="flex items-center gap-4 mb-6">
                                <div class="text-center">
                                    <div class="text-5xl font-extrabold text-heading">{{ number_format($courseAvgRating, 1) }}</div>
                                    <div class="flex items-center gap-1 text-amber-400 mt-1">
                                        @for($s=1;$s<=5;$s++)<i class="{{ $s <= round($courseAvgRating) ? 'ri-star-fill' : 'ri-star-line' }}"></i>@endfor
                                    </div>
                                    <p class="text-xs text-heading/60 mt-1">{{ $reviewCount }} review{{ $reviewCount!==1?'s':'' }}</p>
                                </div>
                                <div class="flex-1 space-y-1">
                                    @foreach([5,4,3,2,1] as $star)
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="w-8 text-heading/60">{{ $star }}</span>
                                        <div class="flex-1 h-2 bg-gray-100 rounded-full">
                                            <div class="h-full bg-amber-400 rounded-full" style="width:{{ $reviewCount > 0 ? round(($ratingCounts[$star]/$reviewCount)*100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach($reviews as $review)
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                                        <i class="ri-user-smile-line text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-heading text-sm">{{ $review->user?->full_name ?? 'Student' }}</span>
                                            <span class="text-xs text-heading/60">{{ $review->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-amber-400 text-xs mb-1">
                                            @for($s=1;$s<=5;$s++)<i class="{{ $s <= $review->rating ? 'ri-star-fill' : 'ri-star-line' }}"></i>@endfor
                                        </div>
                                        <p class="text-sm text-heading/70">{{ $review->review }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="py-8 text-center text-heading/40">
                                <i class="ri-star-line text-3xl block mb-2"></i>
                                <p class="font-semibold">No reviews yet.</p>
                                <p class="text-sm mt-1">Be the first to review this course!</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
                <aside class="lg:w-96 shrink-0">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-28">
                    @if($course->preview_video_url)
                            <video controls class="w-full aspect-video rounded-lg mb-6 shadow-md" preload="metadata">
                                <source src="{{ $course->preview_video_url }}" type="video/mp4">
                            </video>
                        @elseif($course->thumbnail_url)
                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" loading="lazy" class="w-full aspect-video object-cover rounded-lg mb-6">
                        @else
                            <div class="w-full aspect-video bg-gradient-to-br from-[#5F3EED] to-[#8F75F3] flex flex-col items-center justify-center text-white p-4 text-center rounded-lg mb-6 shadow-md">
                                <i class="ri-graduation-cap-line text-5xl mb-2 text-white/80"></i>
                                <span class="font-extrabold text-xs tracking-wider uppercase opacity-90">{{ $course->category }}</span>
                                <span class="text-xs text-white/70 mt-1 font-medium">{{ $course->category }}</span>
                            </div>
                        @endif
                    <h3 class="font-bold text-heading text-lg mb-4">This Course Includes:</h3>
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Duration</span>
                            <span class="font-semibold text-heading">{{ $course->duration ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Lessons</span>
                            <span class="font-semibold text-heading">{{ $course->lessons->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Category</span>
                            <span class="font-semibold text-heading">{{ $course->category }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Students</span>
                            <span class="font-semibold text-heading">{{ $course->enrollments_count }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Price</span>
                            <span class="font-bold text-lg {{ $course->payment_type === 'free' ? 'text-free' : 'text-heading' }}">
                                @if($course->payment_type === 'free')
                                    Free
                                @elseif($course->sale_price)
                                    <span class="text-heading/40 line-through text-xs mr-1">{{ \App\Helpers\CurrencyHelper::format((float)$course->price) }}</span>
                                    {{ \App\Helpers\CurrencyHelper::format((float)$course->sale_price) }}
                                @else
                                    {{ \App\Helpers\CurrencyHelper::format((float)$course->price) }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @auth
                    @if($isEnrolled)
                        @php
                            $resumeLesson = $course->getResumeLesson();
                        @endphp
                        @if($resumeLesson)
                            <a href="/courses/{{ $course->slug }}/lessons/{{ $resumeLesson->id }}" class="w-full px-8 py-4 bg-green-500 text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center block">
                                Continue Learning
                            </a>
                        @else
                            <a href="/dashboard/my-enrolled-course" class="w-full px-8 py-4 bg-green-500 text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center block">
                                Go to Course
                            </a>
                        @endif
                        <a href="/courses/{{ $course->slug }}/materials" class="w-full px-8 py-3 mt-3 bg-primary-50 text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-all duration-300 text-center block text-sm">
                            <i class="ri-tools-line mr-1"></i> Course Materials
                        </a>
                        <a href="/courses/{{ $course->slug }}/discussions" class="w-full px-8 py-3 mt-3 border-2 border-gray-200 text-heading/70 hover:border-primary hover:text-primary font-bold rounded-full transition-all duration-300 text-center block text-sm">
                            <i class="ri-question-answer-line mr-1"></i> Discussions
                        </a>
                    @else
                        <a href="/courses/{{ $course->slug }}/checkout" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center block mb-3">
                            Enroll Now
                        </a>
                        @if($course->payment_type !== 'free')
                        <form method="POST" action="/cart/add/{{ $course->id }}" class="block mb-3">
                            @csrf
                            <button type="submit" class="w-full px-8 py-3 border-2 border-gray-200 text-heading/70 hover:border-primary hover:text-primary font-bold rounded-full transition-all duration-300 text-center block text-sm">
                                <i class="ri-shopping-cart-line mr-1"></i> Add to Cart
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="/dashboard/wishlists/toggle/{{ $course->id }}" class="block">
                            @csrf
                            <button type="submit" class="w-full px-8 py-3 border-2 {{ $inWishlist ? 'border-red-300 text-red-500 bg-red-50' : 'border-gray-200 text-heading/60 hover:border-red-300 hover:text-red-500' }} font-bold rounded-full transition-all duration-300 text-center block text-sm">
                                <i class="{{ $inWishlist ? 'ri-heart-fill' : 'ri-heart-line' }} mr-1"></i>
                                {{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                            </button>
                        </form>
                    @endif
                    @else
                    <a href="/login" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center block">
                        Login to Enroll
                    </a>
                    @endauth
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection