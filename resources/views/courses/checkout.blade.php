@extends('layouts.app')

@section('title', 'Checkout - ' . $course->title)

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Checkout</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/courses" class="hover:text-primary transition-colors">Courses</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/courses/{{ $course->slug }}" class="hover:text-primary transition-colors">{{ $course->title }}</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Checkout</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1">
                @if(session('error'))
                    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">{{ session('error') }}</div>
                @endif

                <h2 class="text-xl font-bold text-heading mb-6">Order Summary</h2>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
                    <div class="flex p-4 gap-4">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-24 h-20 object-cover rounded-lg shrink-0">
                        @else
                            <div class="w-24 h-20 bg-gradient-to-br from-primary-100 to-primary-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="ri-play-circle-line text-2xl text-primary/30"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-heading text-sm mb-1">{{ $course->title }}</h3>
                            <p class="text-xs text-heading/60 mb-1">{{ $course->category }}</p>
                            <p class="text-xs text-heading/60">By {{ $course->instructor?->name ?? 'Instructor' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
                    <h4 class="font-bold text-heading text-sm mb-3">Price Breakdown</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-heading/60">Course Price</span>
                            @if($course->payment_type === 'free')
                                <span class="text-free font-semibold">Free</span>
                            @elseif($course->sale_price)
                                <span class="text-heading/40 line-through">${{ number_format((float)$course->price, 2) }}</span>
                            @else
                                <span class="font-semibold">${{ number_format((float)$course->price, 2) }}</span>
                            @endif
                        </div>
                        @if($course->sale_price)
                        <div class="flex justify-between">
                            <span class="text-heading/60">Sale Price</span>
                            <span class="text-red-500 font-semibold">-${{ number_format((float)$course->price - (float)$course->sale_price, 2) }}</span>
                        </div>
                        @endif
                        <div class="border-t border-gray-100 pt-2 flex justify-between font-bold text-heading">
                            <span>Total</span>
                            @if($course->payment_type === 'free')
                                <span class="text-free">Free</span>
                            @else
                                <span>${{ number_format((float)($course->sale_price ?? $course->price), 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <form method="POST" action="/enroll/{{ $course->id }}">
                    @csrf
                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="px-8 py-3 {{ $course->payment_type === 'free' ? 'bg-free' : 'bg-primary' }} text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">
                            @if($course->payment_type === 'free')
                                Confirm Free Enrollment
                            @else
                                Confirm & Enroll - ${{ number_format((float)($course->sale_price ?? $course->price), 2) }}
                            @endif
                        </button>
                        <a href="/courses/{{ $course->slug }}" class="text-sm text-heading/60 hover:text-primary transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div class="lg:w-80 shrink-0">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sticky top-28">
                    <h4 class="font-bold text-heading text-sm mb-4">Why Enroll?</h4>
                    <ul class="space-y-3 text-sm text-heading/70">
                        <li class="flex items-start gap-2">
                            <i class="ri-checkbox-circle-fill text-free mt-0.5"></i>
                            Lifetime access to all materials
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="ri-checkbox-circle-fill text-free mt-0.5"></i>
                            Learn at your own pace
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="ri-checkbox-circle-fill text-free mt-0.5"></i>
                            Certificate of completion
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="ri-checkbox-circle-fill text-free mt-0.5"></i>
                            Expert instructor support
                        </li>
                    </ul>
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2 text-xs text-heading/60">
                            <i class="ri-shield-check-line"></i>
                            Secure checkout
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection