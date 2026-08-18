@extends('layouts.dashboard')
@section('title', 'Send Notification')
@section('page-title', 'Send Notification')
@section('user-name', auth()->user()->name ?? 'User')
@section('sidebar')
@php
$sidebars = ['admin' => 'admin-sidebar', 'staff' => 'admin-sidebar', 'instructor' => 'instructor-sidebar', 'organization' => 'org-sidebar', 'student' => 'student-sidebar'];
@endphp
@include('components.' . ($sidebars[auth()->user()->role] ?? 'student-sidebar'))
@stop
@section('content')
@if(session('success'))<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>@endif
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6">Send Notification to a User</h3>
        <form method="POST" action="/notifications/send" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-heading mb-1.5">Recipient</label>
                <select name="recipient_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <option value="">Select a user...</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('recipient_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                    @endforeach
                </select>
                @error('recipient_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-1.5">Subject</label>
                <input name="subject" type="text" value="{{ old('subject') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Notification subject...">
                @error('subject')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-1.5">Message</label>
                <textarea name="body" rows="5" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Write your message...">{{ old('body') }}</textarea>
                @error('body')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Send Notification</button>
        </form>
    </div>
</div>
@endsection