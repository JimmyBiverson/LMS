@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Shopping Cart</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Shopping Cart</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-5xl mx-auto px-4">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-semibold">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold">{{ session('info') }}</div>
        @endif

        @if(empty($items))
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-primary-50 flex items-center justify-center">
                    <i class="ri-shopping-cart-line text-4xl text-primary"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-heading mb-3">Ready to Learn?</h2>
                <p class="text-heading/60 mb-6">Your cart is waiting to be filled with knowledge!</p>
                <a href="/courses" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">
                    Browse Courses <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-100">
                    @foreach($items as $item)
                    <div class="flex items-center gap-4 p-6">
                        <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center shrink-0">
                            <i class="{{ $item['type'] === 'bundle' ? 'ri-price-tag-3-line' : 'ri-book-open-line' }} text-2xl text-primary/40"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-heading truncate">{{ $item['title'] }}</h3>
                            <p class="text-xs text-heading/60 mt-1">{{ ucfirst($item['type']) }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-bold text-heading">
                                @if($item['price'] > 0)
                                    ${{ number_format((float)$item['price'], 2) }}
                                @else
                                    <span class="text-green-600">Free</span>
                                @endif
                            </div>
                            <form method="POST" action="/cart/remove/{{ $item['type'] }}s/{{ $item['id'] }}" class="mt-1">
                                @csrf
                                <button type="submit" class="text-xs text-red-500 hover:underline">Remove</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="bg-gray-50 p-6 flex items-center justify-between">
                    <div>
                        <span class="text-heading/60 text-sm">Total:</span>
                        <span class="text-2xl font-extrabold text-heading ml-2">${{ number_format((float)$total, 2) }}</span>
                    </div>
                    <div class="flex gap-3">
                        <form method="POST" action="/cart/clear">
                            @csrf
                            <button type="submit" class="px-6 py-3 border border-heading/20 text-heading font-bold rounded-full hover:bg-gray-100 text-sm">Clear</button>
                        </form>
                        <a href="/checkout" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all text-sm">Checkout</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
