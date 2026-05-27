@extends('layouts.dashboard')
@section('title', 'Org Profile')
@section('page-title', 'Organization Profile')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6 text-center">
        <img src="https://placehold.co/120x120/5F3EED/FFFFFF?text=ORG" class="w-28 h-28 rounded-full mx-auto">
        <h3 class="text-xl font-bold text-heading mt-4">EduLab International</h3>
        <p class="text-heading/60 mt-1">Providing quality education since 2020</p>
        <div class="flex items-center justify-center gap-6 mt-4 text-sm text-heading/60">
            <span><i class="ri-mail-line mr-1"></i>info@edulab.com</span>
            <span><i class="ri-phone-line mr-1"></i>+1 234 567 890</span>
            <span><i class="ri-map-pin-line mr-1"></i>New York, USA</span>
        </div>
        <div class="flex items-center justify-center gap-4 mt-6">
            <div class="text-center"><p class="text-2xl font-bold text-heading">42</p><p class="text-xs text-heading/50">Courses</p></div>
            <div class="text-center"><p class="text-2xl font-bold text-heading">18</p><p class="text-xs text-heading/50">Instructors</p></div>
            <div class="text-center"><p class="text-2xl font-bold text-heading">2,450</p><p class="text-xs text-heading/50">Students</p></div>
        </div>
    </div>
</div>
@endsection