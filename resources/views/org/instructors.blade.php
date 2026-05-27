@extends('layouts.dashboard')
@section('title', 'Org Instructors')
@section('page-title', 'Instructors')
@section('user-name', 'Organization')
@section('sidebar')@include('components.org-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <h3 class="font-bold text-heading">Manage Instructors</h3>
        <a href="/org/instructors/create" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:opacity-90 transition-all duration-300"><i class="ri-add-line mr-1"></i> Add Instructor</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Email</th>
                <th class="text-left py-4 px-6 font-semibold">Courses</th>
                <th class="text-left py-4 px-6 font-semibold">Students</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=4;$i++)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 text-heading/70">{{ $i }}</td>
                    <td class="py-4 px-6 flex items-center gap-3"><img src="https://placehold.co/32x32/5F3EED/FFFFFF?text=I{{ $i }}" class="w-8 h-8 rounded-full"><span class="font-semibold text-heading">Instructor {{ $i }}</span></td>
                    <td class="py-4 px-6 text-heading/70">instructor{{ $i }}@mail.com</td>
                    <td class="py-4 px-6 text-heading/70">{{ 2+$i }}</td>
                    <td class="py-4 px-6 text-heading/70">{{ 50+$i*30 }}</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection