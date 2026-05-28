@extends('layouts.app')

@section('title', $bundle->title)

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">{{ $bundle->title }}</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/bundles" class="hover:text-primary transition-colors">Bundles</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">{{ $bundle->title }}</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-10">
            <div class="flex-1">
                <p class="text-heading/70 leading-relaxed mb-8">{{ $bundle->description }}</p>

                <h3 class="text-lg font-bold text-heading mb-4">Bundle Courses ({{ $bundle->courses->count() }})</h3>
                <div class="grid grid-cols-1 gap-4 mb-8">
                    @forelse($bundle->courses as $course)
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
                    @empty
                        <p class="text-heading/40">No courses in this bundle yet.</p>
                    @endforelse
                </div>
            </div>

            <aside class="lg:w-96 shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-28">
                    <div class="aspect-video bg-gradient-to-br from-primary-100 to-primary-50 rounded-lg flex items-center justify-center mb-6">
                        <i class="ri-price-tag-3-line text-6xl text-primary/30"></i>
                    </div>
                    <h3 class="font-bold text-heading text-lg mb-4">This Bundle Includes:</h3>
                    <div class="space-y-3 mb-6">
                        @if($bundle->level)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Bundle Level</span>
                            <span class="font-semibold text-heading">{{ $bundle->level }}</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Total Courses</span>
                            <span class="font-semibold text-heading">{{ $bundle->courses->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-heading/60">Price</span>
                            <span class="font-bold text-lg text-heading">{{ $bundle->displayPrice() }}</span>
                        </div>
                    </div>
                    @auth
                        <form method="POST" action="/cart/add-bundle/{{ $bundle->id }}">
                            @csrf
                            <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center block">
                                Add To Cart
                            </button>
                        </form>
                    @else
                        <a href="/login" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-center block">
                            Add To Cart
                        </a>
                    @endauth
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
