@extends('layouts.dashboard')
@section('title', 'Support Tickets')
@section('page-title', 'Tickets')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
@endif
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
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $i=>$t)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $t->user?->name ?? 'Deleted User' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->subject }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $t->category }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $t->priority === 'High' ? 'bg-red-100 text-red-700' : ($t->priority === 'Medium' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">{{ $t->priority }}</span></td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $t->status === 'Closed' ? 'bg-green-100 text-green-700' : ($t->status === 'Pending' ? 'bg-gray-100 text-gray-700' : 'bg-amber-100 text-amber-700') }}">{{ $t->status }}</span></td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/support-ticket/ticket/{{ $t->id }}" class="inline">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="text-xs border border-heading/10 rounded px-2 py-1">
                                <option value="Open" @selected($t->status === 'Open')>Open</option>
                                <option value="Pending" @selected($t->status === 'Pending')>Pending</option>
                                <option value="Closed" @selected($t->status === 'Closed')>Closed</option>
                            </select>
                        </form>
                        <form method="POST" action="/admin/support-ticket/ticket/{{ $t->id }}/delete" class="inline ml-2" onsubmit="return confirm('Delete this ticket?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/50 text-sm">No tickets found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
