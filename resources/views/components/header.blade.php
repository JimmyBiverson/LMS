<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="bg-[#111827] text-white text-sm py-2 hidden lg:block">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="mailto:{{ config('app.email', 'info@edulab.com') }}" class="hover:text-secondary transition-colors duration-300 flex items-center gap-2">
                    <i class="ri-mail-line"></i>
                    {{ config('app.email', 'info@edulab.com') }}
                </a>
                <a href="tel:+15529569286" class="hover:text-secondary transition-colors duration-300 flex items-center gap-2">
                    <i class="ri-phone-line"></i>
                    Call: +1552 956 9286
                </a>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-white/80">
                    <span>English</span>
                    <span>Arabic</span>
                    <span>Spanish</span>
                    <span>Bengali</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <a href="/" class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
                    <i class="ri-graduation-cap-fill text-white text-xl"></i>
                </div>
                <span class="text-2xl font-extrabold text-heading">Edulab</span>
            </a>

            <nav class="hidden lg:flex items-center gap-8">
                <a href="/" class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300">Home</a>
                <a href="/courses" class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300">Course</a>
                <div class="relative group">
                    <button class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300 flex items-center gap-1">
                        Pages <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 py-2 z-50">
                        <a href="/bundles" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Course Bundle</a>
                        <a href="/instructors" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Instructor</a>
                        <a href="/organizations" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Organization</a>
                        <a href="/blogs" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Blogs</a>
                        <a href="/about-us" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">About Us</a>
                        <a href="/privacy-policy" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Privacy & Policy</a>
                        <a href="/terms-conditions" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Terms & Condition</a>
                    </div>
                </div>
                <a href="/blogs" class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300">Blogs</a>
                <a href="/contact" class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300">Contact</a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="/cart" class="relative text-heading/80 hover:text-primary transition-colors duration-300">
                    <i class="ri-shopping-cart-line text-xl"></i>
                    <span class="absolute -top-2 -right-2 w-4 h-4 bg-secondary text-[10px] font-bold text-heading rounded-full flex items-center justify-center">0</span>
                </a>
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isInstructor() ? route('instructor.dashboard') : (auth()->user()->isOrganization() ? route('org.dashboard') : route('dashboard'))) }}" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300">
                        <i class="ri-dashboard-line"></i> Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden lg:inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-secondary text-heading text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300">
                            <i class="ri-logout-box-line"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300">
                        <i class="ri-user-line"></i> Log in
                    </a>
                    <a href="{{ route('register') }}" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 bg-secondary text-heading text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300">
                        Sign up
                    </a>
                @endauth
                <button class="lg:hidden text-heading text-2xl" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <i class="ri-menu-line"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-3">
            <a href="/" class="block text-sm font-semibold text-heading/80 py-2">Home</a>
            <a href="/courses" class="block text-sm font-semibold text-heading/80 py-2">Course</a>
            <div>
                <button onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180')" class="flex items-center justify-between w-full text-sm font-semibold text-heading/80 py-2">
                    Pages <i class="ri-arrow-down-s-line transition-transform duration-300"></i>
                </button>
                <div class="hidden pl-4 space-y-2">
                    <a href="/bundles" class="block text-sm text-heading/70 py-1">Course Bundle</a>
                    <a href="/instructors" class="block text-sm text-heading/70 py-1">Instructor</a>
                    <a href="/organizations" class="block text-sm text-heading/70 py-1">Organization</a>
                    <a href="/blogs" class="block text-sm text-heading/70 py-1">Blogs</a>
                    <a href="/about-us" class="block text-sm text-heading/70 py-1">About Us</a>
                    <a href="/privacy-policy" class="block text-sm text-heading/70 py-1">Privacy & Policy</a>
                    <a href="/terms-conditions" class="block text-sm text-heading/70 py-1">Terms & Condition</a>
                </div>
            </div>
            <a href="/blogs" class="block text-sm font-semibold text-heading/80 py-2">Blogs</a>
            <a href="/contact" class="block text-sm font-semibold text-heading/80 py-2">Contact</a>
            <div class="flex gap-3 pt-2">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isInstructor() ? route('instructor.dashboard') : (auth()->user()->isOrganization() ? route('org.dashboard') : route('dashboard'))) }}" class="flex-1 text-center px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-full">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full text-center px-5 py-2.5 bg-secondary text-heading text-sm font-semibold rounded-full">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex-1 text-center px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-full">Log in</a>
                    <a href="{{ route('register') }}" class="flex-1 text-center px-5 py-2.5 bg-secondary text-heading text-sm font-semibold rounded-full">Sign up</a>
                @endauth
            </div>
        </div>
    </div>
</header>