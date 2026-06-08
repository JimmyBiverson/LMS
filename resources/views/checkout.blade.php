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
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-heading text-lg mb-4">Order Items</h3>
                    <div class="space-y-4">
                        @foreach ($items as $item)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div>
                                <p class="font-semibold text-heading">{{ $item['title'] }}</p>
                                <p class="text-xs text-heading/50">{{ ucfirst($item['type']) }}</p>
                            </div>
                            <span class="font-bold text-heading">${{ number_format($item['price'], 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                    <h3 class="font-bold text-heading text-lg mb-4">Coupon</h3>
                    @if ($coupon)
                    <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div>
                            <span class="font-semibold text-green-700">{{ $coupon->code }}</span>
                            <span class="text-sm text-green-600 ml-2">
                                ({{ $coupon->discount_type === 'percentage' ? $coupon->discount . '% off' : '$' . $coupon->discount . ' off' }})
                            </span>
                        </div>
                        <form method="POST" action="/coupon/remove">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-semibold">Remove</button>
                        </form>
                    </div>
                    @else
                    <form method="POST" action="/coupon/apply" class="flex gap-3">
                        @csrf
                        <input name="code" type="text" placeholder="Enter coupon code" class="flex-1 px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                        <button type="submit" class="px-6 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all text-sm">Apply</button>
                    </form>
                    @error('code')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                    <h3 class="font-bold text-heading text-lg mb-4">Payment Method</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 border border-heading/10 rounded-lg cursor-pointer hover:border-primary transition-colors">
                            <input type="radio" name="payment_method" value="offline" form="placeOrder" checked class="accent-primary">
                            <div><p class="font-semibold text-heading text-sm">Offline Payment</p><p class="text-xs text-heading/50">Pay via bank transfer or manual payment</p></div>
                        </label>
                    </div>
                    <form id="placeOrder" method="POST" action="/checkout/place-order" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm">
                            Complete Purchase — ${{ number_format($total, 2) }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-28">
                    <h3 class="font-bold text-heading text-lg mb-4">Order Summary</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-heading/70">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if ($discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>
                        @endif
                        @if ($tax > 0)
                        <div class="flex justify-between text-heading/70">
                            <span>Tax</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        @endif
                        <hr class="border-gray-100">
                        <div class="flex justify-between font-bold text-heading text-base">
                            <span>Total</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection