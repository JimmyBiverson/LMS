@extends('layouts.dashboard')
@section('title', 'All Enrollments')
@section('page-title', 'All Enrollments')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">All Enrollments</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Student</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Amount Paid</th>
                <th class="text-left py-4 px-6 font-semibold">Enrolled At</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($enrollments as $i=>$e)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $e->user?->name ?? 'Deleted User' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $e->course?->title ?? 'Deleted Course' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ \App\Helpers\CurrencyHelper::format((float)($e->amount_paid ?? 0)) }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $e->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-heading/50 text-sm">No enrollments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
