@extends('layouts.dashboard')
@section('title', 'Bundle Purchase')
@section('page-title', 'Bundle Purchase')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Bundle Purchase History</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Bundle Name</th>
                <th class="text-left py-4 px-6 font-semibold">Courses</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bundles as $bundle)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $bundle->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $bundle->totalCourses() }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $bundle->displayPrice() }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $bundle->status === "active" ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-700" }}">{{ ucfirst($bundle->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-12 text-center text-heading/40 text-sm">No bundles available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
