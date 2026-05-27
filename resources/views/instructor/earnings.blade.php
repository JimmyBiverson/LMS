@extends('layouts.dashboard')
@section('title', 'Earnings')
@section('page-title', 'Earnings')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5"><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Total Earnings</p><p class="text-2xl font-bold text-heading mt-1">$28,450</p></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">This Month</p><p class="text-2xl font-bold text-heading mt-1">$3,250</p></div>
        <div class="bg-white rounded-xl shadow-sm p-5"><p class="text-xs text-heading/50 font-semibold uppercase tracking-wider">Pending</p><p class="text-2xl font-bold text-heading mt-1">$1,200</p></div>
    </div>
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
                    @for($i=1;$i<=4;$i++)
                    <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i }}</td><td class="py-4 px-6 text-heading/70">2024-12-0{{ $i }}</td><td class="py-4 px-6 font-semibold text-heading">${{ 500+$i*100 }}</td><td class="py-4 px-6 text-heading/70">Bank Transfer</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Completed</span></td></tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection