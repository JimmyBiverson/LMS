@extends('layouts.app')

@section('title', $blog->title)

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Blog Detail</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/blogs" class="hover:text-primary transition-colors">Blogs</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Blog Detail</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1">
                <div class="bg-white rounded-xl overflow-hidden shadow-sm">
                    @if($blog->image)
                    <div class="h-72 overflow-hidden">
                        <img src="{{ asset('storage/'.$blog->image) }}" alt="{{ $blog->title }}" loading="lazy" class="w-full h-full object-cover">
                    </div>
                    @else
                    <div class="h-72 bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
                        <i class="ri-file-text-line text-8xl text-primary/20"></i>
                    </div>
                    @endif
                    <div class="p-8">
                        <div class="flex items-center gap-3 text-sm text-heading/60 mb-4">
                            <span class="px-3 py-1 rounded-full bg-primary-50 text-primary font-semibold">{{ $blog->category?->name ?? 'General' }}</span>
                            <span>{{ $blog->created_at->format('d M Y') }}</span>
                            <span>{{ $blog->author?->name ?? 'Admin' }}</span>
                        </div>
                        <h1 class="text-3xl lg:text-4xl font-extrabold text-heading mb-6">{{ $blog->title }}</h1>
                        @if($blog->excerpt)
                        <p class="text-heading/60 italic mb-6 border-l-4 border-primary pl-4">{{ $blog->excerpt }}</p>
                        @endif
                        <div class="text-heading/70 leading-relaxed space-y-4">
                            {!! nl2br(e($blog->content)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <aside class="lg:w-80 shrink-0">
                <div class="space-y-6 sticky top-28">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="font-bold text-heading mb-4">Author</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center">
                                <i class="ri-user-smile-line text-primary"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-heading text-sm">{{ $blog->author?->name ?? 'Admin' }}</p>
                                <p class="text-xs text-heading/60">Author</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="font-bold text-heading mb-4">Categories</h3>
                        <div class="space-y-2">
                            <a href="/blogs?category={{ $blog->blog_category_id }}" class="flex items-center justify-between text-sm text-heading/70 hover:text-primary transition-colors py-2 border-b border-gray-100">
                                <span>{{ $blog->category?->name ?? 'General' }}</span>
                                <i class="ri-arrow-right-s-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
