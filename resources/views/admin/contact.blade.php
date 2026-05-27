@extends('layouts.dashboard')
@section('title', 'Contact Messages')
@section('page-title', 'Contact')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Contact Messages</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Email</th>
                <th class="text-left py-4 px-6 font-semibold">Phone</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($messages as $i=>$m)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $m->name }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $m->email }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $m->phone }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $m->subject }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $m->created_at->format('Y-m-d') }}</td>
                    <td class="py-4 px-6">
                        @if($m->is_read)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Read</span>
                        @else
                        <form method="POST" action="/admin/contact/{{ $m->id }}/read" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 hover:bg-amber-200">Unread</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/50 text-sm">No messages found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
