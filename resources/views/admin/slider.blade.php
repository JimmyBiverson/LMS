@extends('layouts.dashboard')
@section('title', 'Slider')
@section('page-title', 'Slider')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Homepage Sliders</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Title</th>
                <th class="text-left py-4 px-6 font-semibold">Subtitle</th>
                <th class="text-left py-4 px-6 font-semibold">Order</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=3;$i++)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i }}</td><td class="py-4 px-6 font-semibold text-heading">Slide {{ $i }} Title</td><td class="py-4 px-6 text-heading/70">Slide {{ $i }} subtitle text</td><td class="py-4 px-6 text-heading/70">{{ $i }}</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span></td></tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection