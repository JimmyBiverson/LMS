@extends('layouts.dashboard')
@section('title', 'Notice Board')
@section('page-title', 'Notice Board')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="space-y-4 max-w-3xl">
    <div class="flex items-center justify-between"><h3 class="font-bold text-heading">All Notices</h3><span x-data="{ open: false }" class="relative inline-block" @mouseenter="open = true" @mouseleave="open = false">
    <a href="javascript:void(0)" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all"><i class="ri-add-line mr-1"></i>Add Notice</a>
    <div x-show="open" x-cloak class="absolute bottom-full right-0 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap shadow-lg z-50">Coming soon</div>
</span></div>
    @for($i=1;$i<=4;$i++)
    <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition-all">
        <div class="flex items-start justify-between gap-4">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0"><i class="ri-megaphone-line"></i></div>
            <div class="flex-1">
                <div class="flex items-center justify-between"><h4 class="font-bold text-heading">Notice {{ $i }}</h4><span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-semibold">Active</span></div>
                <p class="text-sm text-heading/60 mt-1">This is an important notice message for the platform.</p>
                <p class="text-xs text-heading/40 mt-2">2024-12-0{{ $i }}</p>
            </div>
        </div>
    </div>
    @endfor
</div>
@endsection