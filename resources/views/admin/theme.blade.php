@extends('layouts.dashboard')
@section('title', 'Theme')
@section('page-title', 'Theme')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-bold text-heading mb-6">Theme Settings</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach(['Default','Dark','Light','Modern','Classic'] as $t)
        <div class="border border-heading/10 rounded-xl p-5 text-center cursor-pointer hover:border-primary hover:shadow-md transition-all {{ $loop->first ? 'border-primary ring-2 ring-primary/20' : '' }}">
            <div class="w-full h-24 rounded-lg bg-heading/5 mb-3 flex items-center justify-center"><i class="ri-paint-brush-line text-3xl text-heading/30"></i></div>
            <p class="font-semibold text-heading">{{ $t }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection