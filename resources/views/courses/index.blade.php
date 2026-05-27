@extends('layouts.app')
@section('title', 'Course List - EduLab')
@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Courses</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Courses</span>
        </div>
    </div>
</section>
<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-3 mb-8">
            <a href="/courses"
               class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ !request('type') ? 'bg-primary text-white shadow-md shadow-primary/25' : 'bg-gray-100 text-heading hover:bg-gray-200' }}">
                All
            </a>
            <a href="/courses?type=free"
               class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request('type') === 'free' ? 'bg-green-500 text-white shadow-md shadow-green-500/25' : 'bg-gray-100 text-heading hover:bg-gray-200' }}">
                Free
            </a>
            <a href="/courses?type=paid"
               class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request('type') === 'paid' ? 'bg-primary text-white shadow-md shadow-primary/25' : 'bg-gray-100 text-heading hover:bg-gray-200' }}">
                Paid
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($courses as $course)
                <x-course-card
                    slug="{{ $course->id }}"
                    category="{{ $course->category }}"
                    paymentType="{{ $course->payment_type ?? 'free' }}"
                    price="{{ $course->price }}"
                    salePrice="{{ $course->sale_price }}"
                    title="{{ $course->title }}"
                    duration="{{ $course->duration ?? 'N/A' }}"
                    lessons="{{ $course->lessons->count() }}"
                />
            @empty
                <div class="col-span-full text-center py-12 text-heading/40">No courses available yet.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
