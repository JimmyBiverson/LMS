@extends('layouts.dashboard')
@section('title', 'Org Settings')
@section('page-title', 'Settings')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="max-w-2xl space-y-6">
    <!-- Organization Settings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6">Organization Settings</h3>
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="/org/settings" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <div><label class="block text-sm font-semibold text-heading mb-1">Organization Logo / Profile Image</label><input name="profile_image" type="file" accept="image/*" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm bg-gray-50 focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Organization Name *</label><input name="name" type="text" value="{{ old('name', auth()->user()->name) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Email</label><input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Phone</label><input name="phone" type="text" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Address</label><textarea name="address" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">{{ old('address', auth()->user()->address) }}</textarea></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Save Changes</button>
        </form>
    </div>

    <!-- Password Settings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6">Change Password</h3>
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('status') }}</div>
        @endif
        <form method="POST" action="/org/change-password" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-semibold text-heading mb-1">Current Password</label><input name="current_password" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">New Password</label><input name="password" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Confirm New Password</label><input name="password_confirmation" type="password" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Change Password</button>
        </form>
    </div>
</div>
@endsection