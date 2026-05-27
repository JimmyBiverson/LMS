@extends('layouts.app')

@section('title', 'Blogs')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Our Blogs</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Blogs</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-blog-card slug="the-importance-of-programming-in-our-everyday-lives" category="Programming Languages" author="Admin" date="05 Dec 2024" title="How Kindergarten Shapes Future Achievements" />
            <x-blog-card slug="how-kindergarten-shapes-future-achievements" category="Design of Art" author="Admin" date="04 Dec 2024" title="How Kindergarten Shapes Future Achievements" />
            <x-blog-card slug="the-power-of-lifelong-learning-never-stop-growing" category="Design of Art" author="Admin" date="27 Nov 2024" title="The Power of Lifelong Learning, Never Stop Growing" />
            <x-blog-card slug="top-10-courses-to-boost-your-best-career-in-2025" category="Design of Art" author="Admin" date="27 Nov 2024" title="Top 10 Courses to Boost Your Best Career in 2025" />
        </div>
    </div>
</section>
@endsection