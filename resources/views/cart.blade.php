@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Shopping Cart</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Shopping Cart</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center py-16">
            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-primary-50 flex items-center justify-center">
                <i class="ri-shopping-cart-line text-4xl text-primary"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-heading mb-3">Ready to Learn?</h2>
            <p class="text-heading/60 mb-6">Your cart is waiting to be filled with knowledge! Discover new courses and kickstart your education.</p>
            <a href="/courses" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">
                Learning Continue <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    </div>
</section>
@endsection