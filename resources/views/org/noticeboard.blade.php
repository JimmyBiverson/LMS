@extends('layouts.dashboard')
@section('title', 'Org Noticeboard')
@section('page-title', 'Noticeboard')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="space-y-4 max-w-3xl">
    @for($i=1;$i<=4;$i++)
    <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition-all">
        <div class="flex items-start justify-between gap-4">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0"><i class="ri-megaphone-line"></i></div>
            <div class="flex-1">
                <h4 class="font-bold text-heading">Notice Title {{ $i }}</h4>
                <p class="text-sm text-heading/60 mt-1">This is an important notice for all instructors and students regarding upcoming changes to the platform.</p>
                <p class="text-xs text-heading/40 mt-2">Posted on 2024-12-0{{ $i }} by Admin</p>
            </div>
        </div>
    </div>
    @endfor
</div>
@endsection