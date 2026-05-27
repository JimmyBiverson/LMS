@extends('layouts.dashboard')
@section('title', 'Email Templates')
@section('page-title', 'Email Template')
@section('user-name', 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop
@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100"><h3 class="font-bold text-heading">Email Templates</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-heading/60 text-xs uppercase tracking-wider">
                <th class="text-left py-4 px-6 font-semibold">#</th>
                <th class="text-left py-4 px-6 font-semibold">Name</th>
                <th class="text-left py-4 px-6 font-semibold">Subject</th>
                <th class="text-left py-4 px-6 font-semibold">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach([['Welcome','Welcome to EduLab','Active'],['Reset Password','Password Reset Request','Active'],['Enrollment','Enrollment Confirmation','Active']] as $i=>$t)
                <tr class="hover:bg-gray-50"><td class="py-4 px-6 text-heading/70">{{ $i+1 }}</td><td class="py-4 px-6 font-semibold text-heading">{{ $t[0] }}</td><td class="py-4 px-6 text-heading/70">{{ $t[1] }}</td><td class="py-4 px-6"><span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">{{ $t[2] }}</span></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection