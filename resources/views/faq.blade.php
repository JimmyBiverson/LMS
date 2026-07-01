@extends('layouts.app')
@section('title', 'FAQ')
@section('content')

<section class="bg-[#F7F4FF] py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-primary font-bold text-sm uppercase tracking-wider">FAQ</span>
            <h1 class="text-3xl lg:text-5xl font-extrabold text-heading mt-2">Frequently Asked <span class="text-primary">Questions</span></h1>
            <p class="text-heading/60 mt-4 max-w-2xl mx-auto">Find answers to the most common questions about our platform.</p>
        </div>

        <div class="max-w-3xl mx-auto space-y-4">
            @forelse($faqs as $faq)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left font-bold text-heading hover:text-primary transition-colors duration-300">
                    <span>{{ $faq->question }}</span>
                    <i class="ri-add-line text-xl transition-transform duration-300" :class="{'rotate-45': open}"></i>
                </button>
                <div x-show="open" x-collapse class="px-6 pb-6 text-heading/70 leading-relaxed">
                    {{ $faq->answer }}
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-heading/40">No FAQs available yet.</div>
            @endforelse
        </div>
    </div>
</section>

@endsection
