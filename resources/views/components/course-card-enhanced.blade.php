<div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 bg-white">
    {{-- Course Image/Video Preview --}}
    <div class="relative w-full aspect-video bg-gray-900 overflow-hidden">
        {{-- Thumbnail --}}
        @if($course->thumbnail)
            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                <div class="text-center text-white">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-semibold">Course Preview</p>
                </div>
            </div>
        @endif

        {{-- Video Auto-Play Preview (first lesson with video) --}}
        @php
            $firstVideoLesson = $course->lessons()
                ->where('is_free_preview', true)
                ->whereNotNull('video_url')
                ->orWhereNotNull('video_file')
                ->first();
        @endphp

        @if($firstVideoLesson)
            <video 
                class="absolute inset-0 w-full h-full object-cover hidden group-hover:block transition-opacity duration-300"
                autoplay
                muted
                loop
                playsinline
                data-preview-video
            >
                @if($firstVideoLesson->video_file)
                    <source src="{{ asset('storage/' . $firstVideoLesson->video_file) }}" type="video/mp4">
                @else
                    {{-- Handle YouTube/Vimeo URLs as thumbnail only --}}
                @endif
                Your browser does not support the video tag.
            </video>
        @endif

        {{-- Play Button Overlay --}}
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="bg-white rounded-full p-4 transform scale-75 group-hover:scale-100 transition-transform duration-300 shadow-lg">
                <svg class="w-6 h-6 text-blue-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                </svg>
            </div>
        </div>

        {{-- Price Badge --}}
        @if($course->payment_type === 'paid')
            <div class="absolute top-3 right-3 bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-sm font-bold shadow-md">
                <span class="line-through text-xs opacity-70">{{ number_format($course->price, 2) }}</span>
                {{ number_format($course->sale_price ?? $course->price, 2) }}
            </div>
        @else
            <div class="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                FREE
            </div>
        @endif

        {{-- Free Preview Badge --}}
        @if($firstVideoLesson)
            <div class="absolute top-3 left-3 bg-blue-600 text-white px-2 py-1 rounded-full text-xs font-bold shadow-md flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                </svg>
                Preview
            </div>
        @endif
    </div>

    {{-- Course Info --}}
    <div class="p-4">
        {{-- Category Badge --}}
        @if($course->category)
            <span class="inline-block text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded mb-2">
                {{ $course->category->name }}
            </span>
        @endif

        {{-- Title --}}
        <h3 class="font-bold text-gray-900 line-clamp-2 hover:text-blue-600 transition-colors duration-200 mb-2">
            {{ $course->title }}
        </h3>

        {{-- Instructor --}}
        <p class="text-sm text-gray-600 mb-3">
            <span class="font-semibold">{{ $course->instructor->first_name ?? $course->user->first_name }}</span>
        </p>

        {{-- Stats --}}
        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"></path>
                </svg>
                {{ $course->enrollments_count ?? 0 }} students
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11 7h2v2h-2zm0 4h2v2h-2zm0 4h2v2h-2zm-4-4h2v2H7zm0 4h2v2H7zm0-8h2v2H7zm8-4h2v2h-2zm0 4h2v2h-2zm0 4h2v2h-2z"></path>
                </svg>
                {{ $course->lessons_count ?? 0 }} lessons
            </span>
        </div>

        {{-- Level --}}
        @if($course->level)
            <p class="text-xs text-gray-600 mb-3">
                <span class="inline-block bg-gray-100 text-gray-700 px-2 py-0.5 rounded">
                    {{ $course->level->name ?? 'All Levels' }}
                </span>
            </p>
        @endif

        {{-- Description Preview --}}
        <p class="text-sm text-gray-600 line-clamp-2 mb-4">
            {{ $course->description }}
        </p>

        {{-- CTA Button --}}
        <a href="{{ route('courses.show', $course->slug) }}" 
           class="inline-block w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 text-center transform hover:scale-105">
            View Course
        </a>
    </div>
</div>
