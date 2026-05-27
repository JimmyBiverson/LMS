@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="py-12 lg:py-20 bg-[#F7F4FF] min-h-screen flex items-center">
    <div class="max-w-6xl mx-auto px-4 w-full">
        <div class="grid lg:grid-cols-2 gap-8 items-center">
            <div class="hidden lg:flex justify-center">
                <img src="/lms/frontend/assets/images/auth/auth-loti.svg" alt="loti" class="max-w-full h-auto">
            </div>
            <div class="max-w-md mx-auto w-full">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-heading">Sign In!</h1>
                    <p class="text-heading/60 text-sm mt-2">Discover, learn, and thrive with us. Experience a smooth and rewarding educational adventure. Let's get started!</p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div class="flex gap-2 mb-6">
                            <button type="button" data-role="student" class="role-tab flex-1 px-4 py-2.5 rounded-full bg-primary text-white text-sm font-semibold">Student</button>
                            <button type="button" data-role="instructor" class="role-tab flex-1 px-4 py-2.5 rounded-full bg-primary-50 text-heading/70 text-sm font-semibold hover:bg-primary hover:text-white transition-all duration-300">Instructor</button>
                            <button type="button" data-role="organization" class="role-tab flex-1 px-4 py-2.5 rounded-full bg-primary-50 text-heading/70 text-sm font-semibold hover:bg-primary hover:text-white transition-all duration-300">Organization</button>
                            <button type="button" data-role="admin" class="role-tab flex-1 px-4 py-2.5 rounded-full bg-primary-50 text-heading/70 text-sm font-semibold hover:bg-primary hover:text-white transition-all duration-300">Admin</button>
                        </div>

                        <input type="hidden" name="role" id="login-role" value="student">

                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('email') border-red-500 @enderror" placeholder="Email *" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Password *</label>
                            <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('password') border-red-500 @enderror" placeholder="Password *" required>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" class="text-primary rounded focus:ring-primary">
                                <span class="text-sm text-heading/70">Remember me</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-primary font-semibold hover:underline">Forgot Password?</a>
                        </div>
                        <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">
                            Log in
                        </button>
                    </form>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                        <div class="relative flex justify-center"><span class="bg-white px-4 text-sm text-heading/60">OR</span></div>
                    </div>

                    <p class="text-center text-sm text-heading/60">
                        Don't have an account yet?
                        <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Sign up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.role-tab');
    const roleInput = document.getElementById('login-role');
    const activeClass = 'bg-primary text-white';
    const inactiveClass = 'bg-primary-50 text-heading/70';

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const role = this.dataset.role;
            roleInput.value = role;
            tabs.forEach(t => {
                t.className = 'role-tab flex-1 px-4 py-2.5 rounded-full text-sm font-semibold ' + (t.dataset.role === role ? activeClass : inactiveClass + ' hover:bg-primary hover:text-white transition-all duration-300');
            });
        });
    });
});
</script>
@endpush