@extends('layouts.dashboard')
@section('title', 'Settings')
@section('page-title', 'Settings')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop

@php
    $tab = request('tab', 'school');
@endphp

@section('content')
@if (session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-semibold">{{ session('error') }}</div>
@endif
@if ($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
    <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<div x-data="{ tab: '{{ $tab }}' }" class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="flex items-center gap-1 p-1 border-b border-gray-100 overflow-x-auto">
            <button @click="tab = 'school'" :class="tab === 'school' ? 'bg-primary text-white shadow-sm' : 'text-heading/60 hover:bg-gray-100'" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all whitespace-nowrap">
                <i class="ri-settings-3-line mr-1.5"></i>School Info
            </button>
            <button @click="tab = 'theme'" :class="tab === 'theme' ? 'bg-primary text-white shadow-sm' : 'text-heading/60 hover:bg-gray-100'" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all whitespace-nowrap">
                <i class="ri-palette-line mr-1.5"></i>Theme
            </button>
            <button @click="tab = 'backend'" :class="tab === 'backend' ? 'bg-primary text-white shadow-sm' : 'text-heading/60 hover:bg-gray-100'" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all whitespace-nowrap">
                <i class="ri-server-line mr-1.5"></i>Backend
            </button>
            <button @click="tab = 'instructors'" :class="tab === 'instructors' ? 'bg-primary text-white shadow-sm' : 'text-heading/60 hover:bg-gray-100'" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all whitespace-nowrap">
                <i class="ri-user-star-line mr-1.5"></i>Instructor Approval
            </button>
        </div>
    </div>

    <div x-show="tab === 'school'" x-cloak>
        @include('admin.settings.school')
    </div>
    <div x-show="tab === 'theme'" x-cloak>
        @include('admin.theme-setting')
    </div>
    <div x-show="tab === 'backend'" x-cloak>
        @include('admin.backend-setting')
    </div>
    <div x-show="tab === 'instructors'" x-cloak>
        @include('admin.settings.approve-instructors')
    </div>
</div>
@endsection
