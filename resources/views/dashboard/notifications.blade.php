@extends('layouts.dashboard')
@section('title', 'Notifications')
@section('page-title', 'Notification')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="max-w-3xl space-y-4">
    @for($i=1;$i<=4;$i++)
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-all duration-300">
        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0"><i class="ri-notification-{{ $i % 2 == 0 ? 'line' : 'fill' }}"></i></div>
        <div class="flex-1 min-w-0">
            <p class="font-semibold text-heading">New lesson <span class="text-primary">"Advanced Topics"</span> added to your course</p>
            <p class="text-xs text-heading/50 mt-1">2 hours ago</p>
        </div>
        <button class="text-heading/30 hover:text-heading transition-colors"><i class="ri-more-fill"></i></button>
    </div>
    @endfor
</div>
@endsection