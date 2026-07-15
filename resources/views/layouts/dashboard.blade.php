<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') - @endif{{ $school->school_name ?? 'Edulab' }}</title>
    @if($school->favicon)
    <link rel="icon" type="{{ \Illuminate\Support\Str::endsWith($school->favicon, '.svg') ? 'image/svg+xml' : 'image/png' }}" href="{{ asset('storage/'.$school->favicon) }}">
    @else
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @endif
    <!-- Sharing Links Preview (Open Graph & Twitter) -->
    <meta property="og:title" content="@hasSection('title')@yield('title') - @endif{{ $school->school_name ?? 'Edulab' }}">
    <meta property="og:description" content="Discover, learn, and thrive with us. Experience a smooth and rewarding educational adventure.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    @if($school->site_logo)
    <meta property="og:image" content="{{ asset('storage/'.$school->site_logo) }}">
    <meta name="twitter:image" content="{{ asset('storage/'.$school->site_logo) }}">
    @elseif($school->favicon)
    <meta property="og:image" content="{{ asset('storage/'.$school->favicon) }}">
    <meta name="twitter:image" content="{{ asset('storage/'.$school->favicon) }}">
    @else
    <meta property="og:image" content="{{ asset('favicon.png') }}">
    <meta name="twitter:image" content="{{ asset('favicon.png') }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@hasSection('title')@yield('title') - @endif{{ $school->school_name ?? 'Edulab' }}">
    <meta name="twitter:description" content="Discover, learn, and thrive with us. Experience a smooth and rewarding educational adventure.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: {{ $school->primary_color ?? '#5F3EED' }};
            --secondary: {{ $school->secondary_color ?? '#F4B826' }};
            --accent: {{ $school->accent_color ?? '#1AEBC5' }};
        }
        #sidebar nav a.bg-primary-50 {
            position: relative;
        }
        #sidebar nav a.bg-primary-50::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 4px;
            background: var(--primary, #5F3EED);
            border-radius: 0 4px 4px 0;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans text-heading antialiased {{ $roleBg ?? 'bg-gray-50' }}">

<div class="flex h-screen overflow-hidden">
    @php
        $roleSidebarBorder = $roleSidebarBorder ?? 'border-gray-200';
        $roleSidebarLogo = $roleLogoBg ?? 'bg-primary';
        $roleAccent = $roleAccent ?? 'primary';
        $roleHover = $roleHover ?? 'primary-50';
        $notifUrl = $notifUrl ?? match(true) {
            auth()->user()->isAdmin() || auth()->user()->isStaff() => url('admin/notifications'),
            auth()->user()->isInstructor() => url('instructor/notifications'),
            auth()->user()->isOrganization() => url('org/notifications'),
            default => url('dashboard/notifications'),
        };
    @endphp
    <aside id="sidebar" class="w-64 {{ $roleSidebarBg ?? 'bg-white' }} {{ $roleSidebarBorder }} border-r flex flex-col fixed lg:static inset-y-0 left-0 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <div class="h-16 flex items-center gap-2 px-6 border-b {{ $roleSidebarBorder }} shrink-0">
            @if($school->site_logo)
            <img src="{{ asset('storage/'.$school->site_logo) }}" alt="{{ $school->school_name ?? 'Edulab' }}" class="h-8">
            @else
            <div class="w-8 h-8 rounded-lg {{ $roleSidebarLogo }} flex items-center justify-center"><i class="ri-graduation-cap-fill text-white"></i></div>
            @endif
            <span class="text-lg font-extrabold text-heading">{{ $school->school_name ?? 'Edulab' }}</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">@yield('sidebar')</nav>
        <div class="p-3 border-t {{ $roleSidebarBorder }} shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm text-heading/70 hover:text-red-500 hover:bg-red-50 transition-all duration-300">
                    <i class="ri-logout-box-r-line text-lg"></i><span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-20 hidden" onclick="toggleSidebar()"></div>
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-16 {{ $roleHeaderBg ?? 'bg-white' }} border-b {{ $roleSidebarBorder }} flex items-center justify-between px-4 lg:px-6 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-heading text-xl"><i class="ri-menu-line"></i></button>
                <h2 class="text-lg font-bold text-heading hidden sm:block">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-4">
                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                <div class="hidden md:block">
                    <x-admin-search-dropdown />
                </div>
                @endif
                <a href="/" class="text-heading/60 hover:text-{{ $roleAccent }} transition-colors" title="View Site"><i class="ri-external-link-line text-lg"></i></a>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="relative text-heading/60 hover:text-{{ $roleAccent }} transition-colors">
                        <i class="ri-notification-3-line text-lg"></i>
                        @php $unreadCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                        @if ($unreadCount > 0)<span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($unreadCount, 99) }}</span>@endif
                    </button>
                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
                        <div class="p-3 border-b border-gray-100 flex items-center justify-between">
                            <span class="font-bold text-heading text-sm">Notifications</span>
                            <a href="{{ $notifUrl }}" class="text-xs text-primary hover:underline">View all</a>
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                            @php $recentNotifications = auth()->user()->notifications()->latest()->take(5)->get(); @endphp
                            @forelse($recentNotifications as $n)
                            <div class="p-3 flex items-start gap-3 {{ !$n->is_read ? 'bg-primary/5' : '' }}">
                                @if($n->link)<a href="{{ $n->link }}" class="flex items-start gap-3 flex-1 min-w-0 no-underline text-inherit">@endif
                                    <div class="w-8 h-8 rounded-full {{ !$n->is_read ? 'bg-primary text-white' : 'bg-gray-100 text-heading/50' }} flex items-center justify-center shrink-0">
                                        <i class="ri-notification-{{ $n->is_read ? 'line' : 'fill' }} text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-heading text-xs">{{ $n->subject }}</p>
                                        @if($n->body)<p class="text-heading/60 text-xs mt-0.5 leading-relaxed">{{ Str::limit($n->body, 80) }}</p>@endif
                                        <p class="text-heading/40 text-[10px] mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
                                    </div>
                                @if($n->link)</a>@endif
                                @if(!$n->is_read)
                                <form method="POST" action="{{ $notifUrl }}/{{ $n->id }}/read" class="shrink-0">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-primary hover:underline">Mark read</button>
                                </form>
                                @endif
                            </div>
                            @empty
                            <div class="py-8 text-center text-heading/40 text-sm">No notifications yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 cursor-pointer group relative">
                    <div class="w-8 h-8 rounded-full {{ $roleAvatarBg ?? 'bg-primary-50' }} flex items-center justify-center"><i class="ri-user-smile-line text-sm {{ $roleAvatarText ?? 'text-primary' }}"></i></div>
                    <span class="text-sm font-semibold text-heading hidden sm:block">@yield('user-name', 'User')</span>
                    <i class="ri-arrow-down-s-line text-heading/60 text-sm"></i>
                    <div class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        @php
                            $currentUser = auth()->user();
                            $profileUrl = match(true) {
                                $currentUser->isAdmin() || $currentUser->isStaff() => url('admin/profile'),
                                $currentUser->isInstructor() => url('instructor/settings'),
                                $currentUser->isOrganization() => url('org/profile'),
                                default => url('dashboard/profile'),
                            };
                            $settingsUrl = match(true) {
                                $currentUser->isAdmin() || $currentUser->isStaff() => url('admin/settings'),
                                $currentUser->isInstructor() => url('instructor/settings'),
                                $currentUser->isOrganization() => url('org/settings'),
                                default => url('dashboard/settings'),
                            };
                        @endphp
                        <a href="{{ $profileUrl }}" class="block px-4 py-2 text-sm text-heading/70 hover:bg-{{ $roleHover }} hover:text-{{ $roleAccent }} transition-colors"><i class="ri-user-line mr-2"></i>Profile</a>
                        <a href="{{ $settingsUrl }}" class="block px-4 py-2 text-sm text-heading/70 hover:bg-{{ $roleHover }} hover:text-{{ $roleAccent }} transition-colors"><i class="ri-settings-3-line mr-2"></i>Settings</a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors"><i class="ri-logout-box-r-line mr-2"></i>Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @if(session('success'))<div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-semibold">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"><ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            <main id="main-content">@yield('content')</main>
        </main>
    </div>
</div>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({duration:800,once:true,easing:'ease-out-cubic'})</script>
<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.toggle('hidden');
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var nav = document.querySelector('#sidebar nav');
    if (!nav) return;

    var saved = sessionStorage.getItem('sidebarScrollPos');
    if (saved) nav.scrollTop = parseInt(saved, 10);

    var active = nav.querySelector('.bg-primary-50');
    if (active) {
        var accordion = active.closest('[x-data]');
        if (accordion) {
            var alpine = accordion.__x;
            if (alpine && !alpine.$data.open) {
                alpine.$data.open = true;
            }
        }
        var navRect = nav.getBoundingClientRect();
        var activeRect = active.getBoundingClientRect();
        if (activeRect.bottom > navRect.bottom || activeRect.top < navRect.top) {
            active.scrollIntoView({ block: 'center', behavior: 'smooth' });
        }
    }

    window.addEventListener('beforeunload', function () {
        sessionStorage.setItem('sidebarScrollPos', nav.scrollTop);
    });
});
</script>
<script>localStorage.removeItem('login_selected_role');</script>
@stack('scripts')
</body>
</html>