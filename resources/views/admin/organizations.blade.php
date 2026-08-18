@extends('layouts.dashboard')
@section('title', 'Organizations')
@section('page-title', 'Organization')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold flex items-center gap-2">
    <i class="ri-checkbox-circle-fill"></i> {{ session('success') }}
</div>
@endif
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">All Organizations</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Email</th>
                <th class="text-left py-4 px-6 font-semibold">Phone</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $i=>$u)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $u->full_name }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $u->email }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $u->phone ?? '--' }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $u->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($u->status) }}</span></td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <form method="POST" action="/admin/users/{{ $u->id }}/toggle-status" class="inline" x-data @submit.prevent="if(confirm('Toggle status for {{ $u->name }}?')) $el.submit()">
                                @csrf
                                <button type="submit" class="text-xs font-semibold {{ $u->status=='active' ? 'text-red-500 hover:underline' : 'text-green-500 hover:underline' }}">
                                    {{ $u->status=='active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form method="POST" action="/admin/users/{{ $u->id }}/delete" class="inline" x-data @submit.prevent="if(confirm('Delete {{ $u->name }}?')) $el.submit()">
                                @csrf
                                <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-heading/50 text-sm">No organizations found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
