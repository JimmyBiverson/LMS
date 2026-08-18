@extends('layouts.dashboard')
@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
@if(session('success'))<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>@endif
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Contact Messages</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[900px]">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Email</th>
                <th class="text-left py-4 px-6 font-semibold">Phone</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Message</th>
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
                    <td class="py-4 px-6 text-heading/70 max-w-[150px] truncate" title="{{ $m->subject }}">{{ $m->subject }}</td>
                    <td class="py-4 px-6 text-heading/70 max-w-[200px] truncate" title="{{ $m->message }}">{{ $m->message }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $m->created_at->format('Y-m-d') }}</td>
                    <td class="py-4 px-6">
                        @if($m->is_read)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Read</span>
                        @else
                        <form method="POST" action="{{ url('instructor/contact-messages/'.$m->id.'/read') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 hover:bg-amber-200">Unread</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-12 text-center text-heading/40 text-sm">No contact messages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection