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
            @forelse($blogs as $b)
            <x-blog-card slug="{{ $b->slug }}" category="{{ $b->category?->name ?? 'General' }}" author="{{ $b->author?->name ?? 'Admin' }}" date="{{ $b->created_at->format('d M Y') }}" title="{{ $b->title }}" />
            @empty
            <div class="col-span-full text-center py-12 text-heading/40">No blog posts yet.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
