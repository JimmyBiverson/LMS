@extends('layouts.dashboard')
@section('title', 'Settings')
@section('page-title', 'Settings')
@section('user-name', auth()->user()->name ?? 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6">Account Settings</h3>
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ url('dashboard/settings') }}" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-semibold text-heading mb-1">Current Password</label><input name="current_password" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">New Password</label><input name="password" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Confirm New Password</label><input name="password_confirmation" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Change Password</button>
        </form>
    </div>
</div>
@endsection