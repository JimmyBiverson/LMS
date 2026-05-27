@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<section class="py-12 lg:py-20 bg-[#F7F4FF] min-h-screen flex items-center">
    <div class="max-w-md mx-auto px-4 w-full">
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-primary-50 flex items-center justify-center"><i class="ri-lock-line text-4xl text-primary"></i></div>
            <h1 class="text-3xl font-extrabold text-heading">Reset your Password</h1>
            <p class="text-heading/60 text-sm mt-2">No worries, it happens! Just enter your email, and we will help you unlock your account with a fresh password.</p>
        </div>
        <div class="bg-white rounded-xl p-8 shadow-sm">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Email *</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('email') border-red-500 @enderror" placeholder="Email *">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Send Request</button>
            </form>
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center"><span class="bg-white px-4 text-sm text-heading/60">OR</span></div>
            </div>
            <p class="text-center"><a href="/login" class="text-primary font-semibold text-sm hover:underline flex items-center justify-center gap-2"><i class="ri-arrow-left-line"></i> Back to Login</a></p>
        </div>
    </div>
</section>
@endsection