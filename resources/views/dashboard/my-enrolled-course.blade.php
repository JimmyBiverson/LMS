@extends('layouts.dashboard')
@section('title', 'My Enrolled Course')
@section('page-title', 'My Enrolled Course')
@section('user-name', 'Student')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Enrolled Courses</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Course Name</th>
                <th class="text-left py-4 px-6 font-semibold">Instructor</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
                <th class="text-left py-4 px-6 font-semibold">Progress</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
                <th class="text-right py-4 px-6 font-semibold">Action</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=8;$i++)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">Full-Stack Web Development Bootcamp</td>
                    <td class="py-4 px-6 text-heading/70">Robert Smith</td>
                    <td class="py-4 px-6 text-heading/70">{{ $i % 2 == 0 ? '$25.50' : 'Free' }}</td>
                    <td class="py-4 px-6"><div class="flex items-center gap-2"><div class="flex-1 h-2 bg-gray-100 rounded-full"><div class="h-full bg-primary rounded-full" style="width:{{ $i * 12 }}%"></div></div><span class="text-xs text-heading/60">{{ $i * 12 }}%</span></div></td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $i <= 5 ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $i <= 5 ? 'Completed' : 'In Progress' }}</span></td>
                    <td class="py-4 px-6 text-right"><a href="/courses/full-stack-bootcamp" class="text-primary text-sm font-semibold hover:underline">View</a></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div class="p-6 flex items-center justify-center gap-2">
        <span class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold">1</span>
        <a href="#" class="w-10 h-10 rounded-full bg-primary-50 text-heading/70 flex items-center justify-center text-sm font-bold hover:bg-primary hover:text-white transition-all duration-300">2</a>
        <a href="#" class="w-10 h-10 rounded-full bg-primary-50 text-heading/70 flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300"><i class="ri-arrow-right-s-line"></i></a>
    </div>
</div>
@endsection