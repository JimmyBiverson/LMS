@extends('layouts.app')

@section('title', 'Blog Detail')

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
                    <div class="h-72 bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
                        <i class="ri-file-text-line text-8xl text-primary/20"></i>
                    </div>
                    <div class="p-8">
                        <div class="flex items-center gap-3 text-sm text-heading/60 mb-4">
                            <span class="px-3 py-1 rounded-full bg-primary-50 text-primary font-semibold">Programming Languages</span>
                            <span>05 Dec 2024</span>
                            <span>0 Comments</span>
                        </div>
                        <h1 class="text-3xl lg:text-4xl font-extrabold text-heading mb-6">How Kindergarten Shapes Future Achievements</h1>
                        <div class="text-heading/70 leading-relaxed space-y-4">
                            <p>Covers actionable career advice, growth strategies, and insights from industry leaders. This category covers everything from acing interviews to climbing the corporate ladder, helping you achieve your professional goals and unlock new opportunities.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="lg:w-80 shrink-0">
                <div class="space-y-6 sticky top-28">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="font-bold text-heading mb-4">Categories</h3>
                        <div class="space-y-2">
                            <a href="/blogs?category=1" class="flex items-center justify-between text-sm text-heading/70 hover:text-primary transition-colors py-2 border-b border-gray-100">
                                <span>Design of Art</span>
                                <i class="ri-arrow-right-s-line"></i>
                            </a>
                            <a href="/blogs?category=2" class="flex items-center justify-between text-sm text-heading/70 hover:text-primary transition-colors py-2 border-b border-gray-100">
                                <span>Design of Art</span>
                                <i class="ri-arrow-right-s-line"></i>
                            </a>
                            <a href="/blogs?category=3" class="flex items-center justify-between text-sm text-heading/70 hover:text-primary transition-colors py-2 border-b border-gray-100">
                                <span>UI/UX Design</span>
                                <i class="ri-arrow-right-s-line"></i>
                            </a>
                            <a href="/blogs?category=4" class="flex items-center justify-between text-sm text-heading/70 hover:text-primary transition-colors py-2">
                                <span>Programming Languages</span>
                                <i class="ri-arrow-right-s-line"></i>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="font-bold text-heading mb-4">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            <a href="/blogs?category=3" class="px-3 py-1 rounded-full bg-primary-50 text-primary text-xs font-semibold hover:bg-primary hover:text-white transition-all duration-300">UI/UX Design</a>
                            <a href="/blogs?category=1" class="px-3 py-1 rounded-full bg-primary-50 text-primary text-xs font-semibold hover:bg-primary hover:text-white transition-all duration-300">Design of Art</a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection