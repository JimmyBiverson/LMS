<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Edulab') - Edulab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans text-heading antialiased bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed lg:static inset-y-0 left-0 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <div class="h-16 flex items-center gap-2 px-6 border-b border-gray-100 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center"><i class="ri-graduation-cap-fill text-white"></i></div>
            <span class="text-lg font-extrabold text-heading">Edulab</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">@yield('sidebar')</nav>
        <div class="p-3 border-t border-gray-100 shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm text-heading/70 hover:text-red-500 hover:bg-red-50 transition-all duration-300">
                    <i class="ri-logout-box-r-line text-lg"></i><span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-heading text-xl"><i class="ri-menu-line"></i></button>
                <h2 class="text-lg font-bold text-heading hidden sm:block">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-4">
                <a href="/" class="text-heading/60 hover:text-primary transition-colors" title="View Site"><i class="ri-external-link-line text-lg"></i></a>
                <a href="/dashboard/notification" class="relative text-heading/60 hover:text-primary transition-colors">
                    <i class="ri-notification-3-line text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">3</span>
                </a>
                <div class="flex items-center gap-2 cursor-pointer group relative">
                    <div class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center"><i class="ri-user-smile-line text-sm text-primary"></i></div>
                    <span class="text-sm font-semibold text-heading hidden sm:block">@yield('user-name', 'User')</span>
                    <i class="ri-arrow-down-s-line text-heading/60 text-sm"></i>
                    <div class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <a href="@yield('profile-route', '#')" class="block px-4 py-2 text-sm text-heading/70 hover:bg-primary-50 hover:text-primary transition-colors"><i class="ri-user-line mr-2"></i>Profile</a>
                        <a href="@yield('setting-route', '#')" class="block px-4 py-2 text-sm text-heading/70 hover:bg-primary-50 hover:text-primary transition-colors"><i class="ri-settings-3-line mr-2"></i>Settings</a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors"><i class="ri-logout-box-r-line mr-2"></i>Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">@yield('content')</main>
    </div>
</div>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('-translate-x-full');document.getElementById('sidebar-overlay').classList.toggle('hidden')}</script>
@stack('scripts')
</body>
</html>