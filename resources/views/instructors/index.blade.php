@extends('layouts.app')

@section('title', 'Instructors')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Instructors</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Instructors</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-6 flex items-center justify-between">
            <p class="text-sm text-heading/60">{{ $instructors->count() }} Instructor{{ $instructors->count() !== 1 ? 's' : '' }} Available</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($instructors as $instructor)
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 p-6 text-center">
                <div class="w-20 h-20 rounded-full bg-primary-50 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-user-smile-line text-3xl text-primary"></i>
                </div>
                <h3 class="font-bold text-heading text-base group-hover:text-primary transition-colors duration-300">{{ $instructor->full_name }}</h3>
                <p class="text-sm text-primary font-semibold mt-1">{{ $instructor->designation ?? 'Instructor' }}</p>
                @if($instructor->bio)
                <p class="text-xs text-heading/60 mt-2 line-clamp-2">{{ $instructor->bio }}</p>
                @endif
                @php
                    $courseCount = \App\Models\Course::where('user_id', $instructor->id)->where('status', 'Active')->count();
                    $studentCount = \App\Models\Enrollment::whereIn('course_id', \App\Models\Course::where('user_id', $instructor->id)->pluck('id'))->count();
                @endphp
                <div class="flex items-center justify-center gap-4 mt-4 pt-4 border-t border-gray-100 text-xs text-heading/60">
                    <span class="flex items-center gap-1"><i class="ri-book-open-line"></i> {{ $courseCount }} Courses</span>
                    <span class="flex items-center gap-1"><i class="ri-group-line"></i> {{ $studentCount }} Students</span>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-heading/40">
                <i class="ri-user-search-line text-4xl block mb-2"></i>
                No instructors available yet.
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
