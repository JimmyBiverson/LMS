@extends('layouts.dashboard')
@section('title', 'Org Wishlists')
@section('page-title', 'Wishlist')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Course Wishlists</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Users</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($wishlists as $wl)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $wl->course->title ?? "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $wl->course->wishlists_count ?? 0 }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ \App\Helpers\CurrencyHelper::format((float)($wl->course->price ?? 0)) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-12 text-center text-heading/40 text-sm">No wishlists yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
