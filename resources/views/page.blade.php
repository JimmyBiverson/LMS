@extends('layouts.app')

@section('title', $page->title)

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">{{ $page->title }}</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">{{ $page->title }}</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl p-8 shadow-sm prose prose-sm max-w-none text-heading/70">
            {!! $page->content !!}
        </div>
    </div>
</section>
@endsection
