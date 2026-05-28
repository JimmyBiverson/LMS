@extends('layouts.dashboard')
@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('user-name', auth()->user()->role === 'admin' ? 'Admin' : (auth()->user()->role === 'instructor' ? 'Instructor' : 'Student'))
@section('sidebar')
@php
$sidebars = ['admin' => 'admin-sidebar', 'instructor' => 'instructor-sidebar', 'organization' => 'org-sidebar', 'student' => 'student-sidebar'];
@endphp
@include('components.' . ($sidebars[auth()->user()->role] ?? 'student-sidebar'))
@stop
@section('content')
@if(session('success'))<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>@endif

<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading">Notifications</h3>
        @if($notifications->where('is_read', false)->count())
        <form method="POST" action="/dashboard/notifications/read-all">
            @csrf
            <button type="submit" class="text-sm text-primary hover:underline font-semibold">Mark all as read</button>
        </form>
        @endif
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($notifications as $n)
        <div class="p-5 flex items-start gap-4 {{ !$n->is_read ? 'bg-primary/5' : '' }}">
            <div class="w-10 h-10 rounded-full {{ !$n->is_read ? 'bg-primary text-white' : 'bg-gray-100 text-heading/50' }} flex items-center justify-center shrink-0">
                <i class="ri-notification-{{ $n->is_read ? 'line' : 'fill' }} text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-heading text-sm">{{ $n->subject }}</p>
                @if($n->body)
                <p class="text-heading/60 text-xs mt-1">{{ Str::limit($n->body, 200) }}</p>
                @endif
                <p class="text-heading/40 text-xs mt-1">{{ $n->created_at->diffForHumans() }}</p>
            </div>
            @if(!$n->is_read)
            <form method="POST" action="/dashboard/notifications/{{ $n->id }}/read">
                @csrf
                <button type="submit" class="text-xs text-primary hover:underline shrink-0">Mark read</button>
            </form>
            @endif
        </div>
        @empty
        <div class="py-12 text-center text-heading/40 text-sm">No notifications yet.</div>
        @endforelse
    </div>
</div>
@endsection
