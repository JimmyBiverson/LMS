@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<section class="py-12 lg:py-20 bg-[#F7F4FF] min-h-screen flex items-center">
    <div class="max-w-md mx-auto px-4 w-full">
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-primary-50 flex items-center justify-center"><i class="ri-lock-2-line text-4xl text-primary"></i></div>
            <h1 class="text-3xl font-extrabold text-heading">Set New Password</h1>
            <p class="text-heading/60 text-sm mt-2">Your new password must be different from previously used passwords.</p>
        </div>
        <div class="bg-white rounded-xl p-8 shadow-sm">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Email *</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('email') border-red-500 @enderror" placeholder="Email *" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Password *</label>
                    <input name="password" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('password') border-red-500 @enderror" placeholder="New Password *" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-heading mb-1">Confirm Password *</label>
                    <input name="password_confirmation" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Confirm Password *" required>
                </div>
                <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">Reset Password</button>
            </form>
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center"><span class="bg-white px-4 text-sm text-heading/60">OR</span></div>
            </div>
            <p class="text-center"><a href="{{ route('login') }}" class="text-primary font-semibold text-sm hover:underline flex items-center justify-center gap-2"><i class="ri-arrow-left-line"></i> Back to Login</a></p>
        </div>
    </div>
</section>
@endsection
