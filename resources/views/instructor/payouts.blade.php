@extends('layouts.dashboard')
@section('title', 'Payouts')
@section('page-title', 'Payouts')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6"><p class="text-sm text-heading/60 mb-1">Total Earnings</p><p class="text-2xl font-bold text-heading">${{ number_format($totalEarnings, 2) }}</p></div>
    <div class="bg-white rounded-xl shadow-sm p-6"><p class="text-sm text-heading/60 mb-1">Total Paid</p><p class="text-2xl font-bold text-green-600">${{ number_format($totalPaid, 2) }}</p></div>
    <div class="bg-white rounded-xl shadow-sm p-6"><p class="text-sm text-heading/60 mb-1">Pending Balance</p><p class="text-2xl font-bold text-heading">${{ number_format($pendingBalance, 2) }}</p></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-4">Request Payout</h3>
        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"><ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="/instructor/payouts/request" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-semibold text-heading mb-1">Amount ($)</label><input name="amount" type="number" step="0.01" min="1" max="{{ $pendingBalance }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Method</label><select name="method" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" required><option value="bank">Bank Transfer</option><option value="paypal">PayPal</option><option value="stripe">Stripe</option></select></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Account Details</label><textarea name="account_details" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary" placeholder="Bank name, account number, routing number..." required></textarea></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Notes (optional)</label><textarea name="notes" rows="2" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></textarea></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Submit Request</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-4">Payout History</h3>
        <div class="space-y-3">
            @forelse ($payouts as $payout)
            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-lg">
                <div><p class="font-semibold text-heading text-sm">${{ number_format($payout->amount, 2) }}</p><p class="text-xs text-heading/50">{{ $payout->method }} &middot; {{ $payout->created_at->format('M d, Y') }}</p></div>
                <span class="px-3 py-1 rounded-full text-xs font-bold @switch($payout->status) @case('paid') bg-green-100 text-green-700 @break @case('rejected') bg-red-100 text-red-700 @break @default bg-yellow-100 text-yellow-700 @endswitch">{{ ucfirst($payout->status) }}</span>
            </div>
            @empty
            <p class="text-sm text-heading/50 text-center py-8">No payout requests yet.</p>
            @endforelse
        </div>
        @if ($payouts->hasPages())<div class="mt-4">{{ $payouts->links() }}</div>@endif
    </div>
</div>
@endsection