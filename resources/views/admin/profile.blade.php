@extends('layouts.dashboard')
@section('title', 'Profile')
@section('page-title', 'Profile')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-center mb-6"><img src="https://ui-avatars.com/api/?name=Admin+User&background=5F3EED&color=fff&size=96" alt="Admin User" width="96" height="96" class="w-24 h-24 rounded-full mx-auto"><h3 class="text-xl font-bold text-heading mt-4">Admin User</h3><p class="text-heading/60 text-sm">Super Admin</p></div>
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="/admin/profile" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-heading mb-1">First Name</label><input name="first_name" type="text" value="{{ old('first_name', auth()->user()->first_name) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Last Name</label><input name="last_name" type="text" value="{{ old('last_name', auth()->user()->last_name) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Email</label><input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Update Profile</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
        <h3 class="font-bold text-heading mb-6">Change Password</h3>
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('status') }}</div>
        @endif
        <form method="POST" action="/admin/change-password" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-semibold text-heading mb-1">Current Password</label><input name="current_password" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">New Password</label><input name="password" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Confirm New Password</label><input name="password_confirmation" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Change Password</button>
        </form>
    </div>
</div>
@endsection