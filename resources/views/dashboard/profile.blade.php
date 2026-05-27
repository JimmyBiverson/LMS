@extends('layouts.dashboard')
@section('title', 'Profile')
@section('page-title', 'My Profile')
@section('user-name', auth()->user()->name ?? 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6">Profile Settings</h3>
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-heading mb-1">First Name</label><input name="first_name" type="text" value="{{ old('first_name', auth()->user()->first_name) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Last Name</label><input name="last_name" type="text" value="{{ old('last_name', auth()->user()->last_name) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Email</label><input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Phone</label><input name="phone" type="tel" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Update Profile</button>
        </form>
    </div>
</div>
@endsection