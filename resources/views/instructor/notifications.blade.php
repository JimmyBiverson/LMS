@extends('layouts.dashboard')
@section('title', 'Instructor Notifications')
@section('page-title', 'Notification')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="max-w-3xl space-y-4">
    @for($i=1;$i<=4;$i++)
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-all">
        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0"><i class="ri-notification-{{ $i % 2 == 0 ? 'line' : 'fill' }}"></i></div>
        <div class="flex-1"><p class="font-semibold text-heading">New student enrolled in <span class="text-primary">"Course {{ $i }}"</span></p><p class="text-xs text-heading/50 mt-1">{{ $i }} hour{{ $i > 1 ? 's' : '' }} ago</p></div>
        <button class="text-heading/30 hover:text-heading"><i class="ri-more-fill"></i></button>
    </div>
    @endfor
</div>
@endsection