@extends('layouts.dashboard')
@section('title', 'Reviews')
@section('page-title', 'Reviews')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
@if(session('success'))<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>@endif
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Course Reviews</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Student</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Rating</th>
                <th class="text-left py-4 px-6 font-semibold">Review</th>
                <th class="text-left py-4 px-6 font-semibold">Date</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $review->user->full_name ?? "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $review->course->title ?? "N/A" }}</td>
                    <td class="py-4 px-6"><div class="flex items-center gap-0.5 text-amber-400 text-xs">@for($j=1;$j<=5;$j++)<i class="ri-star{{ $j <= $review->rating ? "-fill" : "-line" }}"></i>@endfor</div></td>
                    <td class="py-4 px-6 text-heading/70 max-w-xs truncate">{{ $review->review ?? "N/A" }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $review->created_at->format("Y-m-d") }}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $review->is_approved ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $review->is_approved ? 'Approved' : 'Pending' }}</span>
                            @if(!$review->is_approved)
                            <form method="POST" action="/instructor/reviews/{{ $review->id }}/approve" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all duration-300">Approve</button>
                            </form>
                            <form method="POST" action="/instructor/reviews/{{ $review->id }}/reject" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-300">Reject</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-12 text-center text-heading/40 text-sm">No reviews yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
