@extends('layouts.dashboard')
@section('title', 'Instructor Supports')
@section('page-title', 'Support')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Support Tickets</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">From</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-right py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $ticket->user->full_name ?? "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $ticket->subject }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $ticket->created_at->format("Y-m-d") }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $ticket->status === "resolved" ? "bg-green-100 text-green-700" : ($ticket->status === "pending" ? "bg-red-100 text-red-700" : "bg-amber-100 text-amber-700") }}">{{ ucfirst($ticket->status) }}</span></td>
                    <td class="py-4 px-6 text-right"><a href="/dashboard/supports/{{ $ticket->id }}" class="text-primary text-sm font-semibold hover:underline">View</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-heading/40 text-sm">No support tickets.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
