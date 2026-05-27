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
                @for($i=1;$i<=3;$i++)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-heading/70">{{ $i }}</td>
                    <td class="py-4 px-6 font-semibold text-heading">Full-Stack Web Development</td>
                    <td class="py-4 px-6"><div class="flex items-center gap-1 text-amber-400 text-xs"><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-{{ $i == 3 ? 'line' : 'fill' }}"></i></div></td>
                    <td class="py-4 px-6 text-heading/70 max-w-xs truncate">Great course, learned a lot!</td>
                    <td class="py-4 px-6 text-heading/70">2024-12-0{{ $i }}</td>
                    <td class="py-4 px-6 text-right"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Approved</span></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection