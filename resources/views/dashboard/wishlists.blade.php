@extends('layouts.dashboard')
@section('title', 'Wishlists')
@section('page-title', 'Wishlist')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
@if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
@endif
@if (session('info'))
    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold">{{ session('info') }}</div>
@endif
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($wishlists as $w)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden group hover:shadow-lg transition-all duration-300">
        <div class="relative">
            @if($w->course?->thumbnail)
                <img src="{{ asset('storage/' . $w->course->thumbnail) }}" alt="{{ $w->course->title }}" class="w-full h-48 object-cover">
            @else
                <div class="h-48 bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
                    <i class="ri-play-circle-line text-5xl text-primary/30"></i>
                </div>
            @endif
            <form method="POST" action="/dashboard/wishlists/toggle/{{ $w->course->id }}" class="absolute top-3 right-3">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-full bg-white/90 flex items-center justify-center text-red-500 hover:bg-white transition-all"><i class="ri-heart-fill"></i></button>
            </form>
        </div>
        <div class="p-5">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider">{{ $w->course?->category ?? 'General' }}</span>
            <h4 class="font-bold text-heading mt-1 group-hover:text-primary transition-colors">
                <a href="/courses/{{ $w->course?->slug ?? $w->course_id }}">{{ $w->course?->title ?? 'Deleted Course' }}</a>
            </h4>
            <div class="flex items-center gap-3 mt-2 text-xs text-heading/60">
                <span><i class="ri-book-open-line mr-1"></i>{{ $w->course?->lessons->count() ?? 0 }} Lessons</span>
                <span><i class="ri-time-line mr-1"></i>{{ $w->course?->duration ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                <span class="font-bold text-heading">
                    @if($w->course?->payment_type === 'free')
                        <span class="text-free">Free</span>
                    @elseif($w->course?->sale_price)
                        <span class="text-heading/40 line-through text-xs mr-1">${{ number_format($w->course->price, 2) }}</span>
                        ${{ number_format($w->course->sale_price, 2) }}
                    @else
                        ${{ number_format($w->course?->price ?? 0, 2) }}
                    @endif
                </span>
                <a href="/courses/{{ $w->course?->slug ?? $w->course_id }}/checkout" class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-full hover:opacity-90 transition-all duration-300">Enroll Now</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <i class="ri-heart-line text-5xl text-heading/20 block mb-4"></i>
        <p class="text-heading/50 text-sm">Your wishlist is empty.</p>
        <a href="/courses" class="mt-3 inline-block text-primary font-semibold hover:underline text-sm"><i class="ri-arrow-left-line mr-1"></i>Browse Courses</a>
    </div>
    @endforelse
</div>
<div class="mt-6 text-center"><a href="/courses" class="text-primary font-semibold hover:underline text-sm"><i class="ri-arrow-left-line mr-1"></i>Browse Courses</a></div>
@endsection
