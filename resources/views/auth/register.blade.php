@extends('layouts.app')

@section('title', 'Register')

@section('content')
<section class="py-12 lg:py-20 bg-[#F7F4FF] min-h-screen flex items-center">
    <div class="max-w-6xl mx-auto px-4 w-full">
        <div class="grid lg:grid-cols-2 gap-8 items-center">
            <div class="hidden lg:flex justify-center">
                <img src="/lms/frontend/assets/images/auth/auth-loti.svg" alt="loti" class="max-w-full h-auto">
            </div>
            <div class="max-w-lg mx-auto w-full">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-heading">Register!</h1>
                    <p class="text-heading/60 text-sm mt-2">Discover, learn, and thrive with us. Experience a smooth and rewarding educational adventure. Let's get started!</p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <div class="flex gap-2 mb-6">
                        <button data-role="student" class="role-tab flex-1 px-4 py-2.5 rounded-full bg-primary text-white text-sm font-semibold">Student</button>
                        <button data-role="instructor" class="role-tab flex-1 px-4 py-2.5 rounded-full bg-primary-50 text-heading/70 text-sm font-semibold hover:bg-primary hover:text-white transition-all duration-300">Instructor</button>
                        <button data-role="organization" class="role-tab flex-1 px-4 py-2.5 rounded-full bg-primary-50 text-heading/70 text-sm font-semibold hover:bg-primary hover:text-white transition-all duration-300">Organization</button>
                    </div>

                    <form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-4">
                        @csrf
                        <input type="hidden" name="role" id="register-role" value="student">

                        {{-- Student/Instructor Fields --}}
                        <div id="name-fields" class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-heading mb-1">First Name *</label>
                                <input type="text" name="first_name" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('first_name') border-red-500 @enderror" placeholder="First Name *">
                                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-heading mb-1">Last Name *</label>
                                <input type="text" name="last_name" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('last_name') border-red-500 @enderror" placeholder="Last Name *">
                                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div id="org-name-field" class="hidden">
                            <label class="block text-sm font-semibold text-heading mb-1">Full Name *</label>
                            <input type="text" name="name" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('name') border-red-500 @enderror" placeholder="Full Name *">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('email') border-red-500 @enderror" placeholder="Email *" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-heading mb-1">Phone *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('phone') border-red-500 @enderror" placeholder="Phone *" required>
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-heading mb-1">Password *</label>
                                <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('password') border-red-500 @enderror" placeholder="Password *" required>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-heading mb-1">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Confirm Password *" required>
                            </div>
                        </div>

                        <div id="designation-field" class="hidden">
                            <label class="block text-sm font-semibold text-heading mb-1">Designation *</label>
                            <input type="text" name="designation" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary @error('designation') border-red-500 @enderror" placeholder="e.g. Web Developer">
                            @error('designation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div id="address-field" class="hidden">
                            <label class="block text-sm font-semibold text-heading mb-1">Address</label>
                            <input type="text" name="address" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm text-heading font-semibold focus:outline-none focus:border-primary" placeholder="Address">
                        </div>

                        <button type="submit" class="w-full px-8 py-4 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all duration-300">
                            Sign up
                        </button>
                    </form>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                        <div class="relative flex justify-center"><span class="bg-white px-4 text-sm text-heading/60">OR</span></div>
                    </div>

                    <p class="text-center text-sm text-heading/60">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Sign In</a>
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
    const roleInput = document.getElementById('register-role');
    const nameFields = document.getElementById('name-fields');
    const orgNameField = document.getElementById('org-name-field');
    const designationField = document.getElementById('designation-field');
    const addressField = document.getElementById('address-field');
    const activeClass = 'bg-primary text-white';
    const inactiveClass = 'bg-primary-50 text-heading/70';

    function toggleFields(role) {
        roleInput.value = role;
        if (role === 'organization') {
            nameFields.classList.add('hidden');
            orgNameField.classList.remove('hidden');
            designationField.classList.add('hidden');
            addressField.classList.remove('hidden');
        } else {
            nameFields.classList.remove('hidden');
            orgNameField.classList.add('hidden');
            designationField.classList.toggle('hidden', role !== 'instructor');
            addressField.classList.add('hidden');
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const role = this.dataset.role;
            tabs.forEach(t => {
                t.className = 'role-tab flex-1 px-4 py-2.5 rounded-full text-sm font-semibold ' + (t.dataset.role === role ? activeClass : inactiveClass + ' hover:bg-primary hover:text-white transition-all duration-300');
            });
            toggleFields(role);
        });
    });

    toggleFields('student');
});
</script>
@endpush