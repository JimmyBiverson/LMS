@extends('layouts.app')
@section('title', "Search: $query")
@section('content')
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-10">
            <form action="/search" method="GET" class="max-w-2xl mx-auto">
                <div class="flex gap-3">
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search courses and blogs..." class="flex-1 px-6 py-4 rounded-full border border-heading/10 text-sm focus:outline-none focus:border-primary">
                    <button type="submit" class="px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Search</button>
                </div>
            </form>
        </div>
        <h2 class="text-2xl font-bold text-heading mb-2">Results for "{{ $query }}"</h2>
        <p class="text-heading/60 mb-10">{{ $courses->count() + $blogs->count() }} results found</p>

        @if($courses->isNotEmpty())
            <h3 class="text-xl font-bold text-heading mb-6">Courses</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($courses as $course)
                    <x-course-card
                        :slug="$course->slug"
                        level="{{ $course->level?->name ?? 'Intermediate' }}"
                        :title="$course->title"
                        :category="$course->category"
                        :payment-type="$course->payment_type"
                        :price="$course->price"
                        :sale-price="$course->sale_price"
                        :duration="$course->duration"
                        :lessons="$course->lessons->count()"
                    />
                @endforeach
            </div>
        @endif

        @if($blogs->isNotEmpty())
            <h3 class="text-xl font-bold text-heading mb-6">Blogs</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($blogs as $blog)
                    <x-blog-card
                        :slug="$blog->slug"
                        :title="$blog->title"
                        :category="$blog->category?->name ?? 'Blog'"
                        :author="$blog->author?->name ?? 'Admin'"
                        :date="$blog->created_at->format('M d, Y')"
                    />
                @endforeach
            </div>
        @endif

        @if($courses->isEmpty() && $blogs->isEmpty())
            <div class="text-center py-20">
                <i class="ri-search-line text-6xl text-heading/20 mb-4"></i>
                <p class="text-lg text-heading/40">No results found for "{{ $query }}". Try different keywords.</p>
            </div>
        @endif
    </div>
</section>
@endsection
