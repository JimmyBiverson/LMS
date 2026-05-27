@extends('layouts.dashboard')
@section('title', 'My Course')
@section('page-title', 'Dashboard')
@section('user-name', 'Student')
@section('profile-route', '/dashboard')
@section('sidebar')@include('components.student-sidebar')@stop
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-primary">
        <p class="text-heading/60 text-sm">Enrolled Course</p>
        <p class="text-2xl font-extrabold text-heading mt-1">8</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-amber-400">
        <p class="text-heading/60 text-sm">In Progress</p>
        <p class="text-2xl font-extrabold text-heading mt-1">3</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-green-500">
        <p class="text-heading/60 text-sm">Completed</p>
        <p class="text-2xl font-extrabold text-heading mt-1">5</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-purple-500">
        <p class="text-heading/60 text-sm">Certificate</p>
        <p class="text-2xl font-extrabold text-heading mt-1">2</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-red-400">
        <p class="text-heading/60 text-sm">Wishlist</p>
        <p class="text-2xl font-extrabold text-heading mt-1">4</p>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-heading">Latest Enrolled Course</h3>
        <a href="/dashboard/my-enrolled-course" class="text-sm text-primary font-semibold hover:underline">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">Thumbnail</th>
                <th class="text-left py-4 px-6 font-semibold">Course Name</th>
                <th class="text-left py-4 px-6 font-semibold">Author</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=4;$i++)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6"><div class="w-12 h-8 rounded bg-primary-50 flex items-center justify-center"><i class="ri-play-circle-line text-primary"></i></div></td>
                    <td class="py-4 px-6 font-semibold text-heading">Full-Stack Web Development Bootcamp</td>
                    <td class="py-4 px-6 text-heading/70">Robert Smith</td>
                    <td class="py-4 px-6 text-heading/70">Free</td>
                    <td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Completed</span></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection