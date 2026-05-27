@props([
    'slug' => '#',
    'level' => 'Intermediate',
    'category' => 'Web Development',
    'paymentType' => 'free',
    'price' => 0,
    'salePrice' => null,
    'title' => 'Course Title',
    'rating' => '0',
    'duration' => '0h',
    'lessons' => '0',
    'students' => '0',
    'image' => null,
    'course' => null,
])

@php
    $isFree = $paymentType === 'free';
    $price = (float) $price;
    $salePrice = ($salePrice === null || $salePrice === '') ? null : (float) $salePrice;
    $hasSale = !$isFree && $salePrice !== null && $salePrice < $price;
@endphp

<div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
    <div class="relative overflow-hidden">
        @if($image)
            <img src="{{ asset('storage/' . $image) }}" alt="{{ $title }}" class="w-full h-48 object-cover">
        @else
            <div class="h-48 bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
                <i class="ri-play-circle-line text-5xl text-primary/30"></i>
            </div>
        @endif
        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold bg-secondary text-heading">
            {{ $level }}
        </span>
        @if($isFree)
            <span class="absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-bold bg-free text-white">FREE</span>
        @elseif($hasSale)
            <span class="absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-bold bg-red-500 text-white">SALE</span>
        @endif
    </div>
    <div class="p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-primary">{{ $category }}</span>
            <span class="text-sm font-bold {{ $isFree ? 'text-free' : 'text-heading' }}">
                @if($isFree)
                    Free
                @elseif($hasSale)
                    <span class="text-heading/40 line-through text-xs mr-1">${{ number_format($price, 2) }}</span>
                    ${{ number_format($salePrice, 2) }}
                @else
                    ${{ number_format($price, 2) }}
                @endif
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