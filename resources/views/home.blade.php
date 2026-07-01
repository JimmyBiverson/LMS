@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- Hero Carousel with Video Player --}}
<section class="relative bg-[#F7F4FF] overflow-hidden"
    x-data="heroSlider"
    x-init="startAutoplay()"
    @mouseenter="stopAutoplay()"
    @mouseleave="startAutoplay()"
>
    <div class="absolute top-[-100px] left-[-100px] w-[400px] h-[400px] rounded-full bg-[#1AEBC5] opacity-15 blur-[200px] hidden md:block"></div>
    <div class="absolute bottom-[-50px] left-1/2 -translate-x-1/2 w-[500px] h-[300px] rounded-full bg-[#F98272] opacity-15 blur-[200px] hidden md:block"></div>
    <div class="absolute top-[-50px] right-[-50px] w-[350px] h-[350px] rounded-full bg-[#5F3EED] opacity-20 blur-[200px] hidden md:block"></div>

    <template x-if="slides.length === 0">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12 py-16 lg:py-24">
                <div class="flex-1 text-center lg:text-left">
                    <span class="text-primary font-bold text-sm uppercase tracking-wider">Best Education Tutor</span>
                    <h1 class="text-4xl lg:text-6xl xl:text-7xl font-extrabold text-heading leading-tight mt-4 mb-6">
                        Here is Your Course Chart for
                        <span class="text-primary">Success</span>
                    </h1>
                    <p class="text-heading/70 text-lg leading-relaxed mb-8 max-w-lg">
                        Discover expertly crafted courses designed to empower your skills and transform your career. Start learning today!
                    </p>
                    <a href="/courses" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 shadow-lg shadow-primary/25">
                        Get Started Now <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="flex-1">
                    <div class="relative">
                        <div class="w-full aspect-[4/3] bg-gradient-to-br from-primary-100 to-primary-50 rounded-3xl flex items-center justify-center">
                            <i class="ri-video-on-line text-8xl text-primary/20"></i>
                        </div>
                        <button @click="openVideo()" class="absolute -bottom-4 -right-4 w-24 h-24 bg-secondary rounded-2xl flex items-center justify-center shadow-lg hover:scale-105 transition-transform duration-300 animate-pulse-subtle">
                            <i class="ri-play-circle-fill text-white text-4xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template x-for="(slide, i) in slides" :key="i">
        <div x-show="current === i" x-transition:enter="transition-all duration-700" x-transition:enter-start="opacity-0 translate-x-16" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-500" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-16" class="max-w-7xl mx-auto px-4 relative z-10" x-cloak>
            <div class="flex flex-col lg:flex-row items-center gap-12 py-16 lg:py-24">
                <div class="flex-1 text-center lg:text-left">
                    <span class="text-primary font-bold text-sm uppercase tracking-wider" x-text="slide.subtitle || 'Best Education Tutor'"></span>
                    <h1 class="text-4xl lg:text-6xl xl:text-7xl font-extrabold text-heading leading-tight mt-4 mb-6" x-text="slide.title"></h1>
                    <p class="text-heading/70 text-lg leading-relaxed mb-8 max-w-lg" x-text="slide.description || 'Discover expertly crafted courses designed to empower your skills and transform your career.'"></p>
                    <a :href="slide.btn_link || '/courses'" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 shadow-lg shadow-primary/25">
                        <span x-text="slide.btn_text || 'Get Started Now'"></span> <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="flex-1">
                    <div class="relative">
                        <template x-if="slide.image">
                            <img :src="'/storage/' + slide.image" :alt="slide.title" loading="lazy" class="w-full aspect-[4/3] object-cover rounded-3xl shadow-lg">
                        </template>
                        <template x-if="!slide.image">
                            <div class="w-full aspect-[4/3] bg-gradient-to-br from-primary-100 to-primary-50 rounded-3xl flex items-center justify-center">
                                <i class="ri-video-on-line text-8xl text-primary/20"></i>
                            </div>
                        </template>
                        <button @click="openVideo()" class="absolute -bottom-4 -right-4 w-24 h-24 bg-secondary rounded-2xl flex items-center justify-center shadow-lg hover:scale-105 transition-transform duration-300 animate-pulse-subtle">
                            <i class="ri-play-circle-fill text-white text-4xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Carousel Controls --}}
    <div x-show="slides.length > 1" class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-3 z-20">
        <button @click="prev()" class="w-10 h-10 rounded-full bg-white/80 hover:bg-white shadow flex items-center justify-center transition-all duration-300">
            <i class="ri-arrow-left-s-line text-heading text-xl"></i>
        </button>
        <div class="flex items-center gap-2">
            <template x-for="(slide, i) in slides" :key="'dot-'+i">
                <button @click="goTo(i)" class="rounded-full transition-all duration-300" :class="current === i ? 'w-8 h-2.5 bg-primary' : 'w-2.5 h-2.5 bg-heading/20 hover:bg-heading/40'"></button>
            </template>
        </div>
        <button @click="next()" class="w-10 h-10 rounded-full bg-white/80 hover:bg-white shadow flex items-center justify-center transition-all duration-300">
            <i class="ri-arrow-right-s-line text-heading text-xl"></i>
        </button>
    </div>

    <div class="absolute bottom-0 left-0 w-full">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0 60V0C240 30 480 45 720 45C960 45 1200 30 1440 0V60H0Z" fill="white"/>
        </svg>
    </div>

    {{-- Floating Video Player --}}
    <div x-show="videoOpen"
         x-cloak
         x-transition:enter="transition-all duration-500 ease-out"
         x-transition:enter-start="opacity-0 scale-50 translate-y-20"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition-all duration-500 ease-in"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-0 translate-y-40"
         @keydown.window.escape="closeVideo()"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
         @click.self="closeVideo()">
        <div class="relative w-full max-w-4xl mx-4">
            <div class="bg-black rounded-2xl overflow-hidden shadow-2xl">
                <video id="heroVideo" controls class="w-full aspect-video" @ended="minimizeVideo()">
                    @if($school->slider_video)
                    <source src="{{ asset('storage/'.$school->slider_video) }}" type="video/mp4">
                    @else
                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                    @endif
                </video>
            </div>
            <button @click="closeVideo()" class="absolute -top-4 -right-4 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-100 transition-all duration-300">
                <i class="ri-close-line text-heading text-xl"></i>
            </button>
        </div>
    </div>

    {{-- Mini Player (after closing, shows minimized) --}}
    <div x-show="minimized"
         x-cloak
         x-transition:enter="transition-all duration-500 ease-out"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition-all duration-300 ease-in"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         class="fixed bottom-6 right-6 z-50 w-72 bg-black rounded-xl overflow-hidden shadow-2xl cursor-pointer group"
         @click="restoreVideo()">
        <video class="w-full aspect-video" muted loop autoplay>
            @if($school->slider_video)
            <source src="{{ asset('storage/'.$school->slider_video) }}" type="video/mp4">
            @else
            <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
            @endif
        </video>
        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <i class="ri-play-circle-fill text-white text-4xl"></i>
        </div>
        <button @click.stop="minimized = false" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <i class="ri-close-line"></i>
        </button>
    </div>
