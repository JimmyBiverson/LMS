@extends('layouts.dashboard')
@section('title', 'My Courses')
@section('page-title', 'My Courses')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <h3 class="font-bold text-heading">All Courses</h3>
        <a href="/instructor/courses/create" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300"><i class="ri-add-line mr-1"></i> Add New</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course</th>
                <th class="text-left py-4 px-6 font-semibold">Category</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
                <th class="text-left py-4 px-6 font-semibold">Lessons</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-right py-4 px-6 font-semibold">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($courses as $i => $course)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i + 1 }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">{{ $course->title }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $course->category }}</td>
                    <td class="py-4 px-6 text-heading/70">${{ number_format($course->price, 2) }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ $course->lessons->count() }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $course->status === 'Active' ? 'bg-green-100 text-green-700' : ($course->status === 'Draft' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">{{ $course->status }}</span></td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('instructor.dashboard.courses.edit', $course->id) }}" class="px-3 py-1 text-xs font-semibold rounded-full border border-heading/10 hover:bg-primary hover:text-white hover:border-primary transition-all">Edit</a>
                            <a href="{{ route('instructor.dashboard.courses.lessons', $course->id) }}" class="px-3 py-1 text-xs font-semibold rounded-full border border-heading/10 hover:bg-primary hover:text-white hover:border-primary transition-all">Lessons</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-heading/40 text-sm">No courses yet. <a href="/instructor/courses/create" class="text-primary hover:underline">Create your first course!</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
