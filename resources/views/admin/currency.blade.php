@extends('layouts.dashboard')
@section('title', 'Currency')
@section('page-title', 'Currency')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Currencies</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Code</th>
                <th class="text-left py-4 px-6 font-semibold">Symbol</th>
                <th class="text-left py-4 px-6 font-semibold">Rate</th>
                <th class="text-left py-4 px-6 font-semibold">Default</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach([['US Dollar','USD','$',1.00,'Yes'],['Euro','EUR','€',0.92,'No'],['Pound','GBP','£',0.79,'No']] as $i=>$c)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td><td class="py-4 px-6 font-semibold text-heading">{{ $c[0] }}</td><td class="py-4 px-6 text-heading/70">{{ $c[1] }}</td><td class="py-4 px-6 text-heading/70">{{ $c[2] }}</td><td class="py-4 px-6 text-heading/70">{{ $c[3] }}</td><td class="py-4 px-6 text-heading/70">{{ $c[4] }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection