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
                        <div class="w-24 h-24 rounded-full bg-primary-50 flex items-center justify-center shrink-0"><i class="ri-user-smile-line text-4xl text-primary"></i></div>
                        <div>
                            <h2 class="text-2xl font-extrabold text-heading">Robert Smith</h2>
                            <div class="flex items-center gap-4 mt-2 text-sm text-heading/60">
                                <span class="flex items-center gap-1"><i class="ri-book-open-line"></i> 8 Courses</span>
                                <span class="flex items-center gap-1"><i class="ri-user-line"></i> 2 Students</span>
                            </div>
                            <p class="text-primary font-semibold text-sm mt-1">Senior Web Developer</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm mb-8">
                    <h3 class="text-lg font-bold text-heading mb-4">Biography</h3>
                    <p class="text-heading/70 leading-relaxed">Hi, I'm Robert, a passionate UI/UX designer with a keen eye for creating intuitive, user-centric designs. I specialize in crafting seamless digital experiences that not only look great, but are also functional and accessible.</p>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm mb-8">
                    <h3 class="text-lg font-bold text-heading mb-4">Contact Form</h3>
                    <form method="POST" action="/profile/contact" class="space-y-4">
                        @csrf
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
                    <x-course-card slug="digital-product-design" level="Intermediate" category="Web Development" price="Free" title="Full-Stack Web Development Bootcamp" rating="5" duration="2h 30m" lessons="2" students="0" />
                    <x-course-card slug="e-commerce-development" level="Advanced" category="Web Development" price="25.50" title="Full Stack Web Development with JavaScript" rating="0" duration="2h 30m" lessons="2" students="0" />
                    <x-course-card slug="wordpress-development-for-beginners" level="Professional" category="Web Development" price="Free" title="UI/UX Design Fundamentals" rating="5" duration="4h 20m" lessons="6" students="0" />
                </div>
                <div class="flex items-center justify-center gap-2 mt-8">
                    <span class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold">1</span>
                    <a href="?page=2" class="w-10 h-10 rounded-full bg-primary-50 text-heading/70 flex items-center justify-center text-sm font-bold hover:bg-primary hover:text-white transition-all duration-300">2</a>
                    <a href="?page=3" class="w-10 h-10 rounded-full bg-primary-50 text-heading/70 flex items-center justify-center text-sm font-bold hover:bg-primary hover:text-white transition-all duration-300">3</a>
                    <a href="?page=2" class="w-10 h-10 rounded-full bg-primary-50 text-heading/70 flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300"><i class="ri-arrow-right-s-line"></i></a>
                </div>
            </div>
            <aside>
                <div class="bg-white rounded-xl p-6 shadow-sm sticky top-28">
                    <div class="flex items-center gap-3 text-sm text-heading/60 mb-4"><i class="ri-mail-line"></i><span>robert@example.com</span></div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection