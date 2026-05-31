@extends('layouts.dashboard')
@section('title', 'Offline Payment')
@section('page-title', 'Offline Payment')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading">Offline Payment Requests</h3>
        <a href="#" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300">New Request</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Amount</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $payment->course->title ?? "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">${{ number_format((float)$payment->amount_paid, 2) }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $payment->created_at->format("Y-m-d") }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $payment->status === "completed" ? "bg-green-100 text-green-700" : "bg-amber-100 text-amber-700" }}">{{ ucfirst($payment->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-12 text-center text-heading/40 text-sm">No payment requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
