@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">About Us</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">About Us</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1">
                <span class="text-primary font-bold text-sm uppercase tracking-wider">About Us</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2 mb-6">
                    Connect with Experts and<br>
                    Learn from <span class="text-primary">Anywhere</span>
                </h2>
                <p class="text-heading/70 leading-relaxed">
                    We believe that the foundation of a child's education should be built on creativity, curiosity, and joy. Our mission is to make learning accessible, exciting, and tailored to every child's unique pace and style.
                </p>
            </div>
            <div class="flex-1">
                <div class="w-full aspect-[4/3] bg-gradient-to-br from-primary-100 to-primary-50 rounded-3xl flex items-center justify-center">
                    <i class="ri-graduation-cap-fill text-9xl text-primary/20"></i>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection