@extends('layouts.app')

@section('title', 'Instructors')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Instructors</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Instructors</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <aside class="lg:w-72 shrink-0">
                <div class="bg-white rounded-xl p-6 shadow-sm space-y-6">
                    <div>
                        <h3 class="font-bold text-heading mb-3">Designation</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="text-primary rounded focus:ring-primary">
                                <span class="text-sm text-heading/70">Senior Web Developer (1)</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-heading mb-3">Time Zones</h3>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <x-instructor-card name="Robert Smith" designation="Senior Web Developer" url="/users/3/profile" />
                </div>
            </div>
        </div>
    </div>
</section>
@endsection