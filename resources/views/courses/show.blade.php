@extends('layouts.app')

@section('title', 'Course Details')

@section('content')
@php
    $isEnrolled = auth()->check() && \App\Models\Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->exists();
    $inWishlist = auth()->check() && \App\Models\Wishlist::where('user_id', auth()->id())->where('course_id', $course->id)->exists();
@endphp
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
                    @php $courseAvgRating = \App\Models\Review::where('course_id', $course->id)->avg('rating') ?? 0; @endphp
                    @for($s=1;$s<=5;$s++)<i class="{{ $s <= round($courseAvgRating) ? 'ri-star-fill' : 'ri-star-line' }}"></i>@endfor
                    <span class="text-heading/60 ml-1">({{ \App\Models\Review::where('course_id', $course->id)->count() }} {{ \App\Models\Review::where('course_id', $course->id)->count() === 1 ? 'Review' : 'Reviews' }})</span>
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
                <div class="border-b border-gray-200 mb-8">
                    <nav class="flex gap-8">
                        <button class="pb-3 border-b-2 border-primary text-primary font-bold text-sm">Course Overview</button>
                        <button class="pb-3 border-b-2 border-transparent text-heading/60 font-semibold text-sm hover:text-primary transition-colors">Curriculum</button>
                        <button class="pb-3 border-b-2 border-transparent text-heading/60 font-semibold text-sm hover:text-primary transition-colors">Instructor</button>
                        <button class="pb-3 border-b-2 border-transparent text-heading/60 font-semibold text-sm hover:text-primary transition-colors">Reviews</button>
                    </nav>
                </div>

                <div>
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
                    @php $faqs = \App\Models\Faq::where('status', 'active')->orderBy('order')->take(3)->get(); @endphp
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
                        <details class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <summary class="font-bold text-heading cursor-pointer flex items-center justify-between">
                                1. What will I learn in this course?
                                <i class="ri-arrow-down-s-line text-primary"></i>
                            </summary>
                            <p class="mt-3 text-heading/70">This course covers all the key concepts and practical skills needed to master the subject. By the end, you'll be able to apply your knowledge to real-world projects.</p>
                        </details>
                        <details class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <summary class="font-bold text-heading cursor-pointer flex items-center justify-between">
                                2. Who is this course for?
                                <i class="ri-arrow-down-s-line text-primary"></i>
                            </summary>
                            <p class="mt-3 text-heading/70">This course is designed for learners at all levels. Whether you're a beginner or looking to advance your skills, you'll find valuable content throughout.</p>
                        </details>
                        <details class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <summary class="font-bold text-heading cursor-pointer flex items-center justify-between">
                                3. How long will I have access?
                                <i class="ri-arrow-down-s-line text-primary"></i>
                            </summary>
                            <p class="mt-3 text-heading/70">Once enrolled, you'll have lifetime access to all course materials, including any future updates.</p>
                        </details>
                        @endforelse
                    </div>

                    <h3 class="text-lg font-bold text-heading mb-4">Course Curriculum</h3>
                    @forelse($course->lessons->sortBy('order') as $lesson)
                    @php
                        $lessonCompleted = auth()->check() && \App\Models\LessonCompletion::where('user_id', auth()->id())
                            ->where('lesson_id', $lesson->id)->exists();
                    @endphp
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
                        @if($lesson->is_free_preview && $lesson->video_url)
                        <div class="p-4 bg-green-50 border-t border-green-100">
                            <a href="{{ $lesson->video_url }}" target="_blank" class="text-sm text-primary font-semibold hover:underline flex items-center gap-2"><i class="ri-play-circle-fill"></i> Free Preview</a>
                        </div>
                        @endif
                    </div>
                    @empty
                    <p class="text-sm text-heading/60">Curriculum is being developed.</p>
                    @endforelse

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

                    <h3 class="text-lg font-bold text-heading mb-4">Course Reviews</h3>
                    @php
                        $reviews = \App\Models\Review::with('user')->where('course_id', $course->id)->latest()->get();
                        $avgRating = $reviews->avg('rating') ?? 0;
                        $ratingCounts = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
                        foreach($reviews as $r) { if(isset($ratingCounts[$r->rating])) $ratingCounts[$r->rating]++; }
                    @endphp
                    @if($reviews->count())
                    <div class="flex items-center gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-5xl font-extrabold text-heading">{{ number_format($avgRating, 1) }}</div>
                            <div class="flex items-center gap-1 text-amber-400 mt-1">
                                @for($s=1;$s<=5;$s++)<i class="{{ $s <= round($avgRating) ? 'ri-star-fill' : 'ri-star-line' }}"></i>@endfor
                            </div>
                            <p class="text-xs text-heading/60 mt-1">{{ $reviews->count() }} review{{ $reviews->count()!==1?'s':'' }}</p>
                        </div>
                        <div class="flex-1 space-y-1">
                            @foreach([5,4,3,2,1] as $star)
                            <div class="flex items-center gap-2 text-sm">
                                <span class="w-8 text-heading/60">{{ $star }}</span>
                                <div class="flex-1 h-2 bg-gray-100 rounded-full">
                                    <div class="h-full bg-amber-400 rounded-full" style="width:{{ $reviews->count() > 0 ? round(($ratingCounts[$star]/$reviews->count())*100) : 0 }}%"></div>
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

            {{-- Sidebar --}}
                <aside class="lg:w-96 shrink-0">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-28">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full aspect-video object-cover rounded-lg mb-6">
                        @else
                            <div class="aspect-video bg-gradient-to-br from-primary-100 to-primary-50 rounded-lg flex items-center justify-center mb-6">
                                <i class="ri-play-circle-line text-6xl text-primary/30"></i>
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
                                    <span class="text-heading/40 line-through text-xs mr-1">${{ number_format((float)$course->price, 2) }}</span>
                                    ${{ number_format((float)$course->sale_price, 2) }}
                                @else
                                    ${{ number_format((float)$course->price, 2) }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @auth
                    @if($isEnrolled)
                        <a href="/dashboard/my-enrolled-course" class="w-full px-8 py-4 bg-green-500 text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center block">
                            Go to Course
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