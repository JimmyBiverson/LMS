@extends('layouts.app')

@section('title', 'Organizations')

@section('content')
<section class="bg-[#F7F4FF] py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-heading mb-2">Organizations</h1>
        <div class="flex items-center gap-2 text-sm text-heading/60">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-primary font-semibold">Organizations</span>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-6 flex items-center justify-between">
            <p class="text-sm text-heading/60">{{ $organizations->count() }} Organization{{ $organizations->count() !== 1 ? 's' : '' }} Listed</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($organizations as $org)
            @php
                $courseCount = \App\Models\Course::where('user_id', $org->id)->where('status', 'Active')->count();
                $instructorCount = \App\Models\User::where('organization_id', $org->id)->count();
                $studentCount = \App\Models\Enrollment::whereIn('course_id', \App\Models\Course::where('user_id', $org->id)->pluck('id'))->count();
            @endphp
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="flex items-center gap-6 p-6">
                    <div class="w-20 h-20 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                        <i class="ri-building-line text-3xl text-primary"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-heading text-lg group-hover:text-primary transition-colors duration-300">{{ $org->name ?? $org->full_name }}</h3>
                        @if($org->address)
                        <p class="text-sm text-heading/60 mt-0.5">{{ $org->address }}</p>
                        @endif
                        <div class="flex items-center gap-6 mt-3 text-sm text-heading/60">
                            <span class="flex items-center gap-1"><i class="ri-book-open-line"></i> {{ $courseCount }} Courses</span>
                            <span class="flex items-center gap-1"><i class="ri-group-line"></i> {{ $instructorCount }} Instructors</span>
                            <span class="flex items-center gap-1"><i class="ri-user-line"></i> {{ $studentCount }} Students</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-heading/40">
                <i class="ri-building-line text-4xl block mb-2"></i>
                No organizations registered yet.
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
