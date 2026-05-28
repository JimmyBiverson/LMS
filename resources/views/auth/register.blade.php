@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="min-w-full min-h-screen flex items-center">
    <div class="grow min-h-screen h-full w-full lg:w-1/2 p-3 bg-primary-50 hidden lg:flex-center">
        <img src="{{ asset('lms/frontend/assets/images/auth/auth-loti.svg') }}" alt="loti">
    </div>
    <div class="grow min-h-screen h-full w-full lg:w-1/2 pt-32 pb-12 px-3 lg:p-3 flex-center flex-col">
        <h2 class="area-title">Register!</h2>
        <p class="area-description max-w-screen-sm mx-auto text-center mt-5">
            Discover, learn, and thrive with us. Experience a smooth and rewarding educational adventure. Let's get started!
        </p>

        <div class="dashkit-tab flex-center gap-2 flex-wrap mt-10" id="userRegisterTab">
            <button type="button" aria-label="User registration tab for Student"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white active"
                id="asStudent">Student</button>
            <button type="button" aria-label="User registration tab for Instructor"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white"
                id="asInstructor">Instructor</button>
            <button type="button" aria-label="User registration tab for Organization"
                class="dashkit-tab-btn btn b-light btn-primary-light btn-lg h-11 !rounded-full text-[14px] sm:text-[16px] md:text-[18px] [&.active]:bg-primary [&.active]:text-white"
                id="asOrganization">Organization</button>
        </div>

        <div class="dashkit-tab-content mt-10 w-full max-w-screen-sm *:hidden" id="userRegisterTabContent">
            {{-- Student Form --}}
            <div class="dashkit-tab-pane !block" data-tab="asStudent">
                <form method="POST" action="{{ route('register') }}" class="w-full form">
                    @csrf
                    <input type="hidden" name="role" value="student">
                    <div class="grid grid-cols-2 gap-x-3 gap-y-5">
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="text" name="first_name" id="std_first_name" value="{{ old('first_name') }}"
                                    class="form-input rounded-full peer @error('first_name') border-red-500 @enderror" placeholder="">
                                <label for="std_first_name" class="form-label floating-form-label">First Name <span class="text-danger">*</span></label>
                            </div>
                            @error('first_name') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="text" name="last_name" id="std_last_name" value="{{ old('last_name') }}"
                                    class="form-input rounded-full peer @error('last_name') border-red-500 @enderror" placeholder="">
                                <label for="std_last_name" class="form-label floating-form-label">Last Name <span class="text-danger">*</span></label>
                            </div>
                            @error('last_name') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="email" name="email" id="std_email" value="{{ old('email') }}"
                                    class="form-input rounded-full peer @error('email') border-red-500 @enderror" placeholder="" required>
                                <label for="std_email" class="form-label floating-form-label">Email <span class="text-danger">*</span></label>
                            </div>
                            @error('email') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="text" name="phone" id="std_phone" value="{{ old('phone') }}"
                                    class="form-input rounded-full peer @error('phone') border-red-500 @enderror" placeholder="" required>
                                <label for="std_phone" class="form-label floating-form-label">Phone <span class="text-danger">*</span></label>
                            </div>
                            @error('phone') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="password" name="password" id="std_password"
                                    class="form-input rounded-full peer @error('password') border-red-500 @enderror" placeholder="" required>
                                <label for="std_password" class="form-label floating-form-label">Password <span class="text-danger">*</span></label>
                                <label class="size-8 rounded-full cursor-pointer flex-center hover:bg-gray-200 focus:bg-gray-200 absolute top-1/2 -translate-y-1/2 right-2 rtl:right-auto rtl:left-2">
                                    <input type="checkbox" class="inputTypeToggle peer/it" hidden>
                                    <i class="ri-eye-off-line text-gray-500 peer-checked/it:before:content-['\ecb5']"></i>
                                </label>
                            </div>
                            @error('password') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="std_password_confirmation"
                                    class="form-input rounded-full peer" placeholder="" required>
                                <label for="std_password_confirmation" class="form-label floating-form-label">Confirm Password <span class="text-danger">*</span></label>
                                <label class="size-8 rounded-full cursor-pointer flex-center hover:bg-gray-200 focus:bg-gray-200 absolute top-1/2 -translate-y-1/2 right-2 rtl:right-auto rtl:left-2">
                                    <input type="checkbox" class="inputTypeToggle peer/it" hidden>
                                    <i class="ri-eye-off-line text-gray-500 peer-checked/it:before:content-['\ecb5']"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-span-full">
                            <button type="submit" class="btn b-solid btn-secondary-solid !text-heading dark:text-white btn-xl !rounded-full font-bold w-full h-12" aria-label="Sign up">
                                Sign up
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Instructor Form --}}
            <div class="dashkit-tab-pane" data-tab="asInstructor">
                <form method="POST" action="{{ route('register') }}" class="w-full form">
                    @csrf
                    <input type="hidden" name="role" value="instructor">
                    <div class="grid grid-cols-2 gap-x-3 gap-y-5">
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="text" name="first_name" id="ins_first_name" value="{{ old('first_name') }}"
                                    class="form-input rounded-full peer @error('first_name') border-red-500 @enderror" placeholder="">
                                <label for="ins_first_name" class="form-label floating-form-label">First Name <span class="text-danger">*</span></label>
                            </div>
                            @error('first_name') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="text" name="last_name" id="ins_last_name" value="{{ old('last_name') }}"
                                    class="form-input rounded-full peer @error('last_name') border-red-500 @enderror" placeholder="">
                                <label for="ins_last_name" class="form-label floating-form-label">Last Name <span class="text-danger">*</span></label>
                            </div>
                            @error('last_name') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="email" name="email" id="ins_email" value="{{ old('email') }}"
                                    class="form-input rounded-full peer @error('email') border-red-500 @enderror" placeholder="" required>
                                <label for="ins_email" class="form-label floating-form-label">Email <span class="text-danger">*</span></label>
                            </div>
                            @error('email') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="text" name="phone" id="ins_phone" value="{{ old('phone') }}"
                                    class="form-input rounded-full peer @error('phone') border-red-500 @enderror" placeholder="" required>
                                <label for="ins_phone" class="form-label floating-form-label">Phone <span class="text-danger">*</span></label>
                            </div>
                            @error('phone') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="password" name="password" id="ins_password"
                                    class="form-input rounded-full peer @error('password') border-red-500 @enderror" placeholder="" required>
                                <label for="ins_password" class="form-label floating-form-label">Password <span class="text-danger">*</span></label>
                                <label class="size-8 rounded-full cursor-pointer flex-center hover:bg-gray-200 focus:bg-gray-200 absolute top-1/2 -translate-y-1/2 right-2 rtl:right-auto rtl:left-2">
                                    <input type="checkbox" class="inputTypeToggle peer/it" hidden>
                                    <i class="ri-eye-off-line text-gray-500 peer-checked/it:before:content-['\ecb5']"></i>
                                </label>
                            </div>
                            @error('password') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="ins_password_confirmation"
                                    class="form-input rounded-full peer" placeholder="" required>
                                <label for="ins_password_confirmation" class="form-label floating-form-label">Confirm Password <span class="text-danger">*</span></label>
                                <label class="size-8 rounded-full cursor-pointer flex-center hover:bg-gray-200 focus:bg-gray-200 absolute top-1/2 -translate-y-1/2 right-2 rtl:right-auto rtl:left-2">
                                    <input type="checkbox" class="inputTypeToggle peer/it" hidden>
                                    <i class="ri-eye-off-line text-gray-500 peer-checked/it:before:content-['\ecb5']"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="text" name="designation" id="ins_designation" value="{{ old('designation') }}"
                                    class="form-input rounded-full peer @error('designation') border-red-500 @enderror" placeholder="">
                                <label for="ins_designation" class="form-label floating-form-label">Designation <span class="text-danger">*</span></label>
                            </div>
                            @error('designation') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full">
                            <button type="submit" class="btn b-solid btn-secondary-solid !text-heading dark:text-white btn-xl !rounded-full font-bold w-full h-12" aria-label="Sign up">
                                Sign up
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Organization Form --}}
            <div class="dashkit-tab-pane" data-tab="asOrganization">
                <form method="POST" action="{{ route('register') }}" class="w-full form">
                    @csrf
                    <input type="hidden" name="role" value="organization">
                    <div class="grid grid-cols-2 gap-x-3 gap-y-5">
                        <div class="col-span-full">
                            <div class="relative">
                                <input type="text" name="name" id="org_name" value="{{ old('name') }}"
                                    class="form-input rounded-full peer @error('name') border-red-500 @enderror" placeholder="">
                                <label for="org_name" class="form-label floating-form-label">Full Name <span class="text-danger">*</span></label>
                            </div>
                            @error('name') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="email" name="email" id="org_email" value="{{ old('email') }}"
                                    class="form-input rounded-full peer @error('email') border-red-500 @enderror" placeholder="" required>
                                <label for="org_email" class="form-label floating-form-label">Email <span class="text-danger">*</span></label>
                            </div>
                            @error('email') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="text" name="phone" id="org_phone" value="{{ old('phone') }}"
                                    class="form-input rounded-full peer @error('phone') border-red-500 @enderror" placeholder="" required>
                                <label for="org_phone" class="form-label floating-form-label">Phone <span class="text-danger">*</span></label>
                            </div>
                            @error('phone') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="password" name="password" id="org_password"
                                    class="form-input rounded-full peer @error('password') border-red-500 @enderror" placeholder="" required>
                                <label for="org_password" class="form-label floating-form-label">Password <span class="text-danger">*</span></label>
                                <label class="size-8 rounded-full cursor-pointer flex-center hover:bg-gray-200 focus:bg-gray-200 absolute top-1/2 -translate-y-1/2 right-2 rtl:right-auto rtl:left-2">
                                    <input type="checkbox" class="inputTypeToggle peer/it" hidden>
                                    <i class="ri-eye-off-line text-gray-500 peer-checked/it:before:content-['\ecb5']"></i>
                                </label>
                            </div>
                            @error('password') <p class="text-danger text-xs mt-1 px-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-full lg:col-auto">
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="org_password_confirmation"
                                    class="form-input rounded-full peer" placeholder="" required>
                                <label for="org_password_confirmation" class="form-label floating-form-label">Confirm Password <span class="text-danger">*</span></label>
                                <label class="size-8 rounded-full cursor-pointer flex-center hover:bg-gray-200 focus:bg-gray-200 absolute top-1/2 -translate-y-1/2 right-2 rtl:right-auto rtl:left-2">
                                    <input type="checkbox" class="inputTypeToggle peer/it" hidden>
                                    <i class="ri-eye-off-line text-gray-500 peer-checked/it:before:content-['\ecb5']"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-span-full">
                            <div class="relative">
                                <textarea name="address" id="org_address" rows="10"
                                    class="form-input rounded-2xl h-auto peer @error('address') border-red-500 @enderror" placeholder="">{{ old('address') }}</textarea>
                                <label for="org_address" class="form-label floating-form-label">Address</label>
                            </div>
                        </div>
                        <div class="col-span-full">
                            <button type="submit" class="btn b-solid btn-secondary-solid !text-heading dark:text-white btn-xl !rounded-full font-bold w-full h-12" aria-label="Sign up">
                                Sign up
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
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary hover:underline" aria-label="Sign in page">Sign In</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('#userRegisterTab .dashkit-tab-btn');
    const panes = document.querySelectorAll('.dashkit-tab-pane');

    function showForm(tabId) {
        panes.forEach(p => p.classList.remove('!block'));
        const pane = document.querySelector(`.dashkit-tab-pane[data-tab="${tabId}"]`);
        if (pane) pane.classList.add('!block');
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.id;
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            showForm(tabId);
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

    showForm('asStudent');
});
</script>
@endpush