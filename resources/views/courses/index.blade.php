@extends('layouts.app')
@section('title', 'Course List - EduLab')
@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Courses</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Courses</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Active Filters Bar --}}
        @php
            $activeFilters = collect();
            if (request('categories')) {
                $cat = $categories->firstWhere('id', (int)request('categories'));
                if ($cat) $activeFilters->push(['label' => $cat->name, 'param' => 'categories']);
            }
            if (request('level')) {
                $lv = $levels->firstWhere('id', (int)request('level'));
                if ($lv) $activeFilters->push(['label' => $lv->name, 'param' => 'level']);
            }
            if (request('tag')) {
                $tg = $tags->firstWhere('id', (int)request('tag'));
                if ($tg) $activeFilters->push(['label' => $tg->name, 'param' => 'tag']);
            }
        @endphp

        @if($activeFilters->isNotEmpty())
        <div class="flex items-center gap-2 mb-6 flex-wrap">
            <span class="text-xs font-semibold text-heading/50 uppercase tracking-wider">Active Filters:</span>
            @foreach($activeFilters as $filter)
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary">
                {{ $filter['label'] }}
                <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except([$filter['param'], 'page']))) }}" class="hover:text-red-500 transition-colors">
                    <i class="ri-close-line"></i>
                </a>
            </span>
            @endforeach
            <a href="/courses" class="text-xs text-red-500 hover:underline font-semibold ml-2">Clear All</a>
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Filter Sidebar --}}
            <aside class="lg:w-64 shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sticky top-24 space-y-6">
                    {{-- Type Tabs --}}
                    <div>
                        <p class="text-xs font-semibold text-heading/50 uppercase tracking-wider mb-3">Course Type</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ request()->fullUrlWithQuery(['type' => null, 'page' => null]) }}"
                               class="px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 {{ !request('type') ? 'bg-primary text-white shadow-md shadow-primary/25' : 'bg-gray-100 text-heading hover:bg-gray-200' }}">
                                All
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'free', 'page' => null]) }}"
                               class="px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 {{ request('type') === 'free' ? 'bg-green-500 text-white shadow-md shadow-green-500/25' : 'bg-gray-100 text-heading hover:bg-gray-200' }}">
                                Free
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'paid', 'page' => null]) }}"
                               class="px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 {{ request('type') === 'paid' ? 'bg-primary text-white shadow-md shadow-primary/25' : 'bg-gray-100 text-heading hover:bg-gray-200' }}">
                                Paid
                            </a>
                        </div>
                    </div>

                    {{-- Categories --}}
                    <div>
                        <p class="text-xs font-semibold text-heading/50 uppercase tracking-wider mb-3">Category</p>
                        <div class="space-y-1 max-h-48 overflow-y-auto">
                            <a href="{{ request()->fullUrlWithQuery(['categories' => null, 'page' => null]) }}"
                               class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ !request('categories') ? 'bg-primary-50 text-primary font-semibold' : 'text-heading/70 hover:bg-gray-50 hover:text-heading' }}">
                                All Categories
                            </a>
                            @foreach($categories as $cat)
                            <a href="{{ request()->fullUrlWithQuery(['categories' => $cat->id, 'page' => null]) }}"
                               class="block px-3 py-1.5 rounded-lg text-sm transition-colors flex items-center justify-between {{ request('categories') == $cat->id ? 'bg-primary-50 text-primary font-semibold' : 'text-heading/70 hover:bg-gray-50 hover:text-heading' }}">
                                {{ $cat->name }}
                                <span class="text-xs text-heading/40">({{ $cat->courses_count }})</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Levels --}}
                    <div>
                        <p class="text-xs font-semibold text-heading/50 uppercase tracking-wider mb-3">Level</p>
                        <div class="space-y-1">
                            <a href="{{ request()->fullUrlWithQuery(['level' => null, 'page' => null]) }}"
                               class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ !request('level') ? 'bg-primary-50 text-primary font-semibold' : 'text-heading/70 hover:bg-gray-50 hover:text-heading' }}">
                                All Levels
                            </a>
                            @foreach($levels as $lv)
                            <a href="{{ request()->fullUrlWithQuery(['level' => $lv->id, 'page' => null]) }}"
                               class="block px-3 py-1.5 rounded-lg text-sm transition-colors {{ request('level') == $lv->id ? 'bg-primary-50 text-primary font-semibold' : 'text-heading/70 hover:bg-gray-50 hover:text-heading' }}">
                                {{ $lv->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Course Grid --}}
            <div class="flex-1 min-w-0">
                {{-- Sort & Results Count --}}
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm text-heading/60">
                        <span class="font-semibold text-heading">{{ $courses->total() }}</span> courses found
                    </p>
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-xs text-heading/60 font-semibold">Sort:</label>
                        <select id="sort" onchange="window.location.href = this.value"
                                class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:border-primary bg-white">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest', 'page' => null]) }}" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular', 'page' => null]) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest', 'page' => null]) }}" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-low', 'page' => null]) }}" {{ request('sort') === 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-high', 'page' => null]) }}" {{ request('sort') === 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($courses as $course)
                        <x-course-card :course="$course" />
                    @empty
                        <div class="col-span-full text-center py-16 text-heading/40">
                            <i class="ri-book-open-line text-5xl block mb-3"></i>
                            <p class="font-semibold text-lg">No courses match your filters.</p>
                            <p class="text-sm mt-1">Try adjusting your search criteria.</p>
                            <a href="/courses" class="inline-block mt-4 px-5 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 transition-opacity">Clear Filters</a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if(method_exists($courses, 'hasPages') && $courses->hasPages())
                <div class="mt-10 flex items-center justify-center">
                    {{ $courses->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection