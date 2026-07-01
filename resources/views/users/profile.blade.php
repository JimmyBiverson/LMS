@extends('layouts.app')
@section('title', 'Instructor Profile')
@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Instructor Profile</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a><i class="ri-arrow-right-s-line"></i>
            <a href="/instructors" class="hover:text-primary transition-colors">Instructors</a><i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Instructor Profile</span>
        </div>
    </div>
</section>
<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl p-8 shadow-sm mb-8">
                    <div class="flex items-start gap-6">
                        @if($instructor->profile_image)
                            <img src="{{ $instructor->profile_image_url }}" alt="{{ $instructor->name }}" class="w-24 h-24 rounded-full object-cover shrink-0">
                        @else
                            <div class="w-24 h-24 rounded-full bg-primary-50 flex items-center justify-center shrink-0"><i class="ri-user-smile-line text-4xl text-primary"></i></div>
                        @endif
                        <div>
                            <h2 class="text-2xl font-extrabold text-heading">{{ $instructor->name }}</h2>
                            <div class="flex items-center gap-4 mt-2 text-sm text-heading/60">
                                <span class="flex items-center gap-1"><i class="ri-book-open-line"></i> {{ $courseCount }} Courses</span>
                                <span class="flex items-center gap-1"><i class="ri-user-line"></i> {{ $studentCount }} Students</span>
                            </div>
                            <p class="text-primary font-semibold text-sm mt-1">{{ $instructor->designation ?? 'Instructor' }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm mb-8">
                    <h3 class="text-lg font-bold text-heading mb-4">Biography</h3>
                    <p class="text-heading/70 leading-relaxed">{{ $instructor->bio ?? 'No biography provided yet.' }}</p>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm mb-8">
                    <h3 class="text-lg font-bold text-heading mb-4">Contact Form</h3>
                    <form method="POST" action="/profile/contact" class="space-y-4">
                        @csrf
                        <input type="hidden" name="instructor_id" value="{{ $instructor->id }}">
                        <div class="grid grid-cols-2 gap-4">
                            <input name="name" type="text" placeholder="Full Name *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                            <input name="email" type="email" placeholder="Email *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                        </div>
                        <input name="phone" type="tel" placeholder="Phone *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                        <input name="subject" type="text" placeholder="Subject *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary">
                        <textarea name="message" rows="4" placeholder="Write your message *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary"></textarea>
                        <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Send Now</button>
                    </form>
                </div>
                <h3 class="text-xl font-bold text-heading mb-6">Explore My Courses</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($courses as $course)
                        <x-course-card 
                            :course="$course"
                            :slug="$course->slug" 
                            :level="$course->level->name ?? 'All Levels'" 
                            :category="$course->categoryRelation->name ?? $course->category ?? 'General'" 
                            :price="$course->payment_type === 'free' ? 'Free' : \App\Helpers\CurrencyHelper::format((float)$course->price)" 
                            :title="$course->title" 
                            :rating="$course->averageRating()" 
                            :duration="$course->duration ?? '0h'" 
                            :lessons="$course->lessons->count()" 
                            :students="$course->enrollments_count ?? 0" 
                        />
                    @empty
                        <div class="col-span-full py-8 text-center text-heading/50">
                            <i class="ri-book-3-line text-4xl mb-3 block"></i>
                            <p>No courses published yet.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-8">
                    {{ $courses->links() }}
                </div>
            </div>
            <aside>
                <div class="bg-white rounded-xl p-6 shadow-sm sticky top-28">
                    <div class="flex items-center gap-3 text-sm text-heading/60 mb-4"><i class="ri-mail-line"></i><span>{{ $instructor->email }}</span></div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection