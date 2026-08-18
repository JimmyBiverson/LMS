    <header class="bg-white shadow-sm sticky top-0 z-50" x-data="headerSearch()">
    <div class="bg-[#111827] text-white text-sm py-2 hidden lg:block">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="mailto:{{ $school->school_email ?? config('app.email', 'info@edulab.com') }}" class="hover:text-secondary transition-colors duration-300 flex items-center gap-2">
                    <i class="ri-mail-line"></i>
                    {{ $school->school_email ?? config('app.email', 'info@edulab.com') }}
                </a>
                <a href="tel:{{ $school->school_phone ?? '+15529569286' }}" class="hover:text-secondary transition-colors duration-300 flex items-center gap-2">
                    <i class="ri-phone-line"></i>
                    Call: {{ $school->school_phone ?? '+1552 956 9286' }}
                </a>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-white/80">
                    <span>English</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <a href="/" class="flex items-center gap-2">
                @if($school->site_logo)
                <img src="{{ asset('storage/'.$school->site_logo) }}" alt="{{ $school->school_name ?? 'Edulab' }}" class="h-10">
                @else
                <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
                    <i class="ri-graduation-cap-fill text-white text-xl"></i>
                </div>
                @endif
                <span class="text-2xl font-extrabold text-heading">{{ $school->school_name ?? 'Edulab' }}</span>
            </a>

            <nav class="hidden lg:flex items-center gap-8">
                <a href="/" class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300">Home</a>
                <a href="/courses" class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300">Courses</a>
                <div class="relative" x-data="{ pagesOpen: false }" @click.outside="pagesOpen = false">
                    <button type="button" @click="pagesOpen = !pagesOpen" class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300 flex items-center gap-1">
                        Pages <i class="ri-arrow-down-s-line transition-transform duration-300" :class="{ 'rotate-180': pagesOpen }"></i>
                    </button>
                    <div x-show="pagesOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="/bundles" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Course Bundles</a>
                        <a href="/instructors" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Instructors</a>
                        <a href="/organizations" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Organizations</a>
                        <a href="/blogs" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Blogs</a>
                        <a href="/about-us" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">About Us</a>
                        <a href="/privacy-policy" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Privacy Policy</a>
                        <a href="/terms-conditions" class="block px-4 py-2 text-sm text-heading/70 hover:text-primary hover:bg-primary-50 transition-colors duration-300">Terms & Conditions</a>
                    </div>
                </div>
                <a href="/blogs" class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300">Blogs</a>
                <a href="/contact" class="text-sm font-semibold text-heading/80 hover:text-primary transition-colors duration-300">Contact</a>
            </nav>

            <div class="flex items-center gap-4">
                {{-- Search Button --}}
                <button @click="searchOpen = !searchOpen" class="text-heading/80 hover:text-primary transition-colors duration-300">
                    <i class="ri-search-line text-xl"></i>
                </button>
                <a href="/cart" class="relative text-heading/80 hover:text-primary transition-colors duration-300">
                    <i class="ri-shopping-cart-line text-xl"></i>
                    @php $cartCount = count(session('cart.courses', [])) + count(session('cart.bundles', [])); @endphp
                    <span class="absolute -top-2 -right-2 w-4 h-4 bg-secondary text-[10px] font-bold text-heading rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                </a>
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard.dashboard') : (auth()->user()->isInstructor() ? route('instructor.dashboard.dashboard') : (auth()->user()->isOrganization() ? route('org.dashboard.dashboard') : route('dashboard'))) }}" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300">
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
                <button class="lg:hidden text-heading text-2xl" @click="mobileMenuOpen = !mobileMenuOpen">
                    <i class="ri-menu-line"></i>
                </button>
            </div>
        </div>

        {{-- Search Overlay --}}
        <div x-show="searchOpen"
             x-cloak
             @click.away="open = false"
             @keydown.escape="open = false; searchOpen = false"
             x-transition:enter="transition-all duration-300 ease-out"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition-all duration-200 ease-in"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="pb-4 relative">
            <form action="/search" method="GET" class="flex gap-2" @submit="open = false">
                <div class="flex-1 relative">
                    <input type="text" name="q" x-model="searchQuery" @input="search()" @keydown.down.prevent="selectedIndex = Math.min(selectedIndex + 1, results.length - 1)" @keydown.up.prevent="selectedIndex = Math.max(selectedIndex - 1, 0)" @keydown.enter="handleEnter($event)" placeholder="Search courses, blogs, instructors..." class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                    <div x-show="open && results.length > 0"
                         x-cloak
                         class="absolute top-full left-0 right-0 mt-1 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
                        <template x-for="(result, index) in results" :key="result.title + result.type">
                            <a :href="result.url"
                               class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
                               :class="{ 'bg-primary-50' : index === selectedIndex }"
                               @mouseenter="selectedIndex = index">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold"
                                     :class="{
                                         'bg-primary-50 text-primary': result.type === 'Course',
                                         'bg-green-100 text-green-700': result.type === 'Blog',
                                         'bg-amber-100 text-amber-700': result.type === 'Instructor'
                                     }">
                                    <i class="text-sm"
                                       :class="{
                                           'ri-book-open-line': result.type === 'Course',
                                           'ri-file-text-line': result.type === 'Blog',
                                           'ri-user-star-line': result.type === 'Instructor'
                                       }"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-heading truncate" x-text="result.title"></p>
                                    <p class="text-xs text-heading/50 truncate">
                                        <span x-text="result.type"></span>
                                        <span x-show="result.subtitle"> · <span x-text="result.subtitle"></span></span>
                                    </p>
                                </div>
                            </a>
                        </template>
                    </div>
                    <div x-show="open && loading"
                         x-cloak
                         class="absolute top-full left-0 right-0 mt-1 bg-white rounded-xl shadow-lg border border-gray-100 p-4 text-center z-50">
                        <p class="text-sm text-heading/50">Searching...</p>
                    </div>
                </div>
                <button type="submit" class="px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:opacity-90 transition-all">
                    <i class="ri-search-line"></i> Search
                </button>
            </form>
        </div>
    </div>

    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition-all duration-300 ease-out"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition-all duration-200 ease-in"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-3">
            <a href="/" class="block text-sm font-semibold text-heading/80 py-2">Home</a>
            <a href="/courses" class="block text-sm font-semibold text-heading/80 py-2">Courses</a>
            <div>
                <button onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180')" class="flex items-center justify-between w-full text-sm font-semibold text-heading/80 py-2">
                    Pages <i class="ri-arrow-down-s-line transition-transform duration-300"></i>
                </button>
                <div class="hidden pl-4 space-y-2">
                    <a href="/bundles" class="block text-sm text-heading/70 py-1">Course Bundles</a>
                    <a href="/instructors" class="block text-sm text-heading/70 py-1">Instructors</a>
                    <a href="/organizations" class="block text-sm text-heading/70 py-1">Organizations</a>
                    <a href="/blogs" class="block text-sm text-heading/70 py-1">Blogs</a>
                    <a href="/about-us" class="block text-sm text-heading/70 py-1">About Us</a>
                    <a href="/privacy-policy" class="block text-sm text-heading/70 py-1">Privacy Policy</a>
                    <a href="/terms-conditions" class="block text-sm text-heading/70 py-1">Terms & Conditions</a>
                </div>
            </div>
            <a href="/blogs" class="block text-sm font-semibold text-heading/80 py-2">Blogs</a>
            <a href="/contact" class="block text-sm font-semibold text-heading/80 py-2">Contact</a>
            <div class="pt-3 border-t border-gray-100 space-y-2">
                <a href="mailto:{{ $school->school_email ?? config('app.email', 'info@edulab.com') }}" class="flex items-center gap-2 text-sm text-heading/60 hover:text-primary transition-colors">
                    <i class="ri-mail-line"></i>
                    {{ $school->school_email ?? config('app.email', 'info@edulab.com') }}
                </a>
                <a href="tel:{{ $school->school_phone ?? '+15529569286' }}" class="flex items-center gap-2 text-sm text-heading/60 hover:text-primary transition-colors">
                    <i class="ri-phone-line"></i>
                    Call: {{ $school->school_phone ?? '+1552 956 9286' }}
                </a>
            </div>
            <div class="flex gap-3 pt-2">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard.dashboard') : (auth()->user()->isInstructor() ? route('instructor.dashboard.dashboard') : (auth()->user()->isOrganization() ? route('org.dashboard.dashboard') : route('dashboard'))) }}" class="flex-1 text-center px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-full">Dashboard</a>
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

@push('scripts')
<script>
    function headerSearch() {
        return {
            mobileMenuOpen: false,
            searchOpen: false,
            searchQuery: '',
            results: [],
            open: false,
            loading: false,
            selectedIndex: 0,
            debounceTimer: null,

            search() {
                if (this.debounceTimer) clearTimeout(this.debounceTimer);
                const q = this.searchQuery.trim();

                if (q.length < 2) {
                    this.results = [];
                    this.open = false;
                    return;
                }

                this.loading = true;
                this.debounceTimer = setTimeout(() => {
                    fetch('/search/suggestions?q=' + encodeURIComponent(q))
                        .then(response => response.json())
                        .then(data => {
                            this.results = data.results || [];
                            this.open = this.results.length > 0;
                            this.selectedIndex = 0;
                        })
                        .catch(() => {
                            this.results = [];
                            this.open = false;
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }, 300);
            },

            handleEnter(event) {
                if (this.results.length > 0 && this.selectedIndex >= 0) {
                    event.preventDefault();
                    window.location.href = this.results[this.selectedIndex].url;
                }
            },

            goToResult() {
                if (this.results.length > 0 && this.selectedIndex >= 0) {
                    window.location.href = this.results[this.selectedIndex].url;
                }
            }
        }
    }
</script>
@endpush
