@extends('layouts.dashboard')
@section('title', 'Org Profile')
@section('page-title', 'Organization Profile')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6 text-center">
        <img src="{{ $user->profile_image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&background=5F3EED&color=fff&size=120' }}" width="112" height="112" loading="lazy" class="w-28 h-28 rounded-full mx-auto">
        <h3 class="text-xl font-bold text-heading mt-4">{{ $user->full_name }}</h3>
        <p class="text-heading/60 mt-1">{{ $user->designation ?? "Organization" }}</p>
        <div class="flex items-center justify-center gap-6 mt-4 text-sm text-heading/60">
            <span><i class="ri-mail-line mr-1"></i>{{ $user->email }}</span>
            <span><i class="ri-phone-line mr-1"></i>{{ $user->phone ?? "N/A" }}</span>
        </div>
        <div class="flex items-center justify-center gap-4 mt-6">
            <div class="text-center"><p class="text-2xl font-bold text-heading">{{ $courseCount }}</p><p class="text-xs text-heading/50">Courses</p></div>
            <div class="text-center"><p class="text-2xl font-bold text-heading">{{ $instructorCount }}</p><p class="text-xs text-heading/50">Instructors</p></div>
            <div class="text-center"><p class="text-2xl font-bold text-heading">{{ $studentCount }}</p><p class="text-xs text-heading/50">Students</p></div>
        </div>
    </div>
</div>
@endsection
