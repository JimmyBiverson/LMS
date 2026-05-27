@extends('layouts.dashboard')
@section('title', 'All Certificates')
@section('page-title', 'All Certificates')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">All Certificates</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Created</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($certificates as $i=>$c)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $c->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->course?->title ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-8 text-center text-heading/50 text-sm">No certificates found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
