@extends('layouts.dashboard')
@section('title', 'FAQ Manage')
@section('page-title', 'Faq Manage')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Frequently Asked Questions</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Question</th>
                <th class="text-left py-4 px-6 font-semibold">Category</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @for($i=1;$i<=4;$i++)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i }}</td><td class="py-4 px-6 font-semibold text-heading">Sample question {{ $i }}?</td><td class="py-4 px-6 text-heading/70">General</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span></td></tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection