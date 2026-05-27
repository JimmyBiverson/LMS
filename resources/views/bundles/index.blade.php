@extends('layouts.app')

@section('title', 'Course Bundles')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Our Bundles</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Bundle</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
            <a href="/bundles/full-stack-development-mastery-bundle" class="group bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-secondary/20 flex items-center justify-center">
                    <i class="ri-discount-percent-fill text-3xl text-secondary"></i>
                </div>
                <div class="text-3xl font-extrabold text-heading mb-2">$499</div>
                <h3 class="font-bold text-heading text-lg group-hover:text-primary transition-colors duration-300">Full Stack Development Mastery Bundle</h3>
            </a>
            <a href="/bundles/digital-marketing-power-bundle" class="group bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="ri-price-tag-3-fill text-3xl text-primary"></i>
                </div>
                <div class="text-3xl font-extrabold text-heading mb-2">$160</div>
                <h3 class="font-bold text-heading text-lg group-hover:text-primary transition-colors duration-300">Full Stack Development Mastery Bundle</h3>
            </a>
        </div>
    </div>
</section>
@endsection