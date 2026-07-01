@extends('layouts.dashboard')
@section('title', 'Review Manage')
@section('page-title', 'Review')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
@if($completedEnrollments->isNotEmpty())
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Write a Review</h3></div>
    <div class="p-6">
        <form method="POST" action="/dashboard/course-review/0" x-data="{ courseId: 0 }" :action="'/dashboard/course-review/' + courseId" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <select x-model="courseId" required class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                <option value="0">Select a course...</option>
                @foreach($completedEnrollments as $enrollment)
                <option value="{{ $enrollment->course_id }}">{{ $enrollment->course->title }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2" x-data="{ rating: 0 }">
                <span class="text-sm text-heading/60 mr-1">Rating:</span>
                <template x-for="star in 5" :key="star">
                    <button type="button" @click="rating = star" class="text-2xl transition-colors" :class="star <= rating ? 'text-amber-400' : 'text-gray-200'">
                        <i :class="star <= rating ? 'ri-star-fill' : 'ri-star-line'"></i>
                    </button>
                </template>
                <input type="hidden" name="rating" :value="rating">
            </div>
            <div class="md:col-span-2">
                <textarea name="review" placeholder="Share your thoughts about this course..." rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"></textarea>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-semibold rounded-lg hover:opacity-90 text-sm">Submit Review</button>
            </div>
        </form>
    </div>
</div>
@endif
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
