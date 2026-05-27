@extends('layouts.dashboard')
@section('title', 'Purchase Course')
@section('page-title', 'Course Purchase')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading">Purchase History</h3>
        <a href="/courses" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300">Buy New Course</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Amount</th>
                <th class="text-left py-4 px-6 font-semibold">Payment Method</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=5;$i++)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">Full-Stack Web Development</td>
                    <td class="py-4 px-6 text-heading/70">$25.50</td>
                    <td class="py-4 px-6 text-heading/70">PayPal</td>
                    <td class="py-4 px-6 text-heading/70">2024-12-0{{ $i }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Paid</span></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection