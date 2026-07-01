@extends('layouts.dashboard')
@section('title', 'Sale History')
@section('page-title', 'Sale History')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Sale History</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">User</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Amount</th>
                <th class="text-left py-4 px-6 font-semibold">Method</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                    @php
                        $sales = \App\Models\Enrollment::with('course', 'user')
                            ->where('amount_paid', '>', 0)
                            ->latest()->take(20)->get();
                    @endphp
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                        <td class="py-4 px-6 font-semibold text-heading">{{ $sale->user?->name ?? 'N/A' }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ $sale->course?->title ?? 'Deleted' }}</td>
                        <td class="py-4 px-6 text-heading/70">{{ \App\Helpers\CurrencyHelper::format((float)$sale->amount_paid) }}</td>
                        <td class="py-4 px-6 text-heading/70">Paystack</td>
                        <td class="py-4 px-6 text-heading/70">{{ $sale->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-12 text-center text-heading/40 text-sm">No sales recorded yet.</td></tr>
                    @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection