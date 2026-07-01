@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="min-w-full min-h-screen flex items-center">
    <div class="grow min-h-screen h-full w-full lg:w-1/2 p-3 bg-primary-50 hidden lg:flex lg:items-center lg:justify-center">
        <img src="{{ asset('lms/frontend/assets/images/auth/auth-loti.svg') }}" alt="loti" loading="lazy" class="max-w-full h-auto">
    </div>
    <div class="grow min-h-screen h-full w-full lg:w-1/2 pt-32 pb-12 px-3 lg:p-3 flex-center flex-col">
        <h2 class="area-title">Sign In!</h2>
        <p class="area-description max-w-screen-sm mx-auto text-center mt-5">
            Discover, learn, and thrive with us. Experience a smooth and rewarding educational adventure. Let's get started!
        </p>

        <div class="dashkit-tab flex-center gap-2 flex-wrap mt-10" id="userRegisterTab">
            <button type="button" aria-label="Login tab for Student"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white login-credentials"
                id="asStudent" data-email="" data-password="">Student</button>
            <button type="button" aria-label="Login tab for Instructor"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white login-credentials"
                id="asInstructor" data-email="" data-password="">Instructor</button>
            <button type="button" aria-label="Login tab for Organization"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white login-credentials"
                id="asOrganization" data-email="" data-password="">Organization</button>
            <button type="button" aria-label="Login tab for Admin"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white login-credentials"
                id="admin">Admin</button>
        </div>

        <div class="dashkit-tab-content w-full max-w-screen-sm mt-10 *:hidden" id="userRegisterTabContent">
            {{-- Non-admin form (student / instructor / organization) --}}
            <div class="dashkit-tab-pane" data-tab="non-admin">
                    <form method="POST" action="{{ route('login') }}" class="w-full form">
                        @csrf
                        <input type="hidden" name="login_role" value="non-admin">
                        <input type="hidden" name="selected_role" id="selected_role_input" value="{{ old('selected_role', 'student') }}">
                    <div class="grid grid-cols-2 gap-x-3 gap-y-5">
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="email" name="email" id="role_email" value="{{ old('email') }}"
                                    class="form-input rounded-full peer @error('email') border-red-500 @enderror" placeholder="" required>
                                <label for="role_email" class="form-label floating-form-label">Email <span class="text-danger">*</span></label>
                            </div>
                            @error('email') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="password" name="password" id="role_password"
                                    class="form-input rounded-full peer @error('password') border-red-500 @enderror" placeholder="" required>
                                <label for="role_password" class="form-label floating-form-label">Password <span class="text-danger">*</span></label>
                                <label class="size-8 rounded-full cursor-pointer flex-center hover:bg-gray-200 focus:bg-gray-200 absolute top-1/2 -translate-y-1/2 right-2 rtl:right-auto rtl:left-2">
                                    <input type="checkbox" class="inputTypeToggle peer/it" hidden>
                                    <i class="ri-eye-off-line text-gray-500 peer-checked/it:before:content-['\ecb5']"></i>
                                </label>
                            </div>
                            @error('password') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full">
                            <div class="flex-center-between px-4">
                                <label class="flex items-center gap-2.5 cursor-pointer py-2.5 select-none">
                                    <input type="checkbox" name="remember" class="checkbox checkbox-primary rounded-sm">
                                    <span class="text-heading font-medium leading-none">Remember me</span>
                                </label>
                                <div class="text-heading text-sm">
                                    <a href="{{ route('password.request') }}" class="text-primary underline">Forgot Password?</a>
                                </div>
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
            <div class="dashkit-tab-pane" data-tab="admin">
                <form method="POST" action="{{ url('/admin/admin-login') }}" class="w-full form">
                    @csrf
                    <input type="hidden" name="login_role" value="admin">
                    <input type="hidden" name="selected_role" value="admin">
                    <div class="grid grid-cols-2 gap-x-3 gap-y-5">
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="email" name="email" id="admin-email" value="{{ old('email') }}"
                                    class="form-input rounded-full peer @error('email') border-red-500 @enderror" placeholder="" required>
                                <label for="admin-email" class="form-label floating-form-label">Email <span class="text-danger">*</span></label>
                            </div>
                            @error('email') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="password" name="password" id="admin-password" value=""
                                    class="form-input rounded-full peer @error('password') border-red-500 @enderror" placeholder="" required>
                                <label for="admin-password" class="form-label floating-form-label">Password <span class="text-danger">*</span></label>
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

        <div class="flex-center w-full max-w-screen-sm py-6 h-max relative text-heading font-normal before:absolute inset-0 before:w-full before:h-px before:bg-border">
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

    function showPane(tab) {
        panes.forEach(p => p.classList.remove('!block'));
        const pane = document.querySelector(`.dashkit-tab-pane[data-tab="${tab}"]`);
        if (pane) pane.classList.add('!block');
    }

    function activateRole(role) {
        let tabId = 'asStudent';
        if (role === 'instructor') tabId = 'asInstructor';
        else if (role === 'organization') tabId = 'asOrganization';
        else if (role === 'admin') tabId = 'admin';

        const tabBtn = document.getElementById(tabId);
        if (tabBtn) {
            tabs.forEach(t => t.classList.remove('active'));
            tabBtn.classList.add('active');
            if (role === 'admin') {
                showPane('admin');
            } else {
                showPane('non-admin');
                const selectedInput = document.getElementById('selected_role_input');
                if (selectedInput) {
                    selectedInput.value = role;
                }
            }
            localStorage.setItem('login_selected_role', role);
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            let role = 'student';
            if (this.id === 'asInstructor') role = 'instructor';
            else if (this.id === 'asOrganization') role = 'organization';
            else if (this.id === 'admin') role = 'admin';

            activateRole(role);
        });
    });

    // Check old selected role first, then local storage, defaulting to 'student'
    const oldRole = "{{ old('selected_role') }}";
    const savedRole = localStorage.getItem('login_selected_role');
    const roleToActivate = oldRole || savedRole || 'student';
    activateRole(roleToActivate);

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