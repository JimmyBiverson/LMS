@extends('layouts.dashboard')
@section('title', 'Page Manage')
@section('page-title', 'Page Manage')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Custom Pages</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Page</th>
                <th class="text-left py-4 px-6 font-semibold">Slug</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach(['About Us','Terms & Conditions','Privacy Policy','Contact'] as $i=>$p)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td><td class="py-4 px-6 font-semibold text-heading">{{ $p }}</td><td class="py-4 px-6 text-heading/70">{{ preg_replace('/[^a-z0-9-]/','-',strtolower(str_replace('& ','',$p))) }}</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Published</span></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection