@props([
    'slug' => '#',
    'level' => 'Intermediate',
    'category' => 'Web Development',
    'price' => 'Free',
    'title' => 'Course Title',
    'rating' => '0',
    'duration' => '0h',
    'lessons' => '0',
    'students' => '0',
    'image' => null,
])

<div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
    <div class="relative overflow-hidden">
        <div class="h-48 bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
            <i class="ri-play-circle-line text-5xl text-primary/30"></i>
        </div>
        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold bg-secondary text-heading">
            {{ $level }}
        </span>
    </div>
    <div class="p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-primary">{{ $category }}</span>
            <span class="text-sm font-bold {{ $price == 'Free' ? 'text-green-600' : 'text-heading' }}">
                {{ $price == 'Free' ? 'Free' : '$'.$price }}
            </span>
        </div>
        <h3 class="font-bold text-heading mb-3 line-clamp-2 group-hover:text-primary transition-colors duration-300">
            <a href="/courses/{{ $slug }}">{{ $title }}</a>
        </h3>
        <div class="flex items-center gap-1 text-amber-400 text-sm mb-3">
            @for($i = 1; $i <= 5; $i++)
                <i class="{{ $i <= (int)$rating ? 'ri-star-fill' : 'ri-star-line' }}"></i>
            @endfor
            <span class="text-heading/60 text-xs ml-1">({{ $rating }} Rating)</span>
        </div>
        <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-xs text-heading/60">
            <span class="flex items-center gap-1"><i class="ri-time-line"></i> {{ $duration }}</span>
            <span class="flex items-center gap-1"><i class="ri-book-open-line"></i> {{ $lessons }} Lessons</span>
            <span class="flex items-center gap-1"><i class="ri-user-line"></i> {{ $students }} Student</span>
        </div>
    </div>
</div>