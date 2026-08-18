@extends('layouts.dashboard')
@section('title', 'Payout Requests')
@section('page-title', 'Payout Requests')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Instructor Payout Requests</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Instructor</th>
                <th class="text-left py-4 px-6 font-semibold">Amount</th>
                <th class="text-left py-4 px-6 font-semibold">Method</th>
                <th class="text-left py-4 px-6 font-semibold">Account</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($payouts as $payout)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $payouts->firstItem() + $loop->index }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $payout->user?->name ?? 'Deleted' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ \App\Helpers\CurrencyHelper::format($payout->amount) }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ ucfirst($payout->method) }}</td>
                    <td class="py-4 px-6 text-heading/70 max-w-xs truncate">{{ $payout->account_details }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $payout->created_at->format('Y-m-d') }}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-bold @switch($payout->status) @case('paid') bg-green-100 text-green-700 @break @case('rejected') bg-red-100 text-red-700 @break @default bg-amber-100 text-amber-700 @endswitch">{{ ucfirst($payout->status) }}</span>
                    </td>
                    <td class="py-4 px-6">
                        @if ($payout->status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="/admin/financial/payouts/{{ $payout->id }}/approve">@csrf<button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full hover:bg-green-600">Approve</button></form>
                            <form method="POST" action="/admin/financial/payouts/{{ $payout->id }}/reject" onsubmit="return prompt('Rejection reason:') !== null">@csrf<button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full hover:bg-red-600">Reject</button></form>
                        </div>
                        @else
                        <span class="text-xs text-heading/50">â€”</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-12 text-center text-heading/50">No payout requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($payouts->hasPages())<div class="p-4 border-t border-gray-100">{{ $payouts->links() }}</div>@endif
</div>
@endsection