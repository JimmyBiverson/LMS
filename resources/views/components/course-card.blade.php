@props([
    'course' => null,
    'slug' => '#',
    'level' => 'Intermediate',
    'category' => 'General',
    'paymentType' => 'free',
    'price' => 0,
    'salePrice' => null,
    'title' => 'Course Title',
    'rating' => '0',
    'duration' => 'N/A',
    'lessons' => '0',
    'students' => '0',
    'image' => null,
])

@php
    if ($course) {
        $slug = $course->slug ?? $course->id;
        $levelName = $course->level?->name ?? 'All Levels';
        $catName = $course->category ?? 'General';
        $paymentType = $course->payment_type ?? 'free';
        $price = (float) ($course->price ?? 0);
        $salePrice = $course->sale_price !== null ? (float) $course->sale_price : null;
        $title = $course->title;
        $duration = $course->duration ?? 'N/A';
        $lessonsCount = $course->lessons->count();
        $enrollCount = $course->enrollments_count ?? $course->enrollments->count() ?? 0;
        $image = $course->thumbnail_url;
        $avgRating = $course instanceof \App\Models\Course ? round($course->averageRating(), 1) : 0;

        $previewLesson = $course->lessons->first(function ($l) {
            return $l->is_free_preview && ($l->video_file || $l->video_url);
        });
    } else {
        $levelName = $level;
        $catName = $category;
        $price = (float) $price;
        $salePrice = ($salePrice === null || $salePrice === '') ? null : (float) $salePrice;
        $lessonsCount = $lessons;
        $enrollCount = $students;
        $avgRating = $rating;
        $previewLesson = null;
    }

    $isFree = $paymentType === 'free';
    $hasSale = !$isFree && $salePrice !== null && $salePrice < $price;
    $hasPreview = $previewLesson && ($previewLesson->video_file || $previewLesson->video_url);
    $previewSrc = $previewLesson?->video_file ? asset('storage/' . $previewLesson->video_file) : ($previewLesson?->video_url ?? null);
    if (!$hasPreview && $course && $course->preview_video_url) {
        $hasPreview = true;
        $previewSrc = $course->preview_video_url;
    }
@endphp

<div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
    <div class="relative w-full aspect-video bg-gray-900 overflow-hidden"
        x-data="{ 
            hovering: false,
            timeoutId: null,
            startPreview() {
                this.hovering = true;
                if (this.$refs.previewVideo) {
                    this.$refs.previewVideo.currentTime = 0;
                    this.$refs.previewVideo.play().catch(e => {});
                    
                    this.timeoutId = setTimeout(() => {
                        this.stopPreview();
                    }, 8000);
                }
            },
            stopPreview() {
                this.hovering = false;
                if (this.timeoutId) {
                    clearTimeout(this.timeoutId);
                    this.timeoutId = null;
                }
                if (this.$refs.previewVideo) {
                    this.$refs.previewVideo.pause();
                }
            }
        }"
        @mouseenter="startPreview()"
        @mouseleave="stopPreview()"
    >
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full bg-gradient-to-br from-[#5F3EED] to-[#8F75F3] flex flex-col items-center justify-center text-white p-4 text-center">
                <i class="ri-graduation-cap-line text-5xl mb-2 text-white/80"></i>
                <span class="font-extrabold text-xs tracking-wider uppercase opacity-90">{{ $catName }}</span>
                <span class="text-xs text-white/70 mt-1 font-medium">{{ $catName }}</span>
            </div>
        @endif

        {{-- Preview Video on Hover --}}
        @if($hasPreview && $previewSrc)
            <video
                x-ref="previewVideo"
                x-show="hovering"
                x-transition:enter="transition-opacity duration-300"
                x-transition:leave="transition-opacity duration-300"
                class="absolute inset-0 w-full h-full object-cover z-10"
                muted
                playsinline
                preload="metadata"
            >
                <source src="{{ $previewSrc }}" type="video/mp4">
            </video>
        @endif

        {{-- Hover Overlay --}}
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100 z-20">
            <div class="bg-white rounded-full p-4 transform scale-75 group-hover:scale-100 transition-all duration-300 shadow-lg">
                <i class="ri-play-fill text-primary text-2xl ml-0.5"></i>
            </div>
        </div>

        {{-- Level Badge --}}
        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold bg-secondary text-heading shadow-md">
            {{ $levelName }}
        </span>

        {{-- Price Badge --}}
        @if($isFree)
            <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white shadow-md">FREE</span>
        @elseif($hasSale)
            <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white shadow-md">SALE</span>
        @endif

        {{-- Preview Badge --}}
        @if($hasPreview)
            <span class="absolute bottom-3 left-3 px-2 py-1 rounded-full text-xs font-bold bg-primary/90 text-white shadow-md flex items-center gap-1">
                <i class="ri-play-circle-line text-xs"></i> Preview
            </span>
        @endif
    </div>

    <div class="p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-primary bg-primary-50 px-2 py-1 rounded">{{ $catName }}</span>
            <span class="text-sm font-bold {{ $isFree ? 'text-green-600' : 'text-heading' }}">
                @if($isFree)
                    Free
                @elseif($hasSale)
                    <span class="text-heading/40 line-through text-xs mr-1">{{ \App\Helpers\CurrencyHelper::format($price) }}</span>
                    {{ \App\Helpers\CurrencyHelper::format($salePrice) }}
                @else
                    {{ \App\Helpers\CurrencyHelper::format($price) }}
                @endif
            </span>
        </div>

        <h3 class="font-bold text-heading mb-2 line-clamp-2 group-hover:text-primary transition-colors duration-300">
            <a href="/courses/{{ $slug }}">{{ $title }}</a>
        </h3>

        @if($avgRating > 0)
        <div class="flex items-center gap-1 text-amber-400 text-sm mb-3">
            @for($i = 1; $i <= 5; $i++)
                <i class="{{ $i <= (int)$avgRating ? 'ri-star-fill' : 'ri-star-line' }}"></i>
            @endfor
            <span class="text-heading/60 text-xs ml-1">({{ $avgRating }})</span>
        </div>
        @endif

        <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-xs text-heading/60">
            <span class="flex items-center gap-1"><i class="ri-time-line"></i> {{ $duration }}</span>
            <span class="flex items-center gap-1"><i class="ri-book-open-line"></i> {{ $lessonsCount }} Lessons</span>
            <span class="flex items-center gap-1"><i class="ri-user-line"></i> {{ $enrollCount }}</span>
        </div>
        @php
            $quizCount = $course->quizzes_count ?? 0;
            $assignCount = $course->assignments_count ?? 0;
        @endphp
        @if($quizCount > 0 || $assignCount > 0)
        <div class="flex items-center gap-2 mt-2 pt-2 border-t border-gray-50">
            @if($quizCount > 0)
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-primary-50 text-primary">
                <i class="ri-questionnaire-line text-xs"></i> {{ $quizCount }} Quiz{{ $quizCount > 1 ? 'zes' : '' }}
            </span>
            @endif
            @if($assignCount > 0)
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">
                <i class="ri-file-list-3-line text-xs"></i> {{ $assignCount }} Assignment{{ $assignCount > 1 ? 's' : '' }}
            </span>
            @endif
        </div>
        @endif
    </div>
</div>
