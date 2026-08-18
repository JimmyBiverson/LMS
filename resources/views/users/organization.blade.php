@extends('layouts.app')
@section('title', 'Organization Details')
@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Organization Details</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a><i class="ri-arrow-right-s-line"></i>
            <a href="/organizations" class="hover:text-primary transition-colors">Organizations</a><i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Organization Details</span>
        </div>
    </div>
</section>
<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-xl p-8 shadow-sm mb-8">
            <div class="flex items-start gap-6">
                <div class="w-24 h-24 rounded-full bg-primary-50 flex items-center justify-center shrink-0"><i class="ri-building-line text-4xl text-primary"></i></div>
                <div class="flex-1">
                    <h2 class="text-2xl font-extrabold text-heading">Codexshapper</h2>
                    <p class="text-heading/60 text-sm">Toronto, Canada</p>
                    <div class="flex items-center gap-6 mt-3 text-sm text-heading/60">
                        <span>0 Courses</span><span>0 Total instructors</span>
                    </div>
                    <div class="flex items-center gap-3 mt-4">
                        <span x-data="{ open: false }" class="relative inline-block" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="javascript:void(0)" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300"><i class="ri-mail-line mr-1"></i>Send Email</a>
                            <div x-show="open" x-cloak class="absolute bottom-full right-0 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap shadow-lg z-50">Coming soon</div>
                        </span>
                        <span x-data="{ open: false }" class="relative inline-block" @mouseenter="open = true" @mouseleave="open = false">
                            <a href="javascript:void(0)" class="px-5 py-2.5 bg-secondary text-heading text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300"><i class="ri-share-line mr-1"></i>Share</a>
                            <div x-show="open" x-cloak class="absolute bottom-full right-0 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap shadow-lg z-50">Coming soon</div>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-8 shadow-sm">
            <h3 class="text-lg font-bold text-heading mb-4">About Us</h3>
            <p class="text-heading/70 leading-relaxed">At {{ $school->school_name ?? 'Edulab' }}, we understand that learning is at the heart of growth, innovation, and success. That's why we've created a user-friendly platform that caters to diverse audiences, from students and employees to instructors and administrators. Our LMS is built to scale, making it the perfect solution for organizations of all sizes.</p>
        </div>
    </div>
</section>
@endsection