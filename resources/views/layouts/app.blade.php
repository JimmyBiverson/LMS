<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $school->school_name ?? 'Edulab') - {{ $school->school_name ?? 'Edulab' }}</title>
    @if($school->favicon)
    <link rel="icon" type="{{ \Illuminate\Support\Str::endsWith($school->favicon, '.svg') ? 'image/svg+xml' : 'image/png' }}" href="{{ asset('storage/'.$school->favicon) }}">
    @else
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @endif
    <!-- Sharing Links Preview (Open Graph & Twitter) -->
    <meta property="og:title" content="@yield('title', $school->school_name ?? 'Edulab') - {{ $school->school_name ?? 'Edulab' }}">
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
    <meta name="twitter:title" content="@yield('title', $school->school_name ?? 'Edulab') - {{ $school->school_name ?? 'Edulab' }}">
    <meta name="twitter:description" content="Discover, learn, and thrive with us. Experience a smooth and rewarding educational adventure.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>
        :root { --primary: {{ $school->primary_color ?? '#5F3EED' }}; --secondary: {{ $school->secondary_color ?? '#F4B826' }}; --accent: {{ $school->accent_color ?? '#1AEBC5' }}; }
        .bg-primary { background-color: var(--primary); }
        .text-primary { color: var(--primary); }
        .border-primary { border-color: var(--primary); }
        .bg-primary\/10 { background-color: color-mix(in srgb, var(--primary) 10%, transparent); }
        .bg-primary\/20 { background-color: color-mix(in srgb, var(--primary) 20%, transparent); }
        .bg-primary-50 { background-color: color-mix(in srgb, var(--primary) 10%, white); }
        .bg-primary-100 { background-color: color-mix(in srgb, var(--primary) 20%, white); }
        .text-primary\/20 { color: color-mix(in srgb, var(--primary) 20%, transparent); }
        .text-primary\/30 { color: color-mix(in srgb, var(--primary) 30%, transparent); }
        .shadow-primary\/25 { box-shadow: 0 4px 20px color-mix(in srgb, var(--primary) 25%, transparent); }
        .bg-secondary { background-color: var(--secondary); }
        .text-secondary { color: var(--secondary); }
        .ring-primary { --tw-ring-color: var(--primary); }
        .focus\:border-primary:focus { border-color: var(--primary); }
        .focus\:ring-primary\/20:focus { --tw-ring-color: color-mix(in srgb, var(--primary) 20%, transparent); }
        .hover\:bg-primary:hover { background-color: var(--primary); }
        .hover\:text-primary:hover { color: var(--primary); }
        .from-primary { --tw-gradient-from: var(--primary); }
        .to-primary { --tw-gradient-to: var(--primary); }
        @media (prefers-color-scheme: dark) { :root { } }
        {{ $school->custom_css ?? '' }}
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans text-heading antialiased">

@include('components.header')

<main>
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">{{ session('error') }}</div>
        </div>
    @endif
    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="px-4 py-3 bg-blue-100 border border-blue-300 rounded-lg text-blue-700 text-sm">{{ session('info') }}</div>
        </div>
    @endif
    <main id="main-content">@yield('content')</main>
</main>

@include('components.footer')

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({duration:800,once:true,easing:'ease-out-cubic'})</script>
@stack('scripts')
</body>
</html>