</section>

{{-- Featured Courses --}}
@if($featuredCourses->isNotEmpty())
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12" data-aos="fade-up">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Featured</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    Featured <span class="text-primary">Courses</span>
                </h2>
            </div>
            <a href="/courses" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                View All <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up" data-aos-delay="100">
            @foreach($featuredCourses as $course)
            <div data-aos="fade-up" data-aos-delay="{{ 150 + $loop->index * 50 }}">
                <x-course-card :course="$course" />
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Top Categories --}}
@if($categories->isNotEmpty())
<section class="py-16 lg:py-24 bg-[#F7F4FF]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12" data-aos="fade-up">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Top Category</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    Explore Top <span class="text-primary">Categories</span>
                </h2>
            </div>
            <a href="/courses" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                View All <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up" data-aos-delay="100">
            @foreach($categories as $cat)
            <x-category-card name="{{ $cat->name }}" courseCount="{{ $cat->courses_count }}" url="/courses" icon="ri-bookmark-line" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Popular Courses --}}
@if($courses->isNotEmpty())
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12" data-aos="fade-up">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Popular Course</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    See Our Popular <span class="text-primary">Courses</span>
                </h2>
            </div>
            <a href="/courses" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-semibold rounded-full hover:opacity-90 transition-all duration-300">
                View all course <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
            @foreach($courses as $course)
            <x-course-card :course="$course" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
@if($testimonials->isNotEmpty())
<section class="py-16 lg:py-24 bg-[#F7F4FF]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-primary font-bold text-sm uppercase tracking-wider">Testimonials</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                What Our <span class="text-primary">Students Say</span>
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="100">
            @foreach($testimonials as $t)
            <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ 150 + $loop->index * 100 }}">
                <div class="flex items-center gap-1 text-amber-400 mb-4">
                    @for($s=0;$s<5;$s++)
                        <i class="{{ $s < $t->rating ? 'ri-star-fill' : 'ri-star-line' }}"></i>
                    @endfor
                </div>
                <p class="text-heading/70 leading-relaxed mb-6">{{ $t->content }}</p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center overflow-hidden">
                        @if($t->avatar)
                            <img src="{{ asset('storage/'.$t->avatar) }}" alt="{{ $t->name }}" loading="lazy" class="w-full h-full object-cover">
                        @else
                            <i class="ri-user-smile-line text-primary"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-heading text-sm">{{ $t->name }}</h4>
                        <p class="text-xs text-heading/60">{{ $t->position ?? 'Student' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Stats with Animated Counters (Enhanced) --}}
<style>
    .count-up { display: inline-block; }
    .counter-pulse { animation: counterPulse 0.6s ease-in-out; }
    @keyframes counterPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.15); }
    }
</style>
<section class="py-20 bg-gradient-to-r from-[#111827] via-[#1a2332] to-[#111827] text-white relative overflow-hidden"
    x-data="{
        courses: 0,
        instructors: 0,
        students: 0,
        enrollments: 0,
        animating: false,
        done: false,
        animate() {
            if (this.done) return;
            this.done = true;
            this.animating = true;
            const t = 2500;
            const frames = 60;
            const interval = t / frames;
            const c = {{ $stats['totalCourses'] }};
            const i = {{ $stats['totalInstructors'] }};
            const s = {{ $stats['totalStudents'] }};
            const e = {{ $stats['totalEnrollments'] }};
            let frame = 0;
            const timer = setInterval(() => {
                frame++;
                const progress = frame / frames;
                const eased = 1 - Math.pow(1 - progress, 3);
                this.courses = Math.round(c * eased);
                this.instructors = Math.round(i * eased);
                this.students = Math.round(s * eased);
                this.enrollments = Math.round(e * eased);
                if (frame >= frames) {
                    this.courses = c;
                    this.instructors = i;
                    this.students = s;
                    this.enrollments = e;
                    this.animating = false;
                    clearInterval(timer);
                }
            }, interval);
        }
    }"
    x-init="
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                animate();
                observer.disconnect();
            }
        });
        observer.observe($el);
    ">
    <div class="absolute top-0 left-0 w-full h-full opacity-5">
        <div class="absolute top-10 left-10 w-40 h-40 rounded-full bg-primary blur-[100px]"></div>
        <div class="absolute bottom-10 right-10 w-60 h-60 rounded-full bg-secondary blur-[100px]"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-secondary font-bold text-sm uppercase tracking-wider">Our Impact</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold mt-2">
                Platform <span class="text-secondary">Statistics</span>
            </h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8 text-center" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6 sm:p-8 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300" :class="{ 'counter-pulse': animating }">
                    <i class="ri-book-open-line text-3xl text-secondary"></i>
                </div>
                <span class="text-4xl lg:text-5xl font-extrabold text-secondary count-up"><span x-text="courses">0</span><span x-show="done">+</span></span>
                <p class="text-white/70 mt-2 font-medium text-sm sm:text-base">Active Courses</p>
            </div>
            <div class="p-6 sm:p-8 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300" :class="{ 'counter-pulse': animating }">
                    <i class="ri-user-star-line text-3xl text-secondary"></i>
                </div>
                <span class="text-4xl lg:text-5xl font-extrabold text-secondary count-up"><span x-text="instructors">0</span><span x-show="done">+</span></span>
                <p class="text-white/70 mt-2 font-medium text-sm sm:text-base">Expert Tutors</p>
            </div>
            <div class="p-6 sm:p-8 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300" :class="{ 'counter-pulse': animating }">
                    <i class="ri-group-line text-3xl text-secondary"></i>
                </div>
                <span class="text-4xl lg:text-5xl font-extrabold text-secondary count-up"><span x-text="students">0</span><span x-show="done">+</span></span>
                <p class="text-white/70 mt-2 font-medium text-sm sm:text-base">Enrolled Students</p>
            </div>
            <div class="p-6 sm:p-8 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300" :class="{ 'counter-pulse': animating }">
                    <i class="ri-user-add-line text-3xl text-secondary"></i>
                </div>
                <span class="text-4xl lg:text-5xl font-extrabold text-secondary count-up"><span x-text="enrollments">0</span><span x-show="done">+</span></span>
                <p class="text-white/70 mt-2 font-medium text-sm sm:text-base">Total Enrollments</p>
            </div>
        </div>
    </div>
</section>

{{-- Upcoming Courses --}}
@if($courses->count() > 1)
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Upcoming Courses</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    Our Upcoming <span class="text-primary">Courses</span>
                </h2>
            </div>
            <a href="/courses?upcoming=1" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                View Upcoming Course <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
            @foreach($courses->take(3) as $course)
            <div data-aos="fade-up" data-aos-delay="{{ 150 + $loop->index * 50 }}">
                <x-course-card :course="$course" />
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Course Bundles --}}
@if($bundles->isNotEmpty())
<section class="py-16 lg:py-24 bg-[#F7F4FF]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-primary font-bold text-sm uppercase tracking-wider">Latest Bundle</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                See Our Popular Bundle <span class="text-primary">Courses</span>
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            @foreach($bundles as $bundle)
                <a href="/bundles/{{ $bundle->slug }}" class="group bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 text-center" data-aos="fade-up" data-aos-delay="{{ 150 + $loop->index * 100 }}">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full {{ $loop->even ? 'bg-primary/10' : 'bg-secondary/20' }} flex items-center justify-center">
                        <i class="ri-discount-percent-fill text-3xl {{ $loop->even ? 'text-primary' : 'text-secondary' }}"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-heading mb-2">{{ $bundle->displayPrice() }}</div>
                    <h3 class="font-bold text-heading text-lg group-hover:text-primary transition-colors duration-300">{{ $bundle->title }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Instructors --}}
