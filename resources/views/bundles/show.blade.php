@extends('layouts.app')

@section('title', 'Bundle Details')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Bundle Details</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/bundles" class="hover:text-primary transition-colors">Bundles</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Bundle Details</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-10">
            <div class="flex-1">
                <h1 class="text-3xl lg:text-4xl font-extrabold text-heading mb-4">Full Stack Development Mastery Bundle</h1>
                <p class="text-heading/70 leading-relaxed mb-8">
                    Master the art of full-stack web development with this comprehensive bundle. Learn frontend technologies like HTML, CSS, and React, along with backend frameworks like Node.js and databases like MongoDB. This bundle also includes a crash course on deploying applications using AWS and Docker.
                </p>

                <div class="border-b border-gray-200 mb-8">
                    <nav class="flex gap-8">
                        <button class="pb-3 border-b-2 border-primary text-primary font-bold text-sm">Bundle Overview</button>
                        <button class="pb-3 border-b-2 border-transparent text-heading/60 font-semibold text-sm hover:text-primary transition-colors">Course</button>
                        <button class="pb-3 border-b-2 border-transparent text-heading/60 font-semibold text-sm hover:text-primary transition-colors">Instructor</button>
                    </nav>
                </div>

                <h2 class="text-xl font-bold text-heading mb-4">Bundle Overview</h2>
                <p class="text-heading/70 leading-relaxed mb-6">
                    Master the art of full-stack web development with this comprehensive bundle. Learn frontend technologies like HTML, CSS, and React, along with backend frameworks like Node.js and databases like MongoDB. This bundle also includes a crash course on deploying applications using AWS and Docker.
                </p>

                <h3 class="text-lg font-bold text-heading mb-4">Bundle Courses</h3>
                <div class="grid grid-cols-1 gap-4 mb-8">
                    <x-course-card slug="e-commerce-development" level="Advanced" category="Web Development" price="25.50" title="Full Stack Web Development with JavaScript" rating="0" duration="2h 30m" lessons="2" students="0" />
                    <x-course-card slug="full-stack-web-development-with-php-laravel-vue-js" level="Intermediate" category="Web Development" price="82" title="Full-Stack Web Development Bootcamp" rating="0" duration="2h 30m" lessons="4" students="0" />
                    <x-course-card slug="wordpress-theme-development" level="Professional" category="Web Development" price="42.30" title="WordPress Theme Development Course for Building Custom and Dynamic Websites" rating="0" duration="1h 20m" lessons="1" students="0" />
                </div>
            </div>

            <aside class="lg:w-96 shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-28">
                    <div class="aspect-video bg-gradient-to-br from-primary-100 to-primary-50 rounded-lg flex items-center justify-center mb-6">
                        <i class="ri-price-tag-3-line text-6xl text-primary/30"></i>
                    </div>
                    <h3 class="font-bold text-heading text-lg mb-4">This Bundle Includes:</h3>
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Bundle Level</span>
                            <span class="font-semibold text-heading">Advanced</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Total Course</span>
                            <span class="font-semibold text-heading">3</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Price</span>
                            <span class="font-bold text-lg text-heading">$499</span>
                        </div>
                    </div>
                    <a href="/login" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center block">
                        Add To Cart
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection