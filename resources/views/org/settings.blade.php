@extends('layouts.dashboard')
@section('title', 'Org Settings')
@section('page-title', 'Settings')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="max-w-2xl">
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
        <form method="POST" action="/org/settings" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="flex items-center gap-4 mb-6"><img src="https://placehold.co/80x80/5F3EED/FFFFFF?text=ORG" class="w-20 h-20 rounded-full"><input name="logo" type="file" accept="image/*" class="px-4 py-2 border border-primary text-primary text-sm font-semibold rounded-full hover:bg-primary hover:text-white transition-all cursor-pointer"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Organization Name *</label><input name="name" type="text" value="{{ old('name', auth()->user()->name) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Email</label><input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Phone</label><input name="phone" type="text" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Address</label><textarea name="address" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">{{ old('address', auth()->user()->address) }}</textarea></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Save Changes</button>
        </form>
    </div>
</div>
@endsection