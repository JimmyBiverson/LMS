@extends('layouts.dashboard')
@section('title', 'Site Language')
@section('page-title', 'Site Language')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Site Language Settings</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Language</th>
                <th class="text-left py-4 px-6 font-semibold">Code</th>
                <th class="text-left py-4 px-6 font-semibold">Default</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach([['English','en','Yes'],['Spanish','es','No'],['French','fr','No'],['Arabic','ar','No']] as $i=>$l)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td><td class="py-4 px-6 font-semibold text-heading">{{ $l[0] }}</td><td class="py-4 px-6 text-heading/70">{{ $l[1] }}</td><td class="py-4 px-6 text-heading/70">{{ $l[2] }}</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection