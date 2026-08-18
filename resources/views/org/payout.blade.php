@extends('layouts.dashboard')
@section('title', 'Payout')
@section('page-title', 'Payout')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Payout History</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Amount</th>
                <th class="text-left py-4 px-6 font-semibold">Method</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                <tr><td colspan="5" class="py-12 text-center text-heading/40 text-sm">No payouts yet.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
