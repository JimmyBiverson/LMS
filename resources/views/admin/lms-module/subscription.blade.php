@extends('layouts.dashboard')
@section('title', 'Subscription')
@section('page-title', 'Subscription')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Subscription Plans</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Plan</th>
                <th class="text-left py-4 px-6 font-semibold">Price</th>
                <th class="text-left py-4 px-6 font-semibold">Duration</th>
                <th class="text-left py-4 px-6 font-semibold">Subscribers</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach([['Basic','$9.99','Monthly',120],['Standard','$19.99','Monthly',340],['Premium','$49.99','Monthly',89],['Annual','$99.99','Yearly',45]] as $i=>$s)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td><td class="py-4 px-6 font-semibold text-heading">{{ $s[0] }}</td><td class="py-4 px-6 text-heading/70">{{ $s[1] }}</td><td class="py-4 px-6 text-heading/70">{{ $s[2] }}</td><td class="py-4 px-6 text-heading/70">{{ $s[3] }}</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection