@extends('layouts.dashboard')
@section('title', 'Wishlists')
@section('page-title', 'Wishlist')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @for($i=1;$i<=6;$i++)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden group hover:shadow-lg transition-all duration-300">
        <div class="relative">
            <img src="https://placehold.co/800x500/5F3EED/FFFFFF?text=Course+{{ $i }}" alt class="w-full h-48 object-cover">
            <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 flex items-center justify-center text-red-500 hover:bg-white transition-all"><i class="ri-heart-fill"></i></button>
        </div>
        <div class="p-5">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider">Development</span>
            <h4 class="font-bold text-heading mt-1 group-hover:text-primary transition-colors">Course Title {{ $i }}</h4>
            <div class="flex items-center gap-3 mt-2 text-xs text-heading/60"><span><i class="ri-book-open-line mr-1"></i>12 Lessons</span><span><i class="ri-time-line mr-1"></i>8h 30m</span></div>
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                <span class="font-bold text-heading">$49.00</span>
                <a href="#" class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-full hover:opacity-90 transition-all duration-300"><i class="ri-shopping-cart-line mr-1"></i>Add to Cart</a>
            </div>
        </div>
    </div>
    @endfor
</div>
<div class="mt-6 text-center"><a href="#" class="text-primary font-semibold hover:underline text-sm"><i class="ri-arrow-left-line mr-1"></i>Browse Courses</a></div>
@endsection