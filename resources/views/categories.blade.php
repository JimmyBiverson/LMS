@extends('layouts.app')
@section('title', 'Categories')
@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Categories</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a><i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Categories</span>
        </div>
    </div>
</section>
<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-category-card name="Web Development" courseCount="3" url="/courses?categories=1" icon="ri-global-line" />
            <x-category-card name="Web Development" courseCount="3" url="/courses?categories=2" icon="ri-code-s-slash-line" />
            <x-category-card name="UI/UX Design" courseCount="1" url="/courses?categories=3" icon="ri-pencil-ruler-line" />
            <x-category-card name="Digital Marketing" courseCount="1" url="/courses?categories=4" icon="ri-megaphone-line" />
        </div>
    </div>
</section>
@endsection