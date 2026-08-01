@extends('layouts.dashboard')
@section('title', 'Pending Payments')
@section('page-title', 'Pending Payments')
@section('user-name', auth()->user()->name ?? 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
@if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-semibold">{{ session('error') }}</div>
@endif
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-heading">Pending Offline Payments</h3>
        <p class="text-sm text-heading/50 mt-1">Approve or reject student enrollments made via Airtel / MTN mobile money</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[800px]">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Student</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Amount</th>
                <th class="text-left py-4 px-6 font-semibold">Provider</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pendingPayments as $enrollment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-primary-10 flex items-center justify-center">
                                <i class="ri-user-smile-line text-sm text-primary"></i>
                            </div>
                            <span class="font-semibold text-heading">{{ $enrollment->user?->name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $enrollment->course?->title ?? 'N/A' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ \App\Helpers\CurrencyHelper::format((float)$enrollment->amount_paid) }}</td>
                    <td class="py-4 px-6">
                        @if ($enrollment->payment_provider === 'airtel')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold"
                                  style="background:#FEE2E2;color:#991B1B;">
                                <svg width="12" height="12" viewBox="0 0 36 36" fill="none" class="inline-block"><rect width="36" height="36" rx="8" fill="#ED1B24"/><text x="18" y="23" font-family="Arial" font-weight="900" font-size="16" fill="white" text-anchor="middle">A</text></svg>
                                Airtel
                            </span>
                        @elseif ($enrollment->payment_provider === 'mtn')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold"
                                  style="background:#FEF3C7;color:#92400E;">
                                <svg width="12" height="12" viewBox="0 0 36 36" fill="none" class="inline-block"><rect width="36" height="36" rx="8" fill="#FFCC00"/><text x="18" y="23" font-family="Arial" font-weight="900" font-size="16" fill="#000" text-anchor="middle">M</text></svg>
                                MTN
                            </span>
                        @else
                            <span class="text-heading/50">—</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-heading/70">{{ $enrollment->created_at->format('Y-m-d') }}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2 whitespace-nowrap">
                            <form method="POST" action="/instructor/pending-payments/{{ $enrollment->id }}/approve">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="ri-check-line mr-1"></i>Approve
                                </button>
                            </form>
                            <form method="POST" action="/instructor/pending-payments/{{ $enrollment->id }}/reject"
                                  onsubmit="return confirm('Reject this payment? The student enrollment will remain inactive.')">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition-colors">
                                    <i class="ri-close-line mr-1"></i>Reject
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-12 text-center text-heading/40 text-sm">No pending payments. All clear!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
