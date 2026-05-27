@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- Hero Section --}}
<section class="relative bg-[#F7F4FF] overflow-hidden">
    <div class="absolute top-[-100px] left-[-100px] w-[400px] h-[400px] rounded-full bg-[#1AEBC5] opacity-15 blur-[200px]"></div>
    <div class="absolute bottom-[-50px] left-1/2 -translate-x-1/2 w-[500px] h-[300px] rounded-full bg-[#F98272] opacity-15 blur-[200px]"></div>
    <div class="absolute top-[-50px] right-[-50px] w-[350px] h-[350px] rounded-full bg-[#5F3EED] opacity-20 blur-[200px]"></div>

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
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-secondary rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ri-play-circle-fill text-white text-4xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 w-full">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0 60V0C240 30 480 45 720 45C960 45 1200 30 1440 0V60H0Z" fill="white"/>
        </svg>
    </div>
</section>

{{-- Top Categories --}}
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Top Category</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    Optimize Your Brain for<br>
                    <span class="text-primary">Peak Performance</span>
                </h2>
            </div>
            <a href="/courses" class="hidden lg:inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                View All Category <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-category-card name="Digital Marketing" courseCount="1" url="/courses?categories=4" icon="ri-megaphone-line" />
            <x-category-card name="UI/UX Design" courseCount="1" url="/courses?categories=3" icon="ri-pencil-ruler-line" />
            <x-category-card name="Web Development" courseCount="3" url="/courses?categories=2" icon="ri-code-s-slash-line" />
            <x-category-card name="Web Development" courseCount="4" url="/courses?categories=1" icon="ri-global-line" />
        </div>
    </div>
</section>

{{-- Popular Courses --}}
<section class="py-16 lg:py-24 bg-[#F7F4FF]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Popular Course</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    See Our Popular <span class="text-primary">Courses</span>
                </h2>
            </div>
            <a href="/courses" class="hidden lg:inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-semibold rounded-full hover:opacity-90 transition-all duration-300">
                View all course <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
            <x-course-card
                slug="{{ $course->id }}"
                category="{{ $course->category ?? 'General' }}"
                price="{{ $course->price > 0 ? '$' . number_format($course->price, 2) : 'Free' }}"
                title="{{ $course->title }}"
                duration="{{ $course->duration ?? 'N/A' }}"
                lessons="{{ $course->lessons->count() }}"
            />
            @empty
            <div class="col-span-full text-center py-12 text-heading/40">No courses available yet.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- Testimonials --}}
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-primary font-bold text-sm uppercase tracking-wider">Testimonials</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                Edulab Received More than <span class="text-primary">3 +</span> Reviews
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl p-8 shadow-sm">
                <div class="flex items-center gap-1 text-amber-400 mb-4">
                    <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                </div>
                <p class="text-heading/70 leading-relaxed mb-6">
                    The content delivery is seamless, with a mix of videos, quizzes, and assignments that keep things interesting. I also appreciate the interactive features like discussion boards and group activities, which allow me to collaborate with other learners and feel part of a community.
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center">
                        <i class="ri-user-smile-line text-primary"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-heading text-sm">Liam Martinez</h4>
                        <p class="text-xs text-heading/60">Lead Developer</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-8 shadow-sm">
                <div class="flex items-center gap-1 text-amber-400 mb-4">
                    <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                </div>
                <p class="text-heading/70 leading-relaxed mb-6">
                    One of the things I love most about this how flexible it is. I can access my courses anytime and on any device, which fits perfectly into my busy schedule. Whether I'm at home or on the go, I never miss an opportunity to learn.
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center">
                        <i class="ri-user-smile-line text-primary"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-heading text-sm">Emma Johnson</h4>
                        <p class="text-xs text-heading/60">Senior Product Manager</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-8 shadow-sm">
                <div class="flex items-center gap-1 text-amber-400 mb-4">
                    <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                </div>
                <p class="text-heading/70 leading-relaxed mb-6">
                    One of the things I love most about this how flexible it is. I can access my courses anytime and on any device, which fits perfectly into my busy schedule. Whether I'm at home or on the go, I never miss an opportunity to learn.
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center">
                        <i class="ri-user-smile-line text-primary"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-heading text-sm">Sophia Patel</h4>
                        <p class="text-xs text-heading/60">Marketing Specialist</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="py-16 bg-[#111827] text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div>
                <span class="text-4xl lg:text-5xl font-extrabold text-secondary">8+</span>
                <p class="text-white/70 mt-2">Years Experience</p>
            </div>
            <div>
                <span class="text-4xl lg:text-5xl font-extrabold text-secondary">2+</span>
                <p class="text-white/70 mt-2">Expert Tutors</p>
            </div>
            <div>
                <span class="text-4xl lg:text-5xl font-extrabold text-secondary">2+</span>
                <p class="text-white/70 mt-2">Satisfied Students</p>
            </div>
            <div>
                <span class="text-4xl lg:text-5xl font-extrabold text-secondary">2+</span>
                <p class="text-white/70 mt-2">Upcoming Courses</p>
            </div>
        </div>
    </div>
