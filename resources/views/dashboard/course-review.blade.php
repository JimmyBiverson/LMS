@extends('layouts.dashboard')
@section('title', 'Review Manage')
@section('page-title', 'Review')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">My Course Reviews</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Rating</th>
                <th class="text-left py-4 px-6 font-semibold">Review</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-right py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $review->course->title ?? "N/A" }}</td>
                    <td class="py-4 px-6"><div class="flex items-center gap-1 text-amber-400 text-xs">@for($j=1;$j<=5;$j++)<i class="ri-star{{ $j <= $review->rating ? "-fill" : "-line" }}"></i>@endfor</div></td>
                    <td class="py-4 px-6 text-heading/70 max-w-xs truncate">{{ $review->review ?? "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $review->created_at->format("Y-m-d") }}</td>
                    <td class="py-4 px-6 text-right"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $review->is_approved ? "bg-green-100 text-green-700" : "bg-amber-100 text-amber-700" }}">{{ $review->is_approved ? "Approved" : "Pending" }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-heading/40 text-sm">No reviews yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
