@extends('layouts.dashboard')
@section('title', 'Bundle Purchase')
@section('page-title', 'Bundle Purchase')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Bundle Purchase History</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Bundle Name</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
                <th class="text-left py-4 px-6 font-semibold">Payment</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">1</td>
                    <td class="py-4 px-6 font-semibold text-heading">Full Stack Development Mastery Bundle</td>
                    <td class="py-4 px-6 text-heading/70">$499</td>
                    <td class="py-4 px-6 text-heading/70">Stripe</td>
                    <td class="py-4 px-6 text-heading/70">2024-11-15</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Paid</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection