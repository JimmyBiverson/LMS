@extends('layouts.dashboard')
@section('title', 'Support Manage')
@section('page-title', 'My Tickets')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
@endif
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading">Support Tickets</h3>
        <a href="/dashboard/supports/create" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300"><i class="ri-add-line mr-1"></i> New Ticket</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Category</th>
                <th class="text-left py-4 px-6 font-semibold">Priority</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-right py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $i=>$t)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $t->subject }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->category }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $t->priority === 'High' ? 'bg-red-100 text-red-700' : ($t->priority === 'Medium' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">{{ $t->priority }}</span></td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->created_at->format('Y-m-d') }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $t->status === 'Open' ? 'bg-green-100 text-green-700' : ($t->status === 'Pending' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700') }}">{{ $t->status }}</span></td>
                    <td class="py-4 px-6 text-right"><a href="/dashboard/supports/{{ $t->id }}" class="text-primary text-sm font-semibold hover:underline">View</a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/50 text-sm">No tickets yet. Create one to get support.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
