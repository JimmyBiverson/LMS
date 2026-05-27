@extends('layouts.app')

@section('title', 'Organizations')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Organization</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Organization</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <aside class="lg:w-72 shrink-0">
                <div class="bg-white rounded-xl p-6 shadow-sm space-y-6">
                    <div>
                        <h3 class="font-bold text-heading mb-3">Time Zone</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="text-primary rounded focus:ring-primary">
                                <span class="text-sm text-heading/70">Africa/Lusaka</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-heading mb-3">Language</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="text-primary rounded focus:ring-primary">
                                <span class="text-sm text-heading/70">English</span>
                            </label>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="flex-1">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm text-heading/60">Showing 1 - 1 of 1 Results</p>
                    <button class="flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary text-sm font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                        <i class="ri-filter-line"></i> Filter
                    </button>
                </div>

                <a href="/users/2/profile" class="block group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-6 p-6">
                        <div class="w-20 h-20 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                            <i class="ri-building-line text-3xl text-primary"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-heading text-lg group-hover:text-primary transition-colors duration-300">Codexshapper</h3>
                            <p class="text-sm text-heading/60">Toronto, Canada</p>
                            <div class="flex items-center gap-6 mt-3 text-sm">
                                <span class="text-heading/60">0 Courses</span>
                                <span class="text-heading/60">0 Total instructors</span>
                            </div>
                        </div>
                        <i class="ri-arrow-right-s-line text-2xl text-heading/30 group-hover:text-primary transition-colors duration-300"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection