@extends('layouts.dashboard')
@section('title', 'Coupons')
@section('page-title', 'Coupon')
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
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Add New Coupon</h3></div>
    <div class="p-6">
        <form method="POST" action="/admin/marketing/coupon" class="flex flex-wrap gap-4">
            @csrf
            <input type="text" name="code" placeholder="Coupon Code" required class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
            <input type="number" step="0.01" name="discount" placeholder="Discount" required class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary w-28">
            <select name="discount_type" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                <option value="percentage">Percentage</option><option value="fixed">Fixed</option>
            </select>
            <input type="number" name="max_uses" placeholder="Max Uses" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary w-28">
            <input type="number" step="0.01" name="min_amount" placeholder="Min Amount" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary w-28">
            <input type="date" name="expires_at" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
            <select name="status" class="px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                <option value="active">Active</option><option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Add</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Coupons</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Code</th>
                <th class="text-left py-4 px-6 font-semibold">Discount</th>
                <th class="text-left py-4 px-6 font-semibold">Type</th>
                <th class="text-left py-4 px-6 font-semibold">Uses</th>
                <th class="text-left py-4 px-6 font-semibold">Expires</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-left py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($coupons as $i=>$c)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading uppercase">{{ $c->code }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->discount_type === 'percentage' ? $c->discount.'%' : '$'.number_format($c->discount,2) }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ ucfirst($c->discount_type) }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->used_count }}{{ $c->max_uses ? '/'.$c->max_uses : '' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $c->expires_at ? $c->expires_at->format('d M Y') : '--' }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $c->status=='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($c->status) }}</span></td>
                    <td class="py-4 px-6">
                        <form method="POST" action="/admin/marketing/coupon/{{ $c->id }}" class="inline">
                            @csrf
                            <input type="hidden" name="code" value="{{ $c->code }}">
                            <input type="hidden" name="discount" value="{{ $c->discount }}">
                            <input type="hidden" name="discount_type" value="{{ $c->discount_type }}">
                            <input type="hidden" name="max_uses" value="{{ $c->max_uses }}">
                            <input type="hidden" name="min_amount" value="{{ $c->min_amount }}">
                            <input type="hidden" name="expires_at" value="{{ $c->expires_at ? $c->expires_at->format('Y-m-d') : '' }}">
                            <input type="hidden" name="status" value="{{ $c->status === 'active' ? 'inactive' : 'active' }}">
                            <button type="submit" class="text-xs text-primary hover:underline font-semibold">{{ $c->status === 'active' ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                        <form method="POST" action="/admin/marketing/coupon/{{ $c->id }}/delete" class="inline ml-2" onsubmit="return confirm('Delete this coupon?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-8 text-center text-heading/50 text-sm">No coupons found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
