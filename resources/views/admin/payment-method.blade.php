@extends('layouts.dashboard')
@section('title', 'Payment Methods')
@section('page-title', 'Payment Method')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
@if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Add New Payment Method</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/payment-method" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="name" placeholder="Method Name" required class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
            <select name="type" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                <option value="Online">Online</option><option value="Offline">Offline</option>
            </select>
            <select name="status" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                <option value="active">Active</option><option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Payment Methods</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Method</th>
                <th class="text-left py-4 px-6 font-semibold">Type</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($methods as $i=>$m)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $m->name }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $m->type }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $m->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($m->status) }}</span></td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/payment-method/{{ $m->id }}" class="inline">
                            @csrf
                            <input type="hidden" name="name" value="{{ $m->name }}">
                            <input type="hidden" name="type" value="{{ $m->type }}">
                            <input type="hidden" name="status" value="{{ $m->status === 'active' ? 'inactive' : 'active' }}">
                            <button type="submit" class="text-xs text-primary hover:underline font-semibold">{{ $m->status === 'active' ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                        <form method="POST" action="/admin/payment-method/{{ $m->id }}/delete" class="inline ml-2" onsubmit="return confirm('Delete this payment method?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-heading/50 text-sm">No payment methods found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