</section>

{{-- Upcoming Courses --}}
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Upcoming Courses</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    Our Upcoming <span class="text-primary">Courses</span>
                </h2>
            </div>
            <a href="/courses?upcoming=1" class="hidden lg:inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                View Upcoming Course <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($courses->take(3) as $course)
            <x-course-card
                slug="{{ $course->id }}"
                category="{{ $course->category ?? 'General' }}"
                price="{{ $course->price > 0 ? '$' . number_format($course->price, 2) : 'Free' }}"
                title="{{ $course->title }}"
                duration="{{ $course->duration ?? 'N/A' }}"
                lessons="{{ $course->lessons->count() }}"
            />
            @empty
            <div class="col-span-full text-center py-12 text-heading/40">No courses available yet.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- Course Bundles --}}
<section class="py-16 lg:py-24 bg-[#F7F4FF]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-primary font-bold text-sm uppercase tracking-wider">Latest Bundle</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                See Our Popular Bundle <span class="text-primary">Courses</span>
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
            <a href="/bundles/digital-marketing-power-bundle" class="group bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-secondary/20 flex items-center justify-center">
                    <i class="ri-discount-percent-fill text-3xl text-secondary"></i>
                </div>
                <div class="text-3xl font-extrabold text-heading mb-2">$160</div>
                <h3 class="font-bold text-heading text-lg group-hover:text-primary transition-colors duration-300">Full Stack Development Mastery Bundle</h3>
            </a>
            <a href="/bundles/full-stack-development-mastery-bundle" class="group bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="ri-price-tag-3-fill text-3xl text-primary"></i>
                </div>
                <div class="text-3xl font-extrabold text-heading mb-2">$499</div>
                <h3 class="font-bold text-heading text-lg group-hover:text-primary transition-colors duration-300">Full Stack Development Mastery Bundle</h3>
            </a>
        </div>
    </div>
</section>

{{-- Instructors --}}
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Our Team Member</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    Meet Our Best <span class="text-primary">Instructors</span>
                </h2>
            </div>
            <a href="/instructors" class="hidden lg:inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                More Instructors <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-instructor-card name="Robert Smith" designation="Senior Web Developer" url="/users/3/profile" />
        </div>
    </div>
</section>

{{-- Become an Instructor --}}
<section class="py-16 lg:py-24 bg-[#F7F4FF]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1">
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Intro</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2 mb-8">
                    Become an <span class="text-primary">Instructor</span>
                </h2>
                <form method="POST" action="/become-instructor" class="space-y-4 max-w-lg">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <input name="first_name" type="text" placeholder="First Name *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                        <input name="last_name" type="text" placeholder="Last Name *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    </div>
                    <input name="email" type="email" placeholder="Email *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    <input name="phone" type="tel" placeholder="Phone *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                    <div class="grid grid-cols-2 gap-4">
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
            <div class="flex-1">
                <div class="w-full aspect-square bg-gradient-to-br from-primary-100 to-primary-50 rounded-3xl flex items-center justify-center">
                    <i class="ri-team-line text-9xl text-primary/20"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Blog Posts --}}
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="text-primary font-bold text-sm uppercase tracking-wider">Frequent Updates</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-heading mt-2">
                    Updated News & <span class="text-primary">Blogs</span>
                </h2>
            </div>
            <a href="/blogs" class="hidden lg:inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary font-semibold rounded-full hover:bg-primary hover:text-white transition-all duration-300">
                See all blog <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-blog-card slug="the-importance-of-programming-in-our-everyday-lives" category="Programming Languages" author="Admin" date="05 Dec 2024" title="How Kindergarten Shapes Future Achievements" />
            <x-blog-card slug="how-kindergarten-shapes-future-achievements" category="Design of Art" author="Admin" date="04 Dec 2024" title="How Kindergarten Shapes Future Achievements" />
            <x-blog-card slug="the-power-of-lifelong-learning-never-stop-growing" category="Design of Art" author="Admin" date="27 Nov 2024" title="The Power of Lifelong Learning, Never Stop Growing" />
        </div>
    </div>
</section>
@endsection