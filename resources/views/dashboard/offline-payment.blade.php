@extends('layouts.dashboard')
@section('title', 'Offline Payment')
@section('page-title', 'Offline Payment')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
@if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
@endif
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-heading">Offline Payment Requests</h3>
            <p class="text-sm text-heading/50 mt-1">Track your Airtel / MTN mobile money enrollments</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Amount</th>
                <th class="text-left py-4 px-6 font-semibold">Provider</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $payment->course->title ?? "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ \App\Helpers\CurrencyHelper::format((float)$payment->amount_paid) }}</td>
                    <td class="py-4 px-6">
                        @if ($payment->payment_provider === 'airtel')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold"
                                  style="background:#FEE2E2;color:#991B1B;">
                                <svg width="12" height="12" viewBox="0 0 36 36" fill="none" class="inline-block"><rect width="36" height="36" rx="8" fill="#ED1B24"/><text x="18" y="23" font-family="Arial" font-weight="900" font-size="16" fill="white" text-anchor="middle">A</text></svg>
                                Airtel
                            </span>
                        @elseif ($payment->payment_provider === 'mtn')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold"
                                  style="background:#FEF3C7;color:#92400E;">
                                <svg width="12" height="12" viewBox="0 0 36 36" fill="none" class="inline-block"><rect width="36" height="36" rx="8" fill="#FFCC00"/><text x="18" y="23" font-family="Arial" font-weight="900" font-size="16" fill="#000" text-anchor="middle">M</text></svg>
                                MTN
                            </span>
                        @else
                            <span class="text-heading/50">—</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-heading/70">{{ $payment->created_at->format("Y-m-d") }}</td>
                    <td class="py-4 px-6">
                        @if ($payment->payment_status === 'pending')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                        @elseif ($payment->payment_status === 'approved')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Approved</span>
                        @elseif ($payment->payment_status === 'rejected')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Rejected</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $payment->status === "completed" ? "bg-green-100 text-green-700" : "bg-amber-100 text-amber-700" }}">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-heading/40 text-sm">No payment requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
