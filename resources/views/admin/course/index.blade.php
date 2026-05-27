@extends('layouts.dashboard')
@section('title', 'All Courses')
@section('page-title', 'All Courses')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Courses</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Instructor</th>
                <th class="text-left py-4 px-6 font-semibold">Category</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
                <th class="text-left py-4 px-6 font-semibold">Type</th>
                <th class="text-left py-4 px-6 font-semibold">Students</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($courses as $i=>$c)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td><td class="py-4 px-6 font-semibold text-heading">{{ $c->title }}</td><td class="py-4 px-6 text-heading/70">{{ $c->instructor?->name ?? 'N/A' }}</td><td class="py-4 px-6 text-heading/70">{{ $c->category ?? '--' }}</td><td class="py-4 px-6 text-heading/70">{{ $c->payment_type === 'free' ? 'Free' : ($c->sale_price ? '$'.number_format($c->sale_price,2) : '$'.number_format($c->price,2)) }}</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $c->payment_type === 'free' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">{{ ucfirst($c->payment_type ?? 'free') }}</span></td><td class="py-4 px-6 text-heading/70">{{ $c->enrollments_count }}</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $c->status=='Active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $c->status }}</span></td></tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/50 text-sm">No courses found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
