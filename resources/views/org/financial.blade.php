@extends('layouts.dashboard')
@section('title', 'Org Financial')
@section('page-title', 'Financial')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5"><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Revenue</p><p class="text-2xl font-bold text-heading mt-1">${{ number_format($totalRevenue, 2) }}</p></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">This Month</p><p class="text-2xl font-bold text-heading mt-1">${{ number_format($currentMonth, 2) }}</p></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Pending</p><p class="text-2xl font-bold text-heading mt-1">${{ number_format($pendingAmount, 2) }}</p></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Transactions</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                    <th class="text-left py-4 px-6 font-semibold">#</th>
                    <th class="text-left py-4 px-6 font-semibold">Course</th>
                    <th class="text-left py-4 px-6 font-semibold">Student</th>
                    <th class="text-left py-4 px-6 font-semibold">Amount</th>
                    <th class="text-left py-4 px-6 font-semibold">Date</th>
                    <th class="text-left py-4 px-6 font-semibold">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6 font-semibold text-heading">{{ $t->course->title ?? "N/A" }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $t->user->full_name ?? "N/A" }}</td>
                        <td class="py-4 px-6 font-semibold text-heading">${{ number_format((float)$t->amount_paid, 2) }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $t->created_at->format("Y-m-d") }}</td>
                        <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $t->status === "completed" ? "bg-green-100 text-green-700" : "bg-amber-100 text-amber-700" }}">{{ ucfirst($t->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-12 text-center text-heading/40 text-sm">No transactions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
