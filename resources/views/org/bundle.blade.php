@extends('layouts.dashboard')
@section('title', 'Bundle Courses')
@section('page-title', 'Bundle Courses')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">All Bundles</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Bundle</th>
                <th class="text-left py-4 px-6 font-semibold">Courses</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bundles as $i => $bundle)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i + 1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $bundle->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $bundle->courses_count }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ \App\Helpers\CurrencyHelper::format((float)($bundle->sale_price ?? $bundle->price ?? 0)) }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ ($bundle->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($bundle->status ?? 'active') }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-heading/40 text-sm">No bundles found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
