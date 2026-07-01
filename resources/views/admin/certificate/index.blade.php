@extends('layouts.dashboard')
@section('title', 'All Certificates')
@section('page-title', 'All Certificates')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-fill"></i> {{ session('success') }}
</div>
@endif
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">All Certificates</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Student</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Created</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($certificates as $i=>$c)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $c->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->user?->name ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->course?->title ?? '--' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->created_at->format('Y-m-d') }}</td>
                    <td class="py-4 px-6">
                        @if($c->file_path)
                        <a href="{{ asset('storage/' . $c->file_path) }}" target="_blank" class="text-xs text-primary hover:underline font-semibold flex items-center gap-1">
                            <i class="ri-download-line"></i> Download
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No certificates found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
