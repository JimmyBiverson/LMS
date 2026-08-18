@extends('layouts.dashboard')
@section('title', 'Review Manage')
@section('page-title', 'Review')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Course Reviews</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Student</th>
                <th class="text-left py-4 px-6 font-semibold">Rating</th>
                <th class="text-left py-4 px-6 font-semibold">Review</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $review->course->title ?? 'N/A' }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $review->user->full_name ?? $review->user->name ?? 'N/A' }}</td>
                    <td class="py-4 px-6"><div class="flex items-center gap-1 text-amber-400 text-xs">@for($j=1;$j<=5;$j++)<i class="ri-star{{ $j <= $review->rating ? '-fill' : '-line' }}"></i>@endfor</div></td>
                    <td class="py-4 px-6 text-heading/70 max-w-xs truncate">{{ $review->review ?? 'N/A' }}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $review->is_approved ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $review->is_approved ? 'Approved' : 'Pending' }}</span>
                            @if(!$review->is_approved)
                            <form method="POST" action="/admin/review/{{ $review->id }}/approve" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all duration-300">Approve</button>
                            </form>
                            @endif
                            <form method="POST" action="/admin/review/{{ $review->id }}/delete" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-300" onclick="return confirm('Delete this review?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-heading/40 text-sm">No reviews found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection