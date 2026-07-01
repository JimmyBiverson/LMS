@extends('layouts.dashboard')
@section('title', 'My Certificate')
@section('page-title', 'Certificate')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">My Certificates</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-right py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($certificates as $i=>$c)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $c->course?->title ?? $c->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->created_at->format('Y-m-d') }}</td>
                    <td class="py-4 px-6 text-right"><a href="/dashboard/certificate/{{ $c->id }}/download" class="inline-flex items-center gap-1 px-4 py-2 bg-primary text-white text-xs font-bold rounded-full hover:opacity-90 transition-all duration-300"><i class="ri-download-line"></i> Download</a></td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-8 text-center text-heading/50 text-sm">You haven't earned any certificates yet. Complete a course to get one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
