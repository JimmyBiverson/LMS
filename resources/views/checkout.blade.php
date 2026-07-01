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
                            <span class="font-bold text-heading">{{ \App\Helpers\CurrencyHelper::format($item['price']) }}</span>
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
                                ({{ $coupon->discount_type === 'percentage' ? $coupon->discount . '% off' : \App\Helpers\CurrencyHelper::format($coupon->discount) . ' off' }})
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
                        @if ($subtotal > 0 && $hasPaystack)
                        <form method="POST" action="/checkout/paystack">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 p-4 border border-heading/10 rounded-lg cursor-pointer hover:border-primary transition-colors bg-primary-50">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm">
                                    <span class="font-extrabold text-primary text-xs">P</span>
                                </div>
                                <div class="text-left flex-1">
                                    <p class="font-semibold text-heading text-sm">Pay with Paystack</p>
                                    <p class="text-xs text-heading/50">Pay via Card, Bank Transfer, USSD, or Mobile Money</p>
                                </div>
                                <i class="ri-arrow-right-s-line text-xl text-heading/40"></i>
                            </button>
                        </form>
                        @endif

                        @php
                            $offlineMethods = $paymentMethods->where('type', 'Offline');
                        @endphp
                        @if ($offlineMethods->isNotEmpty() && $subtotal > 0)
                        <div x-data="{ selectedProvider: null, selectedMethodId: null }" class="space-y-3">
                            <p class="text-sm font-semibold text-heading/60">Pay with Mobile Money</p>
                            @foreach ($offlineMethods as $method)
                            <label class="flex items-center gap-3 p-4 border border-heading/10 rounded-lg cursor-pointer hover:border-primary transition-colors"
                                   :class="selectedMethodId === '{{ $method->id }}' ? 'border-primary bg-primary-50' : ''">
                                <input type="radio" name="payment_method_radio"
                                       value="{{ $method->id }}"
                                       data-provider="{{ $method->provider }}"
                                       class="accent-primary"
                                       @change="selectedProvider = '{{ $method->provider }}'; selectedMethodId = '{{ $method->id }}'">
                                <div class="flex items-center gap-3">
                                    @if ($method->provider === 'airtel')
                                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="8" fill="#ED1B24"/><text x="18" y="23" font-family="Arial" font-weight="900" font-size="16" fill="white" text-anchor="middle">A</text></svg>
                                    @elseif ($method->provider === 'mtn')
                                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="8" fill="#FFCC00"/><text x="18" y="23" font-family="Arial" font-weight="900" font-size="16" fill="#000" text-anchor="middle">M</text></svg>
                                    @else
                                    <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center"><i class="ri-bank-line text-heading/50"></i></div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-heading text-sm">{{ $method->name }}</p>
                                        <p class="text-xs text-heading/50">
                                            @if ($method->provider === 'airtel') Pay via Airtel Money
                                            @elseif ($method->provider === 'mtn') Pay via MTN Mobile Money
                                            @else Manual payment
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </label>
                            @endforeach

                            <form id="placeOrder" method="POST" action="/checkout/place-order" class="mt-6">
                                @csrf
                                <input type="hidden" name="payment_method" value="offline">
                                <input type="hidden" name="payment_method_id" :value="selectedMethodId">
                                <input type="hidden" name="payment_provider" :value="selectedProvider">
                                <button type="submit"
                                        :disabled="!selectedMethodId"
                                        class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm"
                                        :class="!selectedMethodId ? 'opacity-50 cursor-not-allowed' : ''">
                                    Complete Purchase — {{ \App\Helpers\CurrencyHelper::format($total) }}
                                </button>
                            </form>
                            <p x-show="!selectedMethodId" class="text-xs text-heading/40 text-center mt-2">Select a mobile money method above</p>
                        </div>
                        @elseif ($subtotal > 0)
                        <form id="placeOrder" method="POST" action="/checkout/place-order" class="mt-6">
                            @csrf
                            <input type="hidden" name="payment_method" value="offline">
                            <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300 text-sm">
                                Complete Purchase — {{ \App\Helpers\CurrencyHelper::format($total) }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-28">
                    <h3 class="font-bold text-heading text-lg mb-4">Order Summary</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-heading/70">
                            <span>Subtotal</span>
                            <span>{{ \App\Helpers\CurrencyHelper::format($subtotal) }}</span>
                        </div>
                        @if ($discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span>-{{ \App\Helpers\CurrencyHelper::format($discount) }}</span>
                        </div>
                        @endif
                        @if ($tax > 0)
                        <div class="flex justify-between text-heading/70">
                            <span>Tax</span>
                            <span>{{ \App\Helpers\CurrencyHelper::format($tax) }}</span>
                        </div>
                        @endif
                        <hr class="border-gray-100">
                        <div class="flex justify-between font-bold text-heading text-base">
                            <span>Total</span>
                            <span>{{ \App\Helpers\CurrencyHelper::format($total) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
