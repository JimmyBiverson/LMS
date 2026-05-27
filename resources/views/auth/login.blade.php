@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="min-w-full min-h-screen flex items-center">
    <div class="grow min-h-screen h-full w-full lg:w-1/2 p-3 bg-primary-50 hidden lg:flex-center">
        <img src="/lms/frontend/assets/images/auth/auth-loti.svg" alt="loti" class="max-w-lg w-full">
    </div>
    <div class="grow min-h-screen h-full w-full lg:w-1/2 pt-32 pb-12 px-3 lg:p-3 flex-center flex-col">
        <h2 class="area-title">Sign In!</h2>
        <p class="area-description max-w-screen-sm mx-auto text-center mt-5">
            Discover, learn, and thrive with us. Experience a smooth and rewarding educational adventure. Let's get started!
        </p>

        <div class="dashkit-tab flex-center gap-2 flex-wrap mt-10" id="loginTab">
            <button type="button" aria-label="Login tab for Student"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white login-credentials"
                data-role="student" data-email="" data-password="">Student</button>
            <button type="button" aria-label="Login tab for Instructor"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white login-credentials"
                data-role="instructor" data-email="" data-password="">Instructor</button>
            <button type="button" aria-label="Login tab for Organization"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white login-credentials"
                data-role="organization" data-email="" data-password="">Organization</button>
            <button type="button" aria-label="Login tab for Admin"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white active login-credentials"
                data-role="admin">Admin</button>
        </div>

        <div class="dashkit-tab-content w-full max-w-screen-sm mt-10 *:hidden" id="loginFormContent">
            {{-- Non-admin form (student / instructor / organization) --}}
            <div class="dashkit-tab-pane" data-tab="user">
                <form method="POST" action="{{ route('login') }}" class="w-full form">
                    @csrf
                    <input type="hidden" name="role" id="login-role" value="student">
                    <div class="grid grid-cols-2 gap-x-3 gap-y-5">
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="email" name="email" id="role_email" value="{{ old('email') }}"
                                    class="form-input rounded-full peer @error('email') border-red-500 @enderror" placeholder=" " required>
                                <label for="role_email" class="form-label floating-form-label">Email <span class="text-red-500">*</span></label>
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="password" name="password" id="role_password"
                                    class="form-input rounded-full peer @error('password') border-red-500 @enderror" placeholder=" " required>
                                <label for="role_password" class="form-label floating-form-label">Password <span class="text-red-500">*</span></label>
                                <label class="size-8 rounded-full cursor-pointer flex-center hover:bg-gray-200 focus:bg-gray-200 absolute top-1/2 -translate-y-1/2 right-2 rtl:right-auto rtl:left-2">
                                    <input type="checkbox" class="inputTypeToggle peer/it" hidden>
                                    <i class="ri-eye-off-line text-gray-500 peer-checked/it:before:content-['\ecb5']"></i>
                                </label>
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full">
                            <div class="flex-center-between px-4">
                                <label class="flex items-center gap-2.5 cursor-pointer py-2.5 select-none">
                                    <input type="checkbox" name="remember" class="checkbox checkbox-primary rounded-sm">
                                    <span class="text-heading font-medium leading-none">Remember me</span>
                                </label>
                                <a href="{{ route('password.request') }}" class="text-primary underline">Forgot Password?</a>
                            </div>
                        </div>
                        <div class="col-span-full">
                            <button type="submit" class="btn b-solid btn-secondary-solid !text-heading dark:text-white btn-xl !rounded-full font-bold w-full h-12" aria-label="Login">
                                Log in
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Admin form --}}
            <div class="dashkit-tab-pane !block" data-tab="admin">
                <form method="POST" action="{{ url('/admin/admin-login') }}" class="w-full form">
                    @csrf
                    <div class="grid grid-cols-2 gap-x-3 gap-y-5">
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="email" name="email" id="admin-email" value="admin@gmail.com"
                                    class="form-input rounded-full peer" placeholder=" " required>
                                <label for="admin-email" class="form-label floating-form-label">Email <span class="text-red-500">*</span></label>
                            </div>
                        </div>
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="password" name="password" id="admin-password" value="123456"
                                    class="form-input rounded-full peer" placeholder=" " required>
                                <label for="admin-password" class="form-label floating-form-label">Password <span class="text-red-500">*</span></label>
                                <label class="size-8 rounded-full cursor-pointer flex-center hover:bg-gray-200 focus:bg-gray-200 absolute top-1/2 -translate-y-1/2 right-2 rtl:right-auto rtl:left-2">
                                    <input type="checkbox" class="inputTypeToggle peer/it" hidden>
                                    <i class="ri-eye-off-line text-gray-500 peer-checked/it:before:content-['\ecb5']"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-span-full">
                            <button type="submit" class="btn b-solid btn-secondary-solid !text-heading dark:text-white btn-xl !rounded-full font-bold w-full h-12" aria-label="Login">
                                Log in
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex-center w-full max-w-screen-sm py-6 h-max relative text-heading font-normal before:absolute inset-0 before:w-full before:h-px before:bg-gray-200">
            <span class="relative z-10 px-5 bg-white text-sm">OR</span>
        </div>

        <div class="text-heading">
            Don't have an account yet?
            <a href="{{ route('register') }}" class="text-primary hover:underline" aria-label="Sign up page">Sign up</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.login-credentials');
    const panes = document.querySelectorAll('.dashkit-tab-pane');
    const roleInput = document.getElementById('login-role');
    const emailInput = document.getElementById('role_email');
    const passwordInput = document.getElementById('role_password');

    function showPane(role) {
        panes.forEach(p => p.classList.remove('!block'));
        const pane = document.querySelector(`.dashkit-tab-pane[data-tab="${role}"]`);
        if (pane) pane.classList.add('!block');
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const role = this.dataset.role;
            const email = this.dataset.email;
            const password = this.dataset.password;

            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            if (role === 'admin') {
                showPane('admin');
            } else {
                showPane('user');
                roleInput.value = role;
                emailInput.value = email || '';
                passwordInput.value = password || '';
            }
        });
    });

    document.querySelectorAll('.inputTypeToggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const container = this.closest('.relative');
            const input = container.querySelector('input[type="password"], input[type="text"]');
            if (input) {
                input.type = this.checked ? 'text' : 'password';
            }
        });
    });
});
</script>
@endpush