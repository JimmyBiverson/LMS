@extends('layouts.dashboard')
@section('title', 'Earnings')
@section('page-title', 'Earnings')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5"><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Earnings</p><p class="text-2xl font-bold text-heading mt-1">${{ number_format($totalEarnings, 2) }}</p></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">This Month</p><p class="text-2xl font-bold text-heading mt-1">${{ number_format($currentMonth, 2) }}</p></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Pending</p><p class="text-2xl font-bold text-heading mt-1">${{ number_format($pendingEarnings, 2) }}</p></div>
    </div>
</div>
@endsection