@if($instructors->isNotEmpty())
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12" data-aos="fade-up">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Our Team Member</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    Meet Our Best <span class="text-primary">Instructors</span>
                </h2>
            </div>
            <a href="/instructors" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                More Instructors <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up" data-aos-delay="100">
            @foreach($instructors as $instructor)
            <div data-aos="fade-up" data-aos-delay="{{ 150 + $loop->index * 50 }}">
                <x-instructor-card :name="$instructor->name" :designation="$instructor->designation ?? 'Instructor'" :image="$instructor->profile_image ?? null" url="/users/{{ $instructor->id }}/profile" />
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Become an Instructor --}}
<section class="py-16 lg:py-24 bg-[#F7F4FF]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1" data-aos="fade-right">
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Intro</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2 mb-8">
                    Become an <span class="text-primary">Instructor</span>
                </h2>
                <form method="POST" action="/become-instructor" class="space-y-4 max-w-lg">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input name="first_name" type="text" placeholder="First Name *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                        <input name="last_name" type="text" placeholder="Last Name *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    </div>
                    <input name="email" type="email" placeholder="Email *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    <input name="phone" type="tel" placeholder="Phone *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input name="password" type="password" placeholder="Password *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                        <input name="password_confirmation" type="password" placeholder="Confirm Password *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    </div>
                    <input name="designation" type="text" placeholder="Designation *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    <textarea name="about" rows="3" placeholder="About" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary"></textarea>
                    <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">
                        Join as Instructor
                    </button>
                </form>
            </div>
            <div class="flex-1" data-aos="fade-left">
                <div class="w-full aspect-square bg-gradient-to-br from-primary-100 to-primary-50 rounded-3xl flex items-center justify-center">
                    <i class="ri-team-line text-9xl text-primary/20"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Blog Posts --}}
@if($blogs->isNotEmpty())
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12" data-aos="fade-up">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Frequent Updates</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    Updated News & <span class="text-primary">Blogs</span>
                </h2>
            </div>
            <a href="/blogs" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                See all blog <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
            @foreach($blogs as $blog)
            <div data-aos="fade-up" data-aos-delay="{{ 150 + $loop->index * 50 }}">
                <x-blog-card
                    slug="{{ $blog->slug }}"
                    category="{{ $blog->category?->name ?? 'General' }}"
                    author="{{ $blog->author?->name ?? 'Admin' }}"
                    date="{{ $blog->created_at->format('d M Y') }}"
                    title="{{ $blog->title }}"
                    image="{{ $blog->image }}"
                />
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<style>
@keyframes pulse-subtle {
    0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(244, 184, 38, 0.4); }
    50% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(244, 184, 38, 0); }
}
.animate-pulse-subtle { animation: pulse-subtle 2s infinite; }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('heroSlider', () => ({
            slides: @json($sliders->toArray()),
            current: 0,
            autoplayInterval: null,
            videoOpen: false,
            minimized: false,
            startAutoplay() {
                if (this.slides.length <= 1) return;
                const delay = (this.slides[this.current].duration || 6) * 1000;
                this.autoplayInterval = setTimeout(() => {
                    this.current = (this.current + 1) % this.slides.length;
                    this.startAutoplay();
                }, delay);
            },
            stopAutoplay() { clearTimeout(this.autoplayInterval); },
            goTo(i) { this.current = i; this.stopAutoplay(); this.startAutoplay(); },
            prev() { this.current = (this.current - 1 + this.slides.length) % this.slides.length; this.stopAutoplay(); this.startAutoplay(); },
            next() { this.current = (this.current + 1) % this.slides.length; this.stopAutoplay(); this.startAutoplay(); },
            openVideo() {
                this.videoOpen = true;
                this.minimized = false;
            },
            closeVideo() {
                this.videoOpen = false;
                setTimeout(() => { this.minimized = true; }, 300);
            },
            minimizeVideo() {
                this.videoOpen = false;
                setTimeout(() => { this.minimized = true; }, 300);
            },
            restoreVideo() {
                this.minimized = false;
                this.videoOpen = true;
            }
        }));
    });
</script>
@endsection
