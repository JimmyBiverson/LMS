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
            @php $categories = \App\Models\Category::withCount('courses')->get(); @endphp
            @forelse($categories as $cat)
            <x-category-card name="{{ $cat->name }}" courseCount="{{ $cat->courses_count }}" url="/courses?categories={{ $cat->id }}" icon="ri-bookmark-line" />
            @empty
            <p class="col-span-full text-center text-heading/50 text-sm">No categories available yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection