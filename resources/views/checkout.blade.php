@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Checkout</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/cart" class="hover:text-primary transition-colors">Cart</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Checkout</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-50 flex items-center justify-center">
                <i class="ri-bank-card-line text-4xl text-green-500"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-heading mb-3">Payment Method</h2>
            <p class="text-heading/60 mb-8">Online payment gateway integration is coming soon. For now, please use offline payment.</p>
            <div class="max-w-md mx-auto space-y-4">
                <p class="text-sm text-heading/60">Complete your enrollment by proceeding with offline payment. An admin will confirm your enrollment after payment verification.</p>
                <a href="/dashboard/offline-payment" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">
                    Proceed with Offline Payment <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
