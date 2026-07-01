@extends('layouts.app')

@section('title', $lesson->title . ' - ' . $course->title)

@section('content')
<script>window.courseSlug = '{{ $course->slug }}';</script>
@php
    $lessonIndex = $course->lessons->sortBy('order')->search(function($item) use ($lesson) {
        return $item->id === $lesson->id;
    });

    $previousLesson = $course->lessons->sortBy('order')->where('order', '<', $lesson->order)->last();
    $nextLesson = $course->lessons->sortBy('order')->where('order', '>', $lesson->order)->first();
@endphp

{{-- Breadcrumb --}}
<section class="bg-gradient-to-r from-primary/5 to-secondary/5 py-8 border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center gap-2 text-sm text-heading/60 mb-3">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/courses" class="hover:text-primary transition-colors">Courses</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/courses/{{ $course->slug }}" class="hover:text-primary transition-colors">{{ $course->title }}</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">{{ $lesson->title }}</span>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold mb-2">
                    Lesson {{ $lessonIndex + 1 }} of {{ $totalLessons }}
                </span>
                <h1 class="text-3xl font-bold text-heading">{{ $lesson->title }}</h1>
            </div>
            <a href="/courses/{{ $course->slug }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 text-heading hover:bg-gray-50 transition-colors">
                <i class="ri-arrow-left-s-line"></i> Back to Course
            </a>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="py-12">
    <div class="max-w-6xl mx-auto px-4">
        @if(!$canView && auth()->check())
            {{-- Not Enrolled Message --}}
            <div class="max-w-2xl mx-auto text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-100 mb-6">
                    <i class="ri-lock-line text-3xl text-amber-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-heading mb-2">This content is locked</h2>
                <p class="text-heading/60 mb-6">You need to enroll in this course to access this lesson.</p>
                <a href="/courses/{{ $course->slug }}/checkout" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary/90 transition-colors">
                    <i class="ri-shopping-cart-line"></i> Enroll Now
                </a>
            </div>
        @elseif(!$canView && !auth()->check())
            {{-- Login Required --}}
            <div class="max-w-2xl mx-auto text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-6">
                    <i class="ri-login-box-line text-3xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-heading mb-2">Sign in to continue</h2>
                <p class="text-heading/60 mb-6">Please log in to access this lesson content.</p>
                <div class="flex gap-3 justify-center">
                    <a href="/login" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary/90 transition-colors">
                        <i class="ri-login-line"></i> Sign In
                    </a>
                    <a href="/register" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-primary text-primary font-semibold hover:bg-primary/5 transition-colors">
                        Create Account
                    </a>
                </div>
            </div>
        @else
            {{-- Lesson Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Video & Content --}}
                <div class="lg:col-span-2">
                    {{-- Video Player --}}
                    @if($lesson->videoSource() || $lesson->video_url)
                        <x-video-player :lesson="$lesson" :enrolled="$isEnrolled" :last-position="$lastPosition" :is-completed="$lessonCompleted" />
                    @else
                        <div class="bg-gray-100 rounded-xl aspect-video flex items-center justify-center">
                            <div class="text-center">
                                <i class="ri-image-off-line text-4xl text-gray-400 mb-2 block"></i>
                                <p class="text-gray-500">No video available for this lesson</p>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Lesson Details --}}
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                @if($isEnrolled && auth()->check())
                                    <form method="POST" action="/lessons/{{ $lesson->id }}/toggle-completion" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ $lessonCompleted ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }} font-semibold hover:opacity-90 transition-opacity">
                                            @if($lessonCompleted)
                                                <i class="ri-checkbox-circle-fill"></i> Completed
                                            @else
                                                <i class="ri-checkbox-blank-circle-line"></i> Mark as Complete
                                            @endif
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-2 text-sm text-heading/60">
                                @if($lesson->duration)
                                    <i class="ri-time-line"></i> {{ $lesson->duration }}
                                @endif
                            </div>
                        </div>
                        
                        {{-- Lesson Description --}}
                        @if($lesson->content)
                        <div class="prose prose-sm max-w-none mb-8">
                            <h3 class="text-xl font-bold text-heading mb-4">Lesson Overview</h3>
                            <p class="text-heading/70 leading-relaxed whitespace-pre-wrap">{{ $lesson->content }}</p>
                        </div>
                        @endif
                        
                        {{-- Document Resources --}}
                        @if($lesson->document_file)
                        <div class="mb-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
                            <h3 class="font-bold text-heading mb-4 flex items-center gap-2">
                                <i class="ri-file-pdf-line text-blue-600"></i> Lesson Materials
                            </h3>
                            <a href="{{ asset('storage/' . $lesson->document_file) }}" target="_blank" class="inline-flex items-center gap-3 p-4 rounded-lg bg-white border border-blue-200 hover:border-blue-400 transition-colors w-full">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="ri-file-pdf-fill text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-heading text-sm">Course Material</p>
                                    <p class="text-xs text-heading/60">PDF Document</p>
                                </div>
                                <i class="ri-download-cloud-line text-blue-600"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Discussion Section --}}
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2">
                            <i class="ri-chat-3-line text-primary"></i> Discussions
                        </h3>
                        @auth
                        <form method="POST" action="/courses/{{ $course->slug }}/discussions" class="mb-6">
                            @csrf
                            <textarea name="body" rows="3" placeholder="Share your thoughts or ask a question about this lesson..." required maxlength="2000" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none"></textarea>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-heading/40">Max 2000 characters</p>
                                <button type="submit" class="px-5 py-2 bg-primary text-white font-semibold rounded-lg text-sm hover:opacity-90 transition-opacity">
                                    <i class="ri-send-plane-line mr-1"></i> Post Discussion
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="mb-6 p-4 bg-blue-50 rounded-xl text-center">
                            <p class="text-sm text-blue-700">
                                <a href="/login" class="font-bold underline">Sign in</a> to join the discussion.
                            </p>
                        </div>
                        @endauth

                        @php
                            $recentDiscussions = \App\Models\CourseDiscussion::with('user', 'replies.user')
                                ->where('course_id', $course->id)
                                ->whereNull('parent_id')
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp

                        @forelse($recentDiscussions as $discussion)
                        <div class="bg-white rounded-xl border border-gray-100 p-4 mb-3">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                                    <i class="ri-user-smile-line text-primary text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-bold text-heading">{{ $discussion->user?->name ?? 'Student' }}</span>
                                        <span class="text-xs text-heading/40">{{ $discussion->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-heading/70">{{ $discussion->body }}</p>
                                    @if($discussion->replies->count())
                                    <div class="mt-3 pl-4 border-l-2 border-gray-100 space-y-2">
                                        @foreach($discussion->replies->take(3) as $reply)
                                        <div class="flex items-start gap-2">
                                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                                                <i class="ri-user-smile-line text-gray-500 text-xs"></i>
                                            </div>
                                            <div>
                                                <span class="text-xs font-bold text-heading">{{ $reply->user?->name ?? 'Student' }}</span>
                                                <p class="text-xs text-heading/60">{{ $reply->body }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                        @if($discussion->replies->count() > 3)
                                        <a href="/courses/{{ $course->slug }}/discussions" class="text-xs text-primary font-semibold hover:underline">View all {{ $discussion->replies->count() }} replies</a>
                                        @endif
                                    </div>
                                    @endif
                                    @auth
                                    <form method="POST" action="/courses/{{ $course->slug }}/discussions/{{ $discussion->id }}/reply" class="mt-2">
                                        @csrf
                                        <input type="text" name="body" placeholder="Write a reply..." required maxlength="2000" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    </form>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="bg-gray-50 rounded-xl p-8 text-center">
                            <i class="ri-chat-off-line text-3xl text-gray-400 block mb-2"></i>
                            <p class="text-gray-500 font-semibold">No discussions yet</p>
                            <p class="text-gray-400 text-sm mt-1">Be the first to start a discussion about this lesson!</p>
                        </div>
                        @endforelse

                        <a href="/courses/{{ $course->slug }}/discussions" class="inline-flex items-center gap-1 text-sm text-primary font-semibold hover:underline mt-2">
                            View all discussions <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
                
                {{-- Sidebar --}}
                <div>
                    {{-- Course Info Card --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6 sticky top-20">
                        <h3 class="font-bold text-heading mb-4">Course Progress</h3>
                        
                        @if($isEnrolled)
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-heading">{{ $completedLessons }} of {{ $totalLessons }}</span>
                                    <span class="text-sm font-bold text-primary">{{ $progressPercent }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-primary h-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-heading/60 mb-4">Enroll to track your progress</p>
                        @endif
                        
                        {{-- Lesson Navigation --}}
                        <div class="space-y-2 mb-6">
                            @if($previousLesson)
                            <a href="/courses/{{ $course->slug }}/lessons/{{ $previousLesson->id }}" class="block p-3 rounded-lg border border-gray-200 hover:border-primary hover:bg-primary/5 transition-colors text-sm">
                                <p class="text-heading/60 text-xs mb-1">← Previous</p>
                                <p class="font-semibold text-heading truncate">{{ $previousLesson->title }}</p>
                            </a>
                            @endif
                            
                            @if($nextLesson)
                            <a href="/courses/{{ $course->slug }}/lessons/{{ $nextLesson->id }}" class="block p-3 rounded-lg bg-primary hover:bg-primary/90 text-white transition-colors text-sm">
                                <p class="text-white/70 text-xs mb-1">Next →</p>
                                <p class="font-semibold truncate">{{ $nextLesson->title }}</p>
                            </a>
                            @endif
                        </div>
                        
                        {{-- Quick Actions --}}
                        @if($isEnrolled)
                        <div class="mb-6 p-4 bg-gradient-to-br from-primary-50 to-secondary-50 rounded-xl border border-primary-100">
                            <h4 class="font-semibold text-heading text-sm mb-3 flex items-center gap-1">
                                <i class="ri-flashlight-line text-primary"></i> Quick Actions
                            </h4>
                            <div class="space-y-2">
                                @php
                                    $courseExams = \App\Models\Quiz::where('course_id', $course->id)->where('status', 'published')->where('is_exam', true)->get();
                                    $courseQuizzes = \App\Models\Quiz::where('course_id', $course->id)->where('status', 'published')->where('is_exam', false)->get();
                                    $courseAssignments = \App\Models\Assignment::where('course_id', $course->id)->where('status', 'published')->get();
                                @endphp
                                @foreach($courseExams as $e)
                                @php
                                    $hasExamResult = \App\Models\QuizResult::where('quiz_id', $e->id)->where('user_id', auth()->id())->exists();
                                @endphp
                                @if($hasExamResult)
                                <a href="/dashboard/quizzes/my-result" class="flex items-center gap-2 p-2 rounded-lg hover:bg-white/60 transition-colors text-xs font-semibold text-green-600">
                                    <i class="ri-checkbox-circle-fill"></i> View Exam Result
                                </a>
                                @elseif(!$e->isAvailable())
                                <span class="flex items-center gap-2 p-2 text-xs text-amber-600 font-semibold">{!! $e->availabilityBadge() !!}</span>
                                @else
                                <a href="/dashboard/exams/{{ $e->id }}/instructions" class="flex items-center gap-2 p-2 rounded-lg hover:bg-white/60 transition-colors text-xs font-semibold text-purple-600">
                                    <i class="ri-edit-box-line"></i> Take Exam: {{ $e->title }}
                                </a>
                                @endif
                                @endforeach
                                @foreach($courseQuizzes as $q)
                                @php
                                    $hasResult = \App\Models\QuizResult::where('quiz_id', $q->id)->where('user_id', auth()->id())->exists();
                                @endphp
                                @if($hasResult)
                                <a href="/dashboard/quizzes/my-result" class="flex items-center gap-2 p-2 rounded-lg hover:bg-white/60 transition-colors text-xs font-semibold text-green-600">
                                    <i class="ri-checkbox-circle-fill"></i> View Quiz Result
                                </a>
                                @elseif(!$q->isAvailable())
                                <span class="flex items-center gap-2 p-2 text-xs text-amber-600 font-semibold">{!! $q->availabilityBadge() !!}</span>
                                @else
                                <a href="/dashboard/quizzes/{{ $q->id }}/instructions" class="flex items-center gap-2 p-2 rounded-lg hover:bg-white/60 transition-colors text-xs font-semibold text-primary">
                                    <i class="ri-questionnaire-line"></i> Take Quiz: {{ $q->title }}
                                </a>
                                @endif
                                @endforeach
                                @foreach($courseAssignments as $a)
                                @php
                                    $hasSubmission = \App\Models\AssignmentSubmission::where('assignment_id', $a->id)->where('user_id', auth()->id())->exists();
                                @endphp
                                <a href="{{ $hasSubmission ? '/dashboard/assignments' : '/dashboard/assignments/' . $a->id . '/submit' }}" class="flex items-center gap-2 p-2 rounded-lg hover:bg-white/60 transition-colors text-xs font-semibold {{ $hasSubmission ? 'text-green-600' : 'text-amber-600' }}">
                                    <i class="{{ $hasSubmission ? 'ri-check-line' : 'ri-file-list-3-line' }}"></i>
                                    {{ $hasSubmission ? 'View Assignment Status' : 'Submit: ' . $a->title }}
                                </a>
                                @endforeach
                                <a href="/courses/{{ $course->slug }}/materials" class="flex items-center gap-2 p-2 rounded-lg hover:bg-white/60 transition-colors text-xs font-semibold text-heading/70">
                                    <i class="ri-tools-line"></i> All Course Materials
                                </a>
                            </div>
                        </div>
                        @endif

                        {{-- Course Lessons List --}}
                        <h4 class="font-semibold text-heading text-sm mb-3">Course Lessons</h4>
                        <div class="space-y-2 max-h-80 overflow-y-auto">
                            @foreach($course->lessons->sortBy('order') as $l)
                                @php $lCompleted = $lessonCompletionMap[$l->id] ?? false; @endphp
                                <a href="/courses/{{ $course->slug }}/lessons/{{ $l->id }}" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors {{ $lesson->id === $l->id ? 'bg-primary/10 border border-primary' : 'border border-transparent' }} group">
                                    <div class="flex-shrink-0">
                                        @if($lCompleted)
                                            <i class="ri-checkbox-circle-fill text-green-500"></i>
                                        @else
                                            <i class="ri-circle-blank-line text-gray-300"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-heading truncate group-hover:text-primary transition-colors">{{ $l->title }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
