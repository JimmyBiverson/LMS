@extends('layouts.dashboard')
@section('title', 'Language')
@section('page-title', 'Language')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Language Translations</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Key</th>
                <th class="text-left py-4 px-6 font-semibold">English</th>
                <th class="text-left py-4 px-6 font-semibold">Translation</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach(['home','courses','about','contact'] as $i=>$k)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td><td class="py-4 px-6 font-semibold text-heading">{{ $k }}</td><td class="py-4 px-6 text-heading/70">{{ ucfirst($k) }}</td><td class="py-4 px-6 text-heading/70">{{ ucfirst($k) }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection