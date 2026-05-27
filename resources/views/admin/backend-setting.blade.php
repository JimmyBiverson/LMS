@extends('layouts.dashboard')
@section('title', 'Backend Settings')
@section('page-title', 'Backend Settings')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <h3 class="font-bold text-heading mb-6">Backend Configuration</h3>
        <form method="POST" action="/admin/backend-setting" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-semibold text-heading mb-1">Application Name</label><input name="app_name" type="text" value="{{ old('app_name', config('app.name')) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Email Address</label><input name="email" type="email" value="{{ old('email', config('app.email')) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Timezone</label><select name="timezone" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"><option value="UTC" @selected(old('timezone', config('app.timezone')) === 'UTC')>UTC</option><option value="America/New_York" @selected(old('timezone', config('app.timezone')) === 'America/New_York')>America/New_York</option><option value="Europe/London" @selected(old('timezone', config('app.timezone')) === 'Europe/London')>Europe/London</option></select></div>
            <div><label class="flex items-center gap-2"><input name="maintenance_mode" type="checkbox" value="1" class="w-5 h-5 rounded border-heading/20 text-primary" @checked(old('maintenance_mode', app()->isDownForMaintenance()))><span class="text-sm text-heading font-semibold">Enable maintenance mode</span></label></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Save Settings</button>
        </form>
    </div>
</div>
@endsection