@extends('layouts.dashboard')

@section('title', 'Class Calendar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-heading">Class Calendar</h1>
            <p class="text-sm text-gray-500 mt-1">Browse your scheduled Zoom classes month by month.</p>
        </div>
        <a href="{{ route('zoom.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">
            <i class="ri-arrow-left-s-line"></i> Back to Classes
        </a>
    </div>

    <x-zoom-calendar :calendar="$calendar" routePrefix="zoom" :user="auth()->user()" />
</div>
@endsection
