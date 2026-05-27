@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Contact</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Contact</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-10">
            <div class="flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-primary-50 flex items-center justify-center">
                            <i class="ri-mail-line text-2xl text-primary"></i>
                        </div>
                        <h3 class="font-bold text-heading mb-2">Email</h3>
                        <p class="text-sm text-heading/60">{{ config('app.email', 'info@edulab.com') }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-primary-50 flex items-center justify-center">
                            <i class="ri-phone-line text-2xl text-primary"></i>
                        </div>
                        <h3 class="font-bold text-heading mb-2">Phone</h3>
                        <p class="text-sm text-heading/60">+1552 956 9286</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-primary-50 flex items-center justify-center">
                            <i class="ri-time-line text-2xl text-primary"></i>
                        </div>
                        <h3 class="font-bold text-heading mb-2">Office Hour</h3>
                        <p class="text-sm text-heading/60">9AM-6PM<br>Online 24/7</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-primary-50 flex items-center justify-center">
                            <i class="ri-map-pin-line text-2xl text-primary"></i>
                        </div>
                        <h3 class="font-bold text-heading mb-2">Location</h3>
                        <p class="text-sm text-heading/60">New York, America</p>
                    </div>
                </div>
            </div>

            <div class="flex-1">
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-heading mb-6">Free Consultation</h2>
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                            <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="/contact" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <input name="name" type="text" value="{{ old('name') }}" placeholder="Full Name *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('name') border-red-500 @enderror">
                            <input name="email" type="email" value="{{ old('email') }}" placeholder="Email *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('email') border-red-500 @enderror">
                        </div>
                        <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="Phone *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('phone') border-red-500 @enderror">
                        <input name="subject" type="text" value="{{ old('subject') }}" placeholder="Subject *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('subject') border-red-500 @enderror">
                        <textarea name="message" rows="5" placeholder="Write your message *" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">
                            Send Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection