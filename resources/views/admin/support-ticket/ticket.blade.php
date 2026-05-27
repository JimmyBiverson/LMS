@extends('layouts.dashboard')
@section('title', 'Support Tickets')
@section('page-title', 'Tickets')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">All Tickets</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">User</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Category</th>
                <th class="text-left py-4 px-6 font-semibold">Priority</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=5;$i++)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i }}</td><td class="py-4 px-6 font-semibold text-heading">User {{ $i }}</td><td class="py-4 px-6 text-heading/70">Ticket {{ $i }}</td><td class="py-4 px-6 text-heading/70">Technical</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $i <= 2 ? 'bg-red-100 text-red-700' : ($i <= 4 ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">{{ $i <= 2 ? 'High' : ($i <= 4 ? 'Medium' : 'Low') }}</span></td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $i <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">{{ $i <= 3 ? 'Open' : 'Closed' }}</span></td></tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection